<?php

/**
 * WP Cron class.
 *
 * @package     lime-catalog
 * @subpackage  public/
 * @copyright   Copyright (c) 2016, Codeweby - Attila Abraham
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License 
 * @since       1.0.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
 
class LMCTLG_WP_Cron {
	
	const FiveSeconds = 5;
	const OneWeek     = 604800;
	const OneMonth    = 2635200;

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'cron_schedules', array( $this, 'lmctlg_wp_cron_add_new_intervals') ); // wp use: cron_schedules
		add_action( 'wp', array( $this, 'lmctlg_wp_cron_add_events' ) );

	}

	/**
	 * Register new schedules.
	 *
	 * @since 1.0.0
	 * @param array $schedules
	 * @return array
	 */
	public function lmctlg_wp_cron_add_new_intervals($schedules) 
	{
		// The default intervals provided by WordPress are: hourly, twicedaily, daily
		
		// add five_seconds interval (usualy for testings only)
		$schedules['five_seconds'] = array(
			'interval' => self::FiveSeconds,
			'display'  => esc_html__('Every Five Seconds', 'lime-catalog')
		);
		// add weekly interval
		$schedules['weekly'] = array(
			'interval' => self::OneWeek,
			'display'  => esc_html__('Once Weekly', 'lime-catalog')
		);
	    // add monthly interval
		$schedules['monthly'] = array(
			'interval' => self::OneMonth,
			'display'  => esc_html__('Once a month', 'lime-catalog')
		);
	
		return $schedules;
	}
	
	/**
	 * Add events.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function lmctlg_wp_cron_add_events() {
		$this->lmctlg_five_seconds_event();
		$this->lmctlg_daily_event();
		$this->lmctlg_weekly_event();
		$this->lmctlg_monthly_event();
	}

	/**
	 * Schedule event Every Five Seconds. (only for testing purposes)
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function lmctlg_five_seconds_event() {
		if ( ! wp_next_scheduled( 'lmctlg_wp_cron_five_seconds_event' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'five_seconds', 'lmctlg_wp_cron_five_seconds_event' );
		}
	}
	
	/**
	 * Schedule event Once a Day.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function lmctlg_daily_event() {
		if ( ! wp_next_scheduled( 'lmctlg_wp_cron_daily_event' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'daily', 'lmctlg_wp_cron_daily_event' );
		}
	}
	
	/**
	 * Schedule event Once a Week.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function lmctlg_weekly_event() {
		if ( ! wp_next_scheduled( 'lmctlg_wp_cron_weekly_event' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'lmctlg_wp_cron_weekly_event' );
		}
	}

	/**
	 * Schedule event Once a Month.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function lmctlg_monthly_event() {
		if ( ! wp_next_scheduled( 'lmctlg_wp_cron_monthly_event' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'monthly', 'lmctlg_wp_cron_monthly_event' );
		}
	}
	
	
}

$lmctlg_wp_cron = new LMCTLG_WP_Cron;

?>