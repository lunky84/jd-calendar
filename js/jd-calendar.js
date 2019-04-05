

$.fn.jd_calendar = function() {

	var calendar_position = 0;

	function generate_view(calendar_position){

		$.ajax({
			type : 'POST',
			url : 'includes/generate_view.php',
			dataType : 'html',
			data: {
				calendar_position : calendar_position,
			},
			success : function(data){
				$(".jd-calendar").html( "" );
				$(".jd-calendar").html( data );
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log ('Something went wrong');
			}
		});

		return false;
	}


	generate_view(calendar_position);

	$('body').on('click', '.prev', function() {
		calendar_position -= 1;
		generate_view(calendar_position);
	});

	$('body').on('click', '.today', function() {
		calendar_position = 0;
		generate_view(calendar_position);
	});

	$('body').on('click', '.next', function() {
		calendar_position += 1;
		generate_view(calendar_position);
	});

};