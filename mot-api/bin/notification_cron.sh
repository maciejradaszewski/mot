#!/bin/bash
[ -e /etc/dvsa/notifications ] && . /etc/dvsa/notifications

DEFAULT_HOST=${DEFAULT_HOST:-"http://mot-api.mot.gov.uk"};
CRON_USERNAME=${CRON_USERNAME:-"cron-job"};
CRON_HASH_PASSWORD=${CRON_HASH_PASSWORD:-"UGFzc3dvcmQxCg=="};
CRON_PASSWORD=`echo "$CRON_HASH_PASSWORD" | base64 --decode`
E_NOBROAD=64

if [ "$1" != "" ]
then
    MOTAPI_HOST=$1;
else
    MOTAPI_HOST=$DEFAULT_HOST;
fi

clear;

echo "Using Host: $MOTAPI_HOST     ( Use $0 [host] to overide default host )";

echo 'Sending Auth Request...';
authRequest=$(curl -s -H Content-Type:application/json -X POST "$MOTAPI_HOST/session" -d "{\"username\": \"$CRON_USERNAME\", \"password\": \"$CRON_PASSWORD\"}");

echo 'Parsing AccessToken...';
accessToken=$(ruby -rjson -e "j= JSON.parse('$authRequest'); puts j['data']['accessToken'] ");

echo 'Sending Broadcast Notification request...';
notificationResponse=$(curl -s -H Content-Type:application/json -H "Authorization:Bearer $accessToken" -X POST "$MOTAPI_HOST/special-notice-broadcast");
notificationSuccess=$(ruby -rjson -e "j=JSON.parse('$notificationResponse'); puts j['data']['success']");

if [ $notificationSuccess ]
 then
 	echo 'Successfully sent Broadcast messages';
 else
 	echo 'Failed to send Broadcast messages';
  exit $E_NOBROAD
fi
