# -*- coding: utf-8 -*-

from flask import Flask, json
from flask.ext.script import Manager
from flask.ext.sqlalchemy import SQLAlchemy
from sqlalchemy import create_engine, desc
from sqlalchemy.orm import scoped_session, create_session
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream
import pusher
import re
from datetime import datetime
import pytz
import ast

from config import *

utc = pytz.utc
tzone = pytz.timezone('Europe/Paris')

app = Flask(__name__)
app.debug = True

manager = Manager(app)

engine = None
db_session = scoped_session(lambda: create_session(bind=engine))
db = SQLAlchemy(app)


def init_engine(uri, **kwargs):
    global engine
    engine = create_engine(uri, **kwargs)
    return engine


@manager.command
def start(user=DATABASE['username'], password=DATABASE['password'], serverhost=DATABASE['host'],bdd=DATABASE['database']):
    """ Lancer le grabber. Usage :\n\tpython grabber.py start -u user -p password -s localhost -b smswall\n\tValeur par défaut: config.py"""

    app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql://%s:%s@%s/%s' % (user,password,serverhost,bdd)
    app.config['SQLALCHEMY_NATIVE_UNICODE'] = True
    app.config['SECRET_KEY'] = 'okokiputanotherkeyhere'

    init_engine(app.config['SQLALCHEMY_DATABASE_URI'])
    app.OLD_HASH = get_config_param('hashtag')

    start_grabber()


def start_grabber():
    s = SmsWallListener()
    auth = OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)
    app.stream = Stream(auth, s, timeout=43200.0) # timeout: 12 heures

    if not get_config_param('userstream'):
        print "lancement du grabber. Hashtag en cours : %s" % app.OLD_HASH
        app.stream.filter(track=[ get_config_param('hashtag') ], stall_warnings=True)
    else:
        print "stream du compte Twitter associé à l'application WallFactory"
        app.stream.userstream()

# TWITTER

# Go to http://dev.twitter.com and create an app.
# The consumer key and secret will be generated for you after
#consumer_key="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
#consumer_secret="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"

# After the step above, you will be redirected to your app's page.
# Create an access token under the the "Your access token" section
#access_token="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
#access_token_secret="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"

# PUSHER

#pusher_app_id='xxxx'
#pusher_key='xxxxxxxxxx'
#pusher_secret='xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'

pushy = pusher.Pusher(app_id=pusher_app_id, key=pusher_key, secret=pusher_secret)



class ConfigWall(db.Model):
    __tablename__ = 'config_wall'
    id = db.Column(db.Integer, primary_key=True)
    channel_id = db.Column(db.String(50))
    modo_type = db.Column(db.Integer)
    hashtag = db.Column(db.Text)
    userstream = db.Column(db.Integer)
    phone_number = db.Column(db.String(12))
    theme = db.Column(db.String(20))
    bulle = db.Column(db.Integer)
    avatar = db.Column(db.Integer)
    retweet = db.Column(db.Integer)
    ctime = db.Column(db.DateTime)
    mtime = db.Column(db.DateTime)

    def __init__(self, channel_id=None, modo_type=None, hashtag=None, userstream=None, phone_number=None, theme=None, bulle=None, avatar=None, retweet=None, ctime=None, mtime=None):
        self.channel_id = channel_id
        self.modo_type = modo_type
        self.hashtag = hashtag
        self.userstream = userstream
        self.phone_number = phone_number
        self.theme = theme
        self.bulle = bulle
        self.avatar = avatar
        self.retweet = retweet
        self.ctime = ctime
        self.mtime = mtime

    def __repr__(self):
        return '<ConfigWall %s : %s>' % (self.channel_id, self.hashtag)

class Tweet(db.Model):
    __tablename__ = 'messages'
    id = db.Column(db.Integer, primary_key=True)
    provider = db.Column(db.String(20))
    ref_id = db.Column(db.String(20))
    author = db.Column(db.String(25))
    message = db.Column(db.Text)
    message_html = db.Column(db.Text)
    avatar = db.Column(db.String(250))
    links = db.Column(db.Text)
    medias = db.Column(db.Text)
    ctime = db.Column(db.DateTime)
    visible = db.Column(db.Integer)
    bulle = db.Column(db.Integer)

    def __init__(self, provider='TWITTER', ref_id=None, author=None, message=None, message_html=None, avatar=None, links=None, medias=None, ctime=None, visible=None, bulle=None):

        self.provider = provider
        self.ref_id = ref_id
        self.author = author
        self.message = message
        self.message_html = message_html
        self.avatar = avatar
        self.links = json.dumps(links)
        self.medias = json.dumps(medias)
        self.ctime = ctime
        self.visible = visible
        self.bulle = bulle

    def __repr__(self):
        return '<Tweet %s>' % self.ref_id

    def make_link(self, txt):
        r = re.compile(r"(http://[^ ]+)")
        return r.sub(r'<a href="\1" rel="nofollow" target="_blank">\1</a>', txt)


def make_rich_links(txt, links, medias):
    html = txt
    if links:
        for link in links:
            r = re.compile(r"(%s)" % link['url'])
            html = r.sub(r'<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">\1</a>', html)
            html = html % (link['expanded_url'], link['type'], link['expanded_url'])
    if medias:
        for media in medias:
            r = re.compile(r"(%s)" % media['url'])
            html = r.sub(r'<a href="%s" rel="nofollow" target="_blank" data-type="%s" data-toggle="tooltip" title="%s">\1</a>', html)
            html = html % (media['media_url'], media['type'], media['media_url'])
    return html


def check_last_tweet():
    t = Tweet.query.order_by(desc(Tweet.ref_id)).first()
    return t


def get_config_param(param):
    h = ConfigWall.query.first()
    resp = h.__getattribute__(param)
    if param == 'hashtag':
        resp = resp.encode('utf8')
        #print resp
    return resp



class SmsWallListener(StreamListener):
    """ A listener handles tweets are the received from the stream. """

    def on_data(self, data):

        current_hash = get_config_param('hashtag')

        if current_hash != app.OLD_HASH:
            app.OLD_HASH = current_hash
            app.stream.disconnect()
            del(app.stream)
            start_grabber()

        data = json.loads(data)

        #if data['id_str'] > last_tweet_id:
        if 'id_str' in data:
            if get_config_param('retweet') or (not get_config_param('retweet') and 'retweeted_status' not in data):

                # created_at en UTC pour la BDD
                ca_origin = datetime.strptime(data['created_at'], "%a %b %d %H:%M:%S +0000 %Y")

                # conversion en timezone locale pour la vue
                ca_utc = utc.localize(ca_origin)
                ca_fr = ca_utc.astimezone(tzone)
                # Format ISO-8601 pour coller à Moment.js
                fmt = '%Y-%m-%d %H:%M:%S'
                created_at = ca_fr.strftime(fmt)

                # liens interne à l'ancienne (comparaison du tweet brut et du tweet htmlisé)
                # internallink = True if new_tweet.title != new_tweet.description else False

                # liens interne basé sur l'objet 'entities' de la réponse de l'API Twitter
                internallink = False
                links = ""
                if 'entities' in data and 'urls' in data['entities'] and len(data['entities']['urls']):
                    internallink = True
                    links = []
                    for url in data['entities']['urls']:
                        links.append({"type": "link", "url": url['url'], "expanded_url": url['expanded_url']})
                        print "link : %s" % url['expanded_url']


                medias = ""
                if 'entities' in data and 'media' in data['entities']:
                    internallink = True
                    medias = []
                    for media in data['entities']['media']:
                        th = 'medium' if 'medium' in media['sizes'] else ''
                        th = 'small' if 'small' in media['sizes'] else th
                        th = 'thumb' if 'thumb' in media['sizes'] else th

                        medias.append({"type": media['type'], "url": media['url'], "media_url": media['media_url'], "thumb_size": th })
                        print "media : %s" % media['media_url']

                message_html = make_rich_links(data['text'], links, medias)

                # Enregistrement en base de donnée du Tweet
                new_tweet = Tweet('TWITTER', data['id_str'], data['user']['screen_name'], data['text'], message_html,
                            data['user']['profile_image_url'], links, medias, ca_origin,
                            get_config_param('modo_type'), 0 )

                db.session.add(new_tweet)
                db.session.commit()



                # @todo: filtrer l'enregistrement ou non des RTs en fonction de la config,
                # Actuellement c'est seulement filtré à l'affichage mais ca passe dans tous
                # les tuyaux ... pas classe
                if 'retweeted_status' in data and 'retweet_count' in data['retweeted_status']:
                    print "%s : %s" % (new_tweet.id, data['retweeted_status']['retweet_count'])
                else:
                    print 'no retweet_count'

                print "%s : %s" % (new_tweet.id, str(new_tweet.ctime))
                print "-----------------------------------------------"


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
                    'visible': new_tweet.visible,
                    # 'internallink' devrait dégager
                    'internallink': internallink,
                }

                pushy['Channel_%s' % get_config_param('channel_id')].trigger('new_twut', new_pusher_tweet)

        else:
            print data


        return True

    def on_error(self, status):
        print status
        return True # Don't kill the stream

    def on_timeout(self):
        print 'Timeout...'
        return True # Don't kill the stream


if __name__ == "__main__":
    manager.run()

