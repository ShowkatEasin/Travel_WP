<?php

class Resa_Hooks_Footer

{

	public function __construct()
	{
		add_action('resa_footer', array($this, 'footer_widgets'), 20);
		add_action('resa_footer', array($this, 'copyright'), 30);
		add_action('resa_after_site', array($this, 'canvas'));

	}


	public function footer_widgets()
	{
		if ((boolean)resa()->options->get('resa_top_footer_show_widgets') != true) {
			return;
		}
		?>
		<div class="resa-top-footer">
			<div class="resa-container">
				<div class="resa-row">
					<?php

					if (is_active_sidebar('footer-widget-area-1') || is_active_sidebar('footer-widget-area-2')
						|| is_active_sidebar('footer-widget-area-3') || is_active_sidebar('footer-widget-area-4')
					) {
						?>
						<div class="column-3">
							<?php dynamic_sidebar('footer-widget-area-1'); ?>
						</div>
						<div class="column-3">
							<?php dynamic_sidebar('footer-widget-area-2'); ?>
						</div>
						<div class="column-3">
							<?php dynamic_sidebar('footer-widget-area-3'); ?>
						</div>
						<div class="column-3">
							<?php dynamic_sidebar('footer-widget-area-4'); ?>
						</div>
					<?php } else { ?>
						<div class="column-12">
							<?php if (current_user_can('manage_options')) { ?>
								<p class="text-center">You do not have any content on the sidebar. You can add it from <a
										target="_blank" href="<?php echo esc_url(admin_url('widgets.php')) ?>">Widgets</a></p>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function copyright()
	{
		?>
		<div class="resa-bottom-footer">
			<div class="resa-container">
				<div class="resa-row">
					<div class="column-12">
						<?php resa_footer_copyright(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function canvas()
	{
		get_template_part('template-parts/canvas');

	}


}

new Resa_Hooks_Footer();
