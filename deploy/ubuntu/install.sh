#!/bin/bash
#
# SwiftRiver Debian Deployment Bash Script
# ========================================
#
# This file is part of SwiftMeme.
#
# SwiftMeme is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# SwiftMeme is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with SwiftMeme.  If not, see <http://www.gnu.org/licenses/>.

# Apply all system updates.
apt-get update
apt-get upgrade -y

# Install the necessary Debian packages.
apt-get install -y apache2 libapache2-mod-wsgi memcached python-pip git

# Install the necessary Python packages.
pip install Flask oauth2 python-memcached

# Create a user for SwiftMeme processes to run as.
adduser --disabled-password --gecos "" swiftmeme

# Create a local clone of the application.
git clone https://github.com/ushahidi/SwiftMeme.git /var/www/swiftmeme

# Replace the default Apache configuration with the bundled one.
cp /var/www/swiftmeme/deploy/debian/000-default /etc/apache2/sites-enabled/

# Tell Apache to reload its configuration.
/etc/init.d/apache2 reload

# Copy the example SwiftMeme configuration file for customisation.
cp /var/www/swiftmeme/api/config.example.py /var/www/swiftmeme/api/config.py
