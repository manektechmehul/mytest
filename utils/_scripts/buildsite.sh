###########################################################################
# This is the trigger file for the site copy process
# GL May - July 2013
# 
# convert all the files used to unix and 777
# if you need to do it on this one
#


# then execute the make file with the following parameters
# $sitenameurl - $ownername - $dbname - $dbpassword
# Example:
## /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/makesite.sh lowflyersbusinesscoaching.com lowflyers dbname dbpassword
/home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_makesite.sh $1 $2 $3 $4

