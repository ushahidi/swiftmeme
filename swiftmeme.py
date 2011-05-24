# SwiftMeme Controllers
# =====================
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
from flask   import Flask, redirect, request, session
from helpers import loggedin, loggedout, show
from riverid import RiverID
from string  import join

app = Flask(__name__)
riverid = RiverID(RIVERID_BASE)

@loggedout
@app.route("/")
def index():
    return show("index.html")

@loggedin
@app.route("/dashboard")
def dashboard():
    return show("dashboard.html")

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        try:
            riverid.authenticate(request.form["riverid"], request.form["password"])
            session["riverid"] = request.form["riverid"]
            return redirect("/dashboard")
        except Exception as e:
            return show("login.html", error=join(e.args, " "), riverid=request.form["riverid"])
    else:
        return redirect("/dashboard") if "riverid" in session else show("login.html")

@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        try:
            riverid.register(request.form["riverid"], request.form["password"], request.form["emailaddress"])
            session["riverid"] = request.form["riverid"]
            return redirect("/dashboard")
        except Exception as e:
            return show("register.html", error=join(e.args, " "), riverid=request.form["riverid"], emailaddress=request.form["emailaddress"])
    else:
        return redirect("/dashboard") if "riverid" in session else show("register.html")

@app.route("/logout")
def logout():
    if "riverid" in session:
        del session["riverid"]
    return redirect("/")

def main():
    app.debug = DEBUG_MODE
    app.secret_key = SECRET_KEY
    app.run(host=HOST_IP)

if __name__ == "__main__":
    main()
