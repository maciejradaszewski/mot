#!/bin/bash

DEFAULT_HOST=${DEFAULT_HOST:-"http://mot-api"};
CRON_USERNAME=${CRON_USERNAME:-"cron-job"};
CRON_PASSWORD=${CRON_PASSWORD:-"Password1"};

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
#accessToken=$(ruby -rjson -e "j= JSON.parse('$authRequest'); puts j['data']['accessToken'] ");
accessToken=$(echo "$authRequest" | grep -Po '(?<="accessToken":")[^"]*');

echo 'Parsing cpms notifications...';
notificationResponse=$(curl -s -H Content-Type:application/json -H "Authorization:Bearer $accessToken" -X GET "$MOTAPI_HOST/notifications/process");

echo 'Finished processing notifications.'
