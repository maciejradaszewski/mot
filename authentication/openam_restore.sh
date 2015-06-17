#!/bin/bash

# Script to populate the OpenAM config
# This uses the file mot-backup.xml
# If you wish to replace the config replace this file.

/opt/openam/admintools/secure/bin/ssoadm \
        import-svc-cfg \
        --adminid amadmin \
        --encryptsecret rxTMRjAYWR4xtfP604k01b5x98yrgSit \
        --password-file /etc/openam/.amadmin_password \
        --xmlfile /workspace/authentication/openam/mot-backup.xml &&

/sbin/service tomcat6 restart
