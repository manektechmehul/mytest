# /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/start.sh ffald-y-brenin.org
# dos2unix /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/start.sh 
# chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/start.sh 

dos2unix /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/buildsite.sh 
chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/buildsite.sh 

dos2unix  /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_getowner.sh
chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_getowner.sh

dos2unix  /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_makesite.sh
chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_makesite.sh

dos2unix  /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_configure_base_install.sql
chmod 777 /home/httpd/vhosts/codebase.co.uk/httpdocs/utils/_scripts/_configure_base_install.sql

# get owner of new site folder

cd /home/httpd/vhosts/$1
ls -l | grep httpdocs | awk '{print $3}' 