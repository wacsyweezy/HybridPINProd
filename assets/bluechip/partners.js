$(document).ready(function() {
	$(document).on('click', '.applyBtn', function() {
		var start_date = $('input[name=daterangepicker_start]').val();
		var end_date = $('input[name=daterangepicker_end]').val();
		var url = window.location.href+'&start='+start_date+'&end='+end_date;
		document.location.href=url;
	});
})