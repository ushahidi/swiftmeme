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

import json, oauth2, time, urllib, urllib2

class Gateway(object):
    def __init__(self, base, key, secret):
        self.base = base
        self.key = key
        self.secret = secret

    def request(self, key, secret, path, parameters={}, method="POST"):
        url = self.base + path

        oauth_parameters = parameters.copy()
        oauth_parameters["oauth_timestamp"] = int(time.time())
        oauth_parameters["oauth_nonce"] = None
        oauth_parameters["oauth_signature_method"] = "HMAC-SHA1"
        oauth_consumer = oauth2.Consumer(key=key, secret=secret)
        oauth_request = oauth2.Request(method=method, url=url, parameters=oauth_parameters)
        oauth_signature_method = oauth2.SignatureMethod_HMAC_SHA1()
        oauth_request.sign_request(oauth_signature_method, oauth_consumer, None)

        http_data = urllib.urlencode(parameters)
        http_request = urllib2.Request(url, headers=oauth_request.to_header(), data=http_data)
        http_response = urllib2.urlopen(http_request).read()

        result = json.loads(http_response)

        if result["status"] == "failure":
            raise Exception(*result["response"]["errors"])

        return result

    def authenticate(self, riverid, password):
        return self.request(self.key, self.secret, "swiftmeme/1/authenticate", {"riverid": riverid, "password": password})

    def register(self, riverid, password, emailaddress):
        return self.request(self.key, self.secret, "swiftmeme/1/register", {"riverid": riverid, "password": password, "emailaddress": emailaddress})

    def get_meme_overview(self, id, secret):
        return self.request(id, secret, "swiftmeme/1/getmemeoverview")
