// Toggle control
wp.customize.controlConstructor[ 'resa-toggle' ] = wp.customize.Control.extend({
	ready: function() {
		"use strict";

		var control = this;

		// Change the value
		control.container.on( 'click', '.resa-toggle-switch', function() {
			control.setting.set( ! control.setting.get() );
		});

		control.container.on( 'click', '.customize-control-title', function() {
			control.container.find('.resa-toggle-switch').trigger('click');
		});
	},
});
