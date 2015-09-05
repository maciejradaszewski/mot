#!/bin/bash

PACKAGE="java-1.8.0-openjdk-headless"
yum list installed $PACKAGE
if [ "$?" -ne 0 ];
then 
sudo yum install -y $PACKAGE
fi

