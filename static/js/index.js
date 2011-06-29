$("#loginBox").live("submit", function() {
 $.getJSON("/api/authenticate", {riverid: $("#loginid").val(), password: $("#loginpw").val()}, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#loginerror").slideUp().text(data.response.errors.pop()).slideDown();
   $("#loginpw").val("").focus();
  }
 });
 return false;
});
$("#signupBox").live("submit", function() {
 $.getJSON("/api/register", {riverid: $("#signupid").val(), password: $("#signuppw").val(), emailaddress: $("#signupmail").val()}, function(data) {
  if (data.status == "success") {
   localStorage.setItem("memes", JSON.stringify(data.response.memes));
   location = "/dashboard";
  } else {
   $("#signuperror").slideUp("slow", function() {
    $(this).text(data.response.errors.pop()).slideDown("slow");
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
