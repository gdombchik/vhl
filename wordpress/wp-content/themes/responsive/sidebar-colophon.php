<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Colophon Widget Template
 *
 *
 * @file           sidebar-colophon.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2012 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/sidebar-colophon.php
 * @link           http://codex.wordpress.org/Theme_Development#Widgets_.28sidebar.php.29
 * @since          available since Release 1.0
 */
?>
    <?php
        if (! is_active_sidebar('colophon-widget')
	    )
            return;
    ?>
    <div id="widgets" class="grid col-940">
        <?php responsive_widgets(); // above widgets hook ?>
        
            <?php if (is_active_sidebar('colophon-widget')) : ?>
            
            <?php dynamic_sidebar('colophon-widget'); ?>

            <?php endif; //end of colophon-widget ?>

        <?php responsive_widgets_end(); // after widgets hook ?>
    </div><!-- end of #widgets -->