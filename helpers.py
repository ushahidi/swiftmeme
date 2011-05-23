from flask import redirect, render_template, session

def show(template, **vars):
    return render_template(template, session=session, **vars)

def loggedin(f):
    return lambda: f() if "riverid" in session else redirect("/login")

def loggedout(f):
    return lambda: redirect("/dashboard") if "riverid" in session else f()
