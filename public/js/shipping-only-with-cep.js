(function ($) {
	'use strict';
	$(document).on('ready updated_wc_div', function (e) {
		var input = $('input[name="calc_shipping_postcode"]')
		input.attr('maxlength', 9)
		input.attr('type', 'tel')
		input.on('keyup', function (e) {
			var val = $(this).val()
			val = val.replace(/\D/g, "")
			val = val.replace(/^(\d{5})(\d)/g, "$1-$2")
			$(this).val(val)
		});
	})
})(jQuery);