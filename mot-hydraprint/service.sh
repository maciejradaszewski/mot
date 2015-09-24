#!/bin/bash

JAVA_HOME="/usr/lib/jvm/jre-1.8.0"
CMD_KILL_EXISTING_WORKER='ps x | grep -v "grep" | grep mot-hydraprint-worker | awk "{print $1}" | xargs kill -9 2> /dev/null'

CMD_GOTO_HOME="cd /workspace/mot-hydraprint"

function check_download_needed {
  local URL=$1	
	local BASEFILE=$2
	
	if [ -f $BASEFILE ];
	then
		REMOTE_CHECKSUM=`curl -sS "${URL}.md5"`
		LOCAL_CHECKSUM=`md5 $BASEFILE | awk '{print $4}'`
		if [ "$REMOTE_CHECKSUM" == "$LOCAL_CHECKSUM" ];
			then return 0
			else return 1
		fi
	else
		return 1
	fi
}

function check_worker_status {
	CHECK_CMD='ps x | grep -v "grep" | grep mot-hydraprint-worker | awk "{print $1}"'
	run_foreground_command "$CHECK_CMD"
}

function reset_pending {
	local URL=$1
	local BASEFILE=${URL##*/}
	
	check_download_needed "$URL" "$BASEFILE"
	if [ $? -ne 0 ];
	then
		download_file "$URL"
	fi
	ensure_java
	local CMD_RUN="$JAVA_HOME/bin/java -jar $BASEFILE --spring.config.location=file:pending_resetter.properties"
	local CMD="$CMD_GOTO_HOME; $CMD_RUN"
	
	echo "Resetting pending requests..."
	run_foreground_command "$CMD"
	echo "Pending requests reset"
}

function reset_failures {
	local URL=$1
	local BASEFILE=${URL##*/}

	check_download_needed "$URL" "$BASEFILE"
	if [ $? -ne 0 ];
	then
		download_file "$URL"
	fi
	ensure_java
  local CMD_RUN="$JAVA_HOME/bin/java -jar $BASEFILE --spring.config.location=file:failure_resetter.properties"
	local CMD="$CMD_GOTO_HOME; $CMD_RUN"
	
	echo "Resetting failed requests..."
	run_foreground_command "$CMD"
	echo "Failed requests reset"
}


function start_worker {
	local URL=$1
	local BASEFILE=${URL##*/}

	check_download_needed "$URL" "$BASEFILE"
	if [ $? -ne 0 ];
	then
		download_file "$URL"
	fi

	ensure_java
	
  local CMD_RUN="$JAVA_HOME/bin/java -jar $BASEFILE --spring.config.location=file:worker.properties"
	local CMD="$CMD_GOTO_HOME; $CMD_KILL_EXISTING_WORKER; $CMD_RUN"

	echo "Starting worker..."
	run_background_command "$CMD"
	echo "Worker started"
}

function stop_worker {
	echo "Stopping worker..."
	run_foreground_command "$CMD_KILL_EXISTING_WORKER"
}

function ensure_java {

	echo "Ensuring Java 8..."
	cd $dev_workspace/../infrastructure
	vagrant ssh lamp-mot -c "$CMD_GOTO_HOME; ./ensure_java.sh"
}

function run_foreground_command {
	cd $dev_workspace/../infrastructure
	vagrant ssh lamp-mot -c "$1"
}

function run_background_command {
	cd $dev_workspace/../infrastructure
	nohup vagrant ssh lamp-mot -c "$1" >/dev/null 2>&1 &
}

function download_file {
	curl -O -J $1
	RET_CODE=$?
	if [ $RET_CODE -ne 0 ]; then
		echo "Could not download file $1"
		exit $RET_CODE
	fi
}

case "$1" in
  start-worker)	
			URL=`cat worker_source`
			start_worker "$URL"
    ;;
  stop-worker)
    stop_worker
    ;;
	worker-status)
		check_worker_status
	;;
	reset-pending)
		URL=`cat pending_resetter_source`
		reset_pending "$URL"
		;;
	reset-failed)
		URL=`cat failure_resetter_source`
		reset_failures "$URL"
		;;
  *)
    echo $"Usage: $0 {start-worker|stop-worker|worker-status|reset-pending|reset-failed}"
    exit 1
esac

exit 0

