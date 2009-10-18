#!/bin/sh
# this little script helps you setting up your webserver to install Phenotype CMS
# please adjust the settings as you wish.
#
# first make this file executable:
# chmod 755 setrights.sh
#
# second execute it:
# ./setrights.sh
echo "setting up file permissions for phenotype install"

chmod 777 .
chmod 777 htdocs
chmod 777 htdocs/media -R
chmod 777 _phenotype/cache -R
chmod 777 _phenotype/application -R
chmod 777 _phenotype/temp -R