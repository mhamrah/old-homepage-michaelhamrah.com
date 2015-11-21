$(document).ready(function(){
 
	$("a.status, a.advanced").click(function() { return false; });
	
	$(".inactive").live('mouseenter',function() {
		$(this).addClass('rollover');
	    $(this).children('.titleContainer').children('.status').text('Click to Activate');
	}
);
$('.inactive').live('mouseleave',function() {
	$(this).removeClass('rollover');
	
    $(this).children('.titleContainer').children('.status').text('Not Active');
});
	
	$(".inactive").live('click',function()
	{
		$(this).removeClass('inactive');
		$(this).addClass('active');
		$(this).children('.titleContainer').children('.status').text('Active');
		$(this).children('.titleContainer').append('<a href="#" class="deactivate">Remove From Active Filters</a>');
	});
	
	$('.deactivate').live('click', function() {

		$(this).parents('.filterItem').addClass('inactive');
		$(this).parents('.filterItem').removeClass('active');
		$(this).remove();
		return false;
	});
});