# SwiftMeme Controller
# ====================
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

from config  import *
from flask   import Flask, make_response, request
from gateway import Gateway

app = Flask(__name__)
gateway = Gateway(GATEWAY_BASE, GATEWAY_KEY, GATEWAY_SECRET)

@app.route("/api/<method>", methods=["POST"])
def api(method):
    result = getattr(gateway, method)(**request.json)
    response = make_response(result)
    response.headers["Content-Type"] = "application/json; charset=UTF-8"
    return response

def main():
    app.debug = DEBUG_MODE
    app.run(host=HOST_IP)

if __name__ == "__main__":
    main()
