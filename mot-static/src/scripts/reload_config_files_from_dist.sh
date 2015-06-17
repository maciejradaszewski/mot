#!/bin/sh

# This script drops all .php files from config/autoload/ (web and api)
# and copies *.dist files to replace dropped ones.
#
# Used by: mot-static/src/grunt/config/shell.js::build_dist_update
#   example: `grunt build:config-reload`
#
# Reloads config from *.dist files
# There must be given ONE argument
#   string dir
function config_reload {
    dir=$1

    echo "\n$(date) removing all $1*.php config scripts"
    rm -f $1*.php

    for distFile in $1*.dist
    do
        configFile=${distFile%.dist}
        echo "$(date) copying ${distFile} to ${configFile}"
        cp ${distFile} ${configFile}
    done
    echo "Done for $1"
}

config_reload ./mot-api/config/autoload/
config_reload ./mot-web-frontend/config/autoload/
