#!/bin/bash
sudo /opt/openam/admintools/secure/bin/ssoadm \
 export-svc-cfg \
 --adminid amadmin \
 --encryptsecret rxTMRjAYWR4xtfP604k01b5x98yrgSit \
 --password-file /etc/openam/.amadmin_password \
 --outfile /workspace/authentication/openam/backup-`date -u +%F-%m-%S`.xml
