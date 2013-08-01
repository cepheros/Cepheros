/* [ ---- Gebo Admin Panel - user profile (static) ---- ] */

	$(document).ready(function() {
		$('.item-list-show').click(function(e) {
			var items = $(this).data('items');
			var hiddenItems = $(this).prev('.item-list').find('.item-list-more').filter(':hidden');
			hiddenItems.each( function(i) {
				if( i < items ) {
					$(this).show();
				}
			});
			e.preventDefault();
			if(hiddenItems.length <= items) {
				$(this).hide();
			};
		});
	});


