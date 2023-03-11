/**
 * Extend Customizer Panel
 *
 * @package Mantrabrain
 */
(function ($, wpcustomize) {

	var api = wp.customize;

	var $document = $(document);

	var ResaCustomizerControls = {

		init: function () {

			this.responsiveDeviceControls();


		},
		responsiveDeviceControls: function () {
			$('.resa-control-responsive').each(function () {
				$(this).find('.control-responsive').first().addClass('active');
				$(this).find('.resa-responsive-switchers li').first().addClass('active');
			});
			// Responsive switchers
			$('.customize-control').on('click', '.resa-responsive-switchers li', function (event) {

				var $this = $(this),
					$switcher_container = $this.closest('.resa-responsive-switchers'),
					$switcher_buttons = $switcher_container.find('li'),
					$device = $(event.currentTarget).data('device'),
					$control = $('.resa-control-responsive'),
					$body = $('.wp-full-overlay'),
					$footer_devices = $('.wp-full-overlay-footer .devices');
				$switcher_buttons.removeClass('active');
				$this.addClass('active');
				// Control class
				$control.find('.control-responsive').removeClass('active');
				$control.find('.control-responsive.' + $device).addClass('active');

				$footer_devices.find('button.preview-' + $device).trigger('click');
			});

			// If panel footer buttons clicked
			$('.wp-full-overlay-footer .devices button').on('click', function (event) {

				// Set up variables
				var $this = $(this),
					$devices = $('.customize-control .resa-responsive-switchers'),
					$device = $(event.currentTarget).data('device'),
					$control = $('.resa-control-responsive');

				// Button class
				$devices.find('li').removeClass('active');
				$devices.find('li[data-device="' + $device + '"]').addClass('active');

				// Control class
				$control.find('.control-responsive').removeClass('active');
				$control.find('.control-responsive.' + $device).addClass('active');
			});
		}


	};
	wp.customize.bind('ready', function () {

		ResaCustomizerControls.init();

	})

})(jQuery, wp.customize || null);
