<?php

/**
 * Adds a 'Slideshows' widget to the WordPress widgets interface
 *
 * @author Matthew Ruddy
 * @since 2.0
 */
class ESL_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @since 2.0
     */
    public function __construct() {
        parent::__construct(
            'easingsliderlite_widget',
            __( 'Slideshow', 'easingsliderlite' ),
            array( 'description' => __( 'Display a slideshow using a widget', 'easingsliderlite' ) )
        );
    }

    /**
     * Widget logic
     *
     * @since 2.0
     */
    public function widget( $args, $instance ) {

        /** Extract arguments */
        extract( $args );

        /** Get widget title */
        $title = apply_filters( 'widget_title', $instance['title'] );

        /** Display widget header */
        echo $before_widget;
        if ( !empty( $title ) )
            echo $before_title . $title . $after_title;
        
        /** Display slideshow */
        echo ESL_Slideshow::get_instance()->display_slideshow();

        /** Display widget footer */
        echo $after_widget;


    }

    /**
     * Returns updated settings array. Also does some sanatization.
     *
     * @since 2.0
     */
    public function update( $new_instance, $old_instance ) {
        return array(
            'title' => strip_tags( $new_instance['title'] )
        );
    }

    /**
     * Widget settings form
     *
     * @since 2.0
     */
    public function form( $instance ) {

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'easingsliderlite' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php if ( isset( $instance['title'] ) ) echo esc_attr( $instance['title'] ); ?>">
        </p>
        <?php

    }

}