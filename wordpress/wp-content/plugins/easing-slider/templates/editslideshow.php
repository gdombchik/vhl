<?php
    /** Get the slideshow */
    $slideshow = $s = EasingSliderLite::get_instance()->validate( get_option( 'easingsliderlite_slideshow' ) );
?>
<div class="wrap">
    <form name="post" action="admin.php?page=easingsliderlite_edit_slideshow" method="post">
        <div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
        <h2><?php _e( 'Edit Slideshow', 'easingsliderlite' ); ?></h2>
        <?php

            /** Display welcome message */
            require 'editslideshow-welcome.php';

            /** Security nonce field */
            wp_nonce_field( "easingsliderlite-save_easingsliderlite_edit_slideshow", "easingsliderlite-save_easingsliderlite_edit_slideshow", false );

        ?>
        <div class="main-panel">
            <div class="clearfix">
                <div class="thumbnails-container">
                    <div class="inner clearfix">
                        <?php
                            /** We display the current slides anyway using PHP (rather than Javascript) to avoid any rendering delays */
                            if ( $s->slides ) {
                                foreach ( $s->slides as $slide ) {

                                    /** Pretty rebust set of fallbacks for the slide thumbnail! Shouldn't ever fail. */
                                    if (isset($slide->sizes->thumbnail)) {
                                        $thumbnail = $slide->sizes->thumbnail->url;
                                    }
                                    else if (isset($slide->sizes->small)) {
                                        $thumbnail = $slide->sizes->small->url;
                                    }
                                    else if (isset($slide->sizes->medium)) {
                                        $thumbnail = $slide->sizes->medium->url;
                                    }
                                    else if (isset($slide->sizes->large)) {
                                        $thumbnail = $slide->sizes->large->url;
                                    }
                                    else if (isset($slide->sizes->full)) {
                                        $thumbnail = $slide->sizes->full->url;
                                    }
                                    else {
                                        $thumbnail = $slide->url;
                                    }

                                    echo "<div class='thumbnail' data-id='{$slide->id}'><a href='#' class='delete-button'></a><img src='{$thumbnail}' alt='{$slide->alt}' /></div>";

                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="settings-container">
                    <?php require 'editslideshow-sidebar.php'; ?>
                </div>
            </div>
            <div class="divider"></div>

            <input type="hidden" name="author" value="<?php echo esc_attr( $s->author ); ?>">
            <input type="hidden" name="slides" id="slideshow-images" value="">
            <input type="submit" name="save" class="button button-primary button-large" id="save" accesskey="p" value="<?php _e( 'Save Slideshow', 'easingsliderlite' ); ?>">
            <?php /** This ensures that the slide's JSON is encoded correctly. Using PHP JSON encode can cause magic quote issues */ ?>
            <script type="text/javascript">document.getElementById('slideshow-images').value = '<?php echo addslashes( json_encode( $s->slides ) ); ?>';</script>
        </div>
    </form>
</div>