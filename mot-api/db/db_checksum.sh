#!/bin/bash
# Checksums the DB schema and data for fast restores
tar -c ./dev | md5sum | cut -b 1-32