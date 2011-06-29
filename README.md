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

## Rackspace Deployment

1. Create a server of type: Ubuntu 10.10 (Maverick Meerkat)
2. SSH into the new server as root.
3. Execute: `curl https://raw.github.com/ushahidi/SwiftMeme/master/deploy/ubuntu/install.sh | bash`
4. The configuration file will open in `vim`; focus on the gateway, the rest should be fine as-is.

## Apache Configuration

    <VirtualHost *:80>
     Alias /static/ /var/www/swiftmeme/static/
     AliasMatch ^/$ /var/www/swiftmeme/static/index.html
     AliasMatch ^/dashboard$ /var/www/swiftmeme/static/dashboard.html
     WSGIDaemonProcess swiftmeme user=swiftmeme group=swiftmeme threads=5
     WSGIScriptAlias / /var/www/swiftmeme/api/swiftmeme.wsgi
    </VirtualHost>

If your application is installed in a different directory than `/var/www/swiftmeme`, please modify the path accordingly.

## Licenses

* [GNU Affero General Public License](http://www.gnu.org/licenses/agpl.html)
* [GNU Free Documentation License](http://www.gnu.org/licenses/fdl.html)

## See Also

* [SwiftRiver](http://swiftly.org)
