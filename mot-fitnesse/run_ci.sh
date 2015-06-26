#!/bin/bash
set -o errexit
set -o nounset
SUITE=${1-"FrontPage.SuiteAcceptanceTests.PdfCertificatePrinting?suite"}
FORMAT=${2-"xml"}

./create_context.sh

TEST_RESULTS_DIR=TestResults

mkdir -p $TEST_RESULTS_DIR

rm -rvf $TEST_RESULTS_DIR/*

# No need to mess around with awk, the non-xml output is separated into stderr already
java -Xmx512M -jar fitnesse-standalone.jar -c "$SUITE&format=$FORMAT&excludeSuiteFilter=quarantine&excludeSuiteFilter=elasticsearch&includehtml=true" | tee $TEST_RESULTS_DIR/results.xml
