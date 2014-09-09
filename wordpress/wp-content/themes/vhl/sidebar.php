<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Main Widget Template
 *
 *
 */
?>
<div id="widgets" class="grid col-300 fit">
<?php responsive_widgets(); // above widgets hook ?>
    
    <?php if (!dynamic_sidebar('main-sidebar')) : ?>
    <div class="widget-wrapper" style="margin-top:0">
            
    <?php
    
      //-- No sidebar images on these pages
      if ($post->ID == 8 || $post->ID == 224)
      {
        
      }
      else
      {
        //-- Display sidebar menu for this section
?>        
        <div id="menu_sidebar">
<?php       //-- Get the top most parent and then list all the child pages below it
          global $post;
          
          if (empty($post->post_parent))
          {
            //-- At the very top parent already
            $ParentID = $post->ID;
          }
          else
          {
            $ancestors = get_post_ancestors($post->ID);
            //-- top level parent will be last item in array
            //-- This is now the root parent ID, so get all the child pages for it
            $root = count($ancestors) - 1;
            $ParentID = $ancestors[$root];
          }         
//          $children = wp_list_pages("title_li=&child_of=" . $ParentID . "&echo=0");
?>          
          <h1 style="margin:0"><?php echo get_the_title($ParentID) ?></h1>
          <div id="submenu_content">
          <ul id="submenu">
<?php         
/*              
          if ($children)
          {
            echo '<ul id="subnav">';
            echo $children; 
            echo '</ul>';
          }
*/      
          if ( is_page() ) {
              //$get_children_of = ( isset( $post->ID ) ) ? (int) $post->ID : 0;
              $get_children_of = ( isset( $ParentID ) ) ? (int) $ParentID : 0;
              $walker = new Razorback_Walker_Page_Selective_Children();
              wp_list_pages( array(
                  'title_li' => '',
                  'depth' => 0,
                  'child_of' => $get_children_of,
                  'walker' => $walker,
                  ) );
          }
          
?>           
          </ul>
          </div>
        </div>
<?php
        //-- Sidebar Image
        $SideImage = GetPostSidebarImage($post->ID);
        if (!empty($SideImage))
        {
          echo '<div style="width:100%; margin: 0px 0 10px 0;">';
          echo '<img src="' . $SideImage . '" alt="" width="210px" height="auto" />';
          echo '</div>';
        }
                
        echo '<div id="sidebar_image">';
        //-- Get the image, if any, for this page
        GetPostSidebarImage($post->ID);
        echo '</div>';
      }
    ?>                


            </div><!-- end of .widget-wrapper -->
			<?php endif; //end of main-sidebar ?>

        <?php responsive_widgets_end(); // after widgets hook ?>
        </div><!-- end of #widgets -->
<?php        
function GetPostSidebarImage($PageID)
{
  if (!is_numeric($PageID))
    return "";
  
  global $wpdb;

  $q = "SELECT meta_value FROM wp_postmeta WHERE meta_key = 'SideImage' and post_id = " . $PageID;
  $ImageURL = $wpdb->get_var($q);

  return $ImageURL; 
}
?>