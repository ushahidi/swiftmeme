// scroll back to top
var $ = jQuery.noConflict();

//cufon
Cufon.replace('h1, h2, h3, h4, h5, h6, label');
			Cufon.replace('.mainMenu> li > a',{
						  hover: true
			});

//back to top
$(document).ready(function() {


/* top menu */
$(".mainMenu > li").hover(function() {
    $(this).find(".subMenu").animate({
                                 height: "show"
                                 },150);
},function(){
    $(this).find(".subMenu").delay(300).animate({
                                 height: "hide"
                                 },150);
    });

// animate social links
$('.socialDetails a').hover(
							  function(){

		$(this).stop().animate({"margin-top": "-5px"}, 100);

	},
	function(){

		$(this).stop().animate({"margin-top": "0px"}, 100);

});

  });
