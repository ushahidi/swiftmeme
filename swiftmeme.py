from flask import Flask, redirect, render_template, session

app = Flask(__name__)

def show(template):
    vars = {}
    if "user" in session: vars["user"] = session["user"]
    return render_template(template, **vars)

def loggedin(f):
    def new():
        return f() if "user" in session else redirect("/login")
    return new

def loggedout(f):
    def new():
        return redirect("/dashboard") if "user" in session else f()
    return new

@app.route("/")
@loggedout
def index():
    return show("index.html")

@app.route("/dashboard")
@loggedin
def dashboard():
    return show("dashboard.html")

@app.route("/login")
def login():
    if not "user" in session: session["user"] = 1
    return redirect("/dashboard")

@app.route("/logout")
def logout():
    if "user" in session: del session["user"]
    return redirect("/")

if __name__ == "__main__":
    app.debug = True
    app.secret_key = "abc123"
    app.run(host="0.0.0.0")
