<?php

    /** Get the slideshow */
    $slideshow = $s = EasingSliderLite::get_instance()->validate( get_option( 'easingsliderlite_slideshow' ) );

    /** Get customizations */
    $customizations = $c = json_decode( get_option( 'easingsliderlite_customizations' ) );

    /** Bail if we failed to retrieve the slideshow */
    if ( $s === false ) {
        if ( current_user_can( 'easingsliderlite_edit_slideshow' ) )
            _e( '<p style="background-color: #ffebe8; border: 1px solid #c00; border-radius: 4px; padding: 8px !important;">The slideshow does not appear to exist. Oh dear! Please try contacting support.</p>', 'easingsliderlite' );
        return;
    }

    /** Bail if there are no slides to display */
    if ( count( $slideshow->slides ) == 0 ) {
        if ( current_user_can( 'easingsliderlite_edit_slideshow' ) )
            _e( '<p style="background-color: #ffebe8; border: 1px solid #c00; border-radius: 4px; padding: 8px !important;">This slideshow contains no slides. Uh oh!', 'easingsliderlite' );
        return;
    }

    /** Get plugin settings */
    $settings = get_option( 'easingsliderlite_settings' );

    /** Load slideshow scripts and styles in foooter (if set to do so) */
    if ( isset( $settings['load_scripts'] ) && $settings['load_scripts'] == 'footer' )
        add_action( 'wp_footer', array( 'ESL_Slideshow', 'enqueue_scripts' ) );
    if ( isset( $settings['load_styles'] ) && $settings['load_styles'] == 'footer' ) {
        add_action( 'wp_footer', array( 'ESL_Slideshow', 'enqueue_styles' ) );
        add_action( 'wp_footer', array( 'ESL_Slideshow', 'print_custom_styles') );
    }

    /** Inline slideshow styles */
    if ( $s->dimensions->responsive )
        $slideshow_styles = "max-width: {$s->dimensions->width}px; max-height: {$s->dimensions->height}px";
    else
        $slideshow_styles = "width: {$s->dimensions->width}px; height: {$s->dimensions->height}px";
    $slideshow_styles = apply_filters( 'easingsliderlite_slideshow_styles', $slideshow_styles, $s );
    $slideshow_options = json_encode(
        array(
            'dimensions' => $s->dimensions,
            'transitions' => $s->transitions,
            'navigation' => $s->navigation,
            'playback' => $s->playback
        )
    );

    /** Dynamically calculcated viewport height */
    $viewport_height = ( 100 * ( $s->dimensions->height / $s->dimensions->width ) );
    $viewport_styles = "padding-top: {$viewport_height}% !important;";
    $viewport_styles = apply_filters( 'easingsliderlite_viewport_styles', $viewport_styles, $s );

    /** Slide container styles */
    $container_width = ( $s->transitions->effect == 'slide' ) ? ( 100 * ( count( $s->slides )+2 ) ) : '100';
    $container_styles = "display: none; width: {$container_width}%;";
    $container_styles = apply_filters( 'easingsliderlite_container_styles', $container_styles, $s );

    /** Add viewport height when using 'fade' transition */
    if ( $s->transitions->effect == 'fade' )
        $container_styles .= " padding-top: {$viewport_height}% !important;";

?>
<div class="easingsliderlite <?php echo ESL_Slideshow::detect_browser(); ?> use-<?php echo $s->transitions->effect; ?>" data-options="<?php echo esc_attr( $slideshow_options ); ?>" style="<?php echo esc_attr( $slideshow_styles ); ?>">
    <div class="easingsliderlite-viewport" style="<?php echo esc_attr( $viewport_styles ); ?>">
        <div class="easingsliderlite-slides-container" style="<?php echo esc_attr( $container_styles ); ?>">
            <?php
                /** Randomize the slides if set to do so */
                if ( $s->general->randomize )
                    shuffle( $s->slides );
            ?>
            <?php foreach ( $s->slides as $index => $slide ) : ?>
            <?php

                /** Get slide styles */
                $slide_styles = $slideshow_styles;
                if ( $s->transitions->effect == 'fade' && $index > 0 )
                    $slide_styles .= " opacity: 0; display: none;";

                /** Apply filter for custom styles */
                $slide_styles = apply_filters( 'easingsliderlite_slide_styles', $slide_styles, $slide, $s );

                /** Image array */
                $image = array( 'url' => $slide->url, 'width' => $s->dimensions->width, 'height' => $s->dimensions->height );

                /** Resize the image (if enabled) */
                if ( $settings['resizing'] ) {
                    $resized_image = ESL_Resize::resize( $slide->url, $s->dimensions->width, $s->dimensions->height, true, false );
                    if ( !is_wp_error( $resized_image ) )
                        $image = $resized_image;
                }

            ?>
                <div class="easingsliderlite-slide" style="<?php echo $slide_styles; ?>">
                    <?php if ( !empty( $slide->link ) ) { ?><a href="<?php echo esc_attr( $slide->link ); ?>" target="<?php echo esc_attr( $slide->linkTarget ); ?>"><?php } ?>
                        <img src="<?php echo esc_attr( $image['url'] ); ?>" class="easingsliderlite-image" alt="<?php echo esc_attr( $slide->alt ); ?>" title="<?php echo esc_attr( $slide->title ); ?>" />
                    <?php if ( !empty( $slide->link ) ) { ?></a><?php } ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="easingsliderlite-preload"></div>

    <?php if ( $s->navigation->arrows && !has_action( 'easingsliderlite_arrows' ) ) : ?>
        <div class="easingsliderlite-arrows easingsliderlite-next <?php echo esc_attr( $s->navigation->arrows_position ); ?>"></div>
        <div class="easingsliderlite-arrows easingsliderlite-prev <?php echo esc_attr( $s->navigation->arrows_position ); ?>"></div>
    <?php else : do_action( 'easingsliderlite_arrows', $s, $c ); endif; ?>

    <?php if ( $s->navigation->pagination && !has_action( 'easingsliderlite_pagination' ) ) : ?>
        <div class="easingsliderlite-pagination <?php echo esc_attr( $s->navigation->pagination_position ) .' '. esc_attr( $s->navigation->pagination_location ); ?>">
            <?php foreach ( $s->slides as $index => $slide ) : ?>
                <div class="easingsliderlite-icon inactive"></div>
            <?php endforeach; ?>
        </div>
    <?php else : do_action( 'easingsliderlite_pagination', $s, $c ); endif; ?>

    <?php /** Edit slideshow link, don't remove this! Won't show if user isn't logged in or doesn't have permission to edit slideshows */ ?>
    <?php if ( current_user_can( 'easingsliderlite_edit_slideshow' ) && apply_filters( 'easingsliderlite_edit_slideshow_icon', __return_true() ) ) : ?>
        <a href="<?php echo admin_url( "admin.php?page=easingsliderlite_edit_slideshow" ); ?>" style="position: absolute; top: -15px; left: -15px; z-index: 50;">
            <img src="<?php echo plugins_url( dirname( plugin_basename( EasingSliderLite::get_file() ) ) ) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'edit_icon.png'; ?>" style="box-shadow: none; border-radius: none;" />
        </a>
    <?php endif; ?>
</div>

<?php
    /** Display the slideshow shadow */
    if ( !apply_filters( 'easingsliderlite_disable_shadow', __return_false() ) ) :

        /** Get the inline styling */
        $styles = ( $c->shadow->enable ) ? 'display: block; ' : 'display: none; ';
        $styles .= ( $s->dimensions->responsive ) ? "max-width: {$s->dimensions->width}px; " : "width: {$s->dimensions->width}px; ";
        $styles .= ( $c->border->width !== 0 ) ? "margin-left: {$c->border->width}px;" : '';

        /** Print the shadow */
        if ( !has_action( 'easingsliderlite_shadow' ) ) :
            echo "<div class='easingsliderlite-shadow' style='{$styles}'>";
            if ( $c->shadow->enable )
                echo "<img src='{$c->shadow->image}' alt='' />";
            echo "</div>";
        else :
            do_action( 'easingsliderlite_shadow', $s, $c );
        endif;

    endif;
?>