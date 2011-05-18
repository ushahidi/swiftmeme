from flask import Flask, redirect, render_template, session

app = Flask(__name__)

def show(template):
    return render_template(template, session=session)

def loggedin(f):
    return lambda: f() if "user" in session else redirect("/login")

def loggedout(f):
    return lambda: redirect("/dashboard") if "user" in session else f()

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
