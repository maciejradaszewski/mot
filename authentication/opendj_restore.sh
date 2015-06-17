#!/bin/bash

#backup \
# --port 4444 \
# --bindDN "cn=Directory Manager" \
# --bindPassword cangetniam \
# --backendID userRoot \
# --backupDirectory ./opendj/mot-`date -u +%F-%m-%S`.bak \
# --start 0

#/opt/opendj/bin/import-ldif  \
# --port 4444 \
# --hostname openam.mot.gov.uk \
# --bindDN "cn=Directory Manager" \
# --bindPassword cangetinam \
# --includeBranch dc=mot,dc=gov,dc=uk \
# --backendID userRoot \
# --append \
# --ldifFile /workspace/authentication/opendj/External.ldif \
# --trustAll \

ImportLDIFFile(){

echo -en "\033[32m\033[40m Importing User LDIF Files \033[31m\033[40m"

/opt/opendj/bin/import-ldif  \
 --port 4444 \
 --hostname localhost \
 --bindDN "cn=Directory Manager" \
 --bindPassword cangetinam \
 --includeBranch dc=mot,dc=gov,dc=uk \
 --backendID userRoot \
 --append \
 --ldifFile /workspace/authentication/opendj/External.ldif \
 --trustAll

if [ $? -ne 0 ]; then
   	echo -en "\033[31m\033[40m Importing Users Failed"
    return 1
else
   	echo -en "\033[32m\033[40m User LDIF File Imported \033[31m\033[40m"
fi

echo -en "\033[32m\033[40m Importing Roles LDIF Files \033[31m\033[40m"

/opt/opendj/bin/import-ldif \
 --port 4444 \
 --hostname localhost \
 --bindDN "cn=Directory Manager" \
 --bindPassword cangetinam \
 --includeBranch dc=mot,dc=gov,dc=uk \
 --backendID userRoot \
 --append \
 --ldifFile /workspace/authentication/opendj/Roles.ldif \
 --trustAll

if [ $? -ne 0 ]; then
   	echo -en "\033[31m\033[40m Importing Roles Failed"
    return 1
else
   	echo -en "\033[32m\033[40m LDIF Files Imported \033[31m\033[40m"
fi
}

CreateUserLDIF(){
cd /workspace/authentication/
./create_users.sh

if [ $? -ne 0 ]; then
   echo "Importing Users Failed"
   return 1
else
   echo "Imported User Data"
fi
}

CreateUserLDIF &&
ImportLDIFFile