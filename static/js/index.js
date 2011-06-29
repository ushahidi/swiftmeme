/**
 * SwiftMeme Homepage JavaScript
 * =============================
 *
 * This file is part of SwiftMeme.
 *
 * SwiftMeme is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftMeme is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with SwiftMeme.  If not, see <http://www.gnu.org/licenses/>.
 */

$("#loginBox").live("submit", function() {
 $("#loginbutton").text("Logging in...").attr("disabled", true);
 $.getJSON("/api/authenticate", {riverid: $("#loginid").val(), password: $("#loginpw").val()}, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#loginerror").slideUp("slow", function() {
    $(this).text(data.response.errors.pop()).slideDown("slow");
    $("#loginpw").val("").focus();
    $("#loginbutton").text("Login").attr("disabled", false);
   });
  }
 });
 return false;
});

$("#signupBox").live("submit", function() {
 $("#signupbutton").text("Processing...").attr("disabled", true);
 $.getJSON("/api/register", {riverid: $("#signupid").val(), password: $("#signuppw").val(), emailaddress: $("#signupmail").val()}, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#signuperror").slideUp("slow", function() {
    $(this).text(data.response.errors.pop()).slideDown("slow");
    $("#signupbutton").text("Sign Up!").attr("disabled", false);
   });
  }
 });
 return false;
});

$("#loginActivator").live("click", function() {
 $("#signupDIV:visible").slideUp("slow");
 $("#loginDIV").slideToggle("slow");
});

$(".signupButtons").live("click", function() {
 $("#loginDIV:visible").slideUp("slow");
 $("#signupDIV").slideToggle("slow");
});
