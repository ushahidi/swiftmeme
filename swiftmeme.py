from flask import Flask, session, redirect, render_template, url_for

app = Flask(__name__)

def show(template):
    vars = {}
    if "user" in session: vars["user"] = session["user"]
    return render_template(template, **vars)

@app.route("/")
def index():
    return show("index.html")

@app.route("/login")
def login():
    session["user"] = 1
    return redirect(url_for("index"))

@app.route("/logout")
def logout():
    session.pop("user", None)
    return redirect(url_for("index"))

if __name__ == "__main__":
    app.debug = True
    app.secret_key = "abc123"
    app.run(host="0.0.0.0")
