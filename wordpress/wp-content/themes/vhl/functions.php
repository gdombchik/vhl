<?php
  define('HEADER_IMAGE_WIDTH', 960); // use width and height appropriate for your theme
  define('HEADER_IMAGE_HEIGHT', 130);
  define('NO_HEADER_TEXT', true);
  
  //-- Unregister parent theme since user only needs the default page template
  function unregister_parent_sidebars()
  {
    unregister_sidebar('main-sidebar');
    unregister_sidebar('right-sidebar');
    unregister_sidebar('left-sidebar');
    unregister_sidebar('left-sidebar-half');
    unregister_sidebar('right-sidebar-half');
    unregister_sidebar('home-widget-1');
    unregister_sidebar('home-widget-2');
    unregister_sidebar('home-widget-3');
    unregister_sidebar('gallery-widget');
    unregister_sidebar('colophon-widget');
    unregister_sidebar('top-widget');
    
    //-- Also remove the default widgets
     unregister_widget('WP_Widget_Pages');
     unregister_widget('WP_Widget_Calendar');
     unregister_widget('WP_Widget_Archives');
     unregister_widget('WP_Widget_Links');
     unregister_widget('WP_Widget_Meta');
     unregister_widget('WP_Widget_Search');
     unregister_widget('WP_Widget_Categories');
     unregister_widget('WP_Widget_Recent_Posts');
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_RSS');
     unregister_widget('WP_Widget_Tag_Cloud');
     unregister_widget('WP_Nav_Menu_Widget');    
  }
  add_action('widgets_init', 'unregister_parent_sidebars', 11);
  
  //-- Now add our sidebars
  add_action('widgets_init', 'my_widgets_init', 12);
  
  function my_widgets_init()
  {        
    register_sidebar( array(
    'name' => 'Main Mission',
    'id' => 'main-mission',
    'description' => __( 'Place mission statement for Main Page here'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main-mission">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
  
    register_sidebar( array(
    'name' => 'Testimonials',
    'id' => 'testimonials',
    'description' => __( 'Place testimonials here'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s testimonials" style="padding:0; margin:0 15px 0 0">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
  
    register_sidebar( array(
    'name' => 'Main Donate',
    'id' => 'main_donate',
    'description' => __( 'Place content here for displaying in the main donate widget'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main_donate">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
  
    register_sidebar( array(
    'name' => 'Main Slider Title',
    'id' => 'main_slider_title',
    'description' => __( 'Place the slider control here along with any additional text'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main_slider_title">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
  
    register_sidebar( array(
    'name' => 'Main Slider',
    'id' => 'main_slider',
    'description' => __( 'Place the slider control here along with any additional text'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main_slider">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
/*  
    register_sidebar( array(
    'name' => 'Main News',
    'id' => 'main_news',
    'description' => __( 'Place the News content here'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main_news">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
*/  
    register_sidebar( array(
    'name' => 'Main Events',
    'id' => 'main_events',
    'description' => __( 'Place the Events content here'),
    'before_widget' => '<li id="%1$s" class="widget-wrapper %2$s main_events">',
    'after_widget' => "</li>",
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ) );
  
    register_sidebar(array(
            'name' => 'Home Widget 1',
            'description' => 'Bottom left widget on home page',
            'id' => 'home-widget-1',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s home_widget_1" style="min-height:390px; background-color:#f2f3d8; margin-left:10px; padding-top:10px">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => 'Home Widget 2',
            'description' => 'Bottom middle widget on home page',
            'id' => 'home-widget-2',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s home_widget_2" style="min-height:390px; background-color:#f2f3d8; padding-top:10px">',
            'after_widget' => '</div>'
        ));

        register_sidebar(array(
            'name' => 'Home Widget 3',
            'description' => 'Bottom right widget on home page',
            'id' => 'home-widget-3',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s home_widget_3" style="min-height:390px; background-color:#f2f3d8; margin-right:10px; padding-top:10px">',
            'after_widget' => '</div>'
        ));    
  }
  
  //-- Add Menu item to Admin Dashboard
  add_action('admin_menu','vhl_admin_menu');
  
  function vhl_admin_menu() {
     add_menu_page('Main Page','Main Page','manage_options', "main_page", 'main_page_settings');
  }
  
  function main_page_settings(){
  ?>
    <h1>Main Page Settings</h1>
    Use this screen to set various areas of the main page
    <form method="post" action="">
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td>MP4 Video URL:</td><td><input type="text" style="width:400px" id="MainMP4VideoURL" name="MainMP4VideoURL" /></td>
        </tr>
        <tr>
          <td>OGV Video URL:</td><td><input type="text" style="width:400px" id="MainOGVVideoURL" name="MainOGVVideoURL" /></td>
        </tr>
        <tr>
          <td>WebM Video URL:</td><td><input type="text" style="width:400px" id="MainWebMVideoURL" name="MainWebMVideoURL" /></td>
        </tr>
        <tr>
          <td>Video Clip Poster:</td><td><input type="text" style="width:400px" id="MainVideoPoster" name="MainVideoPoster" /></td>
        </tr>
      </table>
    </form><?php
  }
  
/**
 * Create HTML list of pages.
 *
 * @package Razorback
 * @subpackage Walker
 * @author Michael Fields <michael@mfields.org>
 * @copyright Copyright (c) 2010, Michael Fields
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @uses Walker_Page
 *
 * @since 2010-05-28
 * @alter 2010-10-09
 */
class Razorback_Walker_Page_Selective_Children extends Walker_Page {
    /**
     * Walk the Page Tree.
     *
     * @global stdClass WordPress post object.
     * @uses Walker_Page::$db_fields
     * @uses Walker_Page::display_element()
     *
     * @since 2010-05-28
     * @alter 2010-10-09
     */
    function walk( $elements, $max_depth ) {
        global $post;
        $args = array_slice( func_get_args(), 2 );
        $output = '';
 
        /* invalid parameter */
        if ( $max_depth < -1 ) {
            return $output;
        }
 
        /* Nothing to walk */
        if ( empty( $elements ) ) {
            return $output;
        }
 
        /* Set up variables. */
        $top_level_elements = array();
        $children_elements  = array();
        $parent_field = $this->db_fields['parent'];
        $child_of = ( isset( $args[0]['child_of'] ) ) ? (int) $args[0]['child_of'] : 0;
 
        /* Loop elements */
        foreach ( (array) $elements as $e ) {
            $parent_id = $e->$parent_field;
            if ( isset( $parent_id ) ) {
                /* Top level pages. */
                if( $child_of === $parent_id ) {
                    $top_level_elements[] = $e;
                }
                /* Only display children of the current hierarchy. */
                else if (
                    ( isset( $post->ID ) && $parent_id == $post->ID ) ||
                    ( isset( $post->post_parent ) && $parent_id == $post->post_parent ) ||
                    ( isset( $post->ancestors ) && in_array( $parent_id, (array) $post->ancestors ) )
                ) {
                    $children_elements[ $e->$parent_field ][] = $e;
                }
            }
        }
 
        /* Define output. */
        foreach ( $top_level_elements as $e ) {
            $this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
        }
        return $output;
    }
}

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
} 


function GetPostTopImage($PageID)
{
  if (!is_numeric($PageID))
    return "";
  
  global $wpdb;
  
  $q = "SELECT meta_value FROM wp_postmeta WHERE meta_key = 'TopImage' and post_id = " . $PageID;
  $ImageURL = $wpdb->get_var($q);

  return $ImageURL; 
} 
?>