#!/bin/bash

##########################################
# Remove outdated entries from table: Jobs
# To be exec every day.
##########################################

##### BEGIN BLOCK: CRON
## Extra commands required for crontab exec of the script

export  PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin ## opt 3 (use Terminal's)
####

####
## crontab exec the script from '/root' directory
## hence local dependencies in the project may not
## be found.
## SOLUTION: Find script's directory ($DIR)
## and move to it.
DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
cd $DIR
####
##### END BLOCK: CRON

#
. admin_config.sh # or: source ./config.sh
#
# Copy table:
# popsql.com/learn-sql/mysql/how-to-duplicate-a-table-in-mysql
#
# Remove outdated entries:
# www.tutorialspoint.com/mysql-query-to-delete-all-rows-older-than-30-days
#
# Reorder/Reset Primary Key (Id):
# stackoverflow.com/questions/740358/reorder-reset-auto-increment-primary-key
#
############################################

mysql -u "${USER}" -p"${PASSWORD}" "${DATABASE}" <<EOF

-- Commented because not easy to drop
-- table with dynamic name:
-- SET @tbl_name = CONCAT("Jobs_simo_exact_copy_",now()); SET @sql = CONCAT("DROP TABLE IF EXISTS Jobs_exact_copy_from_simo_????; CREATE TABLE",@tbl_name," LIKE Jobs; INSERT INTO Jobs_exact_copy_from_simo_???? SELECT * FROM Jobs;"); PREPARE stmt from @sql; EXECUTE stmt;

-- Remove outdated entries
DELETE FROM Jobs WHERE Cierre < NOW() - INTERVAL 1 DAY;
-- Test with: SELECT Cierre FROM Jobs...
-- Final implementation: DELETE FROM Jobs...
EOF
# Older than today: NOW();
# Older than 1 day: NOW() - INTERVAL 1 DAY;
# Older than 1 year: NOW() - INTERVAL 1 YEAR; etc
