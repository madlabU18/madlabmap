$(document).ready( function() {
	$(".content-click").click( function(event) {
		if (this == event.target) {
			console.log($(this).attr( 'rel' ));
			$('#content').load( $(this).attr( 'href' ) );
		}
		return false;
	} );


} );
