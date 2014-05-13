$(function(){
    var top = $('.right_col_content').offset().top - parseFloat($('.right_col_content').css('marginTop').replace(/auto/, 0));
	$(window).scroll(function (event) {
          // what the y position of the scroll is
          var y = $(this).scrollTop();
                 
          // whether that's below the form
          if (y >= top) {
            // if so, ad the fixed class
            $('.right_col_content').addClass('fixed');
          } else {
            // otherwise remove it
            $('.right_col_content').removeClass('fixed');
          }
	});

	$("#add_to_basket").click(function(){
		var url = "/products/addToBasket",
			pid = $("#pid").val(),
			pname = $("#pname").val(),
			rowId = $('#rowId').val(),
			price = $("#price").val();

		$.post(url, {"pid": pid, "pname": pname, "rowId": rowId, "price": price }, function(data){
			// console.log(data.rowID);
			$("#rowId").val(data.rowID);	
			var total_qty = $('#total_qty').text();
			if(!total_qty){
				total_qty = 0;

				var new_total = parseInt(total_qty) + 1;
				var basketLink = "<li style='border-right:none'><a href='/basket'><span id='total_qty'></span> item(s) in the basket</a><li>";
				$('.top-nav > ul').append(basketLink);
				$('.top-nav').after('<a href="/products/empty_basket" class="empty_basket">Empty Basket</a>');
			} else {
				var new_total = parseInt(total_qty) + 1;
			}

			if(data.stock == 0){

			}

			//show total
			$('#total_qty').html(new_total);
		}, "json");
	});

});


