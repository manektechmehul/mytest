#!/bin/bash
clear
###############################################
# Edit these to set target site and file
# example:
# target = "www.target.com"
# file_path = "/modules/twitter/be/twitter.php"
################################################
target=$1 
#file_path="modules/twitter/be/twitter.php"
file_path="modules/twitter/be/twitter.php"
################################################
#
#
#
### Start the Show ####
echo "Hello."
echo "I will copy files from the base version of the site, to another site"
# path to the route of all websites
base_path="/home/httpd/vhosts/"
# code base path from all websites
source_base="codebase.co.uk/httpdocs/"
#
#
#
#
# put it all together
source=$base_path$source_base$file_path
target=$base_path$target"/httpdocs/"$file_path
# output what we are about to do
echo "I will copy the file " $source
echo "I will overwrite file " $target
#
#
#
#
echo "**************************************************************************"
echo "Coping File as requested "
echo "**************************************************************************"
cp -rf $source $target
#chown www-data:www-data $target
chmod 644 $target
echo "**************************************************************************"
#
# edit using notepad++ [to keep the unix linebreaks]
# Using this file. Go to the directory 
# >cd /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts
# type> bash cs_copy_files.sh
