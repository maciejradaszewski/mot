#!/bin/bash

GetDetails(){
echo -e "********************************************"
echo -e "*      Enter Number of Test Accounts       *"
echo -e "********************************************\n"
read accountnum
echo -e "********************************************"
echo -e "*       Enter oAth Token ID                *"
echo -e "********************************************\n"
read tockenid
echo -e "dn: ou=External,dc=mot,dc=gov,dc=uk\nobjectClass: top\nobjectClass: organizationalUnit\nou: External\n" > /workspace/authentication/opendj/External.ldif
}

CreateUserImportFile(){

for i in $(seq 1  $accountnum)
do

first=$(cat first.txt | sort --random-sort | head -n 1)
second=$(cat last.txt | sort --random-sort | head -n 1)

echo -e "dn: uid=motUser.$i,ou=External,dc=mot,dc=gov,dc=uk\n\
objectClass: motUser\n\
objectClass: top\n\
objectClass: organizationalPerson\n\
objectClass: person\n\
cn: $first\n\
sn: $second\n\
givenName: $first $second\n\
oAthCounter: 1\n\
oAthSecret: $tockenid\n\
oAthTokenID: T873465\n\
mail: $first.$second@localhost
uid: motUser.$i\n\
#inetUserStatus: Active\n\
userPassword: password \n\n" >> /workspace/authentication/opendj/External.ldif

clear
tput cup 10 20
echo -e " Creating $i $first $second "

done

}

 GetDetails &&
 CreateUserImportFile