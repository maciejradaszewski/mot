#!/bin/bash
set -o errexit
MyUSER=${1-"root"}
MyPASS=${2-"root"}
MyHOST=${3-"localhost"}

mysql -u $MyUSER -p$MyPASS -h $MyHOST < ./schema/create_db.sql
