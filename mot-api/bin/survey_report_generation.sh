#!/bin/bash

DEFAULT_HOST=${DEFAULT_HOST:-"http://mot-api"};
CRON_USERNAME=${CRON_USERNAME:-"cron-job"};
CRON_PASSWORD=${CRON_PASSWORD:-"Password1"};
E_NOREPORT=65

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
echo $authRequest


echo 'Parsing AccessToken...';
accessToken=$(python -c "import json,sys;obj=json.loads('$authRequest');print obj['data']['accessToken'];");
echo $accessToken


echo 'Sending survey report generation request...';
reportResponse=$(curl -s -H Content-Type:application/json -H "Authorization:Bearer $accessToken" -X GET "$MOTAPI_HOST/survey/reports/generate");
reportSuccess=$(python -c "import json,sys;obj=json.loads('$reportResponse');print obj['data']['total']");
echo $reportSuccess


if [ $reportSuccess ]
 then
     	echo 'Successfully generated survey reports';
 else
     	echo 'Failed to generate survey reports';
  exit $E_NOREPORT
fi