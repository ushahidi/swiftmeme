$("#loginBox button").submit(function() {
 $.getJSON("/api/authenticate", {"riverid": $("#loginriverid").val(), "password": $("#loginpassword").val()}, function(data) {
  localStorage.setItem("memes", JSON.dumps(data.response.memes));
  alert(localStorage.getItem("memes"));
 });
});
