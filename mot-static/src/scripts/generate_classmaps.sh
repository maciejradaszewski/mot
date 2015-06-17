#!/bin/sh

# Generate autoload_classmap.php for each module in API and Frontend
APP_PATHS=../../../mot-api:../../../mot-web-frontend

for app_path in ${APP_PATHS//:/ }
do
    for dir in $app_path/module/*/
    do
        dir=${dir%*/}
        php $app_path/vendor/bin/classmap_generator.php -l $dir/src -w -o $dir/autoload_classmap.php
    done

    # Correct bug in Zend parser that generates spurious classmaps
    find $app_path/module -name autoload_classmap.php -exec perl -pi -e "s/('\w+'\s+=>\s+__DIR__.+)\$/\/\/ REMOVED INVALID MAP: \1/g"  {} \;
done

