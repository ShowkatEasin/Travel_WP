;(function ($) {

	"use strict";

	wp.customize.controlConstructor['resa-spacing'] = wp.customize.Control.extend({

		ready: function () {

			'use strict';

			var control = this;

			// Linked button.
			control.container.on('click', '.resa-spacing-linked', function () {

				// Set up variables.
				var $this = $(this);

				// Remove linked class.
				$this.closest('ul').find('.spacing-input').removeClass('linked');

				// Remove class.
				$this.parent('.spacing-link-values').removeClass('unlinked');
			});

			// Unlinked button.
			control.container.on('click', '.resa-spacing-unlinked', function () {

				// Set up variables.
				var $this = $(this);

				// Remove linked class.
				$this.closest('ul').find('.spacing-input').addClass('linked');

				// Remove class.
				$this.parent('.spacing-link-values').addClass('unlinked');
			});

			// Values linked inputs.
			control.container.on('input', '.linked input', function () {
				var $val = $(this).val();
				$(this).closest('.spacing-input').siblings('.linked').find('input').val($val).change();
			});

			// Change unit.
			control.container.find('.resa-control-unit').on('change', function () {
				control.save_value();
			});

			// Store new inputs.
			control.container.on('change input', '.spacing-input input', function () {
				control.save_value();
			});

			// Reset default values.
			control.container.find('.reset-defaults').on('click', function () {

				control.container.find('input[type="number"]').each(function () {
					$(this).val($(this).data('default'));
				});

				control.container.find('select.resa-control-unit').each(function () {
					$(this).find('option').removeAttr('selected');
					$(this).find('option[value="' + $(this).data('default') + '"]').attr('selected', "selected");
				});

				control.save_value();
			});
		},

		save_value: function () {

			var new_val = {},
				devices = this.params.responsive,
				choices = this.params.choices,
				choice,
				device,
				units = this.container.find('.resa-control-unit');
			if (devices === undefined || devices.length == 0 || !devices) {
				for (choice in choices) {
					new_val[choice] = this.container.find('[data-spacing-choice="' + choice + '"]').val();
				}

				new_val.unit = this.container.find('.resa-spacing-unit').find(":selected").val();
			} else {
				for (device in devices) {
					new_val[device] = {};
					for (choice in choices) {
						new_val[device][choice] = this.container.find('.control-responsive.' + device + ' [data-spacing-choice="' + choice + '"]').val();
					}
					new_val[device].unit = this.container.find('.control-responsive.' + device + ' .resa-spacing-unit').find(":selected").val();
				}
			}

			console.log(new_val);

			this.setting.set(new_val);
		},

	});

})(jQuery);
