(function() {
 var memes = jQuery.parseJSON(localStorage.getItem("memes"));
 for (var i = 0; i < memes.length; i++) {
  jQuery.getJSON("/api/getmemeanalytics", {id: memes[i].id, secret: memes[i].secret}, function(data) {
   if (data.status == "success") {
    alert(data);
   }
  });
 }
})();
