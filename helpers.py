from flask import redirect, render_template, session

def show(template, **vars):
    return render_template(template, session=session, **vars)

def loggedin(f):
    return lambda: f() if "user" in session else redirect("/login")

def loggedout(f):
    return lambda: redirect("/dashboard") if "user" in session else f()
