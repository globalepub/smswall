# -*- coding: utf-8 -*-
from sqlalchemy.orm import sessionmaker
from sqlalchemy import create_engine
from sqlalchemy.engine.url import URL
import time, math
from datetime import datetime

from config import *
from models import ConfigWall, Base

engine = create_engine(URL(**DATABASE))
Base.metadata.create_all(engine)
Session = sessionmaker(bind=engine)
session = Session()

# génération du channel_id Pusher
m = time.time()
uniqid = '%8x%05x' %(math.floor(m),(m-math.floor(m))*1000000)

config_wall = ConfigWall(uniqid, 1, '#rennes', 0, '0606060606', 'default', 6, 1, 1, datetime.utcnow())

session.add(config_wall)
session.commit()
