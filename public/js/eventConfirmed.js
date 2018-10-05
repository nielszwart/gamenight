$(document).mouseup(function(e) {
    var container = $("#just-confirmed");
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
    	$('#modal-container').remove();
    }
});

$('#close').click(function() {
	$('#modal-container').remove();
});