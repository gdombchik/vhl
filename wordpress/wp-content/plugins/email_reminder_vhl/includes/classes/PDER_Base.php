<?php

Class PDER_Base_VHL {
	
	public function __construct(){
		$this->load_files();
		$this->init();
		$this->admin_init();
	}
	
	public function init(){		

		echo 'in initfff';

		//register Ereminder Custom Post Type
		//add_action('init', array( $this,'register_post_type') );
		$this->register_post_type();
		//add_action('init', array( $this, 'register_assets_vhl' ) );
		$this->register_assets_vhl();
		
		//specify our own cron interval
		//add_filter('cron_schedules', array( $this, 'add_cron_intervals' ) );
		$this->add_cron_intervals();
		

		//9/29 - Not sure yet to do with below ?????????????

		//register our event to cron
		add_action('PDER_cron_send_reminders', array( 'PDER', 'send_ereminders') );
	}
	
	function load_files(){

		//echo 'in load_files';

		/* Ereminder Class */
		require_once( PDER_CLASSES_VHL . '/PDER.php' );
		/* Admin */
		require_once( PDER_CLASSES_VHL . '/PDER_Admin.php' );
		/* Utilities */
		require_once( PDER_CLASSES_VHL . '/PDER_Utils.php' );
	}
	
	function admin_init(){

		//echo 'in admin_init';

		//if( !is_admin() ) return;
		
		$admin = new PDER_Admin_VHL;
		$admin->init();
	}
	
	/**
	 * Stuff that needs to be done once. This function gets fired on plugin activation
	 */
	static function on_activate(){
		//verify event has not been scheduled
		if( !wp_next_scheduled( 'PDER_cron_send_reminders' ) ){
			//schedule our custom event
			wp_schedule_event( time(), 'PDER_5minutes', 'PDER_cron_send_reminders' );
		}
	}
	
	/**
	 * Stuff that needs to be done on deactivation.
	 */
	static function on_deactivate(){
		//clear scheduled events
		while( wp_next_scheduled( 'PDER_cron_send_reminders' ) ){
			$timestamp = wp_next_scheduled( 'PDER_cron_send_reminders' );
			wp_unschedule_event( $timestamp,'PDER_cron_send_reminders' );
		}
	}
	
	/**
	 * Add our own Cron Intervals
	 */
	public function add_cron_intervals( $schedules ){
		//create a 'minute' recurrent schedule option
		$schedules['PDER_minute'] = array(
			'interval' => 60,
			'display' => 'Every Minute'
		);
		
		$schedules['PDER_twicehourly'] = array(
			'interval' => 60*30,
			'display' => 'Twice Hourly (30 min)'
		);
		
		$schedules['PDER_5minutes'] = array(
			'interval' => 60*5,
			'display' => 'Every 5 minutes'
		);
		return $schedules;
	}

	/**
	 * Register 'ereminder' Custom Post Type
	 */
	public function register_post_type(){
		$labels = array(
			'name' => __('E-Reminders'),
			'singular_name' => __('E-Reminder'),
			'add_new' => _x('Create New', 'entry'),
			'add_new_item' => __('Create E-Reminder' ),
			'edit_item' => __( 'Edit E-Reminder' ),
			'new_item' => __( 'New E-Reminder' ),
			'view_item' => __( 'View E-Reminder' ),
			'search_items' => __( 'Search E-Reminders' ),
			'not_found' => __('No E-Reminders found' ),
			'not_found_in_trash' => __('No E-Reminders found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_ui' => false,
			'show_in_menu' => false,
			'publicly_queryable' => false,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array(''),
			'description' => 'Stores reminders'
		);
		
		register_post_type( 'ereminder', $args );
	}
	
	function register_assets_vhl(){

		//echo 'in register_assets_vhl';
		//echo '<br>';
		//echo PDER_CSS_VHL;

		/** Scripts **/
		wp_register_script('pder-datepicker', PDER_JS_VHL . '/jquery-ui-1.8.16.custom.min.js', array( 'jquery', 'jquery-ui-core' ) );
		wp_register_script('pder-timepicker', PDER_JS_VHL . '/jquery.ui.timepicker.addon.js', array( 'pder-datepicker' ) );
		wp_register_script('pder-admin-script', PDER_JS_VHL . '/script.js', array( 'pder-datepicker', 'pder-timepicker' ) );
		
		/** Styles **/
		wp_register_style('pder-admin-style', PDER_CSS_VHL . '/style.css' );
		wp_register_style('pder-datepicker-css', PDER_CSS_VHL . '/jquery.ui.datepicker.css' );
		wp_register_style('pder-datepicker-css-custom', PDER_CSS_VHL . '/jquery-ui-1.8.16.custom.css' );

			/** Scripts **/
		//wp_enqueue_script('pder-admin-script' );
		
		/** Styles **/
		//wp_enqueue_style('pder-admin-style' );
		//wp_enqueue_style('pder-datepicker-css' );
		//wp_enqueue_style('pder-datepicker-css-custom' );
	}
	
} //Pogidude_Email_Reminder

//boot strap
new PDER_Base_VHL();
