#!/bin/bash
aptitude update
aptitude safe-upgrade -y
aptitude install -y apache2 libapache2-mod-wsgi memcached python-pip git-core
pip install Flask oauth2 python-memcached
adduser --disabled-password --gecos "" swiftmeme
cd /var/www
git clone https://github.com/ushahidi/SwiftMeme.git swiftmeme
cp swiftmeme/deploy/ubuntu/000-default /etc/apache2/sites-enabled/
/etc/init.d/apache2 reload
cp swiftmeme/api/config.example.py swiftmeme/api/config.py
vim /var/www/swiftmeme/api/config.py
