<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Header Template
 *
 *
 * @file           header.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.2
 * @filesource     wp-content/themes/responsive/header.php
 * @link           http://codex.wordpress.org/Theme_Development#Document_Head_.28header.php.29
 * @since          available since Release 1.0
 */
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<script id='cpf_wp' type='text/javascript'>
   function CleanPrint(postId) {
   	CleanPrintPrintHtml(null,postId);
   }
   function CleanEmail(postId) {
   	CleanPrintSendEmail(null,postId);
   }
   function CleanPDF(postId) {
   	CleanPrintGeneratePdf(null,postId);
   }
</script>
<script id='cpf_loader' type='text/javascript' src='http://cache-02.cleanprint.net/cpf/cleanprint?key=wpdefault&logo=http%3A%2F%2Fcache-02.cleanprint.net%2Fmedia%2Flogos%2FDefault.png'></script>

<?php
  $MetaTagDescription = GetMetaTagDescription($post->ID);
  if (!empty($MetaTagDescription))
  {
    echo '';
  } else {
    //-- Use default description
?>
  

<?php   
  }
  
  $MetaTagKeywords = GetMetaTagKeywords($post->ID);
  if (!empty($MetaTagKeywords))
  {
    echo '<meta name="keywords" content="' . $MetaTagKeywords . '">';
  } else {
    //-- Use default keyword
?>
  <meta name="keywords" content="tumor, cancer treatment, retina, brain, spinal cord, kidney, pancreas, adrenal cancer, tumor, depression, caregiver, carer, pheochromocytoma, paraganglioma, hemangioblastoma, hemangioma, nephroma, adenoma, Angioblastomatosis, Angiomatosis Retinae, Angiophakomatosis retinae et cerebelli, Cerebelloretinal hemangioblastomatosis, Cerebelloretinal hemangioblastomatosis, Hippel Disease; Hippel-Lindau Syndrome, Lindau Disease, Retinocerebellar Angiomatosis, VHL, kindey cancer, HIF, VEGF, ">
  
<?php } 

function GetMetaTagDescription($PostID)
{
  if (!is_numeric($PostID))
    return "";
    
  global $wpdb;
  
  $q = "SELECT meta_value FROM wp_postmeta WHERE meta_key = 'MetaTagDescription' AND post_id = " . $PostID;
  $tag = $wpdb->get_var($q);
  
  return $tag; 
}

function GetMetaTagKeywords($PostID)
{
  if (!is_numeric($PostID))
    return "";
    
  global $wpdb;
  
  $q = "SELECT meta_value FROM wp_postmeta WHERE meta_key = 'MetaTagKeywords' AND post_id = " . $PostID;
  $tag = $wpdb->get_var($q);
  
  return $tag; 
}
?>

<title><?php wp_title('&#124;', true, 'right'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_enqueue_style('responsive-style', get_stylesheet_uri(), false, '1.7.9');?>

<?php wp_head(); ?>

<?php
  global $wpdb;
  $url = get_bloginfo('wpurl');
?>
  <link href="<?=$url?>/includes/video-js.css" rel="stylesheet" type="text/css">
  <!-- video.js must be in the <head> for older IEs to work. -->
  <script src="<?=$url?>/includes/video.js"></script>

  <script>
    _V_.options.flash.swf = "<?=$url?>/includes/video-js.swf";
  </script>     
  
<?php
  if ($post->ID == 8)
  {
    //-- Home page
?>
<!--    <script type="text/javascript" src="<?=get_template_directory_uri()?>/scripts/packed.js"></script>
    <script type="text/javascript" src="<?=get_template_directory_uri()?>/scripts/tinybox.js"></script> -->

<?php 
    //-- Check DB to see if this IP address has visited the site within the last 6 months.
    $UserIP = getRealIpAddr();
    
    $q = "SELECT newsletter_signup_id, date_checked FROM newsletter_signups WHERE ip_address ='" . $UserIP . "' ORDER BY newsletter_signup_id DESC LIMIT 1";
    $row = $wpdb->get_row($q);
    $ShowPopup = false;
    $today = new DateTime("now", new DateTimeZone('America/New_York'));
    
    if (empty($row->newsletter_signup_id))  
    {
      //-- Do not have this IP, so save it
      $UserAgent = $_SERVER['HTTP_USER_AGENT'];
      $q = "INSERT INTO newsletter_signups (ip_address, date_checked, registered, user_agent) VALUES ('$UserIP', '" . $today->format('Y-m-d H:i:s') . "', 1, '$UserAgent')";
      $wpdb->query($q);

      //-- Pop up dialog
      $ShowPopup = true;
    } else {
      //-- See if user was here last time within 6 months
      $chkDate = DateTime::createFromFormat('Y-m-d H:i:s', $row->date_checked);
      $NumMonths = $chkDate->diff($today)->format('%m');
      //echo "Number of months $NumMonths<br />";
      if ($NumMonths >= 6)
      {
        $ShowPopup = true;
        //-- Since 6 or more months since last visit, will display popup and so mark today as the visit date for next time
        $q = "UPDATE newsletter_signups SET date_checked = '" . $today->format('Y-m-d H:i:s') . "' WHERE ip_address = '" . $UserIP . "'";
        $wpdb->query($q);       
      }
    }
    
    if ($ShowPopup) //$UserIP == '71.170.224.233')
    {
?>      
      <style type="text/css">
        /*
        #popupClose - this is referring to the anchor tag inside the popup conatainer.
        We'll absolute position and style it here
        */
        #popupClose{  
          font-size:14px;  
          line-height:20px;  
          position:absolute;
          right:6px;  
          top:4px;  
          font-weight:700; 
          display:block;
          padding:5px;
        }
        
        /*
        #bgPopup - this is referring to the element that will cover the whole page 
        behind the popup and above the rest of the page.
        NOTE: if you are using z-index on the same level in the DOM - 
          #bgPopup z-index needs to have the second highest value (behind #Popup)
        */
        #bgPopup{
          display:none; 
          position:fixed;  
          _position:absolute; /* hack for internet explorer 6*/  
          height:100%;  
          width:100%;  
          top:0;  
          left:0;  
          background:#000000;   
          z-index:1;  
        }  
        
        /*
        #Popup - The popup container
        NOTE: if you are using z-index on the same level in the DOM - 
          #Popup z-index needs to have the highest value.
        */
        #Popup{  
          display:none;  
          position:fixed;  
          _position:absolute; /* hack for internet explorer 6 */ 
/*          background:#88B13F;  
          border:2px solid #cecece; */  
          z-index:2;  
          padding:12px 50px 12px 50px;  
          font-size:16px;
          color:#1e3166;
          background: #ffffff; /* Old browsers */
          background: -moz-linear-gradient(top,  #f7d778 0%, #ffffff 100%); /* FF3.6+ */
          background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7d778), color-stop(100%,#ffffff)); /* Chrome,Safari4+ */
          background: -webkit-linear-gradient(top,  #f7d778 0%,#ffffff 100%); /* Chrome10+,Safari5.1+ */
          background: -o-linear-gradient(top,  #f7d778 0%,#ffffff 100%); /* Opera 11.10+ */
          background: -ms-linear-gradient(top,  #f7d778 0%,#ffffff 100%); /* IE10+ */
          background: linear-gradient(to bottom,  #f7d778 0%,#ffffff 100%); /* W3C */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7d778', endColorstr='#ffffff',GradientType=0 ); /* IE6-9 */
          -webkit-box-shadow:  1px 1px 10px 2px #CACACA;
            box-shadow:  1px 1px 10px 2px #CACACA;          
        } 
        
      </style>
      <script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready( function($){
             
            $("#bgPopup").data("state",0);
               
        var winw = $(window).width();
        var winh = $(window).height();
        var popw = $('#Popup').width();
        var poph = $('#Popup').height();
        
        winw = winw - 300;
        if (winw < 0)
        {
          winw = 0;
        }
            
        $("#Popup").css({
              "position" : "absolute",
              "top" : winh/4-poph/2,
              "left" : winw/2-popw/2
        });

        //IE6
        $("#bgPopup").css({
          "height": winh  
        });
        
        //loads popup only if it is disabled
        if($("#bgPopup").data("state")==0){
          $("#bgPopup").css({
            "opacity": "0.7"
          });
          $("#bgPopup").fadeIn("medium");
          $("#Popup").fadeIn("medium");
          $("#bgPopup").data("state",1);
        }  
             
           $("#popupClose").click(function(){  
                //disablePopup();
          if ($("#bgPopup").data("state")==1){
            $("#bgPopup").fadeOut("medium");
            $("#Popup").fadeOut("medium");
            $("#bgPopup").data("state",0);
          }
                  
           });  
           $(document).keypress(function(e){  
                if(e.keyCode==27) {  
                    //disablePopup();
            if ($("#bgPopup").data("state")==1){
              $("#bgPopup").fadeOut("medium");
              $("#Popup").fadeOut("medium");
              $("#bgPopup").data("state",0);
            }
                }  
            });  
        });  
            
        $(window).resize(function() {  
        var winw = $(window).width();
        var winh = $(window).height();
        var popw = $('#Popup').width();
        var poph = $('#Popup').height();

        $("#Popup").css({
              "position" : "absolute",
              "top" : winh/2-poph/2,
              "left" : winw/2-popw/2
        });

        //IE6
        $("#bgPopup").css({
          "height": winh  
        });
        });       
      </script>
<?php     
    }
  }
?>  

<!--this is temporary hardcoded here for the ereminder.  Probably should be based on the loaded page.  Same as page.php.-->
<!--<link href="<?=$url?>/wp-content/plugins/email_reminder_vhl/assets/css/style.css" rel="stylesheet" type="text/css">
<script src="<?=$url?>/wp-content/plugins/email_reminder_vhl/assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="<?=$url?>/wp-content/plugins/email_reminder_vhl/assets/js/jquery.ui.timepicker.addon.js"></script>
<script src="<?=$url?>/wp-content/plugins/email_reminder_vhl/assets/js/script.js"></script>-->
<!------------------------------------------------------------------------------------------------------------------------>



</head>

<body <?php body_class(); ?>>
                 
<?php responsive_container(); // before container hook ?>
<div id="container" class="hfeed">
         
    <?php responsive_header(); // before header hook ?>
    <div id="header">
    
        <?php if (has_nav_menu('top-menu', 'responsive')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'fallback_cb'	  =>  false,
					'menu_class'      => 'top-menu',
					'theme_location'  => 'top-menu')
					); 
				?>
        <?php } ?>
        
    <?php responsive_in_header(); // header hook ?>
   
	<?php if ( get_header_image() != '' ) : ?>
               
        <div id="logo">
            <a href="<?php echo home_url('/'); ?>"><img src="<?php header_image(); ?>" width="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> width;} else { echo HEADER_IMAGE_WIDTH;} ?>" height="<?php if(function_exists('get_custom_header')) { echo get_custom_header() -> height;} else { echo HEADER_IMAGE_HEIGHT;} ?>" alt="<?php bloginfo('name'); ?>" /></a>
        </div><!-- end of #logo -->
        
    <?php endif; // header image was removed ?>

    <?php if ( !get_header_image() ) : ?>
                
        <div id="logo">
            <span class="site-name"><a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></span>
            <span class="site-description"><?php bloginfo('description'); ?></span>
        </div><!-- end of #logo -->  

    <?php endif; // header image was removed (again) ?>
    
    <?php get_sidebar('top'); ?>
			    
      <div id="above_menu">
        <div style="float:left; width:15%; padding-left:2%;">
          <?php get_search_form(); ?>
        </div>
        <div id="social_media">
          <div style="float:left; padding:0 5px 0 0;"><a href="http://www.inspire.com/groups/vhl-family-alliance/"><img src="<?=$url?>/images/logos/inspire.png" alt="" /></a></div>
          <div style="float:left; padding:0 5px 0 0;"><a href="http://www.youtube.com/vhlfa"><img src="<?=$url?>/images/logos/youtube.png" alt="" /></a></div>
          <div style="float:left; padding:0 5px 0 0;"><a href="https://www.facebook.com/VHLFA"><img src="<?=$url?>/images/logos/facebook-logo.png" alt="" /></a></div>
          <div style="float:left; padding:0 5px 0 0;"><a href="https://twitter.com/vhlfa"><img src="<?=$url?>/images/logos/twitter.png" alt="" /></a></div>
		  <div style="float:left; padding:0 5px 0 0;"><a href="http://www.linkedin.com/groups/VHL-Family-Alliance-4652845?trk=myg_ugrp_ovr"><img src="<?=$url?>/library/LinkedIn.png" width="80px" height="23px" alt="" /></a></div>
<div style="float:left; padding:0 5px 0 0;"><a href="http://www.iGive.com/vhl"><img src="<?=$url?>/library/logo_igive1.png" width="50px" height="23px" alt="" /></a></div>
          <div style="float:left; padding:0 5px 0 0;"><img src="<?=$url?>/images/plus.jpg" alt="" /></div>         
          <div style="float:left; padding:0 5px 0 30px;">
             <a href='.' onClick='CleanPrint(); return false' title='Print page'><img src="<?=$url?>/images/btn_print.png" alt="Print VHL page" width="55px" height="18px" title="Print page"  /></a>
          </div>          
          <div style="float:left; padding:0 4px 0 0;">
             <a href='.' onClick='CleanEmail(); return false' title='Email page'><img src="<?=$url?>/images/btn_email.png" alt="Email VHL page" width="55px" height="18px" title="Email page"  /></a>
          </div>          
          <div style="float:left; padding:0 4px 0 0;">
             <a href='.' onClick='CleanPDF();   return false' title='PDF page'><img src="<?=$url?>/images/btn_pdf.png" alt="Create PDF of VHL page" width="55px" height="18px" title="Create PDF of page"  /></a>
          </div>          
        </div>
        <div id="donate">
          <a href="https://donatenow.networkforgood.org/1411829?code=orange"><img src="<?=$url?>/images/donate.png" alt="" /></a>
        </div>
      </div>
      <div style="clear:both"></div>
      
        <?php wp_nav_menu(array(
				    'container'       => '',
				    'depth' => 1,
					  'theme_location'  => 'header-menu')
					); 
				?>
                
        <?php if (has_nav_menu('sub-header-menu', 'responsive')) { ?>
	      <?php wp_nav_menu(array(
				   'container'       => '',
					 'menu_class'      => 'sub-header-menu',
					 'theme_location'  => 'sub-header-menu')
					); 
				?>
        <?php } ?>
 
    </div><!-- end of #header -->
    <?php responsive_header_end(); // after header hook ?>
    
	<?php responsive_wrapper(); // before wrapper ?>
    <div id="wrapper" class="clearfix">
    <?php responsive_in_wrapper(); // wrapper hook ?>
    
<?php
    if ($ShowPopup)
    {
?>
      <div id="Popup">
        <div style="padding-bottom:10px">
          <a id="popupClose" style="cursor:pointer">[close]</a>
        </div>
        <br />
        <div style="font-weight:bold;">Stay informed with our E-News?</div>
        <div style="text-align:center; padding:15px;">
          <a href="https://app.e2ma.net/app/view:Join/signupId:1416736/acctId:1407876"><img src="http://www.vhl.org/wordpress/images/btn_enews.png" alt="" /></a>
        </div>          
      </div> 
      <div id="bgPopup"></div> 
<?php     
    }
?>    