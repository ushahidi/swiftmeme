# SwiftMeme Helpers
# =================
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

from flask import redirect, render_template, session

def show(template, **vars):
    return render_template(template, session=session, **vars)

def loggedin(f):
    return lambda: f() if "riverid" in session else redirect("/login")

def loggedout(f):
    return lambda: redirect("/dashboard") if "riverid" in session else f()
