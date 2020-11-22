(function ($) {
	'use strict';
	$(document).on('ready updated_wc_div', function (e) {
		var shipping_calculator_input
		shipping_calculator_input = document.querySelector('input[name="calc_shipping_postcode"]')
		shipping_calculator_input.setAttribute("type", "tel")
		shipping_calculator_input.setAttribute("maxlength", "9")

		shipping_calculator_input.addEventListener('keyup', function (event) {
			this.value = createMask(this.value)
		}, false);

		function createMask(string) {
			return string.replace(/(\d{5})(\d{3})/, "$1-$2")
		}
	})
})(jQuery);