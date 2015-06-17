#!/bin/bash
HOST=$(hostname -a |awk '{ print $1 }')
if [ "$HOSTNAME" == "lamp-mot" ]; then
        echo "Vagrant"  
    else
        sed -i s/openam.mot.gov.uk/vipm-opendj.mg.npm/g $WORKSPACE/authentication/FlushLDAP/mot2/flushldap_0_1/contexts/Default.properties
        sed -i s/openam.mot.gov.uk/vipm-opendj.mg.npm/g $WORKSPACE/authentication/userSync_0.1/mot2/usersync_0_1/contexts/Default.properties
        sed -i s/External/$HOST/g $WORKSPACE/authentication/userSync_0.1/mot2/usersync_0_1/contexts/Default.properties
        sed -i s/External/$HOST/g $WORKSPACE/authentication/FlushLDAP/mot2/flushldap_0_1/contexts/Default.properties
        echo "Setting Build Server LDAP Settings"
fi

./FlushLDAP/FlushLDAP_run.sh
./userSync_0.1/userSync_run.sh
echo -e "\033[32mUsers have been reset and synced\033[0m"
