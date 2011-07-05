# SwiftMeme v0.2.0

## Synopsis

SwiftMeme is a source discovery and keyword monitoring tool for tracking memes online.

## Architecture

![Architectural Diagram](https://github.com/ushahidi/SwiftMeme/raw/master/doc/architecture.png)

## Internal Dependencies

* [Apache HTTP Server](http://httpd.apache.org/)
* [Flask](http://flask.pocoo.org/)
* [Memcached](http://memcached.org/)
* [mod_wsgi](http://code.google.com/p/modwsgi/)
* [Python 2.x](http://python.org/)
* [python-memcached](http://www.tummy.com/Community/software/python-memcached/)
* [python-oauth2](https://github.com/simplegeo/python-oauth2)

## External Dependencies

* [SwiftRiver Gateway](https://github.com/ushahidi/SwiftGateway) (Directly)
* [SwiftRiver Core](https://github.com/ushahidi/Swiftriver) (Indirectly)
* [RiverID](https://github.com/ushahidi/RiverID) (Indirectly)

## Debian 6.0 (Squeeze) Installation Instructions

### Automatic

1. `wget https://raw.github.com/ushahidi/SwiftMeme/master/deploy/debian/install.sh`
2. `bash install.sh`
3. `rm install.sh`

### Manual

1. Install the necessary Debian packages.  
`apt-get install apache2 libapache2-mod-wsgi memcached python-pip git`

2. Install the necessary Python packages.  
`pip install Flask oauth2 python-memcached`

3. Create a user for SwiftMeme processes to run as.  
`adduser --disabled-password --gecos "" swiftmeme`

4. Create a local clone of the application.  
`git clone https://github.com/ushahidi/SwiftMeme.git /var/www/swiftmeme`

5. Replace the default Apache configuration with the bundled one.  
`cp /var/www/swiftmeme/deploy/debian/000-default /etc/apache2/sites-enabled/`

6. Tell Apache to reload its configuration.  
`/etc/init.d/apache2 reload`

7. Copy the example SwiftMeme configuration file for customisation.  
`cp /var/www/swiftmeme/api/config.example.py /var/www/swiftmeme/api/config.py`

8. Open the configuration file in vim.  
`vim /var/www/swiftmeme/api/config.py`

## Apache Configuration

    <VirtualHost *:80>
     Alias /static/ /var/www/swiftmeme/static/
     AliasMatch ^/$ /var/www/swiftmeme/static/index.html
     AliasMatch ^/dashboard$ /var/www/swiftmeme/static/dashboard.html
     WSGIDaemonProcess swiftmeme user=swiftmeme group=swiftmeme threads=5
     WSGIScriptAlias / /var/www/swiftmeme/api/swiftmeme.wsgi
    </VirtualHost>

* If your application is installed in a different directory than `/var/www/swiftmeme`, please modify the path accordingly, both in the Apache configuration and in the WSGI handler (`swiftmeme.wsgi`).
* You need a user set up for the SwiftMeme process to run as. In the above, we assume both the user and group will be `swiftmeme`.

## Licenses

* All bundled source code is released under the [GNU Affero General Public License](http://www.gnu.org/licenses/agpl.html).
* All bundled documentation is released under the [GNU Free Documentation License](http://www.gnu.org/licenses/fdl.html).

## Credits

* Charl van Niekerk (Front End and Back End Development)
* Joe Zhou (Design and Front End Development)
* Jon Gosier (Creative Input and Inspiration)
* Matthew Griffiths (Architecture and Inspiration)

## See Also

* [SwiftRiver](http://swiftly.org)
