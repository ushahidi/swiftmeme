$("#loginBox").live("submit", function() {
 var params = {"riverid": $("#loginriverid").val(), "password": $("#loginpassword").val()};
 $.getJSON("/api/authenticate", params, function(data) {
  localStorage.setItem("memes", JSON.stringify(data.response.memes));
  location = "/dashboard";
 });
 return false;
});
$("#signupBox").live("submit", function() {
 var params = {"riverid": $("#registerriverid").val(), "password": $("#registerpassword").val(), "emailaddress": $("#registeremailaddress").val()};
 $.getJSON("/api/register", params, function(data) {
  localStorage.setItem("memes", JSON.stringify(data.response.memes));
  location = "/dashboard";
 });
 return false;
});
