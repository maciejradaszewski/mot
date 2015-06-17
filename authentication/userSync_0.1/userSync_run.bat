%~d0
 cd %~dp0
 java -Xms256M -Xmx1024M -cp ../lib/dom4j-1.6.1.jar;../lib/mysql-connector-java-5.1.30-bin.jar;../lib/talendssl.jar;../lib/systemRoutines.jar;../lib/userRoutines.jar;.;usersync_0_1.jar; mot2.usersync_0_1.userSync --context=Default %* 