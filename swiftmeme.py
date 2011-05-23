from flask import Flask, redirect, request, session
from helpers import loggedin, loggedout, show
from riverid import RiverID
from string import join

app = Flask(__name__)
riverid = RiverID("http://50.57.68.66/riverid/1/")

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
            session["user"] = 1
            return redirect("/dashboard")
        except Exception as e:
            return show("login.html", error=join(e.args, " "), riverid=request.form["riverid"])
    else:
        return redirect("/dashboard") if "user" in session else show("login.html")

@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        try:
            riverid.register(request.form["riverid"], request.form["password"], request.form["emailaddress"])
            session["user"] = request.form["riverid"]
            return redirect("/dashboard")
        except Exception as e:
            return show("register.html", error=join(e.args, " "), riverid=request.form["riverid"], emailaddress=request.form["emailaddress"])
    else:
        return redirect("/dashboard") if "user" in session else show("register.html")

@app.route("/logout")
def logout():
    if "user" in session: del session["user"]
    return redirect("/")

if __name__ == "__main__":
    app.debug = True
    app.secret_key = "abc123"
    app.run(host="0.0.0.0")
