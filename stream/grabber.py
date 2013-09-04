# -*- coding: utf-8 -*-
from flask import Flask
from sqlalchemy.orm import sessionmaker
from sqlalchemy import create_engine, desc
from sqlalchemy.engine.url import URL
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
from datetime import datetime
import json
import pusher
import pytz
import re

from config import *
from models import ConfigWall, Tweet, Base

utc = pytz.utc
tzone = pytz.timezone('Europe/Paris')

app = Flask(__name__)
app.debug = True

engine = create_engine(URL(**DATABASE))
Session = sessionmaker(bind=engine)
session = Session()

pushy = pusher.Pusher(app_id=pusher_app_id, key=pusher_key, secret=pusher_secret)

def get_config_param(param):
    """ Retourne la valeur de 'param' en config """
    h = session.query(ConfigWall).first()
    resp = h.__getattribute__(param)
    if param == 'hashtag':
        resp = resp.encode('utf8')
    return resp

previous_hash = get_config_param('hashtag')

def start_grabber():
    s = SmsWallListener()
    auth = OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)
    app.stream = Stream(auth, s, timeout=43200.0) # timeout: 12 heures

    if not get_config_param('userstream'):
        print "lancement du grabber. Hashtag en cours : %s" % previous_hash
        app.stream.filter(track=[ previous_hash ], stall_warnings=True)
    else:
        print "stream du compte Twitter associé à l'application WallFactory"
        app.stream.userstream()


def make_rich_links(txt, links, medias):
    """ htmlisation des messages """
    html = txt
    if links:
        for link in links:
            urlink = re.compile(r"(%s)" % link['url'])
            tag = '<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">%s</a>' % (link['expanded_url'], link['type'], link['expanded_url'], link['url'])
            html = urlink.sub(tag,html)
    if medias:
        for media in medias:
            urlmedia = re.compile(r"(%s)" % media['url'])
            tag = '<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">%s</a>' % (media['media_url'], media['type'], media['media_url'], media['url'])
            html = urlmedia.sub(tag,html)

    return html


def get_links(data):
    """ Traitement des liens """
    links = ""
    if 'entities' in data and 'urls' in data['entities'] and len(data['entities']['urls']):
        links = []
        for url in data['entities']['urls']:
            links.append({"type": "link", "url": url['url'], "expanded_url": url['expanded_url']})
            print "link : %s" % url['expanded_url']

    return links


def get_medias(data):
    """ Traitement des médias """
    medias = ""
    if 'entities' in data and 'media' in data['entities']:
        medias = []
        for media in data['entities']['media']:
            # Récup de la plus petite taille d'image disponible
            # Ca pique un peu les yeux...
            th = 'medium' if 'medium' in media['sizes'] else ''
            th = 'small' if 'small' in media['sizes'] else th
            th = 'thumb' if 'thumb' in media['sizes'] else th

            medias.append({"type": media['type'], "url": media['url'], "media_url": media['media_url'], "thumb_size": th })
            print "media : %s" % media['media_url']

    return medias


class SmsWallListener(StreamListener):
    """ A listener handles tweets are the received from the stream. """

    def on_data(self, data):

        # Redémarrage du grabber si le hashtag a changé
        global previous_hash
        current_hash = get_config_param('hashtag')

        if current_hash != previous_hash:
            previous_hash = current_hash
            app.stream.disconnect()
            # stream.disconnect()
            del(app.stream)
            # del(stream)
            start_grabber()

        data = json.loads(data)

        # Est-ce un tweet ?
        if 'id_str' in data:
            # Filtrage sur les RT en fonction de la config
            if get_config_param('retweet') or (not get_config_param('retweet') and 'retweeted_status' not in data):

                # created_at en UTC pour la BDD
                ca_origin = datetime.strptime(data['created_at'], "%a %b %d %H:%M:%S +0000 %Y")

                # conversion en timezone locale pour js
                ca_utc = utc.localize(ca_origin)
                created_at = ca_utc.astimezone(tzone).strftime('%Y-%m-%d %H:%M:%S')

                print "%s: %s" % (data['user']['screen_name'], data['text'])

                links = get_links(data)
                medias = get_medias(data)
                message_html = make_rich_links(data['text'], links, medias)

                # Enregistrement du Tweet
                new_tweet = Tweet('TWITTER', data['id_str'], data['user']['screen_name'], data['text'], message_html,
                            data['user']['profile_image_url'], links, medias, ca_origin,
                            get_config_param('modo_type'), 0 )

                session.add(new_tweet)
                session.commit()

                # Publication du tweet sur le channel Pusher
                new_pusher_tweet = {
                    'id': new_tweet.id,
                    'provider': new_tweet.provider,
                    't_id': new_tweet.ref_id,
                    'author': new_tweet.author,
                    'message': new_tweet.message,
                    'message_html': new_tweet.message_html,
                    'avatar': new_tweet.avatar,
                    'links': new_tweet.links,
                    'medias': new_tweet.medias,
                    'ctime': created_at,
                    'visible': new_tweet.visible
                }

                pushy['Channel_%s' % get_config_param('channel_id')].trigger('new_twut', new_pusher_tweet)

                print "-----------------------------------------------"

        else:
            # @todo Pas de traitement pour les stall_warnings, on ne fait que les afficher
            print data

        return True

    def on_error(self, status):
        print status
        return True # Don't kill the stream

    def on_timeout(self):
        print 'Timeout...'
        return True # Don't kill the stream


if __name__ == "__main__":
    start_grabber()
