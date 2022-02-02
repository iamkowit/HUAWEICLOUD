from kafka import KafkaProducer
import threading, logging, time
import json
import ssl
import random, string

def specific_string(length):
    sample_string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' # define the specific string
    # define the condition for random string
    result = ''.join((random.choice(sample_string)) for x in range(length))
    # print(" Randomly generated string is: ", result)
    return result

conf = {
    'bootstrap_servers': ["ip:port","ip:port","ip:port"],
    'topic_name': 'topic-name',
    'sasl_plain_username': 'username',
    'sasl_plain_password': 'password'
}

context = ssl.create_default_context()
context = ssl.SSLContext(ssl.PROTOCOL_SSLv23)
context.verify_mode = ssl.CERT_REQUIRED
context.load_verify_locations("/home/kafka/phy_ca.crt")

print('start producer')
producer = KafkaProducer(bootstrap_servers=conf['bootstrap_servers'],
                        sasl_mechanism="PLAIN",
                        ssl_context=context,
                        security_protocol='SASL_SSL',
                        sasl_plain_username=conf['sasl_plain_username'],
                        sasl_plain_password=conf['sasl_plain_password'])

mytext = specific_string(100)
data = bytes(mytext, encoding="utf-8")
producer.send(conf['topic_name'], data)
producer.close()
print('end producer')
