$("#loginBox").live("submit", function() {
 var params = {"riverid": $("#loginriverid").val(), "password": $("#loginpassword").val()};
 $.getJSON("/api/authenticate", params, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#loginerror").text(data.response.errors.pop());
   $("#loginpassword").val("").focus();
  }
 });
 return false;
});
$("#signupBox").live("submit", function() {
 var params = {"riverid": $("#registerriverid").val(), "password": $("#registerpassword").val(), "emailaddress": $("#registeremailaddress").val()};
 $.getJSON("/api/register", params, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#signuperror").text(data.response.errors.pop());
  }
 });
 return false;
});
$("#loginActivator").live("click", function() {
 $("#loginDIV").slideToggle("slow");
});
$(".signupButtons").live("click", function() {
 $("#signupDIV").slideToggle("slow");
});
