#!/usr/bin/env bash
 
# BEGIN ########################################################################
echo -e "-- --------------- --\n"
echo -e "-- BEGIN BOOTSTRAP --\n"
echo -e "-- --------------- --\n"
 
# VARIABLES ####################################################################
echo -e "-- Setting global variables\n"
APACHE_CONFIG=/etc/apache2/apache2.conf
SITES_ENABLED=/etc/apache2/sites-enabled
PHPMYADMIN_CONFIG=/etc/phpmyadmin/config-db.php
PROJECT_ROOT=/var/www/html/gamenights
DOCUMENT_ROOT=/var/www/html/gamenights/public
VIRTUALHOST=dev.gamenights
LOCALHOST=localhost
MYSQL_DATABASE=symfony
MYSQL_USER=root
MYSQL_PASSWORD=test1234
 
# BOX ##########################################################################
echo -e "-- Updating packages list\n"
apt-get update -y > /dev/null 2>&1
 
# Ab1ACHE #######################################################################
echo -e "-- Installing Apache web server\n"
apt-get install -qq apache2 > /dev/null 2>&1
 
echo -e "-- Adding ServerName to Apache config\n"
grep -q "ServerName ${LOCALHOST}" "${APACHE_CONFIG}" || echo "ServerName ${LOCALHOST}" >> "${APACHE_CONFIG}"
 
echo -e "-- Allowing Apache override to all\n"
sed -i "s/AllowOverride None/AllowOverride All/g" ${APACHE_CONFIG}
 
echo -e "-- Updating vhost file\n"
cat > ${SITES_ENABLED}/000-default.conf <<EOF
<VirtualHost *:80>
    ServerName ${VIRTUALHOST}
    DocumentRoot ${DOCUMENT_ROOT}
 
    <Directory ${DOCUMENT_ROOT}>
        Require all granted
		AllowOverride None
        Allow from All

        FallbackResource /index.php
    </Directory>
 
    <Directory /var/www/project/public/bundles>
        FallbackResource disabled
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/${VIRTUALHOST}-error.log
    CustomLog ${APACHE_LOG_DIR}/${VIRTUALHOST}-access.log combined
</VirtualHost>
EOF
 
echo -e "-- Creating the project directory in apache root folder\n"
mkdir -p ${PROJECT_ROOT}
 
# PHP ##########################################################################
echo -e "-- Installing PHP modules\n"
apt-get install -y software-properties-common > /dev/null 2>&1
apt-get install -y php libapache2-mod-php7.2 php7.2-cli php7.2-common php7.2-mbstring php7.2-gd php7.2-intl php7.2-xml php7.2-mysql php7.2-zip php7.2-curl > /dev/null 2>&1
 
# MYSQL ########################################################################
echo -e "-- Installing MariaDB\n"
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8 > /dev/null 2>&1
add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu bionic main' > /dev/null 2>&1
apt-get update -y > /dev/null 2>&1

apt-get install -y mariadb-server > /dev/null 2>&1
apt-get install -y mariadb-client > /dev/null 2>&1

systemctl start mariadb
systemctl enable mariadb

mysql -e "ALTER USER root@localhost IDENTIFIED VIA mysql_native_password USING PASSWORD('test1234')"
 
echo -e "-- Setting up empty MySQL database\n"
mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} -h ${LOCALHOST} -e "CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE}"
 
# PHPMYADMIN ###################################################################
echo -e "-- Installing phpMyAdmin GUI\n"
debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password ${MYSQL_PASSWORD}"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password ${MYSQL_PASSWORD}"
debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password ${MYSQL_PASSWORD}"
debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
 
echo -e "-- Installing phpMyAdmin package\n"
apt-get install -y phpmyadmin > /dev/null 2>&1
 
echo -e "-- Setting up phpMyAdmin GUI login user\n"
sed -i "s/dbuser='phpmyadmin'/dbuser='${MYSQL_USER}'/g" ${PHPMYADMIN_CONFIG}
 
# MAILCATCHER ##################################################################

sudo apt update
sudo apt install git curl libssl-dev libreadline-dev zlib1g-dev autoconf bison build-essential libyaml-dev libreadline-dev libncurses5-dev libffi-dev libgdbm-dev libsqlite3-dev
sudo gem install mailcatcher

# COMPOSER #####################################################################
echo -e "-- Installing PHP cURL module\n"
apt-get install -y curl > /dev/null 2>&1
 
echo -e "-- Installing Composer\n"
curl -sSk https://getcomposer.org/installer | php -- --disable-tls > /dev/null 2>&1
mv composer.phar /usr/local/bin/composer

# YARN #####################################################################
echo -e "-- Installing Yarn\n"
curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
sudo apt-get update -y > /dev/null 2>&1
sudo apt-get install yarn -y > /dev/null 2>&1

# FISH #####################################################################
echo -e "-- Installing Fish\n"
sudo apt-get install fish -y > /dev/null 2>&1
 
# REFRESH ######################################################################
echo -e "-- Restarting Apache web server\n"
systemctl restart apache2
systemctl enable apache2
 
# SETUP SYMFONY ######################################################################
cd ${PROJECT_ROOT}

echo -e "-- Composer install\n"
composer install

echo -e "-- Execute doctrine migrations\n"
php bin/console doctrine:migrations:migrate -y

# END ##########################################################################
echo -e "-- ------------- --"
echo -e "-- END BOOTSTRAP --"
echo -e "-- ------------- --"