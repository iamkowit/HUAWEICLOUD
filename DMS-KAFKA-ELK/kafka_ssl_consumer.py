#!/usr/bin/env python

from kafka import KafkaConsumer
import threading, logging, time
import json
import ssl
import random, string
import requests, logging

logging.getLogger("urllib3").propagate = False

context = ssl.create_default_context()
context = ssl.SSLContext(ssl.PROTOCOL_SSLv23)
context.verify_mode = ssl.CERT_REQUIRED
context.load_verify_locations("css ca file location")

print('Start consumer on Kafka topic-sub1 SubAcc01')

KafkaConf1 = {
    'bootstrap_servers': ["ip:port","ip:port","ip:port"],
    'topic_name': 'topic-name',
    'consumer_id': 'topic-consumer',
    'sasl_plain_username': 'username',
    'sasl_plain_password': 'password'
}
consumer1 = KafkaConsumer(KafkaConf1['topic_name'],
                        bootstrap_servers=KafkaConf1['bootstrap_servers'],
                        group_id=KafkaConf1['consumer_id'],
                        sasl_mechanism="PLAIN",
                        consumer_timeout_ms=6000,
                        ssl_context=context,
                        security_protocol='SASL_SSL',
                        sasl_plain_username=KafkaConf1['sasl_plain_username'],
                        sasl_plain_password=KafkaConf1['sasl_plain_password'])

for message in consumer1:
    consumer1.commit() 
    kafka_post = str(message.value).replace('b\'','')
    kafka_post = str(kafka_post).replace('\'','')

    headers = {
         'Content-type': 'application/json',
    }
    response = requests.post('https://cssip:9200/myindex/_doc/', auth=('admin', 'password'), verify=False, headers=headers, data=kafka_post)
    response = requests.post('https://cssip:9200/myindex/_doc/', auth=('admin', 'password'), verify=False, headers=headers, data=kafka_post)
    response.close()

consumer1.close()
print('End consumer on Kafka topic-sub1 SubAcc01')
print('================================')
