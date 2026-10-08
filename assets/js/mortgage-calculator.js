jQuery( document ).ready(function($) {
	"use strict";
	/**
	 * Format a number as currency string.
	 *
	 * @param {number} num    - The number to format.
	 * @param {string} symbol - Currency symbol.
	 * @return {string} Formatted currency string.
	 */
	function formatCurrency(num, symbol) {
		return symbol + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

	/**
	 * Format a number with commas for input display.
	 *
	 * @param {number} num - The number to format.
	 * @return {string} Comma-formatted number string.
	 */
	function formatNumber(num) {
		return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	}

	/**
	 * Parse a formatted string back to a number.
	 *
	 * @param {string} str - The formatted string.
	 * @return {number} Parsed number.
	 */
	function parseNum(str) {
		return parseFloat(String(str).replace(/[^0-9.\-]/g, '')) || 0;
	}

	/**
	 * Calculate monthly mortgage payment.
	 *
	 * @param {number} principal  - Loan principal.
	 * @param {number} annualRate - Annual interest rate (%).
	 * @param {number} years      - Loan term in years.
	 * @return {object} Payment breakdown.
	 */
	function calcMortgage(principal, annualRate, years) {
		if (principal <= 0 || years <= 0) {
			return { monthly: 0, totalPayment: 0, totalInterest: 0 };
		}
		if (annualRate <= 0) {
			var m = principal / (years * 12);
			return { monthly: m, totalPayment: principal, totalInterest: 0 };
		}
		var r = (annualRate / 100) / 12;
		var n = years * 12;
		var monthly = principal * (r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
		var totalPayment = monthly * n;
		return {
			monthly: monthly,
			totalPayment: totalPayment,
			totalInterest: totalPayment - principal
		};
	}

	// Initialize each calculator on the page.
	$('.clp-mortgage-calculator').each(function() {
		var $calc    = $(this);
		var currency = $calc.attr('data-currency') || '$';
		var term     = parseInt($calc.attr('data-term'), 10) || 30;

		var $price       = $calc.find('.clp-field-price');
		var $downAmount  = $calc.find('.clp-field-down-amount');
		var $downPercent = $calc.find('.clp-field-down-percent');
		var $rate        = $calc.find('.clp-field-rate');

		/**
		 * Run the calculation and update all output fields.
		 */
		function update() {
			var price      = parseNum($price.val());
			var downAmount = parseNum($downAmount.val());
			var rate       = parseFloat($rate.val()) || 0;
			var loanAmount = Math.max(0, price - downAmount);

			var result = calcMortgage(loanAmount, rate, term);

			$calc.find('.clp-out-monthly').text(formatCurrency(result.monthly, currency));
			$calc.find('.clp-out-loan').text(formatCurrency(loanAmount, currency));
			$calc.find('.clp-out-interest').text(formatCurrency(result.totalInterest, currency));
			$calc.find('.clp-out-total').text(formatCurrency(result.totalPayment, currency));

			// Update breakdown bar.
			var total = loanAmount + result.totalInterest;
			var pPct  = total > 0 ? (loanAmount / total) * 100 : 0;
			var iPct  = total > 0 ? (result.totalInterest / total) * 100 : 0;

			$calc.find('.clp-bar-principal').css('width', pPct + '%');
			$calc.find('.clp-bar-interest').css('width', iPct + '%');
		}

		/**
		 * Sync down payment dollar amount from percent.
		 */
		function syncDownFromPercent() {
			var price   = parseNum($price.val());
			var percent = parseFloat($downPercent.val()) || 0;
			percent = Math.min(100, Math.max(0, percent));
			$downAmount.val(formatNumber(price * percent / 100));
			update();
		}

		/**
		 * Sync down payment percent from dollar amount.
		 */
		function syncPercentFromDown() {
			var price  = parseNum($price.val());
			var amount = parseNum($downAmount.val());
			$downPercent.val(price > 0 ? Math.round((amount / price) * 100) : 0);
			update();
		}

		// ── Event Bindings ──────────────────────────────────

		// Price field.
		$price.on('input keyup change', function() {
			syncDownFromPercent();
		});
		$price.on('blur', function() {
			$price.val(formatNumber(parseNum($price.val())));
			syncDownFromPercent();
		});

		// Down payment amount.
		$downAmount.on('blur', function() {
			$downAmount.val(formatNumber(parseNum($downAmount.val())));
			syncPercentFromDown();
		});

		// Down payment percent.
		$downPercent.on('input keyup change', syncDownFromPercent);

		// Interest rate.
		$rate.on('input keyup change', update);

		// ── Term Buttons (delegated click on <span>) ────────
		$calc.on('click', '.clp-term-btn', function(e) {
			e.preventDefault();
			e.stopPropagation();

			$calc.find('.clp-term-btn').removeClass('clp-term-active');
			$(this).addClass('clp-term-active');

			term = parseInt($(this).attr('data-term'), 10);
			update();
		});

		// ── Initial Calculation ─────────────────────────────
		$price.val(formatNumber(parseNum($price.val())));
		$downAmount.val(formatNumber(parseNum($downAmount.val())));
		update();
	});
});
