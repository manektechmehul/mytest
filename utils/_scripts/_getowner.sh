# If it says file not found .. you need to run these commands
# chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/getowner.sh
# dos2unix /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/getowner.sh 

# /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/getowner.sh  lowflyersbusinesscoaching.com 

cd /home/httpd/vhosts/$1
ls -l | grep httpdocs | awk '{print $3}' 