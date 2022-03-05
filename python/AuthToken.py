#!/usr/bin/python
import requests, json, os, sys
from configparser import ConfigParser

def AuthToken(huawei_id, huawei_iam, huawei_iampwd, region):
    url = "https://iam.{}.myhuaweicloud.com/v3/auth/tokens".format(region)

    headers = {'Content-Type': 'application/json'}

    body = { 
        "auth": { 
            "identity": { 
                "methods": [ 
                    "password" 
                ], 
                "password": { 
                    "user": { 
                        "name": "" + huawei_iam + "",
                        "password": "" + huawei_iampwd + "", 
                        "domain": { 
                            "name": "" + huawei_id + "" 
                        } 
                    } 
                } 
            }, 
            "scope": { 
                "project": { 
                    "name": region 
                } 
            } 
        } 
    }

    response = requests.post(url, headers=headers, json=body)
    return (response.headers["X-Subject-Token"])

def getProjectID(huawei_id, huawei_iam, huawei_iampwd, region):
    url = "https://iam.{}.myhuaweicloud.com/v3/auth/tokens".format(region)

    headers = {'Content-Type': 'application/json'}

    body = { 
        "auth": { 
            "identity": { 
                "methods": [ 
                    "password" 
                ], 
                "password": { 
                    "user": { 
                        "name": "" + huawei_iam + "",
                        "password": "" + huawei_iampwd + "", 
                        "domain": { 
                            "name": "" + huawei_id + "" 
                        } 
                    } 
                } 
            }, 
            "scope": { 
                "project": { 
                    "name": region 
                } 
            } 
        } 
    }

    response = requests.post(url, headers=headers, json=body)    
    return (json.loads(response.text)['token']['project']['id'])

if __name__ == "__main__":
    AuthToken("ap-southeast-2")
