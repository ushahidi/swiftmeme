# SwiftMeme Controller
# ====================
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

import config, flask, gateway, memcache

app = flask.Flask(__name__)
gw = gateway.Gateway(config.GATEWAY_BASE, config.GATEWAY_KEY, config.GATEWAY_SECRET)
memcache = memcache.Client(config.MEMCACHE_HOSTS, config.MEMCACHE_EXPIRE)

@app.route("/api/<method>", methods=["POST"])
def api(method):
    result = getattr(gw, method)(**flask.request.json)
    response = flask.make_response(result)
    response.headers["Content-Type"] = "application/json; charset=UTF-8"
    return response

def main():
    app.debug = config.DEBUG_MODE
    app.run(host=config.HOST_IP)

if __name__ == "__main__":
    main()
