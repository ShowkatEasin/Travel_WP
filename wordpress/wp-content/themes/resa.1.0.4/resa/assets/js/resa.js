(function ($) {

	var ResaJS = {

		init: function () {

			this.listenEvents();
			this.initAccessibility();
		},
		listenEvents: function () {
			$('.resa-mobile-menu-toggle').on('click', this.toggleMobileMenu);
			$('.resa-panel-close').on('click', this.closeThisPanel);
			$('.resa-panel-action-trigger-button').on('click', this.openPanel);
			$(document).keyup(function (e) {
				if (e.key === "Escape") { // escape key maps to keycode `27`
					ResaJS.escapeHit();
				}
			});
			/*$('body').on('focus', '[data-device="desktop"] .menu a', function () {
				let li = $(this).closest('li');
				ResaJS.focusMenuItem(li);
			});
			$('body').on('focusout', '[data-device="desktop"] .menu li', function (e) {
				if (!$(e.target).closest('li') !== $(this)) {
					$(this).removeClass('resa-focus');
				}

			});*/
			$(document).on('resa_focus_inside_element', function (event, parent_id, focusable_el, trap_class = 'active') {
				$('#' + parent_id).find(focusable_el).focus();
				var el = document.getElementById(parent_id);
				ResaJS.trapFocus(el, trap_class);

			});
		},
		toggleMobileMenu: function (e) {

			let is_expanded = $(this).attr('aria-expanded');

			let parent_li = $(this).closest('li');


			if (is_expanded === "true") {

				parent_li.find('>.sub-menu').find('li').removeClass('resa-menu-active');
				parent_li.removeClass('resa-menu-active');

				$(this).attr('aria-expanded', false);
			} else {
				//$(this).closest('.menu').find('>li').removeClass('resa-menu-active');
				$(this).closest('li').addClass('resa-menu-active');
				//$(this).closest('li').find('>.sub-menu').addClass('active');
				$(this).attr('aria-expanded', true);
			}

			//not expand
		},
		closeThisPanel: function () {
			let panel = $(this).closest('.resa-panel');
			panel.removeClass('active');
			$('body').removeAttr('data-resa-panel');
			$('[data-panel-trigger-id="' + panel.attr('id') + '"]').focus();
		},
		openThisPanel: function (panel) {
			panel.addClass('active');
			$('body').attr('data-resa-panel', "true");
			$(document).trigger('resa_focus_inside_element', [panel.attr('id'), 'button.resa-panel-close:first-child', 'active']);
		},
		openPanel: function () {
			let trigger_id = $(this).attr('data-panel-trigger-id');
			if ($('#' + trigger_id).length < 1) {
				return;
			}
			let panel = $('#' + trigger_id);
			if (panel.hasClass('active')) {
				panel.find('.resa-panel-close').trigger('click');
			} else {
				ResaJS.openThisPanel(panel);
			}
		},
		escapeHit: function () {
			$("body").find('.resa-panel').find('.resa-panel-close').trigger('click');

		},
		focusMenuItem: function (li) {
			li.addClass('resa-focus');

		},
		initAccessibility: function () {
			var main_menu_container = $('[data-device="desktop"] .menu');
			main_menu_container.find('li.menu-item, li.page_item').focusin(function () {
				if (!$(this).hasClass('resa-focus')) {
					$(this).addClass('resa-focus');
				}
			});
			main_menu_container.find('li.menu-item, li.page_item').focusout(function () {
				$(this).removeClass('resa-focus');

			});
		},
		trapFocus: function (element, open_class) {
			var focusableEls = element.querySelectorAll('a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="search"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])'),
				firstFocusableEl = focusableEls[0];
			magazineNPLastFocusableEl = focusableEls[focusableEls.length - 1];
			var KEYCODE_TAB = 9;
			element.addEventListener('keydown', function (e) {
				var isTabPressed = (e.key === 'Tab' || e.keyCode === KEYCODE_TAB);

				if (!isTabPressed) {
					return;
				}
				if (!element.classList.contains(open_class)) {
					element.removeEventListener('keydown', this);
					return;

				}

				if (e.shiftKey) /* shift + tab */ {
					if (document.activeElement === firstFocusableEl) {
						magazineNPLastFocusableEl.focus();
						e.preventDefault();
					}
				} else /* tab */ {

					if (document.activeElement === magazineNPLastFocusableEl) {
						firstFocusableEl.focus();
						e.preventDefault();
					}
				}

			});
		},
	};

	ResaJS.init();


})(jQuery);
