"""
This Lambda function accepts the following input:    {"bucketName":"your-bucket-name","fileName":"your-file.*"}
This Lambda function downloads your-file.* from your-bucket-name S3 bucket, zips the file, then uploads the zipped file (zipped-your-file.zip) to the same bucket
"""

import boto3
import zipfile
import uuid
from urllib.parse import unquote_plus
from botocore.exceptions import ClientError

s3_client = boto3.client('s3')

def lambda_handler(event, context):
    try:
        bucket_name = event["bucketName"]
        file_name = event["fileName"]
        key = unquote_plus(file_name)
        tmpkey = key.replace('/', '')
        download_path = '/tmp/{}{}'.format(uuid.uuid4(), tmpkey)
        upload_path = '/tmp/zipped-{}.zip'.format(tmpkey)
        
        s3_client.download_file(bucket_name, key, download_path)
        zip_file(download_path, upload_path, tmpkey)
        s3_client.upload_file(upload_path, bucket_name, 'zipped-{}.zip'.format(tmpkey))
    except ClientError as e:
        return "Lambda's error: Error code: {}, HTTPStatusCode: {}, Message: {}".format(e.response['Error']['Code'], e.response['ResponseMetadata']['HTTPStatusCode'], e.response['Error']['Message'])
    except OSError as ose:
        return "Lambda's error: Message: {}".format(ose)

def zip_file(input_file, output_file, tmpkey):
    with zipfile.ZipFile(output_file, 'w', zipfile.ZIP_DEFLATED) as zipf:
        zipf.write(input_file, 'zipped-{}'.format(tmpkey))
