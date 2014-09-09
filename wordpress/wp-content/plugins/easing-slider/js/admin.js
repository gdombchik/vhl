;(function($) {

    /** Our slide model */
    window.Slide = Backbone.Model.extend({

        defaults: {
            url: null,
            sizes: null,
            alt: null,
            title: null,
            link: null,
            linkTarget: '_blank',
        },

    });

    /** Our slides collection */
    window.SlideCollection = Backbone.Collection.extend({

        model: Slide,

        primary: 0,

        initialize: function() {

            var self = this;

            /** Bind events */
            this.on('add', this.addModel, this);
            this.on('remove', this.removeModel, this);
            this.on('reset', this.resetData, this);
            this.on('change', this.resetData, this);

            /** Increment primary key for each model (timeout ensures models are added before doing so) */
            setTimeout(function() {
                _.each(self.models, function(model) {
                    self.primary++;
                });
            });

        },

        addModel: function(model) {

            /** Increment primary key */
            this.primary++;

            /** Add ID to model & store it */
            model.set({ 'id': this.primary }, { silent: true });

            /** Silently reset the collection (forces refresh of _byId and _byCid properties) */
            this.reset(this.models, { silent: true });

            /** Set stored data */
            this.resetData();

        },

        removeModel: function() {

            var self = this;

            /** Reset model IDs */
            this.resetIDs();

            /** Silently reset the collection (forces refresh of _byId and _byCid properties) */
            this.reset(this.models, { silent: true });

            /** Set stored data */
            this.resetData();

        },

        resetData: function() {

            /** Reset stored (hidden input) data */
            $('#slideshow-images').val(JSON.stringify(this));

        },

        resetIDs: function() {

            var self = this;

            /** Reset primary key */
            this.primary = 0;

            /** Reset ID's */
            _.each(this.models, function(model) {
                self.primary++;
                model.set({ 'id': self.primary }, { silent: true });
            });

            return this;

        },

    });

    /** Our slides view */
    window.SlideView = wp.media.View.extend({

        $container: $('.thumbnails-container .inner'),

        template: wp.media.template('slide'),

        initialize: function() {

            var self = this;

            /** Bind events */
            this.collection.on('add', this.addThumb, this);
            this.collection.on('remove', this.render, this);
            this.collection.on('reset', this.render, this);
            this.collection.on('change:url', this.render, this);

            /** Delete all images functionality */
            $('.delete-images').bind('click', function(event) {

                event.preventDefault();

                /** Remove all of the thumbnails */
                if ( confirm( easingsliderlite.delete_images ) )
                    self.removeThumbs.call(self, event);

            });

            /** Add image(s) functionality */
            $('.add-image').bind('click', function(event) {

                event.preventDefault();

                /** Display the add image view */
                addImageView.render();

            });

            /** Delete image functionality */
            $(document).delegate('.delete-button', 'click', function(event) {

                event.preventDefault();

                /** Confirm before deleting the image */
                if ( confirm( easingsliderlite.delete_image ) )
                    self.collection.remove(self.collection.get($(this).parent().attr('data-id')));

            });

            /** Edit slide functionality */
            $(document).delegate('.thumbnails-container img', 'click', function(event) {

                event.preventDefault();

                /** Get the ID of the slide we want to edit */
                var id = $(this).parent().attr('data-id'),
                    editSlideView = new EditSlideView({
                        model: self.collection.get(id)
                    });

                /** Display slide editor view */
                $(editSlideView.render().el).appendTo('body').focus();

            });

        },

        addThumb: function(model) {

            /** Add the new thumbnail for this model */
            this.$container.append(this.template(model.toJSON()))

        },

        removeThumbs: function(event) {

            event.preventDefault();

            /** Remove thumbnails */
            this.$container.empty();

            /** Clear the collection */
            this.collection.reset();

            /** Reset primary index */
            this.collection.primary = 0;

        },

        render: function() {

            var self = this;

            /** Remove the previous thumbnails */
            this.$container.empty();

            /** Add the current thumbnails */
            _.each(this.collection.models, function(model) {
                self.$container.append(self.template(model.toJSON()));
            });

            return this;

        }

    });

    /** Edit slide view */
    window.EditSlideView = wp.media.View.extend({

        attributes: {
            'tabindex': 0
        },

        changeImageView: null,

        template: wp.media.template('edit-slide'),

        events: {
            'change': 'change',
            'click .media-modal-backdrop, .media-modal-close': 'discardChanges',
            'click .change-image': 'changeImage',
            /** As changes are set to the model automatically, we simple close to save changes */
            'click .media-modal-save': 'close',
            'click .media-menu-item': 'toggleTab',
            'keydown': 'keyDown'
        },

        initialize: function() {

            /** Clone original attributes as a window is opened (for restoration if changes are discarded) */
            this.origAttributes = _.clone(this.model.attributes);

            /** Reset thumbnail on image URL change */
            this.model.on('change:url', this.resetThumbnail, this);

        },

        toggleTab: function(event) {

            event.preventDefault();

            /** Get new tab ID */
            var id = event.target.dataset.tab;

            /** Reset 'active' class */
            $('.media-menu-item').removeClass('active');
            $(event.target).addClass('active');

            /** Toggle the appropriate tab */
            $('.media-tab', this.$el).each(function() {
                if ( this.id === id )
                    $(this).show();
                else
                    $(this).hide();
            });

        },

        close: function(event) {

            event.preventDefault();

            /** Closes the window, and delete the change image view (if created) */
            this.remove();
            if ( this.changeImageView )
                this.changeImageView.remove();

        },

        change: function(event) {

            /** Changes the models changes immediately after the field itself is changed */
            var target = event.target,
                change = {};
            change[target.id] = target.value;
            this.model.set(change);

        },

        discardChanges: function(event) {

            event.preventDefault();

            /** JSON strings of attributes (for comparison) */
            var current = JSON.stringify(this.model.attributes),
                original = JSON.stringify(this.origAttributes);

            /** As model changes are done automatically, to discard changes we simple reset the original model attributes */
            if ( current === original )
                this.close(event);
            else {
                if ( confirm( easingsliderlite.media_upload.discard_changes ) ) {
                    this.model.set(this.origAttributes);
                    this.close(event);
                }
            }

        },

        resetThumbnail: function(model, value) {
            var sizes = model.get('sizes'),
                size = sizes.large || sizes.medium || sizes.thumbnail;
            this.$('.slide-thumbnail').attr('src', size.url);
        },

        changeImage: function(event) {

            event.preventDefault();

            var self = this;

            /** Create new view if doesn't already exist */
            if ( this.changeImageView === null ) {
                this.changeImageView = new ChangeImageView({
                    id: this.model.get('id'),
                    model: this.model
                });
            }

            /** Render the view */
            this.changeImageView.render();

            /** Hide backdrop to prevent it from doubling up */
            $('.media-modal-backdrop').first().hide();

            /** Re-focus & show backdrop after close */
            this.changeImageView.fileFrame.on('close', function() {
                $('.media-modal-backdrop').first().show();
                self.$el.focus();
            });

        },

        keyDown: function(event) {
            
            /** Close view when 'esc' is pressed */
            if ( event.keyCode === 27 ) {
                event.preventDefault();
                this.discardChanges(event);
                return;
            }

        },

        render: function() {
            $(this.el).html(this.template(this.model.toJSON()));
            return this;
        },

    });

    /** Backbone Add Image View */
    window.AddImageView = Backbone.View.extend({

        fileFrame: null,

        frameProperties: {
            title: easingsliderlite.media_upload.title,
            button: easingsliderlite.media_upload.button,
            multiple: true
        },

        modelAttributes: ['url', 'sizes', 'alt', 'title'],

        initialize: function() {

            /** Bail if file frame already exists */
            if ( this.fileFrame )
                return;

            /** Create the media frame */
            this.fileFrame = wp.media.frames.fileFrame = wp.media({
                title: this.frameProperties.title,
                button: {
                    text: this.frameProperties.button
                },
                multiple: this.frameProperties.multiple
            });

            /** Run callback when an image(s) is selected */
            this.fileFrame.on('select', this.onSelect, this);

        },

        onSelect: function() {

            /** Get the selected images */
            var selection = this.fileFrame.state().get('selection'),
                self = this;

            /** Add them to the slides collection */
            _.each(selection.models, function(image) {

                /** Only get the attributes we want */
                var newAttributes = {};
                for ( var i in self.modelAttributes )
                    newAttributes[self.modelAttributes[i]] = image.get(self.modelAttributes[i]);

                /** Push the new slide */
                self.collection.add([newAttributes]);

            });

        },

        render: function() {
            this.fileFrame.open();
        }

    });

    /** Extension of Add Image View, except for changing a slide's image */
    window.ChangeImageView = AddImageView.extend({

        frameProperties: {
            title: easingsliderlite.media_upload.title,
            button: easingsliderlite.media_upload.change,
            multiple: false
        },

        onSelect: function() {

            /** Get the selected attachment image (aka, the new slide) */
            var selection = this.fileFrame.state().get('selection'),
                image = selection.models[0],
                newAttributes = {};

            /** Only get the attributes we want */
            for ( var i in this.modelAttributes )
                newAttributes[this.modelAttributes[i]] = image.get(this.modelAttributes[i]);

            /** Set new slide attributes (thumbnail will reset automagically) */
            this.model.set(newAttributes);

        },

        remove: function() {

            /** Call parent function */
            AddImageView.prototype.remove.apply(this, arguments);

            /** These two lines seem to be the only true way to remove a media uploader (this.fileFrame) at the moment! */
            this.fileFrame.modal.$el.remove();
            this.fileFrame.uploader.$browser.remove();

        },

    });

    /** Override Media Library sidebar template with our own */
    wp.media.view.Attachment.Details.prototype.template = wp.media.template('media-details');

    /** Show/hide settings functionality */
    $('.sidebar-name').bind('click', function() {

        var $thisParent = $(this).parent(),
            $thisContent = $thisParent.find('.sidebar-content');

        // Close the other widgets before opening selected widget
        if ( !$thisParent.hasClass('exclude' ) ) {
            $('.sidebar-name').each(function() {

                // Get parent
                var $parent = $(this).parent();

                // Close the widget
                if ( !$parent.hasClass('exclude') && !$parent.hasClass('closed') ) {
                    $parent.find('.sidebar-content').slideUp(200, function() {
                        $parent.addClass('closed');
                    });
                }

            });
        }

        // Open/close the widget
        if ( $thisParent.hasClass('closed') )
            $thisContent.slideDown(200, function() {
                $thisParent.removeClass('closed');
            });
        else
            $thisContent.slideUp(200, function() {
                $thisParent.addClass('closed');
            });

    });

    /** Sortables functionality */
    $('.thumbnails-container').sortable({
        items: '.thumbnail',
        containment: 'parent',
        tolerance: 'pointer',
        stop: function(event, ui) {

            var order = [],
                sortedModels = [];

            /** Get the new sort order */
            $(this).find('.thumbnail').each(function() {
                order.push($(this).attr('data-id'));
            });

            /** Get array of models in sorted order */
            for ( var i = 0; i < order.length; i++ )
                sortedModels.push(slideCollection.get(order[i]));

            /** Reset the collection & its IDs */
            slideCollection.reset(sortedModels).resetIDs().resetData();

        }
    });

    /** Fade out messages after 5 seconds */
    setTimeout(function() {
        $('.message').not('.permanent').each(function() {
            $(this).fadeOut(400, function() {
                $(this).remove();
            });
        });
    }, 5000);

    /** Show warning prompts */
    $('.warn').bind('click', function() {
        if ( !confirm( easingsliderlite.warn ) )
            return false;
    });

    /** Get slides and bail if they can't be found */
    if ( $('#slideshow-images').length == 0 )
        return;

    /** Get our essentials */
    window.slideCollection = new SlideCollection(JSON.parse($('#slideshow-images').val()));
    window.slideView = new SlideView({
        collection: slideCollection
    });
    window.addImageView = new AddImageView({
        collection: slideCollection
    });

})(jQuery);