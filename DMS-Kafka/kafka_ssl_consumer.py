from kafka import KafkaConsumer
import threading, logging, time
import json
import ssl
import random, string
import requests

conf = {
    'bootstrap_servers': ["ip:port","ip:port","ip:port"],
    'topic_name': 'topic-name',
    'consumer_id': 'topic-consumer',
    'sasl_plain_username': 'username',
    'sasl_plain_password': 'password'
}

context = ssl.create_default_context()
context = ssl.SSLContext(ssl.PROTOCOL_SSLv23)
context.verify_mode = ssl.CERT_REQUIRED
context.load_verify_locations("/home/kafka/phy_ca.crt")

print('start consumer')
consumer = KafkaConsumer(conf['topic_name'],
                        bootstrap_servers=conf['bootstrap_servers'],
                        group_id=conf['consumer_id'],
                        sasl_mechanism="PLAIN",
                        ssl_context=context,
                        security_protocol='SASL_SSL',
                        sasl_plain_username=conf['sasl_plain_username'],
                        sasl_plain_password=conf['sasl_plain_password'])

for message in consumer:
    kafka_post = '{"kafka_topic":"' + str(message.topic) + '","kafka_message":"' + str(message.value).replace('b','') + '"}'
    print(kafka_post)
    headers = {
         'Content-type': 'application/json',
    }
    # POST to Cloud Search Service
    response = requests.post('https://ip:port/index/_doc/', auth=('username', 'password'), verify=False, headers=headers, data=kafka_post)

print('end consumer')
