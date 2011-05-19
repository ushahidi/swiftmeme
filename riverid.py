from json import loads
from urllib import urlencode
from urllib2 import urlopen

class RiverID(object):
    def __init__(self, base="http://50.57.68.66/riverid/1/"):
        self.base = base

    def authenticate(self, riverid, password):
        params = {"riverid": riverid, "password": password}
        result = loads(urlopen(self.base + "authenticate", urlencode(params)).read())
        if result["status"] == "failure":
            raise Exception(result["response"]["errors"][0])
        return result["status"] == "success"

    def register(self, riverid, password, email):
        params = {"riverid": riverid, "password": password, "email": email}
        result = loads(urlopen(self.base + "register", urlencode(params)).read())
        if result["status"] == "failure":
            raise Exception(result["response"]["errors"][0])
