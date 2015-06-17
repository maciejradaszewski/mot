#!/bin/bash
# Updates the context.txt file with paths.
# You don't need to run this script, it's included by other scripts where necessary

if [ -n "$1" ]; then
    echo "This script no longer takes parameters"
    exit 1
fi

set -o errexit
pushd `dirname $0` > /dev/null
SCRIPTPATH=`pwd`
popd > /dev/null
sed -e "s+REPLACE_ME+$SCRIPTPATH+" < FitNesseRoot/content.txt.mac.dist > FitNesseRoot/content.txt
if [ ! -e $SCRIPTPATH/FitNesseRoot/Slim/MotFitnesse/Util/TestBase.php ] ; then
  cp -v $SCRIPTPATH/FitNesseRoot/Slim/MotFitnesse/Util/TestBase.php.dist $SCRIPTPATH/FitNesseRoot/Slim/MotFitnesse/Util/TestBase.php
fi

if [ ! -e $SCRIPTPATH/FitNesseRoot/RecentChanges/content.txt ] ; then
  touch $SCRIPTPATH/FitNesseRoot/RecentChanges/content.txt
fi

case $(uname) in
Darwin)
    SHA="shasum -a 256"
    ;;
Linux)
    SHA="sha256sum"
    ;;
*)
    echo "Unknown OS $(uname)!" 1>&2
    exit 1
    ;;
esac
set +o errexit
$SHA -c $SCRIPTPATH/fitnesse-standalone.jar.sha256
MATCHED=$?
set -o errexit
if [ "$MATCHED" != "0" ] ; then
    curl -o $SCRIPTPATH/fitnesse-standalone.jar 'http://fitnesse.org/fitnesse-standalone.jar?responder=releaseDownload&release=20140201'
    $SHA -c $SCRIPTPATH/fitnesse-standalone.jar.sha256
fi

mkdir -p -v $SCRIPTPATH/FitNesseRoot/files/testResults/ # TODO It may be better to check in this directory than create it here
