<?php
/**
 * Database Background Process
 *
 * @package Resa
 * @since 1.0.3
 */

if (class_exists('Resa_WP_Background_Process')) :

	/**
	 * Database Background Process
	 *
	 * @since 1.0.3
	 */
	class Resa_Theme_WP_Background_Process extends Resa_WP_Background_Process
	{

		/**
		 * Database Process
		 *
		 * @var string
		 */
		protected $action = 'resa_theme_db_migration';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param object $process Queue item object.
		 * @return mixed
		 * @since 1.0.3
		 *
		 */
		protected function task($process)
		{

			do_action('resa_batch_process_task_' . $process, $process);

			if (function_exists($process)) {
				call_user_func($process);
			}

			if ('update_db_version' === $process) {
				Resa_Theme_Background_Updater::update_db_version();
			}

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 1.0.3
		 */
		protected function complete()
		{
			do_action('resa_database_migration_complete');
			parent::complete();
		}

	}

endif;
