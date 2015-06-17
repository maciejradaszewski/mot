%~d0
 cd %~dp0
 java -Xms256M -Xmx1024M -cp ../lib/dom4j-1.6.1.jar;../lib/talendssl.jar;../lib/systemRoutines.jar;../lib/userRoutines.jar;.;flushldap_0_1.jar; mot2.flushldap_0_1.FlushLDAP --context=Default %* 