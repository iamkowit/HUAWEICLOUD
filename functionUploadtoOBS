# -*- coding: utf-8 -*-
# When running this sample code to access OBS, you must specify an agency with global service access permissions (or at least with OBS access permissions).

## Need to add 6 Environment Variables 
## objBucket
## obsAddress
## fileName
## fileURL
## uName
## uPass

from obs import ObsClient #Require public dependency:esdk_obs_python-3.x
from datetime import datetime
from requests.auth import HTTPBasicAuth
import sys
import os
import requests
# import httplib2
# import ssl


def handler (event, context):
    logger = context.getLogger()                     # Obtains a log instance.

    objBucket = context.getUserData('objBucket')     # Enter the name of the bucket to which you want to upload files.
    fileName  = context.getUserData('fileName')      # Enter the file name. By default, the file is placed under the same directory as the function entry.
    fileURL  = context.getUserData('fileURL')      # Enter the URL. 
    uName  = context.getUserData('uName')      # Enter the URL. 
    uPass  = context.getUserData('uPass')      # Enter the URL. 

    now = datetime.now() # current date and time
    date_time = now.strftime("%Y%m%d%H%M%S")
    tmpfileName = fileName + "_" + date_time
    
    if objBucket is None or fileName is None or fileURL is None or uName is None or uPass is None:
        logger.error("Please set environment variables objBucket, fileName, fileURL, uName and uPass.")
        return ("Please set environment variables objBucket, fileName, fileURL, uName and uPass.")

    # You are advised to use the log instance provided by FunctionGraph to debug or print messages and not to use the native print function.
    logger.info("*** objBucket: " + objBucket)
    logger.info("*** fileName:" + fileName)

    # Obtains a temporary AK and SK. An agency is required to access IAM.
    ak = context.getAccessKey()
    sk = context.getSecretKey()

    if ak == "" or sk == "":
        logger.error("Failed to access OBS because no temporary AK, SK, or token has been obtained. Please set an agency.")
        return ("Failed to access OBS because no temporary AK, SK, or token has been obtained. Please set an agency.")

    obs_address = context.getUserData('obsAddress')  # Domain name of the OBS service. Use the default value.
    if obs_address is None:
        obs_address = 'obs.ap-southeast-2.myhuaweicloud.com'
    create_tmp_file(tmpfileName, fileURL, uName, uPass)

    #fileName = resp 
    # Uploads a file to a specified bucket.
    status = upload_file_to_obs(obs_address, objBucket, fileName, tmpfileName, ak, sk)
    if (status == 200 or status == 201):
        logger.info("File uploaded to OBS successfully. View details in OBS.")
        return ("File uploaded to OBS successfully. View details in OBS.")
    else:
        logger.error("Failed to upload the file to OBS.")
        return ("Failed to upload the file to OBS.")

def create_tmp_file(filename, fileURL, uName, uPass):
    resp = requests.get(fileURL, auth=HTTPBasicAuth(uName, uPass), verify=False)
    #print(resp)
    file_path = os.path.join('/tmp', filename)
    open(file_path, 'wb').write(resp.content)
        #f.write('test')
        
def upload_file_to_obs(obsAddr, bucket, fileName, objName, ak, sk):
    TestObs = ObsClient(access_key_id=ak, secret_access_key=sk,server=obsAddr)
    objAbsPath = os.path.join('/tmp', objName)   # Obtains the absolute path of a local file.
    resp = TestObs.putFile(bucketName=bucket, objectKey=fileName + '/' + objName, file_path=objAbsPath)
    if isinstance(resp, list):
        for k, v in resp:
            print('PostObject, objectKey',k, 'common msg:status:', v.status, ',errorCode:', v.errorCode, ',errorMessage:', v.errorMessage)
    else:
        print('PostObject, common msg: status:', resp.status, ',errorCode:', resp.errorCode, ',errorMessage:', resp.errorMessage)
    # Returns the status code of a POST event.
    return (int(resp.status))
