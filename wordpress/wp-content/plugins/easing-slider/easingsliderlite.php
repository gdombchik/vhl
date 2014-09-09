<?php

/*
    Plugin Name: Easing Slider "Lite"
    Plugin URI: http://easingslider.com/
    Version: 2.1.4.2
    Author: Matthew Ruddy
    Author URI: http://matthewruddy.com/
    Description: Easing Slider "Lite" is an easy to use slideshow plugin for WordPress. Simple, lightweight & designed to get the job done, it allows you to get going without any fuss.
    License: GNU General Public License v2.0 or later
    License URI: http://www.opensource.org/licenses/gpl-license.php

    Copyright 2013 Matthew Ruddy

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/** Load all of the necessary class files for the plugin */
spl_autoload_register( 'EasingSliderLite::autoload' );

/** Let's go! */
if ( class_exists( 'EasingSliderLite' ) )
    EasingSliderLite::get_instance();

/**
 * Main plugin class
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class EasingSliderLite {

    /**
     * Class instance
     *
     * @since 2.0
     */
    private static $instance;

    /**
     * String name of the main plugin file
     *
     * @since 2.0
     */
    private static $file = __FILE__;

    /**
     * Our plugin version
     *
     * @since 2.0
     */
    public static $version = '2.1.4.2';

    /**
     * Our array of Easing Slider "Lite" admin pages. These are used to conditionally load scripts.
     *
     * @since 2.0.1.2
     */
    public $whitelist = array();

    /**
     * Arrays of admin messages
     *
     * @since 2.0
     */
    public $admin_messages = array();

    /**
     * A handy flag for identifying if we are currently using the new WordPress admin area
     *
     * @since 2.1.3
     */
    private $is_updated_admin = false;

    /**
     * Flag for indicating that we are on a EasingSliderLite plugin page
     *
     * @since 2.0
     */
    private $is_easingsliderlite_page = false;
    
    /**
     * PSR-0 compliant autoloader to load classes as needed.
     *
     * @since 2.0
     */
    public static function autoload( $classname ) {
    
        if ( 'ESL' !== substr( $classname, 0, 3 ) )
            return;
            
        $filename = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . str_replace( 'ESL_', '', $classname ) . '.php';
        require $filename;
    
    }

    /**
     * Getter method for retrieving the class instance.
     *
     * @since 2.0
     */
    public static function get_instance() {
    
        if ( !self::$instance instanceof self )
            self::$instance = new self;
        return self::$instance;
    
    }

    /**
     * Gets the main plugin file
     *
     * @since 2.0
     */
    public static function get_file() {
        return self::$file;
    }
    
    /**
     * Constructor
     *
     * @since 2.0
     */
    private function __construct() {

        global $wp_version;

        /** Load plugin textdomain for language capabilities */
        load_plugin_textdomain( 'easingsliderlite', false, dirname( plugin_basename( self::get_file() ) ) . '/languages' );

        /** Flag the new admin area if appropriate */
        $this->is_updated_admin = ( version_compare( $wp_version, '3.8', '>=' ) ) ? true : false;

        /** Activation and deactivation hooks. Static methods are used to avoid activation/uninstallation scoping errors. */
        if ( is_multisite() ) {
            register_activation_hook( __FILE__, array( __CLASS__, 'do_network_activation' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'do_network_uninstall' ) );
        }
        else {
            register_activation_hook( __FILE__, array( __CLASS__, 'do_activation' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'do_uninstall' ) );
        }

        /** Legacy functionality */
        if ( apply_filters( 'easingsliderlite_legacy_functionality', __return_true() ) )
            ESL_Legacy::init( $this );

        /** Plugin shortcodes */
        add_shortcode( 'easingsliderlite', array( $this, 'do_shortcode' ) );

        /** Plugin actions */
        add_action( 'init', array( $this, 'register_all_styles' ) );
        add_action( 'init', array( $this, 'register_all_scripts' ) );
        add_action( 'admin_menu', array( $this, 'add_menus' ) );
        add_action( 'admin_menu', array( $this, 'do_actions' ) );
        add_action( 'admin_body_class', array( $this, 'admin_body_classes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'media_buttons', array( $this, 'add_media_button' ), 11 );
        add_action( 'print_media_templates', array( $this, 'print_backbone_templates' ) );
        add_action( 'wp_before_admin_bar_render', array( $this, 'add_admin_bar_links' ) );

        /** Do plugin upgrades */
        add_action( 'admin_init', array( 'ESL_Upgrade', 'do_upgrades' ) );

        /** Register our custom widget */
        add_action( 'widgets_init', create_function( '', 'register_widget( "ESL_Widget" );' ) );

        /** Some hooks for our own custom actions */
        add_action( 'easingsliderlite_edit_slideshow_actions', array( $this, 'do_slideshow_actions' ) );
        add_action( 'easingsliderlite_customizer_actions', array( $this, 'do_customizer_actions' ) );
        add_action( 'easingsliderlite_edit_settings_actions', array( $this, 'do_settings_actions' ) );

        /** Get plugin settings */
        $settings = get_option( 'easingsliderlite_settings' );

        /** Load slideshow scripts & styles in the header if set to do so */
        if ( isset( $settings['load_scripts'] ) && $settings['load_scripts'] == 'header' )
            add_action( 'wp_enqueue_scripts', array( 'ESL_Slideshow', 'enqueue_scripts' ) );
        if ( isset( $settings['load_styles'] ) && $settings['load_styles'] == 'header' ) {
            add_action( 'wp_enqueue_scripts', array( 'ESL_Slideshow', 'enqueue_styles' ) );
            add_action( 'wp_head', array( 'ESL_Slideshow', 'print_custom_styles') );
        }

        /** Initialization hook for adding external functionality */
        do_action_ref_array( 'easingsliderlite', array( $this ) );

    }
    
    /**
     * Executes a network activation
     *
     * @since 2.0
     */
    public static function do_network_activation() {
        self::get_instance()->network_activate();
    }
    
    /**
     * Executes a network uninstall
     *
     * @since 2.0
     */
    public static function do_network_uninstall() {
        self::get_instance()->network_uninstall();
    }
    
    /**
     * Executes an activation
     *
     * @since 2.0
     */
    public static function do_activation() {
        self::get_instance()->activate();
    }
    
    /**
     * Executes an uninstall
     *
     * @since 2.0
     */
    public static function do_uninstall() {
        self::get_instance()->uninstall();
    }
    
    /**
     * Network activation hook
     *
     * @since 2.0
     */
    public function network_activate() {

        /** Do plugin version check */
        if ( !$this->version_check() )
            return;

        /** Get all of the blogs */
        $blogs = $this->get_multisite_blogs();

        /** Execute acivation for each blog */
        foreach ( $blogs as $blog_id ) {
            switch_to_blog( $blog_id );
            $this->activate();
            restore_current_blog();
        }

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderlite_network_activate', array( $this ) );

    }
    
    /**
     * Network uninstall hook
     *
     * @since 2.0
     */
    public function network_uninstall() {

        /** Get all of the blogs */
        $blogs = $this->get_multisite_blogs();

        /** Execute uninstall for each blog */
        foreach ( $blogs as $blog_id ) {
            switch_to_blog( $blog_id );
            $this->uninstall();
            restore_current_blog();
        }

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderlite_network_uninstall', array( $this ) );

    }
    
    /**
     * Activation hook
     *
     * @since 2.0
     */
    public function activate() {

        /** Do plugin version check */
        if ( !$this->version_check() )
            return;

        /** Add "wp_options" table options */
        add_option( 'easingsliderlite_version', self::$version );
        add_option( 'easingsliderlite_slideshow', $this->slideshow_defaults() );
        add_option( 'easingsliderlite_customizations', json_encode( $this->customization_defaults() ) );
        add_option( 'easingsliderlite_settings',
            array(
                'resizing' => false,
                'load_styles' => 'header',
                'load_scripts' => 'header'
            )
        );
        add_option( 'easingsliderlite_major_upgrade', 0 );
        add_option( 'easingsliderlite_disable_welcome_panel', 0 );

        /** Add user capabilities */
        $this->manage_capabilities( 'add' );

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderlite_activate', array( $this ) );

    }
    
    /**
     * Uninstall Hook
     *
     * @since 2.0
     */
    public function uninstall() {

        /** Delete "wp_options" table options */
        delete_option( 'easingsliderlite_version' );
        delete_option( 'easingsliderlite_slideshow' );
        delete_option( 'easingsliderlite_customizations' );
        delete_option( 'easingsliderlite_settings' );
        delete_option( 'easingsliderlite_major_upgrade' );
        delete_option( 'easingsliderlite_disable_welcome_panel' );

        /** Remove user capabilities */
        $this->manage_capabilities( 'remove' );

        /** Trigger hooks */
        do_action_ref_array( 'easingsliderlite_uninstall', array( $this ) );

    }
    
    /**
     *  Does a plugin version check, making sure the current Wordpress version is supported. If not, the plugin is deactivated and an error message is displayed.
     *
     *  @author Matthew Ruddy
     *  @version 2.0.1
     */
    public function version_check() {
        global $wp_version;
        if ( version_compare( $wp_version, '3.5', '<' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( sprintf( 'Sorry, but your version of WordPress, <strong>%s</strong>, is not supported. The plugin has been deactivated. <a href="%s">Return to the Dashboard.</a>', $wp_version, admin_url() ), 'easingsliderlite' ) );
            return false;
        }
        return true;
    }
    
    /**
     * Returns the ids of the various multisite blogs. Returns false if not a multisite installation.
     *
     * @since 2.0
     */
    public function get_multisite_blogs() {

        global $wpdb;

        /** Bail if not multisite */
        if ( !is_multisite() )
            return false;

        /** Get the blogs ids from database */
        $query = "SELECT blog_id from $wpdb->blogs";
        $blogs = $wpdb->get_col($query);

        /** Push blog ids to array */
        $blog_ids = array();
        foreach ( $blogs as $blog )
            $blog_ids[] = $blog;

        /** Return the multisite blog ids */
        return $blog_ids;

    }

    /**
     * Default slideshow options
     *
     * @since 2.0
     */
    public function slideshow_defaults() {

        /** Get the current user to be assigned as the slideshow author */
        $author = __( 'Unknown', 'easingsliderlite' );
        if ( function_exists( 'wp_get_current_user' ) )
            $author = wp_get_current_user()->user_login;

        $object = new stdClass();
        $object->author = $author;
        $object->slides = array();
        $object->general = (object) array( 'randomize' => '' );
        $object->dimensions = (object) array( 'width' => 640, 'height' => 240, 'responsive' => true );
        $object->transitions = (object) array( 'effect' => 'slide', 'duration' => 500 );
        $object->navigation = (object) array( 'arrows' => true, 'arrows_hover' => false, 'arrows_position' => 'inside', 'pagination' => true, 'pagination_hover' => false, 'pagination_position' => 'inside', 'pagination_location' => 'bottom-center' );
        $object->playback = (object) array( 'enabled' => true, 'pause' => 4000 );
        return apply_filters( 'easingsliderlite_slideshow_defaults', $object );

    }

    /**
     * Get custom styling default options
     *
     * @since 2.1
     */
    public function customization_defaults() {

        $object = (object) array(
            'arrows' => (object) array(
                'next' => stripslashes_deep( plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_arrow_next.png' ) ),
                'prev' => stripslashes_deep( plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_arrow_prev.png' ) ),
                'width' => 30,
                'height' => 30
            ),
            'pagination' => (object) array(
                'inactive' => stripslashes_deep( plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_icon_inactive.png' ) ),
                'active' => stripslashes_deep( plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_icon_active.png' ) ),
                'width' => 15,
                'height' => 15
            ),
            'border' => (object) array(
                'color' => '#000',
                'width' => 0,
                'radius' => 0
            ),
            'shadow' => (object) array(
                'enable' => false,
                'image' => stripslashes_deep( plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'slideshow_shadow.png' ) )
            )
        );
        return apply_filters( 'easingsliderlite_customizer_defaults', $object );

    }

    /**
     * Returns the plugin capabilities
     *
     * @since 2.0
     */
    public function capabilities() {
        $capabilities = array(
            'easingsliderlite_edit_slideshow',
            'easingsliderlite_can_customize',
            'easingsliderlite_edit_settings'
        );
        $capabilities = apply_filters( 'easingsliderlite_capabilities', $capabilities );
        return $capabilities; 
    }
    
    /**
     * Manages (adds or removes) user capabilities
     *
     * @since 2.0
     */
    public function manage_capabilities( $action ) {

        global $wp_roles;

        /** Get the capabilities */
        $capabilities = $this->capabilities();
        
        /** Add capability for each applicable user role */
        foreach ( $wp_roles->roles as $role => $info ) {
            $user_role = get_role( $role );
            foreach ( $capabilities as $capability ) {
                if ( $action == 'add' )
                    $this->add_capability( $capability, $user_role );
                elseif ( $action == 'remove' )
                    $this->remove_capability( $capability, $user_role );
            }
        }

    }
    
    /**
     * Adds a user capability
     *
     * @since 2.0
     */
    public function add_capability( $capability, $role ) {
        if ( $role->has_cap( 'edit_plugins' ) )
            $role->add_cap( $capability );
    }
    
    /**
     * Removes a user capability
     *
     * @since 2.0
     */
    public function remove_capability( $capability, $role ) {
        if ( $role->has_cap( $capability ) )
            $role->remove_cap( $capability );
    }
    
    /**
     * Adds the admin menus
     *
     * @since 2.0
     */
    public function add_menus() {

        /** Hook suffixs for admin menus */
        $pages = array( 'easingsliderlite_edit_slideshow', 'easingsliderlite_customizer', 'easingsliderlite_edit_settings' );

        /** Show a dashicon if using the updated admin area */
        $icon = ( $this->is_updated_admin ) ? 'dashicons-format-gallery' : null;

        /** Toplevel menu */
        $this->whitelist[] = add_menu_page(
            __( 'Slideshow', 'easingsliderlite' ),
            __( 'Slideshow', 'easingsliderlite' ),
            'easingsliderlite_edit_slideshow',
            'easingsliderlite_edit_slideshow',
            null,
            $icon
        );

        /** Submenus */
        $this->whitelist[] = add_submenu_page(
            'easingsliderlite_edit_slideshow',
            __( 'Edit Slideshow', 'easingsliderlite' ),
            __( 'Edit Slideshow', 'easingsliderlite' ),
            'easingsliderlite_edit_slideshow',
            'easingsliderlite_edit_slideshow',
            array( $this, 'edit_slideshow_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderlite_edit_slideshow',
            __( 'Customizer', 'easingsliderlite' ),
            __( 'Customize', 'easingsliderlite' ),
            'easingsliderlite_can_customize',
            'easingsliderlite_customizer',
            array( $this, 'customizer_view' )
        );
        $this->whitelist[] = add_submenu_page(
            'easingsliderlite_edit_slideshow',
            __( 'Edit Settings', 'easingsliderlite' ),
            __( 'Settings', 'easingsliderlite' ),
            'easingsliderlite_edit_settings',
            'easingsliderlite_edit_settings',
            array( $this, 'edit_settings_view' )
        );

        /** Set flag if we are on one of our own plugin pages */
        if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $pages ) )
            $this->is_easingsliderlite_page = true;

    }

    /**
     *  Adds plugin links to the admin bar
     *
     *  @author Matthew Ruddy
     *  @version 2.0.1
     */
    function add_admin_bar_links() {
        
        global $wp_admin_bar;

        /** Avoid showing these links when not logged into admin (prevents buddypress bug) */
        if ( ! is_admin() )
            return;

        /** Add the new toplevel menu */
        $wp_admin_bar->add_menu(
            array(
                'id' => 'slideshows-top_menu',
                'title' => __( 'Slideshow', 'easingsliderlite' ),
                'href' => admin_url( "admin.php?page=easingsliderlite_edit_slideshow" )
            )
        );

        /** Add submenu links to our toplevel menu */
        $wp_admin_bar->add_menu(
            array(
                'parent' => 'slideshows-top_menu',
                'id' => 'edit-slideshow-sub_menu',
                'title' => __( 'Edit Slideshow', 'easingsliderlite' ),
                'href' => admin_url( "admin.php?page=easingsliderlite_edit_slideshow" )
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'parent' => 'slideshows-top_menu',
                'id' => 'customizer-sub_menu',
                'title' => __( 'Customize', 'easingsliderlite' ),
                'href' => admin_url( "admin.php?page=easingsliderlite_customizer" )
            )
        );
        $wp_admin_bar->add_menu(
            array(
                'parent' => 'slideshows-top_menu',
                'id' => 'edit-settings-sub_menu',
                'title' => __( 'Settings', 'easingsliderlite' ),
                'href' => admin_url( "admin.php?page=easingsliderlite_edit_settings" )
            )
        );

    }

    /**
     * Adds a media button (for inserting a slideshow) to the Post Editor
     *
     * @since 2.0
     */
    function add_media_button( $editor_id ) {

        /** Load button javascript */
        ?>
        <script type="text/javascript">
            function insertSlideshow() {
                send_to_editor( '[easingsliderlite]' );
                tb_close();
                return false;
            }
        </script>
        <?php
        
        /** Show appropriate button and styling */
        if ( $this->is_updated_admin ) {

            /** WordPress v3.8+ button */
            ?>
            <style type="text/css">
                .insert-slideshow.button .insert-slideshow-icon:before {
                    content: "\f128";
                    font: 400 18px/1 dashicons;
                    speak: none;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }
            </style>
            <a onClick="insertSlideshow();" class="button insert-slideshow" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a slideshow', 'easingsliderlite' ); ?>"><?php echo '<span class="wp-media-buttons-icon insert-slideshow-icon"></span>' . __( ' Add Slideshow', 'easingsliderlite' ); ?></a>
            <?php

        }
        else {

            /** Backwards compatibility button */
            ?>
            <style type="text/css">
                .insert-slideshow.button .insert-slideshow-icon {
                    width: 16px;
                    height: 16px;
                    margin-top: -1px;
                    margin-left: -1px;
                    margin-right: 4px;
                    display: inline-block;
                    vertical-align: text-top;
                    background: url(<?php echo plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'menu_icon_single_grey.png' ); ?>) no-repeat top left;
                }
            </style>
            <a onClick="insertSlideshow();" class="button insert-slideshow" data-editor="<?php echo esc_attr( $editor_id ); ?>" title="<?php _e( 'Add a slideshow', 'easingsliderlite' ); ?>"><?php echo '<span class="insert-slideshow-icon"></span>' . __( 'Add Slideshow', 'easingsliderlite' ); ?></a>
            <?php

        }

    }
    
    /**
     * Queues an admin message to be displayed
     *
     * @since 2.0
     */
    public function queue_message( $text, $type ) {
        if ( !$this->is_easingsliderlite_page )
            return;
        $message = "<div class='message $type'><p>$text</p></div>";
        add_action( 'admin_notices', create_function( '', 'echo "'. $message .'";' ) );
    }

    /**
     * Does security nonce checks
     *
     * @since 2.0
     */
    public function security_check( $action, $page ) {
        if ( check_admin_referer( "easingsliderlite-{$action}_{$page}", "easingsliderlite-{$action}_{$page}" ) )
            return true;
        return false;
    }

    /**
     * Nonce URL function, polyfill for upcoming trac contribution by me! :)
     * http://core.trac.wordpress.org/ticket/22423
     *
     * @since 2.0
     */
    public function nonce_url( $actionurl, $action, $arg = '_wpnonce' ) {
        $actionurl = str_replace( '&amp;', '&', $actionurl );
        return esc_html( add_query_arg( $arg, wp_create_nonce( $action, $actionurl ), $actionurl ) );
    }
    
    /**
     * Does admin actions (if appropriate)
     *
     * @since 2.0
     */
    public function do_actions() {

        /** Bail if we aren't on a EasingSliderLite page */
        if ( !$this->is_easingsliderlite_page )
            return;

        /** Do admin actions */
        do_action( "{$_GET['page']}_actions", $_GET['page'] );

    }
    
    /**
     * Slideshow based actions
     *
     * @since 2.0
     */
    public function do_slideshow_actions( $page ) {

        /** Disable welcome panel if it is dismissed */
        if ( isset( $_GET['disable_welcome_panel'] ) )
            update_option( 'easingsliderlite_disable_welcome_panel', filter_var( $_GET['disable_welcome_panel'], FILTER_VALIDATE_BOOLEAN ) );

        /** Save or update a slideshow. Whichever is appropriate. */
        if ( isset( $_POST['save'] ) ) {

            /** Security check. Page is hardcoded to prevent errors when adding a new slidesow) */
            if ( !$this->security_check( 'save', 'easingsliderlite_edit_slideshow' ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderlite' ) );
                exit();
            }

            /** Updates the slideshow */
            update_option( 'easingsliderlite_slideshow', 
                (object) array(
                    'author' => stripslashes_deep( $_POST['author'] ),
                    'slides' => json_decode( stripslashes_deep( $_POST['slides'] ) ), /** Slides are stored as JSON string and need to be decoded before being saved. */
                    'general' => (object) stripslashes_deep( $_POST['general'] ),
                    'dimensions' => (object) stripslashes_deep( $_POST['dimensions'] ),
                    'transitions' => (object) stripslashes_deep( $_POST['transitions'] ),
                    'navigation' => (object) stripslashes_deep( $_POST['navigation'] ),
                    'playback' => (object) stripslashes_deep( $_POST['playback'] )
                )
            );

            /** Return success message */
            return $this->queue_message( __( 'Slideshow has been <strong>saved</strong> successfully.', 'easingsliderlite' ), 'updated' );

        }

    }
    
    /**
     * Customization page actions
     *
     * @since 2.1
     */
    public function do_customizer_actions( $page ) {

        /** Save customizations */
        if ( isset( $_POST['save'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'save', $page ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderlite' ) );
                exit();
            }

            /** Save the customizations */
            update_option( 'easingsliderlite_customizations',
                json_encode( EasingSliderLite::get_instance()->validate( (object) array(
                    'arrows' => (object) $_POST['arrows'],
                    'pagination' => (object) $_POST['pagination'],
                    'border' => (object) $_POST['border'],
                    'shadow' => (object) $_POST['shadow']
                ) ) )
            );

        }

    }
    
    /**
     * Settings page actions
     *
     * @since 2.0
     */
    public function do_settings_actions( $page ) {

        /** Reset plugin */
        if ( isset( $_POST['reset'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'reset', $page ) ) {
                wp_die( __( 'Security check has failed. Reset has been prevented. Please try again.', 'easingsliderlite' ) );
                exit();
            }

            /** Do reset */
            $this->uninstall();
            $this->activate();

            /** Queue message */
            return $this->queue_message( __( 'Plugin has been reset successfully.', 'easingsliderlite' ), 'updated' );

        }

        /** Save the settings */
        if ( isset( $_POST['save'] ) ) {

            /** Security check */
            if ( !$this->security_check( 'save', $page ) ) {
                wp_die( __( 'Security check has failed. Save has been prevented. Please try again.', 'easingsliderlite' ) );
                exit();
            }

            /** Get settings and do some validation */
            $settings = $this->validate( $_POST['settings'] );

            /** Update database option and get response */
            update_option( 'easingsliderlite_settings', stripslashes_deep( $settings ) );

            /** Show update message */
            return $this->queue_message( __( 'Settings have been <strong>saved</strong> successfully.', 'easingsliderlite' ), 'updated' );

        }

    }

    /**
     * Does validation
     *
     * @since 2.0
     */
    public function validate( $values ) {

        /** Object flag */
        $is_object = ( is_object( $values ) ) ? true : false;

        /** Convert objects to arrays */
        if ( $is_object )
            $values = (array) $values;

        /** Get settings and do some validation */
        foreach ( $values as $key => $value ) {

            /** Validators */
            if ( is_numeric( $value ) )
                $values[ $key ] = filter_var( $value, FILTER_VALIDATE_INT );
            elseif ( $value === 'true' || $value === 'false' )
                $values[ $key ] = filter_var( $value, FILTER_VALIDATE_BOOLEAN );

            /** Recurse if necessary */
            if ( is_object( $value ) || is_array( $value ) )
                $values[ $key ] = $this->validate( $value );

        }

        /** Convert back to an object */
        if ( $is_object )
            $values = (object) $values;

        return stripslashes_deep( $values );

    }
    
    /**
     * Executes a shortcode handler
     *
     * @since 2.0
     */
    public function do_shortcode() {

        /** Get the slideshow */
        $slideshow = ESL_Slideshow::get_instance()->display_slideshow();

        /** Display the slideshow (or error message if it doesn't exist) */
        if ( is_wp_error( $slideshow ) )
            return $slideshow->get_error_message();
        else
            return trim( $slideshow );

    }

    /**
     * Adds our admin specific body classes
     *
     * @since 2.1.3
     */
    public function admin_body_classes( $classes ) {

        /** Add a reference to the new-look flat WordPress admin area if appropriate */
        if ( $this->is_updated_admin ) {
            $classes .= ' easingsliderlite-updated-admin ';
        }

        return $classes;

    }
    
    /**
     * Register all admin stylesheets
     *
     * @since 2.1
     */
    public function register_all_styles() {

        /** Get the extension */
        $ext = ( apply_filters( 'easingsliderlite_debug_styles', __return_false() ) === true ) ? '.css' : '.min.css';

        /** Register styles */
        wp_register_style( 'esl-admin', plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'css'. DIRECTORY_SEPARATOR .'admin'. $ext ), false, self::$version );
        wp_register_style( 'esl-slideshow', plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'css'. DIRECTORY_SEPARATOR .'slideshow'. $ext ), false, self::$version );
        
    }
    
    /**
     * Register all admin scripts
     *
     * @since 2.1
     */
    public function register_all_scripts() {

        /** Get the extension */
        $ext = ( apply_filters( 'easingsliderlite_debug_scripts', __return_false() ) ) ? '.js' : '.min.js';

        /** Register scripts */
        wp_register_script( 'esl-admin',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'admin'. $ext ), array( 'jquery', 'jquery-ui-sortable', 'backbone' ), self::$version, true );
        wp_register_script( 'esl-customizer',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'customizer'. $ext ), array( 'jquery', 'backbone' ), self::$version ); 
        wp_register_script( 'esl-slideshow',  plugins_url( dirname( plugin_basename( self::get_file() ) ) . DIRECTORY_SEPARATOR .'js'. DIRECTORY_SEPARATOR .'slideshow'. $ext ), false, self::$version );

    }
    
    /**
     * Loads admin stylesheets
     *
     * @since 2.0
     */
    public function enqueue_admin_styles( $hook ) {

        /** Bail if not an Easing Slider "Lite" page */
        if ( !in_array( $hook, $this->whitelist ) )
            return;

        /** Load dashicons if using the new flat administration area */
        if ( $this->is_updated_admin ) {
            wp_enqueue_style( 'dashicons' );
        }

        /** Load styles */
        wp_enqueue_style( 'esl-admin' );
        do_action( 'easingsliderlite_enqueue_admin_styles' );

    }
    
    /**
     * Loads admin javascript files
     *
     * @since 2.0
     */
    public function enqueue_admin_scripts( $hook ) {

        /** Bail if not an Easing Slider "Lite" page */
        if ( !in_array( $hook, $this->whitelist ) )
            return;

        /** Print Localized variables */
        wp_localize_script( 'esl-admin', 'easingsliderlite', $this->localizations() );

        /** Load scripts */
        wp_enqueue_media();
        wp_enqueue_script( 'esl-admin' );
        do_action( 'easingsliderlite_enqueue_admin_scripts' );

    }
    
    /**
     * Translations localized via Javascript
     *
     * @since 2.0
     */
    public function localizations() {
        return array(
            'plugin_url' => '/wp-content/plugins/'. dirname( plugin_basename( self::get_file() ) ) .'/',
            'warn' => __( 'Are you sure you wish to do this? This cannot be reversed.', 'easingsliderlite' ),
            'delete_image' => __( 'Are you sure you wish to delete this image? This cannot be reversed.', 'easingsliderlite' ),
            'delete_images' => __( 'Are you sure you wish to delete all of this slideshows images? This cannot be reversed.', 'easingsliderlite' ),
            'media_upload' => array(
                'title' => __( 'Add Images to Slideshow', 'easingsliderlite' ),
                'button' => __( 'Insert into slideshow', 'easingsliderlite' ),
                'change' => __( 'Use this image', 'easingsliderlite' ),
                'discard_changes' => __( 'Are you sure you wish to discard changes?', 'easingsliderlite' )
            )
        );
    }
    
    /**
     * Prints the backbone templates used in the admin area
     *
     * @since 2.0
     */
    public function print_backbone_templates() {

        /** Bail if not a EasingSliderLite page */
        if ( !$this->is_easingsliderlite_page )
            return;

        /** Slide template */
        echo '<script type="text/html" id="tmpl-slide">';
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow-thumbnail.php';
        echo '</script>';
        
        /** Slide editor template */
        echo '<script type="text/html" id="tmpl-edit-slide">';
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow-slide.php';
        echo '</script>';

        /** Media Library custom sidebar */
        echo '<script type="text/html" id="tmpl-media-details">';
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow-media-details.php';
        echo '</script>';
        
    }
    
    /**
     * Edit a slideshow view
     *
     * @since 2.0
     */
    public function edit_slideshow_view() {

        /** Load the edit view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editslideshow.php';

    }

    /**
     * Customizer view
     *
     * @since 2.1
     */
    public function customizer_view() {

        /** Load the customizer view template */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'customizer.php';

    }
    
    /**
     * Edit settings view
     *
     * @since 2.0
     */
    public function edit_settings_view() {

        /** Load the edit settings view */
        require dirname( self::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'editsettings.php';

    }

}

/**
 * Slideshow helper function
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
if ( !function_exists( 'easingsliderlite' ) ) {
    function easingsliderlite() {
        echo ESL_Slideshow::get_instance()->display_slideshow();
    }
}