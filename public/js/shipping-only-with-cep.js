(function ($) {
	'use strict';
	$(document).on('ready updated_wc_div', function (e) {
		var shipping_calculator_input
		shipping_calculator_input = $('input[name="calc_shipping_postcode"]')
		shipping_calculator_input.attr('type', 'tel')
		shipping_calculator_input.attr('maxlength', '8')

		shipping_calculator_input.on("keyup", function () {
			$(this).val(createMask($(this).val()))
		})

		function createMask(string) {
			return string.replace(/(\d{5})(\d{3})/, "$1-$2")
		}
	})

})(jQuery);
