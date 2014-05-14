$('a.remove').click(function(){
	var rowId = $(this).data('rowid');
	// console.log(rowId);
	var url = "/basket/removeProductFromBasket";

	$.post(url, { "rowid":rowId }, function(data){
		if(data.total_items == 0)
			window.location.href="/";
	}, "json");

});
