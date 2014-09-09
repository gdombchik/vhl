<?php if ( !apply_filters( 'easingsliderlite_hide_advert', __return_false() ) ) : ?>
<div style="margin-bottom: 15px;">
    <a href="http://easingslider.com/upgrade-to-pro" target="_blank">
        <img src="<?php echo plugins_url( dirname( plugin_basename( EasingSliderLite::get_file() ) ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'banner-easingsliderpro.png'; ?>" width="285" height="100" alt="<?php _e( 'Need more slideshow? Click here.', 'easingsliderlite' ); ?>" />
    </a>
</div>

<div style="margin-bottom: 15px;">
    <a href="http://rocketgalleries.com/" target="_blank">
        <img src="<?php echo plugins_url( dirname( plugin_basename( EasingSliderLite::get_file() ) ) ) . DIRECTORY_SEPARATOR .'images'. DIRECTORY_SEPARATOR .'banner-rocketgalleries.png'; ?>" width="285" height="100" alt="<?php _e( 'Rocket Galleries for WordPress', 'easingsliderlite' ); ?>" />
    </a>
</div>
<?php endif; ?>

<!-- Manage Slides -->
<div class="widgets-holder-wrap exclude">
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Manage Slides', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix">
        <div class="hide-if-no-js manage-slides-buttons wp-media-buttons" style="margin-top: 1em;">
            <a href="#" id="add-image" class="button button-secondary add-image" data-editor="content" title="<?php _e( 'Add Images', 'easingsliderlite' ); ?>"><span class="wp-media-buttons-icon" style="margin-top: 1px;"></span> <?php _e( 'Add Images', 'easingsliderlite' ); ?></a>
            <a href="#" id="delete-images" class="button button-secondary delete-images" title="<?php _e( 'Delete Images', 'easingsliderlite' ); ?>"><span class="wp-media-buttons-icon" style="margin-top: 1px;"></span> <?php _e( 'Delete Images', 'easingsliderlite' ); ?></a>
            <?php do_action( 'easingsliderlite_manage_slides_buttons', $s ); ?>
        </div>
        <div class="field">
            <label for="randomize">
                <input type="hidden" name="general[randomize]" value="">
                <input type="checkbox" id="randomize" name="general[randomize]" value="true" <?php checked( $s->general->randomize, true ); ?>><span style="display: inline;"><?php _e( 'Randomize the slideshow order.', 'easingsliderlite' ); ?></span>
            </label>
        </div>
        <?php do_action( 'easingsliderlite_manage_slides_metabox', $s ); ?>
    </div>
</div>

<!-- Dimensions -->
<div class="widgets-holder-wrap" <?php if ( (bool) apply_filters( 'easingsliderlite_hide_dimensions_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Dimensions', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix">
        <div class="dimension-settings">
            <div class="field">
                <label for="width">
                    <span><?php _e( 'Width:', 'easingsliderlite' ); ?></span>
                    <input type="number" name="dimensions[width]" id="width" value="<?php echo esc_attr( $s->dimensions->width ); ?>">
                </label>
            </div>
            <div class="field">
                <label for="height">
                    <span><?php _e( 'Height:', 'easingsliderlite' ); ?></span>
                    <input type="number" name="dimensions[height]" id="height" value="<?php echo esc_attr( $s->dimensions->height ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Slideshow "width" and "height" values (in pixels).', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="responsive">
                    <input type="hidden" name="dimensions[responsive]" value="">
                    <input type="checkbox" name="dimensions[responsive]" id="responsive" value="true" <?php checked( $s->dimensions->responsive, true ); ?>><span style="display: inline;"><?php _e( 'Make this slideshow responsive.', 'easingsliderlite' ); ?></span>
                </label>
            </div>
            <p class="description"><?php _e( 'Check this option to make this slideshow responsive. If enabled, the "width" and "height" values above will act as maximums.', 'easingsliderlite' ); ?></p>
        </div>
        <?php do_action( 'easingsliderlite_dimensions_metabox', $s ); ?>
    </div>
</div>

<!-- Transitions -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderlite_hide_transitions_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Transitions', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables clearfix" style="display: none;">
        <div>
            <div class="field">
                <label for="effect">
                    <span><?php _e( 'Effect:', 'easingsliderlite' ); ?></span>
                    <select name="transitions[effect]" id="effect">
                        <option value="slide" <?php selected( $s->transitions->effect, 'slide' ); ?>><?php _e( 'Slide', 'easingsliderlite' ); ?></option>
                        <option value="fade" <?php selected( $s->transitions->effect, 'fade' ); ?>><?php _e( 'Fade', 'easingsliderlite' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Choose the transition effect you would like to use.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="duration">
                    <span><?php _e( 'Duration:', 'easingsliderlite' ); ?></span>
                    <input type="number" name="transitions[duration]" id="duration" value="<?php echo esc_attr( $s->transitions->duration ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Sets the duration (in milliseconds) for the slideshow transition.', 'easingsliderlite' ); ?></p>
        </div>
        <?php do_action( 'easingsliderlite_transitions_metabox', $s ); ?>
    </div>
</div>

<!-- Next & Previous Arrows -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderlite_hide_arrows_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Next & Previous Arrows', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Arrows:', 'easingsliderlite' ); ?></span>
                <div class="buttons">
                    <label for="arrows-enable"><input type="radio" name="navigation[arrows]" id="arrows-enable" value="true" <?php checked( $s->navigation->arrows, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderlite' ); ?></span>
                    </label>
                    <label for="arrows-disable"><input type="radio" name="navigation[arrows]" id="arrows-disable" value="false" <?php checked( $s->navigation->arrows, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderlite' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Toggles the next and previous slide arrows.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'On Hover:', 'easingsliderlite' ); ?></span>
                <div class="buttons">
                    <label for="arrows-hover-true"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-true" value="true" <?php checked( $s->navigation->arrows_hover, true ); ?>>
                        <span><?php _e( 'True', 'easingsliderlite' ); ?></span>
                    </label>
                    <label for="arrows-hover-false"><input type="radio" name="navigation[arrows_hover]" id="arrows-hover-false" value="false" <?php checked( $s->navigation->arrows_hover, false ); ?>>
                        <span><?php _e( 'False', 'easingsliderlite' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Set to "True" to only show the arrows when the user hovers over the slideshow.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="arrows_position">
                    <span><?php _e( 'Position:', 'easingsliderlite' ); ?></span>
                    <select name="navigation[arrows_position]" id="arrows_position">
                        <option value="inside" <?php selected( $s->navigation->arrows_position, 'inside' ); ?>><?php _e( 'Inside', 'easingsliderlite' ); ?></option>
                        <option value="outside" <?php selected( $s->navigation->arrows_position, 'outside' ); ?>><?php _e( 'Outside', 'easingsliderlite' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Select a position for the arrows.', 'easingsliderlite' ); ?></p>
        </div>
        <?php do_action( 'easingsliderlite_arrows_metabox', $s ); ?>
    </div>
</div>

<!-- Pagination Icons -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderlite_hide_pagination_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Pagination Icons', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Pagination:', 'easingsliderlite' ); ?></span>
                <div class="buttons">
                    <label for="pagination-enable"><input type="radio" name="navigation[pagination]" id="pagination-enable" value="true" <?php checked( $s->navigation->pagination, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderlite' ); ?></span>
                    </label>
                    <label for="pagination-disable"><input type="radio" name="navigation[pagination]" id="pagination-disable" value="false" <?php checked( $s->navigation->pagination, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderlite' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Enable/Disable the Pagination Icons. Each icon represents a slide in their respective order.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'On Hover:', 'easingsliderlite' ); ?></span>
                <div class="buttons">
                    <label for="pagination-hover-true"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-true" value="true" <?php checked( $s->navigation->pagination_hover, true ); ?>>
                        <span><?php _e( 'True', 'easingsliderlite' ); ?></span>
                    </label>
                    <label for="pagination-hover-false"><input type="radio" name="navigation[pagination_hover]" id="pagination-hover-false" value="false" <?php checked( $s->navigation->pagination_hover, false ); ?>>
                        <span><?php _e( 'False', 'easingsliderlite' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Set to "True" to only show the pagination when the user hovers over the slideshow.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="pagination_position">
                    <span><?php _e( 'Position:', 'easingsliderlite' ); ?></span>
                    <select name="navigation[pagination_position]" id="pagination_position" style="width: 45%; float: left;">
                        <option value="inside" <?php selected( $s->navigation->pagination_position, 'inside' ); ?>><?php _e( 'Inside', 'easingsliderlite' ); ?></option>
                        <option value="outside" <?php selected( $s->navigation->pagination_position, 'outside' ); ?>><?php _e( 'Outside', 'easingsliderlite' ); ?></option>
                    </select>
                    <select name="navigation[pagination_location]" id="pagination_location" style="width: 45%; float: left; margin-left: 10px;">
                        <option value="top-left" <?php selected( $s->navigation->pagination_location, 'top-left' ); ?>><?php _e( 'Top Left', 'easingsliderlite' ); ?></option>
                        <option value="top-right" <?php selected( $s->navigation->pagination_location, 'top-right' ); ?>><?php _e( 'Top Right', 'easingsliderlite' ); ?></option>
                        <option value="top-center" <?php selected( $s->navigation->pagination_location, 'top-center' ); ?>><?php _e( 'Top Center', 'easingsliderlite' ); ?></option>
                        <option value="bottom-left" <?php selected( $s->navigation->pagination_location, 'bottom-left' ); ?>><?php _e( 'Bottom Left', 'easingsliderlite' ); ?></option>
                        <option value="bottom-right" <?php selected( $s->navigation->pagination_location, 'bottom-right' ); ?>><?php _e( 'Bottom Right', 'easingsliderlite' ); ?></option>
                        <option value="bottom-center" <?php selected( $s->navigation->pagination_location, 'bottom-center' ); ?>><?php _e( 'Bottom Center', 'easingsliderlite' ); ?></option>
                    </select>
                </label>
            </div>
            <p class="description"><?php _e( 'Select a position for the pagination icons.', 'easingsliderlite' ); ?></p>
        </div>
        <?php do_action( 'easingsliderlite_pagination_metabox', $s ); ?>
    </div>
</div>

<!-- Playback -->
<div class="widgets-holder-wrap closed" <?php if ( apply_filters( 'easingsliderlite_hide_playback_metabox', __return_false() ) ) echo 'style="display: none;"'; ?>>
    <div class="sidebar-name">
        <div class="sidebar-name-arrow"></div>
        <h3><?php _e( 'Automatic Playback', 'easingsliderlite' ); ?></h3>
    </div>
    <div class="sidebar-content widgets-sortables" style="display: none;">
        <div>
            <div class="radio clearfix">
                <span><?php _e( 'Playback:', 'easingsliderlite' ); ?></span>
                <div class="buttons">
                    <label for="playback-enable"><input type="radio" name="playback[enabled]" id="playback-enable" value="true" <?php checked( $s->playback->enabled, true ); ?>>
                        <span><?php _e( 'Enable', 'easingsliderlite' ); ?></span>
                    </label>
                    <label for="playback-disable"><input type="radio" name="playback[enabled]" id="playback-disable" value="false" <?php checked( $s->playback->enabled, false ); ?>>
                        <span><?php _e( 'Disable', 'easingsliderlite' ); ?></span>
                    </label>
                </div>
            </div>
            <p class="description"><?php _e( 'Enable/Disable slideshow automatic playback.', 'easingsliderlite' ); ?></p>
        </div>
        <div class="divider"></div>
        <div>
            <div class="field">
                <label for="playback_pause">
                    <span><?php _e( 'Pause Duration:', 'easingsliderlite' ); ?></span>
                    <input type="number" name="playback[pause]" id="playback_pause" value="<?php echo esc_attr( $s->playback->pause ); ?>">
                </label>
            </div>
            <p class="description"><?php _e( 'Sets the duration (in milliseconds) for the pause between slide transitions.', 'easingsliderlite' ); ?></p>
        </div>
        <?php do_action( 'easingsliderlite_playback_metabox', $s ); ?>
    </div>
</div>