#!/usr/bin/env python

from kafka import KafkaProducer
from datetime import datetime
import threading, logging, time
import json
import ssl
import random, string, socket

def get_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.settimeout(0)
    try:
        # doesn't even have to be reachable
        s.connect(('10.255.255.255', 1))
        IP = s.getsockname()[0]
    except Exception:
        IP = '127.0.0.1'
    finally:
        s.close()
    return IP
        
def gen_text(length):  
    timestamp = datetime.today().strftime('%Y-%m-%d-%H:%M:%S')
    hostname = socket.gethostname()
    hostip = get_ip()
    
    sample_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' # define the specific string  
    message = ''.join((random.choice(sample_str)) for x in range(length))
    result = '{"hostname":"' + hostname + '","hostip":"' + hostip + '","timestamp":"' + timestamp + '","message":"' + message + '","topic":"topic-sub1"}'    
    
    return result

context = ssl.create_default_context()
context = ssl.SSLContext(ssl.PROTOCOL_SSLv23)
context.verify_mode = ssl.CERT_REQUIRED
context.load_verify_locations("/home/kafka/phy_ca.crt")

print('start producer to Kafka SubAcc01')
Kafkaconf1 = {
    'bootstrap_servers': ["ip:port","ip:port","ip:port"],
    'topic_name': 'topic-name',
    'sasl_plain_username': 'username',
    'sasl_plain_password': 'password'
}
producer1 = KafkaProducer(bootstrap_servers=Kafkaconf1['bootstrap_servers'],
                        sasl_mechanism="PLAIN",
                        ssl_context=context,
                        security_protocol='SASL_SSL',
                        sasl_plain_username=Kafkaconf1['sasl_plain_username'],
                        sasl_plain_password=Kafkaconf1['sasl_plain_password'])


i = 0
while i < 2:
  mytext = gen_text(20)
  data = bytes(mytext, encoding="utf-8")
  producer1.send(Kafkaconf1['topic_name'], data)
  i = i + 1

producer1.close()

print('end producer to Kafka SubAcc01')
print('==============================')
print('start producer to Kafka SubAcc02')

Kafkaconf2 = {
    'bootstrap_servers':["ip:port","ip:port","ip:port"],
    'topic_name': 'topic-name',
    'sasl_plain_username': 'username',
    'sasl_plain_password': 'password'
}
producer2 = KafkaProducer(bootstrap_servers=Kafkaconf2['bootstrap_servers'],
                        sasl_mechanism="PLAIN",
                        ssl_context=context,
                        security_protocol='SASL_SSL',
                        sasl_plain_username=Kafkaconf2['sasl_plain_username'],
                        sasl_plain_password=Kafkaconf2['sasl_plain_password'])

i = 0
while i < 2:
     mytext = gen_text(20)
     data = bytes(mytext, encoding="utf-8")
     producer2.send(Kafkaconf2['topic_name'], data)
     i = i + 1
producer2.close()
#time.sleep(5)
print('end producer to Kafka SubAcc02')
print('==============================')

