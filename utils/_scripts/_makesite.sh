#!/bin/bash
#
# need to :  add a user to push data in to dbs
# 			 get the current owner details from the folder
#
# - you may need to run, this will get the folder owner and set this file and others to be executable
# /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/buildsite.sh sitename.com folderowner dbname dbpassword
#
# cd /home/httpd/vhosts/
clear
#$path=/home/httpd/vhosts/
#echo $path

echo "**************************************************************************"
echo "Building Site for" $1
echo "**************************************************************************"
echo "backing up current httpdocs in target site ... wait [20 seconds]"
## go to the host dir
cd /home/httpd/vhosts

## Goto the target site and record the current owner name
cp -rf /home/httpd/vhosts/$1/httpdocs /home/httpd/vhosts/$1/bu_httpdocs
echo "httpdocs backed up"

## must go to directory to get this
#echo "*** Owner name is "
#cd /home/httpd/vhosts/$1
#ls -l | grep httpdocs | awk '{print $3}'

##  remove the current httpdocs
echo " > removing the current httpdocs"
rm -rf $1/httpdocs
## rm -rf  /home/httpd/vhosts/lowflyersbusinesscoaching.com/httpdocs

############################
## Do the big copy
############################
# -p to preserve file dates
# -r recursive
# -v verbose - to show progress
# -f force

echo "**************************************************************************"
echo "Preparing to copy files for " $1
echo "**************************************************************************"

cd /home/httpd/vhosts

##  copy from base [add -v for details]
cp -rf  -p codebase.co.uk/httpdocs $1

echo "Neutralizing database connection details"
##  kill database connection
sed -i 's/codebase/*/g' $1/httpdocs/php/databaseconnection.php




echo "Flushing the template cache"
##  first time you hit the new site you will need to use the flush command (unless I delete the template cache dir - aitomaticaaly)
cd $1/httpdocs/templates/templates_c && ls | xargs rm -f

echo "Setting ownership Permissions"
##  check permissions
# ls -l | grep $1/httpdocs | awk '{print $3}'
cd /home/httpd/vhosts
chown $2 -R $1/httpdocs/
 
echo "File Copy complete"
echo "**************************************************************************"
 
echo "Copying Database"
echo "**************************************************************************"

# insert database string
echo "Duplicate site manager db"
mysqldump codebase > codebase_dump.sql 
# mysqldump -u'aclifford' -p'7sLqHi2u' codebase > codebase_dump.sql 

#-u aclifford -p 7sLqHi2u
echo "Push data into new db " $3
mysql  $3 < codebase_dump.sql 

# -u antclifford -p 7sLqHi2u
echo "Database complete"

# echo "Configuring Database"
# mysql -h $3 < "/home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_configure_base_install.sql"
# echo "Site marked as down"

echo "**************************************************************************"
echo "All done"
echo "**************************************************************************"
echo "Next you will need to get a local copy of site manager,"
echo "update the database connection details in the php/databaseconnection.php file"
echo "and upload this to the new site. PLEASE MAKE SURE SITE IS NOT LIVE !"

