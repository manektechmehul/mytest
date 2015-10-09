#!/bin/bash
#
# need to :  add a user to push data in to dbs
# 			 get the current owner details from the folder
#
# - you may need to run 
# /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-live-to-test.sh 
#
# dos2unix /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-live-to-test.sh 
# chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-live-to-test.sh 
# /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-live-to-test.sh 
#
# cd /home/httpd/vhosts/
clear
#$path=/home/httpd/vhosts/
#echo $path
# rdsurgery.co.uk
# rds2011test.co.uk

echo "**************************************************************************"
echo "Building Test Site for Prodental" 
echo "**************************************************************************"

## go to the host dir
cd /home/httpd/vhosts

## Goto the target site and record the current owner name
cp -rf /home/httpd/vhosts/rds2011test.co.uk/httpdocs /home/httpd/vhosts/rds2011test.co.uk/bu_httpdocs -v
echo "httpdocs backed up"

## must go to directory to get this
#echo "*** Owner name is "
#cd /home/httpd/vhosts/$1
#ls -l | grep httpdocs | awk '{print $3}'

##  remove the current httpdocs
echo " > removing the current httpdocs"
rm -rf rds2011test.co.uk/httpdocs
## rm -rf  /home/httpd/vhosts/lowflyersbusinesscoaching.com/httpdocs

############################
## Do the big copy
############################
# -p to preserve file dates
# -r recursive
# -v verbose - to show progress
# -f force

echo "**************************************************************************"
echo "Preparing to copy files for rds2011test.co.uk" 
echo "**************************************************************************"

cd /home/httpd/vhosts

##  copy from live rdsurgery.co.uk [add -v for details]
cp -rf  -p rdsurgery.co.uk/httpdocs rds2011test.co.uk -v


echo "Flushing the template cache"
##  first time you hit the new site you will need to use the flush command (unless I delete the template cache dir - automatically)
cd rds2011test.co.uk/httpdocs/templates/templates_c && ls | xargs rm -f

echo "Setting ownership Permissions"
##  check permissions
# ls -l | grep rds2011test.co.uk/httpdocs | awk '{print $3}'
### NOTICE: the permissions name is rdstest2011 - not the expected rds2011test !!!!
cd /home/httpd/vhosts
chown rdstest2011 -R rds2011test.co.uk/httpdocs/
echo "**************************************************************************"
echo "File Copy complete"
echo "**************************************************************************"


echo " NOW UPDATE THE DATABASE CONNECTION FILE !!!"
 

