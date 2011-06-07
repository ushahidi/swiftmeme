# SwiftRiver Gateway Bindings
# ===========================
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

from json    import loads
from urllib  import urlencode
from urllib2 import urlopen

def build_request(key, secret, url, method='POST', values={}):
    params = {"oauth_timestamp": int(time.time()), "oauth_nonce": None, "oauth_signature_method": "HMAC-SHA1"}
    params.update(values)
    consumer = oauth2.Consumer(key=key,secret=secret)
    req = oauth2.Request(method=method, url=url, parameters=params)
    signature_method = oauth2.SignatureMethod_HMAC_SHA1()
    req.sign_request(signature_method, consumer, None)
    return req

class RiverID(object):
    def __init__(self, base):
        self.base = base

    def authenticate(self, riverid, password):
        params = {"riverid": riverid, "password": password}
        result = loads(urlopen(self.base + "authenticate", urlencode(params)).read())
        if result["status"] == "failure":
            raise Exception(*result["response"]["errors"])
        return result["status"] == "success"

    def register(self, riverid, password, emailaddress):
        params = {"riverid": riverid, "password": password, "emailaddress": emailaddress}
        result = loads(urlopen(self.base + "register", urlencode(params)).read())
        if result["status"] == "failure":
            raise Exception(*result["response"]["errors"])
        return result["status"] == "success"
