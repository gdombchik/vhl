<?php

/**
 * Main Slideshow Class
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESL_Slideshow {

    /**
     * Class instance
     *
     * @since 2.0
     */
    private static $instance;

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
     * Loads slideshow styles
     *
     * @since 2.0
     */
    public static function enqueue_styles() {

        /** Load styling */
        wp_enqueue_style( 'esl-slideshow' );
        do_action( 'easingsliderlite_enqueue_slideshow_styles' );

    }

    /**
     * Loads slideshow scripts
     *
     * @since 2.0
     */
    public static function enqueue_scripts() {

        /** Load scripts */
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'esl-slideshow' );
        do_action( 'easingsliderlite_enqueue_slideshow_scripts' );

    }

    /**
     * Prints the slideshow custom styling
     *
     * @since 2.1
     */
    public static function print_custom_styles() {

        /** Get the customizations & defaults */
        $customizations = $c = json_decode( get_option( 'easingsliderlite_customizations' ) );
        $defaults = EasingSliderLite::get_instance()->customization_defaults();

        /** Bail if there are no customizations */
        if ( $defaults == $c )
            return;

        /** Print the styling. Long selectors here ensure styles take preference over CSS files. */
        ob_start();
        ?>
        <style type="text/css">
            .easingsliderlite {
                <?php if ( $defaults->border->color != $c->border->color ) echo "border-color: {$c->border->color};"; ?>
                <?php if ( $defaults->border->width != $c->border->width ) echo "border-width: {$c->border->width}px; border-style: solid;"; ?>
                <?php if ( $defaults->border->radius != $c->border->radius ) echo "-webkit-border-radius: {$c->border->radius}px; -moz-border-radius: {$c->border->radius}px; border-radius: {$c->border->radius}px;"; ?>
            }
            .easingsliderlite .easingsliderlite-arrows.easingsliderlite-next,
            .easingsliderlite .easingsliderlite-arrows.easingsliderlite-prev {
                <?php if ( $defaults->arrows->width != $c->arrows->width ) echo "width: {$c->arrows->width}px;"; ?>
                <?php if ( $defaults->arrows->height != $c->arrows->height ) { $margin_top = ( $c->arrows->height / 2 ); echo "height: {$c->arrows->height}px; margin-top: -{$margin_top}px;"; } ?>
            }
            .easingsliderlite .easingsliderlite-arrows.easingsliderlite-next {
                <?php if ( $defaults->arrows->next != $c->arrows->next ) echo "background-image: url({$c->arrows->next});"; ?>
            }
            .easingsliderlite .easingsliderlite-arrows.easingsliderlite-prev {
                <?php if ( $defaults->arrows->prev != $c->arrows->prev ) echo "background-image: url({$c->arrows->prev});"; ?>
            }
            .easingsliderlite .easingsliderlite-pagination .easingsliderlite-icon {
                <?php if ( $defaults->pagination->width != $c->pagination->width ) echo "width: {$c->pagination->width}px;"; ?>
                <?php if ( $defaults->pagination->height != $c->pagination->height ) echo "height: {$c->pagination->height}px;"; ?>
            }
            .easingsliderlite .easingsliderlite-pagination .easingsliderlite-icon.inactive {
                <?php if ( $defaults->pagination->inactive != $c->pagination->inactive ) echo "background-image: url({$c->pagination->inactive});"; ?>
            }
            .easingsliderlite .easingsliderlite-pagination .easingsliderlite-icon.active {
                <?php if ( $defaults->pagination->active != $c->pagination->active ) echo "background-image: url({$c->pagination->active});"; ?>
            }
        </style>
        <?php
        print preg_replace( '/\s+/', ' ', ob_get_clean() );

    }

    /**
     * Returns the users current browser
     *
     * @since 2.1
     */
    public function detect_browser() {
        $browser = esc_attr( $_SERVER[ 'HTTP_USER_AGENT' ] );
        if ( preg_match( '/MSIE 7/i', $browser ) )
            return "is-ie7";
        elseif ( preg_match( '/MSIE 8/i', $browser ) )
            return "is-ie8";
        elseif ( preg_match( '/MSIE 9/i', $browser ) )
            return "is-ie9";
        elseif ( preg_match( '/Firefox/i', $browser ) )
            return "is-firefox";
        elseif ( preg_match( '/Safari/i', $browser ) )
            return "is-safari";
        elseif ( preg_match( '/Chrome/i', $browser ) )
            return "is-chrome";
        elseif ( preg_match( '/Flock/i', $browser ) )
            return "is-flock";
        elseif ( preg_match( '/Opera/i', $browser ) )
            return "is-opera";
        elseif ( preg_match( '/Netscape/i', $browser ) )
            return "is-netscape";
        return;
    }

    /**
     * Displays a slideshow
     *
     * @since 2.0
     */
    public function display_slideshow() {

        /** Display the slideshow */
        ob_start();
        require dirname( EasingSliderLite::get_file() ) . DIRECTORY_SEPARATOR .'templates'. DIRECTORY_SEPARATOR .'slideshow.php';
        return ob_get_clean();

    }

}