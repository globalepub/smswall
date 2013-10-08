# -*- coding: utf-8 -*-
from sqlalchemy import Column, Integer, String, Text, DateTime
from sqlalchemy.ext.declarative import declarative_base
import json

Base = declarative_base()

class ConfigWall(Base):
    __tablename__ = 'config_wall'
    id = Column(Integer, primary_key=True)
    channel_id = Column(String(50))
    modo_type = Column(Integer)
    hashtag = Column(Text)
    userstream = Column(Integer)
    phone_number = Column(String(16))
    theme = Column(String(20))
    bulle = Column(Integer)
    avatar = Column(Integer)
    retweet = Column(Integer)
    ctime = Column(DateTime)
    mtime = Column(DateTime)

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

class Tweet(Base):
    __tablename__ = 'messages'
    id = Column(Integer, primary_key=True)
    provider = Column(String(20))
    ref_id = Column(String(20))
    author = Column(String(25))
    message = Column(Text)
    message_html = Column(Text)
    avatar = Column(String(250))
    links = Column(Text)
    medias = Column(Text)
    ctime = Column(DateTime)
    visible = Column(Integer)

    def __init__(self, provider='TWITTER', ref_id=None, author=None, message=None, message_html=None, avatar=None, links=None, medias=None, ctime=None, visible=None):

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

    def __repr__(self):
        return '<Tweet %s>' % self.ref_id

