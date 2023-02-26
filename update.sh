#!/usr/bin/env bash

LOG=`date '+update.%Y-%m-%d.log'`

php bin/fetch.php http://ftp.de.debian.org/debian testing main contrib non-free 2>&1|tee -a $LOG

#broken
#php bin/fetch.php http://ftp.ubuntu.com/ubuntu yakkety main universe multiverse restricted 2>&1|tee -a $LOG

php bin/process.php 2>&1|tee -a $LOG

php bin/sitemaps.php 2>&1|tee -a $LOG
