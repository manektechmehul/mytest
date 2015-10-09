# dos2unix /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-db-live-to-test.sh 
# chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-db-live-to-test.sh 
# /home/httpd/vhosts/codebase.co.uk/httpdocs/_scripts/prodental-db-live-to-test.sh 



echo "**************************************************************************"
echo "Copying Database - this doesn't seem to work !!"
echo "**************************************************************************"

# insert database string
echo "Creating Live Prodental db dump - this will take about a minute"
# mysqldump codebase > codebase_dump.sql 
#mysqldump -u'aclifford' -p'7sLqHi2u' rdsurgery_v2 > prodental_dump.sql 
mysqldump rdsurgery_v2 > prodental_dump.sql 

#-u aclifford -p 7sLqHi2u
echo "Push data into new db - this will take about a minute" 
#mysql -u'aclifford' -p'7sLqHi2u' pdtest2012 < prodental_dump.sql 
mysql pdtest2012 < prodental_dump.sql 
# -u antclifford -p 7sLqHi2u
echo "Database complete"

echo "**************************************************************************"
echo "All done"
echo "**************************************************************************"
