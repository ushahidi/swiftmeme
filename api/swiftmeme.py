# SwiftMeme Controller
# ====================
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

import config, flask, gateway, memcache

app = flask.Flask(__name__)
mc = memcache.Client(config.MEMCACHE_HOSTS)
gw = gateway.Gateway(config.GATEWAY_BASE, config.GATEWAY_KEY, config.GATEWAY_SECRET, mc, config.MEMCACHE_EXPIRE)

@app.route("/api/<method>")
def api(method):
    result = getattr(gw, method)(**flask.request.args.to_dict())
    response = flask.make_response(result)
    response.headers["Content-Type"] = "application/json; charset=UTF-8"
    return response

def main():
    app.debug = config.DEBUG_MODE
    app.run(host=config.HOST_IP)

if __name__ == "__main__":
    main()
