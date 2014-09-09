<?php

    /** Get the slideshow */
    $slideshow = $s = EasingSliderLite::get_instance()->validate( get_option( 'easingsliderlite_slideshow' ) );

    /** Get current customization settings */
    $customizations = $c = json_decode( get_option( 'easingsliderlite_customizations' ) );

    /** Load required extra scripts and styling */
    wp_enqueue_script( 'customize-controls' );
    wp_enqueue_style( 'customize-controls' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'esl-customizer' );
    ESL_Slideshow::enqueue_styles();
    ESL_Slideshow::print_custom_styles();
    ESL_Slideshow::enqueue_scripts();

    /** Prevent slideshow from showing edit icon */
    add_filter( 'easingsliderlite_edit_slideshow_icon', '__return_false' );

?>
<div id="customize-container" class="customize-container" style="display: block; background: url(../../../../wp-admin/images/wpspin_light.gif) no-repeat center center #fff;">
    <div class="wp-full-overlay expanded" style="opacity: 0;">
        <form id="customize-controls" action="admin.php?page=<?php echo esc_attr( $_GET['page'] ); ?>" method="post" class="wrap wp-full-overlay-sidebar" style="z-index: 9999;">
            <?php
                /** Security nonce field */
                wp_nonce_field( "easingsliderlite-save_{$_GET['page']}", "easingsliderlite-save_{$_GET['page']}", false );
            ?>

            <div id="customize-header-actions" class="wp-full-overlay-header">
                <input type="submit" name="save" id="save" class="button button-primary save" value="<?php _e( 'Save', 'easingsliderlite' ); ?>">
                <span class="spinner"></span>
                <a class="back button" href="admin.php?page=easingsliderlite_edit_slideshow"><?php _e( 'Close', 'easingsliderlite' ); ?></a>
            </div>

            <div class="wp-full-overlay-sidebar-content" tabindex="-1">
                <div id="customize-info" class="accordion-section customize-section">
                    <div class="accordion-section-title customize-section-title" aria-label="Theme Customizer Options" tabindex="0">
                        <span class="preview-notice"><?php _e( 'You are customizing <strong class="theme-name">Easing Slider "Lite"</strong>', 'easingsliderlite' ); ?></span>
                    </div>
                </div>
                <div id="customize-theme-controls" class="accordion-container">
                    <ul>
                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Next & Previous Arrows', 'easingsliderlite' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Next" Arrow Image', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="arrows[next]" data-selector=".easingsliderlite-next" data-property="background-image" value="<?php echo esc_attr( $c->arrows->next ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Previous" Arrow Image', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="arrows[prev]" data-selector=".easingsliderlite-prev" data-property="background-image" value="<?php echo esc_attr( $c->arrows->prev ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Width', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="arrows[width]" style="width: 90%" data-selector=".easingsliderlite-arrows" data-property="width" value="<?php echo esc_attr( $c->arrows->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Height', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="arrows[height]" style="width: 90%" data-selector=".easingsliderlite-arrows" data-property="height" value="<?php echo esc_attr( $c->arrows->height ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Pagination Icons', 'easingsliderlite' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Inactive" Image', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="pagination[inactive]" data-selector=".easingsliderlite-icon.inactive" data-property="background-image" value="<?php echo esc_attr( $c->pagination->inactive ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( '"Active" Image', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="pagination[active]" data-selector=".easingsliderlite-icon.active" data-property="background-image" value="<?php echo esc_attr( $c->pagination->active ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Icon Width', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="pagination[width]" style="width: 90%" data-selector=".easingsliderlite-icon" data-property="width" value="<?php echo esc_attr( $c->pagination->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Icon Height', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="pagination[height]" style="width: 90%" data-selector=".easingsliderlite-icon" data-property="height" value="<?php echo esc_attr( $c->pagination->height ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Border', 'easingsliderlite' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Color', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="border[color]" class="color-picker-hex" data-selector=".easingsliderlite" data-property="border-color" data-default="#000" value="<?php echo esc_attr( $c->border->color ); ?>">
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Width', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="border[width]" style="width: 90%" data-selector=".easingsliderlite" data-property="border-width" value="<?php echo esc_attr( $c->border->width ); ?>"> px
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Radius', 'easingsliderlite' ); ?></span>
                                        <input type="number" min="0" step="1" name="border[radius]" style="width: 90%" data-selector=".easingsliderlite" data-property="border-radius" value="<?php echo esc_attr( $c->border->radius ); ?>"> px
                                    </label>
                                </li>
                            </ul>
                        </li>

                        <?php if ( !apply_filters( 'easingsliderlite_disable_shadow', __return_false() ) ) : ?>
                        <li class="control-section accordion-section customize-section">
                            <h3 class="accordion-section-title customize-section-title" tabindex="0" title=""><?php _e( 'Drop Shadow', 'easingsliderlite' ); ?></h3>
                            <ul class="accordion-section-content customize-section-content">
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Display a Drop Shadow', 'easingsliderlite' ); ?></span>
                                        <label for="shadow-enable-true"><input type="radio" name="shadow[enable]" id="shadow-enable-true" data-selector=".easingsliderlite-shadow" data-property="shadow-enable" value="true" style="margin: 0 3px 0 0;" <?php checked( $c->shadow->enable, true ); ?>><?php _e( 'True', 'easingsliderlite' ); ?></label>
                                        <label for="shadow-enable-false"><input type="radio" name="shadow[enable]" id="shadow-enable-false" data-selector=".easingsliderlite-shadow" data-property="shadow-enable" value="false" style="margin: 0 3px 0 20px;" <?php checked( $c->shadow->enable, false ); ?>><?php _e( 'False', 'easingsliderlite' ); ?></label>
                                    </label>
                                </li>
                                <li class="customize-control customize-control-text">
                                    <label>
                                        <span class="customize-control-title"><?php _e( 'Shadow Image', 'easingsliderlite' ); ?></span>
                                        <input type="text" name="shadow[image]" data-selector=".easingsliderlite-shadow" data-property="shadow-image" value="<?php echo esc_attr( $c->shadow->image ); ?>">
                                    </label>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div id="customize-footer-actions" class="wp-full-overlay-footer">
                <a href="#" class="collapse-sidebar button-secondary" title="<?php _e( 'Collapse Sidebar', 'easingsliderlite' ); ?>">
                    <span class="collapse-sidebar-arrow"></span>
                    <span class="collapse-sidebar-label"><?php _e( 'Collapse', 'easingsliderlite' ); ?></span>
                </a>
            </div>

            <input type="hidden" name="customizations" id="customizations" value="">
            <?php /** This ensures that the JSON is encoded correctly. Using PHP JSON encode can cause magic quote issues */ ?>
            <script type="text/javascript">document.getElementById('customizations').value = '<?php echo addslashes( json_encode( $customizations ) ); ?>';</script>
        </form>

        <div id="customize-preview" class="wp-full-overlay-main" style="position: relative;">
            <div style="position: absolute; top: 0; left: 0; margin: 45px; width: 100%; height: 100%;">
                <script type="text/javascript">
                    /** Disable automatic playback */
                    jQuery(document).ready(function($) {
                        setTimeout(function() {
                            $('.easingsliderlite').data('easingsliderlite').endPlayback();
                        }, 1000);
                    });
                </script>
                <?php
                    /** Display the slideshow */
                    echo ESL_Slideshow::get_instance()->display_slideshow();
                ?>
            </div>
        </div>
    </div>
</div>