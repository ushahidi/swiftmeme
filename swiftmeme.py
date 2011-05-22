from flask import Flask, redirect, request, session
from helpers import loggedin, loggedout, show
from riverid import RiverID

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

@app.route("/login")
def login():
    if request.method == "POST":
        if "user" in session:
            return redirect("/dashboard")
        else:
            if riverid.authenticate(request.form["riverid"], requst.form["password"]):
                session["user"] = 1
                return redirect("/dashboard")
            else:
                show("login.html", error=True, riverid=request.form["riverid"])
    else:
        return show("login.html")

@app.route("/logout")
def logout():
    if "user" in session: del session["user"]
    return redirect("/")

if __name__ == "__main__":
    app.debug = True
    app.secret_key = "abc123"
    app.run(host="0.0.0.0")
