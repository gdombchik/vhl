;(function($) {

    /** Main plugin object */
    $.EasingSliderLite = function(el) {

        /** Core variables */
        var base = this,
            o;

        /** Cache the slideshow elements */
        base.el = el;
        base.$el = $(base.el);
        base.$viewport = base.$el.find('.easingsliderlite-viewport');
        base.$container = base.$viewport.find('.easingsliderlite-slides-container');
        base.$slides = base.$container.find('.easingsliderlite-slide');
        base.$images = base.$slides.find('.easingsliderlite-image');

        /** Get the plugin options */
        base.options = o = $.extend({}, $.EasingSliderLite.defaults, $.parseJSON(base.$el.attr('data-options')));

        /** State variables */
        base.current = 0;
        base.previous = 0;
        base.count = base.$slides.length;
        base.width = o.dimensions.width;
        base.height = o.dimensions.height;

        /** Store our data for external use */
        base.$el.data('easingsliderlite', base);

        /**
         * Constructor
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.initialize = function() {

            /** Remove inline CSS display property from container */
            base.$container.css({ 'display': '' });

            /** Detect CSS3 support */
            base.useCSS3 = ( o.general.enableCSS3 ) ? base._supportsCSS3() : false;
            if ( base.useCSS3 )
                base.$el.addClass('use-css3');

            /** Setup responsive functionality */
            if ( o.dimensions.responsive )
                base._setupResponsive();

            /** Detect touch support and set device click/touch event */
            base.supportsTouch = ( 'ontouchstart' in document.documentElement && o.general.enableTouch ) ? true : false;
            base.clickEvent = ( base.supportsTouch ) ? 'touchstart.easingsliderlite' : 'click.easingsliderlite';

            /** Setup navigation elements */
            if ( o.navigation.arrows )
                base._setupArrows();
            if ( o.navigation.pagination )
                base._setupPagination();

            /** Flag current slide (and queue for slide change) */
            base.$slides.eq(base.current).addClass('active');
            base.$el.bind('beforetransition', function() {
                base.$slides.removeClass('active').eq(base.current).addClass('active');
            });

            /** Queue playback (if enabled) */
            if ( o.playback.enabled )
                base.$el.one('onload', base.startPlayback);

            /** Preload slideshow */
            base._preload();

            /** Allow custom hooks */
            base.$el.trigger('initialize', base);

        };

        /**
         * Detects browser CSS3 support
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._supportsCSS3 = function() {

            var element = document.createElement('div'),
                props = [ 'perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective' ];
            for ( var i in props ) {
                if ( typeof element.style[ props[ i ] ] !== 'undefined' ) {
                    base.vendorPrefix = props[i].replace('Perspective', '').toLowerCase();
                    return true;
                }
            }
            return false;

        };

        /**
         * Setup responsive functionality
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._setupResponsive = function() {

            /** Responsive indicator class */
            base.$el.addClass('is-responsive');

            /** Resize slideshow on window resize */
            $(window).bind('resize.easingsliderlite', function(event, force) {

                /** Queue custom resize end event */
                clearTimeout(base.resizeEnd);
                base.resizeEnd = setTimeout(function() {
                    $(window).trigger('resizeend');
                    delete base.resizeEnd;
                }, 50);

                /** Get the new slideshow width and height */
                var width = base.$viewport.outerWidth(),
                    height = base.$viewport.outerHeight();

                /** Bail if slideshow width hasn't changed */
                if ( width === base.width && !force )
                    return;

                /** Save new dimensions */
                base.width = width;
                base.height = height;

                /** Prevent transitions from triggering & reset container positioning */
                if ( o.transitions.effect == 'slide' ) {

                    /** Apply our CSS changes */
                    var properties = {};
                    if ( base.useCSS3 ) {
                        properties[ '-'+ base.vendorPrefix +'-transition-duration' ] = '0ms';
                        properties[ '-'+ base.vendorPrefix +'-transform' ] = 'translate3d(-'+ ( base.current * base.width ) +'px, 0, 0)';
                    }
                    else
                        properties['left'] = '-'+ ( base.current * base.width ) +'px';
                    base.$container.css(properties);

                    /** Queue transition duration reset */
                    $(window).one('resizeend', function() {
                        properties[ '-'+ base.vendorPrefix +'-transition-duration' ] = o.transitions.duration +'ms';
                        base.$container.css(properties);
                    });

                }

                /** Resize the slides */
                base.$slides.css({ 'width': width +'px', 'height': height +'px' });

            });

            /** Trigger a resize now */
            $(window).trigger('resize.easingsliderlite', true);

        };

        /**
         * Sets up the arrow navigation
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._setupArrows = function() {

            /** Get and display the navigation */
            var $next = $('.easingsliderlite-next'),
                $prev = $('.easingsliderlite-prev'),
                $arrows = $().add($next).add($prev);

            /** Bind click events */
            $next.bind(base.clickEvent, function(event) {
                base.nextSlide();
                return false;
            });
            $prev.bind(base.clickEvent, function(event) {
                base.prevSlide();
                return false;
            });

            /** Visibility & hover styling */
            if ( o.navigation.arrows_hover )
                $arrows.addClass('has-hover');
            else {
                base.$el.one('onload', function() {
                    $arrows.css({ 'opacity': 1 });
                });
            }

            /** Trigger custom actions */
            base.$el.trigger('setuparrows', base);

        };

        /**
         * Sets up the pagination navigation
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._setupPagination = function() {

            /** Get & display the navigation */
            var $pagination = $('.easingsliderlite-pagination'),
                $icons = $pagination.children('div');

            /** Bind click events */
            $icons.bind(base.clickEvent, function(event) {
                base.goToSlide($(this).index());
                return false;
            });

            /** Set current icon now & on slide change */
            $icons.eq(base.current).addClass('active').removeClass('inactive');
            base.$el.bind('beforetransition', function() {
                $icons.removeClass('active').addClass('inactive').eq(base.current).addClass('active').removeClass('inactive');
            });

            /** Visibility & hover styling */
            if ( o.navigation.pagination_hover )
                $pagination.addClass('has-hover');
            else {
                base.$el.one('onload', function() {
                    $pagination.css({ 'opacity': 1 });
                });
            }

            /** Trigger custom actions */
            base.$el.trigger('setuppagination', base);

        };

        /**
         * Preloads the slideshows images
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._preload = function() {

            /** Preloaded image count */
            var count = 0;

            /** Complete function run after image is preloaded */
            var loadComplete = function() {

                count++;
                if ( count >= base.count )
                    base.$el.find('.easingsliderlite-preload').animate({ 'opacity': 0 }, { duration: 400, complete: function() {
                        $(this).remove();
                        base.$el.trigger('onload');
                    }});

            };

            /** Loop through and preload each image. Doesn't stop on failure, just continues instead */
            base.$images.each(function(index, image) {

                /**
                 * Create a virtual image element. We set its src after event handler is registered.
                 * We have to do this to prevent IE bugs (it doesn't always fire the onload event if image are loaded (from cache) before the event is bound)
                 */
                preloadImage = new Image();

                /** Bind preload functions. Still continues if a preload fails */
                preloadImage.onload = loadComplete;
                preloadImage.onerror = loadComplete;

                /** Set image src attribute */
                preloadImage.src = image.src;

            });

        };

        /**
         * Transitions the slideshow
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._transition = function() {

            /** Trigger before transition actions */
            base._beforeTransition();

            /** Queue after transition actions (takes place after all transitions occur, not just one) */
            clearTimeout(base.afterTransition);
            base.afterTransition = setTimeout(function() {

                /** Run functon */
                base._afterTransition();

                /** Delete timeout variable */
                delete base.afterTransition;

            }, o.transitions.duration);

            /** Do appropriate transition */
            if ( o.transitions.effect == 'slide' ) {

                /** Do CSS3 transition if supported */
                if ( base.useCSS3 ) {

                    /** Get the properties to transition */
                    var properties = {};
                    properties[ '-'+ base.vendorPrefix +'-transition-duration' ] = o.transitions.duration;
                    properties[ '-'+ base.vendorPrefix +'-transform' ] = 'translate3d(-'+ ( base.width * base.current ) +'px, 0, 0)';

                    /** Do the CSS3 transition */
                    base.$container.css(properties);

                }
                else {
                    /** Otherwise fallback to jQuery/Zepto animate */
                    base.$container.animate({ 'left': '-'+ ( base.width * base.current ) +'px' }, o.transitions.duration);
                }

            }
            else if ( o.transitions.effect == 'fade' ) {

                /** Prevent from triggering fade transition on the currently visible slide */
                if ( base.current === base.previous )
                    return;

                /** Z-index order increment */
                base.order = ( base.order ) ? base.order+1 : 1;

                /** Do some CSS resetting after all fade transitions have occurred */
                base.$el.unbind('aftertransition._transition').one('aftertransition._transition', function() {

                    /** Resets CSS for each slide after fade transition has ended */
                    base.$slides.each(function(index) {
                        var restore = ( index === base.current ) ? { 'z-index': '' } : { 'opacity': 0, 'display': 'none', 'z-index': '' };
                        $(this).css(restore);
                    });

                    /** Delete variables */
                    delete base.order;
                    delete base.animationClear;

                });

                /** Only do jQuery animate for this transition. CSS3 opacity effects mke no performance difference */
                base.$slides.eq(base.current).css({ 'opacity': '0', 'display': 'block', 'z-index': base.order }).animate({ 'opacity': '1' }, o.transitions.duration);

            }
            else {
                /** Allow for custom transitions */
                base.$el.trigger('transition', base, o.transitions.effect );
            }

        };

        /**
         * Executed before slide transition
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._beforeTransition = function() {

            /** Clear playback timer */
            if ( o.playback.enabled )
                clearTimeout(base.playbackTimer);

            /** Trigger actions */
            base.$el.trigger('beforetransition', base);

        };

        /**
         * Executed after slide transition
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base._afterTransition = function() {

            /** Restart playback (if enabled) */
            if ( o.playback.enabled )
                base.startPlayback({ silent: true });

            /** Trigger actions */
            base.$el.trigger('aftertransition', base);

        };

        /**
         * Starts slideshow automatic playback
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.startPlayback = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Alter slideshow playback setting */
            if ( !o.playback.enabled )
                o.playback.enabled = true;

            /** Runtime variable */
            base.runtime = new Date();

            /** Get pause time */
            base.pauseTime = o.playback.pause;

            /** Start automatic playback */
            base.playbackTimer = setTimeout(function() {
                base.nextSlide();
            }, base.pauseTime);

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('startplayback', base);

        };

        /**
         * Ends slideshow automatic playback
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.endPlayback = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Alter slideshow playback setting */
            if ( o.playback.enabled )
                o.playback.enabled = false;

            /** Clear playback timer */
            clearTimeout(base.playbackTimer);

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('endplayback', base);

        };

        /**
         * Pauses slideshow automatic playback
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.pausePlayback = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Clear playback timer */
            clearTimeout(base.playbackTimer);

            /** Calculate runtime left */ 
            base.runtime = Math.ceil( new Date() - base.runtime );

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('pauseplayback', base);

        };

        /**
         * Resumes slideshow automatic playback
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.resumePlayback = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Calculate playback time remaining */
            base.pauseTime = Math.ceil( base.pauseTime - base.runtime );

            /** Reset runtime */
            base.runtime = new Date();

            /** Resume automatic playback */
            base.playbackTimer = setTimeout(function() {
                base.nextSlide();
            }, base.pauseTime);

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('resumeplayback', base);

        };

        /**
         * Transitions to the next slide
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.nextSlide = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Get the next slide and transition the slideshow */
            base.previous = base.current;
            base.current = ( base.current == ( base.count-1 ) ) ? 0 : ( base.current+1 );
            base._transition( base.current, base.previous );

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('nextslide', base);

        };

        /**
         * Transitions to the previous slide
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.prevSlide = function(options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Get the previous slide and transition the slideshow */
            base.previous = base.current;
            base.current = ( base.current == 0 ) ? ( base.count-1 ) : ( base.current-1 );
            base._transition( base.current, base.previous );

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('prevslide', base);

        };

        /**
         * Transitions to a specified slide
         *
         * @author Matthew Ruddy
         * @since 2.0
         */
        base.goToSlide = function(eq, options) {

            /** Extend options */
            options = $.extend({}, { silent: false }, options);

            /** Bail if specified slide doesn't exist */
            if ( base.$slides.eq(eq).length == 0 )
                return;

            /** Transition to the slide */
            base.previous = base.current;
            base.current = eq;
            base._transition( base.current, base.previous, true );

            /** Trigger actions */
            if ( !options.silent )
                base.$el.trigger('gotoslide', base, eq);

        };

        /** Quick a dirty hack for allowing users to override plugin methods with their own */
        base = $.extend({}, base, $.EasingSliderLite.extensions);

        /** Initialize the plugin */
        base.initialize();

    };

    /**
     * Plugin defaults settings
     *
     * @author Matthew Ruddy
     * @since 2.0
     */
    $.EasingSliderLite.defaults = {
        general: {
            enableCSS3: true
        },
        navigation: {
            arrows: true,
            arrows_hover: true,
            arrows_position: 'inside',
            pagination: true,
            pagination_hover: true,
            pagination_position: 'inside',
            pagination_location: 'bottom-left'
        },
        dimensions: {
            width: 500,
            height: 200,
            responsive: true
        },
        transitions: {
            effect: 'slide',
            duration: 500
        },
        playback: {
            enabled: false,
            pause: 1000
        }
    };

    /**
     * Plugin method extensions
     *
     * @author Matthew Ruddy
     * @since 2.0
     */
    $.EasingSliderLite.extensions = {};

    /** Initiates the plugin on each element */
    $.fn.EasingSliderLite = function() {
        return this.each(function() {
            new $.EasingSliderLite(this);
        });
    };

    /** Let's go! */
    $(document).ready(function() {
        $('.easingsliderlite').EasingSliderLite();
    });

})(jQuery);