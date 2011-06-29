# SwiftMeme Configuration
# =======================
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
# GNU Affero Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with SwiftMeme.  If not, see <http://www.gnu.org/licenses/>.

# Debug mode, set to true to turn on and false to turn off
DEBUG_MODE = True

# IP address to bind to
HOST_IP = "0.0.0.0"

# SwiftRiver Gateway
GATEWAY_BASE = "http://50.57.68.66/"
GATEWAY_KEY = "508d26813f26c93cf9bf8f9fc574dc70307b672bf6aee55a62e1e2ee"
GATEWAY_SECRET = "394d4406fa9797448e505055a9173398f16200137bba57c05f80b1b1"

# Memcache
MEMCACHE_HOSTS = ["127.0.0.1:11211"]
MEMCACHE_EXPIRE = 600
