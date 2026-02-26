$(function() {

	// Do our DOM lookups beforehand
	var nav_container = $(".nav-container");
	var nav = $("nav");
	
	var top_spacing = 2;
	var waypoint_offset = 0;

	nav_container.waypoint({
		handler: function(event, direction) {
			
			if (direction == 'down') {
			
				nav_container.css({ 'height':nav.outerHeight()});
                $('.nav').css({ 'width':'75%'});
				nav.stop().addClass("sticky").css("top",-nav.outerHeight()).animate({"top":top_spacing});
				
			} else {
			
				nav_container.css({ 'height':'auto','margin-top':'0px' });
				$('.nav').css({ 'width':'100%'});
				nav.stop().removeClass("sticky").css("top",nav.outerHeight()+waypoint_offset).animate({"top":""});
				
			}
			
		},
		offset: function() {
			return -nav.outerHeight()-waypoint_offset;
		}
	});
	
	var sections = $(".sec_tr");
	var navigation_links = $("nav a");
	sections.waypoint({
		handler: function(event, direction) {

			var active_section;
			active_section = $(this);
			if (direction === "up") active_section = active_section.prev();

			var active_link = $('nav  a[href="#' + active_section.attr("id") + '"]');
			navigation_links.parent().removeClass("active");
			active_link.parent().addClass("active");

		},
		offset: '25%'
	});


	navigation_links.click( function(event) {

		$.scrollTo(
			$(this).attr("href"),
			{

				offset: { 'left':0, 'top':-0.15*$(window).height() }
			}
		);
	});

    $(window).scroll(fixdiv);
    fixdiv();
});

function fixdiv() {
    if($(window).scrollTop()>200){
        $('#table_cont').addClass("abc");
    }else{
        $('#table_cont').removeClass("abc");
    }
}