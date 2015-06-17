#!/bin/bash

export PHP_IDE_CONFIG="serverName=mot-fitnesse"

DIR="$(dirname "${BASH_SOURCE[0]}")"
cd $DIR
./run.sh
