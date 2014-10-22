<?php

$url = get_bloginfo('wpurl');

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Pages Template
 *
 */
?>
<?php get_header(); ?>

  <?php if ($post->ID == 8) 
  		{
          //-- Display custom page for home page
  ?>
        <div id="content" class="grid col-620" style="margin-bottom: 0; padding-bottom: 0">

		    <div id="widgets" class="home-widgets" style="margin-top:20px;">
		        <div class="grid col-300" style="list-style: none">
		        <?php responsive_widgets(); // above widgets hook ?>
		            
		            <?php if (!dynamic_sidebar('main-mission')) : ?>
		            <div class="widget-wrapper">
		            
		                <div class="widget-title-home"><h3><?php _e('Main Mission', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		            
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-1 ?>
		
		        <?php responsive_widgets_end(); // responsive after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300" style="list-style: none;">
		        <?php responsive_widgets(); // responsive above widgets hook ?>
		            
		      <?php if (!dynamic_sidebar('faces_of_vhl')) : ?>
		            <div class="widget-container">
		            
		                <div class="widget-title-home"><h3><?php _e('Faces of VHL', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		            <?php if (function_exists('easing_slider')){ easing_slider(); }; ?>
		            </div><br/><br/><a href="http://www.vhl.org/wordpress/patients-caregivers/getting-support/monthly-telephone-discussions/"><strong><span style="color: #474753;"><span style="font-size: 16px;"><left>NEW PROGRAM:</left><br>
<span style="color: #737ba3;">Monthly Telephone Discussions</strong></span></span></a><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-2 ?>
		            
		        <?php responsive_widgets_end(); // after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300 fit" style="list-style:none">
		        <?php responsive_widgets(); // above widgets hook ?>
		            
		      <?php if (!dynamic_sidebar('testimonials')) : ?>
		            <div class="widget-wrapper">
		            
		                <div class="widget-title-home"><h3><?php _e('Testimonials', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-3 ?>
		            <div style="float:right; padding:9px 45px 0 0; ">
		              <!--<a href="https://app.e2ma.net/app/view:Join/signupId:1416736/acctId:1407876" style="border:0"><img style="border:0" src="<?=$url?>/images/btn_sign_up_enews.jpg" alt="Sign up for eNews" title="Sign up for eNews" /></a>-->
		              <a href="/e-newsletter-signup/" style="border:0"><img style="border:0" src="<?=$url?>/images/btn_sign_up_enews.jpg" alt="Sign up for eNews" title="Sign up for eNews" /></a>
		            </div>                    
		        <?php responsive_widgets_end(); // after widgets hook ?>
		        </div><!-- end of .col-300 fit -->
		      <div style="clear:both"></div>
        
		      <?php //-- Row #2 --- ?>
		      <div class="grid col-220" style="list-style: none; width:30%">
		        <?php responsive_widgets(); // above widgets hook ?>
		            
		            <?php if (!dynamic_sidebar('main_slider')) : ?>
		            <div class="widget-wrapper">            
		                <div class="widget-title-home"><h3><?php _e('Main Slider Title', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>            
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-1 ?>
		
		        <?php responsive_widgets_end(); // responsive after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300" style="list-style: none; width:32%">
		        <?php responsive_widgets(); // responsive above widgets hook ?>		            
		      <?php if (!dynamic_sidebar('main_events')) : ?>
		            <div class="widget-container" >		            
		                <div class="widget-title-home"><h3><?php _e('Main Events', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		                <div style="padding:0 25px 0 25px">
		            <?php if (function_exists('easing_slider')){ easing_slider(); }; ?>
		            	</div>
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-2 ?>
		            
		        <?php responsive_widgets_end(); // after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300 fit" style="list-style:none; width:31%">
		        <?php responsive_widgets(); // above widgets hook ?>		            
		      <?php if (!dynamic_sidebar('main_donate')) : ?>
		            <div class="widget-wrapper">
		            
		                <div class="widget-title-home"><h3><?php _e('Main Donate', 'vhl'); ?></h3></div>
		                <div class="textwidget"> Canadian VHL Alliance</div>
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of main_donate ?>
		        <?php responsive_widgets_end(); // after widgets hook ?>
		        
		        <div style="margin-left:40px; margin-bottom:0;">
		            <?php
		              //-- Get the url from the DB, if not set, will go with the default one
		             $MP4VideoURL = $url . '/videos/vhlFAv-2Min.mp4';
		              $OGVVideoURL = $url . '/videos/vhlFAv-2Min.ogv';
		              $WebMVideoURL = $url . '/vhlFAv-2Min.webm';
		              $VideoPoster = $url . '/videos/poster.png';
		            ?>
		            <video id="video_1" class="video-js vjs-default-skin" controls preload="none" width="250" height="192" 
		              poster="<?=$VideoPoster?>" data-setup="{}">
		              <source src="<?=$MP4VideoURL?>" type='video/mp4' />
		              <source src="<?=$OGVVideoURL?>" type='video/ogg' />
		              <source src="<?=$WebMVideoURL?>" type='video/webm' />
		            </video>
		          </div>
		      </div><!-- end of .col-300 fit -->
		    
		    <div style="clear:both"></div>
              
		    <?php //-- Row #2 --- ?>
		    <div class="grid col-300" style="list-style: none">
		        <?php responsive_widgets(); // above widgets hook ?>
		            
		            <?php if (!dynamic_sidebar('home-widget-1')) : ?>
		            <div class="widget-wrapper">            
		                <div class="widget-title-home"><h3><?php _e('Home Widget 1', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>            
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-1 ?>
		
		        <?php responsive_widgets_end(); // responsive after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300" style="list-style: none;">
		        <?php responsive_widgets(); // responsive above widgets hook ?>
		            
		      <?php if (!dynamic_sidebar('home-widget-2')) : ?>
		            <div class="widget-container">
		                <div class="widget-title-home"><h3><?php _e('Home Widget 2', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of home-widget-2 ?>
		            
		        <?php responsive_widgets_end(); // after widgets hook ?>
		        </div><!-- end of .col-300 -->
		
		        <div class="grid col-300 fit" style="list-style:none">
		        <?php responsive_widgets(); // above widgets hook ?>
		            
		      <?php if (!dynamic_sidebar('home-widget-3')) : ?>
		            <div class="widget-wrapper">
		            
		                <div class="widget-title-home"><h3><?php _e('Home Widget 3', 'vhl'); ?></h3></div>
		                <div class="textwidget"></div>
		            </div><!-- end of .widget-wrapper -->
		      <?php endif; //end of main_donate ?>
		        <?php responsive_widgets_end(); // after widgets hook ?>
		      </div><!-- end of .col-300 fit -->
		    </div><!-- end of #widgets -->
		    <div style="clear:both"></div>              
  <?php
		}
    else {
?>
    <div id="content" class="grid col-620">
<?php      
      //-- Display normal page content
      //-- This allows user to enter an image for a particular page
      $TopImage = GetPostTopImage($post->ID);
      if (!empty($TopImage))
      {
        echo '<div style="width:100%; margin:10px 0 10px 0;">';
        echo '<img src="' . $TopImage . '" alt="" width="700px" height="auto" />';
        echo '</div>';
      }                 
  ?>
          
        <?php if (have_posts()) : ?>
		    <?php while (have_posts()) : the_post(); ?>
        
        <?php $options = get_option('responsive_theme_options'); ?>
		    <?php if ($options['breadcrumb'] == 0): ?>
		          <?php echo responsive_breadcrumb_lists(); ?>
        <?php endif; ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="post-title"><?php the_title(); ?></h1>
 
 				<?php /*
                <?php if ( comments_open() ) : ?>               
                <div class="post-meta">
                <?php responsive_post_meta_data(); ?>
                
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments &darr;', 'responsive'), __('1 Comment &darr;', 'responsive'), __('% Comments &darr;', 'responsive')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                <?php endif; ?> 
                */
               ?>
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'vhl')); ?>
<?php                    
				if ($post->ID == 5686)
				{
					//-- Handbook download form
					require_once("includes/new-hbcopy2011.php");
					form_main();
				}
        else if ($post->ID == 130)
        {
          //-- VHL Store
          include("store.php");
        }
        else if ($post->ID == 9036)
        {
          require_once("includes/enews_signup_form.php");
          echo '<div>';
        }
?>				                    
                    
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php /*
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'vhl') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'vhl') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>
				 * 
				 */             
            	?>

 			<?php
			if ($post->ID == 11230) //email reminder
        	{

        		//include("wp-content/plugins/email_reminder_vhl/hello.php");

        		//echo 'in page.php';

				require_once("wp-content/plugins/email_reminder_vhl/pogidude-reminder.php");
        		require_once("wp-content/plugins/email_reminder_vhl/views/ereminder-page.php");
        		//add_query_arg( 'pder-action', 'add', 'wp-content/plugins/email_reminder_vhl/views/ereminder-page.php' );

 			}
 			?>

            <div class="post-edit"><?php edit_post_link(__('Edit', 'vhl')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
            
            <?php //comments_template( '', true ); ?>
            
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'vhl' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'vhl' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'vhl'); ?></h1>
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'vhl'); ?></p>
        <h6><?php _e( 'You can return', 'vhl' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'vhl' ); ?>"><?php _e( '&larr; Home', 'vhl' ); ?></a> <?php _e( 'or search for the page you were looking for', 'vhl' ); ?></h6>
        <?php get_search_form(); ?>

<?php endif; ?>
        </div><!-- end of #content -->
  
<?php 
    } //-- End of if ($post->ID == 8)
?>

<?php 
//-- No sidebar images on these pages
if ($post->ID == 8 || $post->ID == 224 || $post->ID == 9036)
{
  echo '<div style="padding-right:15px;">';
  GetFooter(31);
  echo '</div>';
}
else
{
  get_sidebar(); 
  GetFooter(28);
}
?>

<?php
function GetFooter($right_width) {
	global $url;
?>  
<div style="clear:both"></div>
<div style="width:100%; margin:0 0 10px 10px;">
  <div style="padding:10px 0 0 0; width:5%; float:left;">
    <a href="http://greatnonprofits.org/reviews/vhl-alliance/?badge=1" target="_blank"><img src="<?=$url?>/images/logos/great-nonprofit-sm.jpg" alt="great non-profile" width="47px" height="61px" /></a>
    <div style="padding:15px 0;">
      <a target="_blank" onclick="window.open('//verify.authorize.net/anetseal/?pid=a2061785-fb49-40be-b5ff-c99f429bbf56&rurl=http%3A//vhl.org/','AuthorizeNetVerification','width=600,height=430,dependent=yes,resizable=yes,scrollbars=yes,menubar=no,toolbar=no,status=no,directories=no,location=yes'); return false;" onmouseout="window.status=''; return true;" onmouseover="window.status='http://www.authorize.net/'; return true;" href="//verify.authorize.net/anetseal/?pid=a2061785-fb49-40be-b5ff-c99f429bbf56&rurl=http%3A//vhl.org/">
        <img src="<?=$url?>/images/logos/authorize-net-sm.png" alt="verified vhl authorize.net" width="50px" height="40px" />
      </a>            
    </div>    
  </div>
  
  <div style="width:95%; float:left;">
    <div style="float:left; padding-left:10px">
      <a target="_blank" href="http://www.vhl.org/belgie"><img src="<?=$url?>/images/flags/belgium.png" alt="Belgium" title="Belgium" width="15" height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.abvhl.com"><img src="<?=$url?>/images/flags/brazil.png" alt="Brazil" title="Brazil" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl.org/wordpress/patients-caregivers/getting-support/support-groups-international/canadian-vhl-alliance/"><img src="<?=$url?>/images/flags/ca.png" alt="Canada" title="Canada"  width="20"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.cn-vhl.org"><img src="<?=$url?>/images/flags/china.png" alt="China" title="China" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl-europa.org/hr/"><img src="<?=$url?>/images/flags/croatia.png" alt="Croatia" title="Croatia" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl.dk"><img src="<?=$url?>/images/flags/denmark.png" alt="Denmark" title="Denmark" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://vhlfrance.org/"><img src="<?=$url?>/images/flags/france.png" alt="France" title="France" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.hippel-lindau.de/"><img src="<?=$url?>/images/flags/germany.png" alt="Germany" title="Germany" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.hlrccinfo.org/vhl_uk_ireland/"><img src="<?=$url?>/images/flags/britain.png" alt="Great Britain" title="Great Britain" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://vhlgr.blogspot.gr"><img src="<?=$url?>/images/flags/greece.jpg" alt="Greece" title="Greece" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl-europa.org/h/index.html"><img src="<?=$url?>/images/flags/hungary.png" alt="Hungary" title="Hungary" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.hlrccinfo.org/vhl_uk_ireland/"><img src="<?=$url?>/images/flags/ireland.png" alt="Ireland" title="Ireland" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl.it/"><img src="<?=$url?>/images/flags/italy.png" alt="Italy" title="Italy"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.vhl-japan.org/"><img src="<?=$url?>/images/flags/japan.png" alt="Japan" title="Japan" width="15"  height="8"  /></a> &nbsp;           
      
      <a target="_blank" href="http://www.genetika.lt/"><img src="<?=$url?>/images/flags/lithuania.png" alt="Lithuania" title="Lithuania" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.vhl.nfk.nl/"><img src="<?=$url?>/images/flags/netherlands.png" alt="Netherlands" title="Netherlands"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.vhl.dk"><img src="<?=$url?>/images/flags/norway.png" alt="Norway" title="Norway"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.vhl-europa.org/PL/index.html"><img src="<?=$url?>/images/flags/poland.png" alt="Poland" title="Poland"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.dcc.fc.up.pt/~nam/VHL/"><img src="<?=$url?>/images/flags/portugal.png" alt="Portugal" title="Portugal"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.alianzavhl.org/"><img src="<?=$url?>/images/flags/spain.png" alt="Spain" title="Spain" width="15"  height="8"  /></a> &nbsp;
      <a target="_blank" href="http://www.switzerland.vhl-europa.org/"><img src="<?=$url?>/images/flags/switzerland.png" alt="Switzerland" title="Switzerland"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://www.vhl-europa.org/sw/"><img src="<?=$url?>/images/flags/sweden.png" alt="Sweden" title="Sweden"  width="15"  height="8" /></a> &nbsp;
      <a target="_blank" href="http://vhl-europa.org/tr/index.html"><img src="<?=$url?>/images/flags/turkey.png" alt="Turkey" title="Turkey"  width="15"  height="8" /></a> &nbsp;
  
      <div style="width:100%; text-align: center">
        <div style="float:left; width:80%; ">
          <div style="text-align: center; font-size:8pt; line-height: 1.4em; font-weight: bold; padding:15px 0 0 0;">&copy;<?php echo date("Y") ?> by The VHL Alliance.  All Rights Reserved.</div>
          <div style="text-align: center; font-size:8pt; line-height: 1.2em; padding:0; margin:0;">The VHL Alliance is a tax exempt 503(c)3 corporation</div>                          
          <div style="padding-top:10px; font-size:8pt;">
            <?php
              echo "Last Modified: "; 
              the_modified_date(); 
            ?>
          </div>                
        </div>
        <div style="float:left; width:19%; padding:25px 0 0 0; float:left;">
          <a href="<?=$url?>/patients-caregivers/get-involved/fundraiser/vhl-store/"><img src="<?=$url?>/images/btn_vhl_store.png" alt="" /></a>
          <br /><br />
          <a href="<?=$url?>/about-the-vhlfa/privacy-policy" ><img src="<?=$url?>/images/privacy.jpg" alt="" /></a></br /></br />
      </div>                
    </div>
    <div style="float:left; width:<?php echo $right_width ?>%">
      <a href="https://www.healthonnet.org/HONcode/Conduct.html?HONConduct582397" onclick="window.open(this.href); return false;" > <img src="http://www.honcode.ch/HONcode/Seal/HONConduct582397_s1.gif" style="border:0px; width: 40px; height: 60px; float: left; margin: 5px;" title="This website is certified by Health On the Net Foundation. Click to verify." alt="This website is certified by Health On the Net Foundation. </a>Click to verify." /> <small></a>This site complies with the <a href="http://www.healthonnet.org/HONcode/Conduct.html" onclick="window.open(this.href); return false;"> HONcode standard for trustworthy health </a>information: <a href="https://www.healthonnet.org/HONcode/Conduct.html?HONConduct582397" onclick="window.open(this.href); return false;">verify here.</small><br /></a>

    </div>    
      <div style="margin-top:8px">
        <div style="float:left">                
          <a href="http://www.independentcharities.org/"><img src="<?=$url?>/images/logos/seal.png" alt=""/></a>                    
        </div>
        <div style="float:left; padding-left:8px;">
          <a href="http://www.bbb.org/charity-reviews/national/cancer/vhl-alliance-in-boston-ma-1043"><img src="<?=$url?>/images/logos/bbb.png" alt=""/></a>
        </div>
        <div style="float:left; padding-left:8px;">
          <a href="http://www.rarediseases.org/"><img src="<?=$url?>/images/logos/mem_nord.jpg" alt=""/></a>
        </div>
<div style="float:left; padding-left:8px;"><head><script language="JavaScript" src="https://seal.networksolutions.com/siteseal/javascript/siteseal.js" type="text/javascript"></script></head>
<body><!--
SiteSeal Html Builder Code:
Shows the logo at URL https://seal.networksolutions.com/images/basicsqgreen.gif
Logo type is  ("NETSB")
//-->
<script language="JavaScript" type="text/javascript"> SiteSeal("https://seal.networksolutions.com/images/basicsqgreen.gif", "NETSB", "none");</script></body?

             </div> 
  </div>
  <div style="clear:both"></div>  
</div>

<?php } ?>

<?php get_footer(); ?>
