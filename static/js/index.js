$("#loginBox").live("submit", function() {
 $.getJSON("/api/authenticate", {"riverid": $("#loginriverid").val(), "password": $("#loginpassword").val()}, function(data) {
  localStorage.setItem("memes", JSON.stringify(data.response.memes));
  location = "/dashboard";
 });
 return false;
});
