$(document).ready(function() {
	$('.report_type').change(function() {
		var type = $(this).val();
		if(type=="") {
			$('div.params').addClass('hidden');
			return false;
		}
		else {
			$('div.params').addClass('hidden');
			$('div.'+type).removeClass('hidden');
		}
	});
	
	$('.generate').click(function() {
		var action = $(this).attr('id');
		if(action=="dealer") {
			var dealer = $('select#tradecode').val();
			var title = $('select#tradecode option:selected').text();
			var start = $('input#dealer_start').val();
			var end = $('input#dealer_end').val();
			$('button#dealer').html("<i class='fa fa-spin fa-spinner'></i> Generating report.").attr({'disabled':'disabled'});
			$.ajax({
				url:'https://hybridvirtual.etisalat.com.ng/hybrid/report/generateDealerReport',
				//url:'http://10.161.11.56/hybrid/report/generateDealerReport',
				data: ({title:title, dealer:dealer, start:start, end:end}),
				type: "get",
				success: function(res) {
					$('.report-page').removeClass('hidden').html(res);

        $('#example1').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Dealer '+title+' Report', className: 'btn-sm'},
                {extend: 'pdf', title: 'Dealer '+title+' Report', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
					console.log(res); 
$('button#dealer').removeAttr('disabled').html('<i class="fa fa-check"></i> Generate Report');
				}
			})
		}
		if(action=="order") {
			var start = $('input#order_start').val();
			var end = $('input#order_end').val();
			$('button#dealer').html("<i class='fa fa-spin fa-spinner'></i> Generating report.").attr({'disabled':'disabled'});
			$.ajax({
				url:'https://hybridvirtual.etisalat.com.ng/hybrid/report/generateOrderReport',
				//url:'http://10.161.11.56/hybrid/report/generateOrderReport',
				data: ({start:start, end:end}),
				type: "get",
				success: function(res) {
					$('.report-page').removeClass('hidden').html(res);

        $('#example1').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Orders Report', className: 'btn-sm'},
                {extend: 'pdf', title: 'Orders Report', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
					console.log(res); 
$('button#order').removeAttr('disabled').html('<i class="fa fa-check"></i> Generate Report');
				}
			})
		}
		if(action=="online") {
			var start = $('input#online_start').val();
			var end = $('input#online_end').val();
			$('button#dealer').html("<i class='fa fa-spin fa-spinner'></i> Generating report.").attr({'disabled':'disabled'});
			$.ajax({
				url:'https://hybridvirtual.etisalat.com.ng/hybrid/report/generateOnlineReport',
				data: ({start:start, end:end}),
				type: "get",
				success: function(res) {
					$('.report-page').removeClass('hidden').html(res);

        $('#example1').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'Online Vending Report', className: 'btn-sm'},
                {extend: 'pdf', title: 'Online Vending Report', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
					console.log(res); 
$('button#online').removeAttr('disabled').html('<i class="fa fa-check"></i> Generate Report');
				}
			})
		}
		if(action=="dealerOrder") {
			var start = $('input#dealer_start').val();
			var end = $('input#dealer_end').val();
			$('button#dealerOrder').html("<i class='fa fa-spin fa-spinner'></i> Generating report.").attr({'disabled':'disabled'});
			$.ajax({
				url:'https://hybridvirtual.etisalat.com.ng/hybrid/report/generateMyReport',
				//url:'http://10.161.11.56/hybrid/report/generateDealerReport',
				data: ({start:start, end:end}),
				type: "get",
				success: function(res) {
					$('.report-page').removeClass('hidden').html(res);

        $('#example1').dataTable( {
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            buttons: [
                {extend: 'copy',className: 'btn-sm'},
                {extend: 'csv',title: 'My Hybrid Orders Report', className: 'btn-sm'},
                {extend: 'pdf', title: 'My Hybrid Orders Report', className: 'btn-sm'},
                {extend: 'print',className: 'btn-sm'}
            ]
        });
					console.log(res); 
$('button#dealerOrder').removeAttr('disabled').html('<i class="fa fa-check"></i> Generate Report');
				}
			})
		}
	});
	
})