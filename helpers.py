from flask import redirect, render_template, session

def show(template):
    return render_template(template, session=session)

def loggedin(f):
    return lambda: f() if "user" in session else redirect("/login")

def loggedout(f):
    return lambda: redirect("/dashboard") if "user" in session else f()
