#!/bin/bash
set -o errexit
set -o nounset

MyUSER=${1-"root"}
MyPASS=${2-"password"}
MyHOST=${3-"localhost"}
MyDATABASE=${4-${MOT_DATABASE-"mot"}}

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR

./create_context.sh
java -Ddb.name=$MyDATABASE -Ddb.user=$MyUSER -Ddb.password=$MyPASS -Ddb.host=$MyHOST -Ddb.name=$MyDATABASE -jar fitnesse-standalone.jar -p 8091
