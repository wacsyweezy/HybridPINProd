$(document).ready(function() {
	$('.js-source-states').change(function() {
		var item = $(this).val();
		if(item=="null") {
			$('div.qty-action').addClass('hidden');
			return false;
		}
		else {
			$('div.qty-action').removeClass('hidden');
		}
	});
	// Add to cart
	$('button.add-to-cart').click(function() {
		var item = $('.js-source-states option:selected').val();
		var qty = $.trim($('input#qty').val());
		$('button.place-order').attr({'disabled':'disabled'});
		$(this).attr({'disabled':'disabled'}).html('Adding.. <i class="fa fa-spin fa-spinner"></i>');
		
		$.ajax({
			url: 'https://hybridvirtual.etisalat.com.ng/hybrid/ajax/addItem',
			//url: 'http://10.161.11.56/hybrid/ajax/addItem',
			data: ({task:'add-to-cart', item:item, qty:qty}),
			//dataType: 'json', 
			type: "post",
			success: function(res){  
				$('.cart-manager').removeClass('hidden')
				$('div.order-cart').html(res);
				$('input#qty').val('');
				$('button.place-order').removeAttr('disabled');
				$('button.add-to-cart').removeAttr('disabled').html('<i class="fa fa-plus"></i> Add Item');
				console.log(response); 
		   }             
		});
	});
	
	$(document).on('click', '.applyBtn', function() {
		var start_date = $('input[name=start]').val();
		var end_date = $('input[name=end]').val();
		var url = window.location.href+'?start='+start_date+'&end='+end_date;
		document.location.href=url;
	});
	// Remove item
	$(document).on('click', '.remove-item', function() {
		if(confirm("Are you sure you want to remove this item?")) {
			var item = $(this).attr('id');
			$(this).html('<i class="fa fa-spin fa-spinner"></i>');
            $.ajax({
    			url: 'https://hybridvirtual.etisalat.com.ng/hybrid/ajax/deleteItem',
			//url: 'http://10.161.11.56/hybrid/ajax/deleteItem',
    			data: ({task:'remove-item', item:item}),
    			//dataType: 'json', 
    			type: "post",
    			success: function(res){  
    				$('.cart-manager').removeClass('hidden')
    				$('div.order-cart').html(res);
    				$('input#qty').val('');
    				$('button.place-order').removeAttr('disabled');
    				$('button.add-to-cart').removeAttr('disabled').html('<i class="fa fa-plus"></i> Add Item');
    				console.log(response); 
    		   }             
    		});
		}
		else{
			return false;
		}
	});
	// Empty caty
	$('button.empty-cart').click(function() {
		if(confirm("Are you sure you want to remove all selected items?")) {
			$('button.place-order, .empty-cart, .add-to-cart').attr('disabled');
			$(this).html('<i class="fa fa-spin fa-spinner"></i> Removing..');
			$.post('https://hybridvirtual.etisalat.com.ng/hybrid/ajax/emptyItem', {task:'remove-all'}, function(res) {
				location.reload();
			});
			/*$.post('https://10.161.11.56/hybrid/ajax/emptyItem', {task:'remove-all'}, function(res) {
				location.reload();
			}); */
		}
		else{
			return false;
		}
	});
	// Place Order
	$('button.place-order').click(function() {
        	swal(
			{
				title: "Are you sure?",
				text: "Do you want to finalize this transaction?",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#74d348",
				confirmButtonText: "Yes!",
				cancelButtonText: "Cancel",
				closeOnConfirm: true,
				closeOnCancel: true,
			},
			function () {
				document.location.href="https://hybridvirtual.etisalat.com.ng/hybrid/ajax/placeOrder";
			}
		);
	});
})