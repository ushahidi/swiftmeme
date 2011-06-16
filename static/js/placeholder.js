$(function() {
 function populate() {
  var input = jQuery(this);
  if (input.val() == "" && this != document.activeElement) {
   input.addClass("placeholder");
   input.val(input.attr("placeholder"));
  }
 };

 function depopulate() {
  var input = jQuery(this);
  if (input.hasClass("placeholder")) {
   input.val("");
   input.removeClass("placeholder");
  }
 };

 var inputs = jQuery("input[placeholder]");
 inputs.each(populate);
 inputs.focus(depopulate);
 inputs.blur(populate);
 $(window).unload(function() {
  inputs.each(depopulate);
 });
 jQuery("form").submit(function() {
  jQuery("input[placeholder]", this).each(depopulate);
 });
});
