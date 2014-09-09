<?php

function vhl_theme_page() {
	add_theme_page( __( 'VHL Theme Options', 'vhl' ), __( 'Theme Options', 'vhl' ), 'edit_theme_options', 'vhl_options', 'vhl_admin_options_page' );
}

add_action( 'admin_menu', 'vhl_theme_page' );

function vhl_register_settings() {
	register_setting( 'vhl_theme_options', 'vhl_theme_options', 'vhl_validate_theme_options' );
}

add_action( 'admin_init', 'vhl_register_settings' );

function vhl_admin_scripts( $page_hook ) {
	if( 'appearance_page_vhl_options' == $page_hook ) {
		wp_enqueue_style( 'vhl_admin_style', get_template_directory_uri() . '/styles/admin.css' );
		wp_enqueue_style( 'jquery-layout', get_template_directory_uri() . '/styles/jquery.layout.css' );
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-layout', get_template_directory_uri() . '/scripts/jquery.layout.js' );
		wp_enqueue_script( 'jquery-layout-state', get_template_directory_uri() . '/scripts/jquery.layout.state.js' );
		wp_enqueue_script( 'json2' );
		wp_enqueue_script( 'farbtastic' );
	}
}

add_action( 'admin_enqueue_scripts', 'vhl_admin_scripts' );

function vhl_admin_options_page() { ?>
	<div class="wrap">
		<?php vhl_admin_options_page_tabs(); ?>
		<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
			<div class='updated'><p><?php _e( 'Theme settings updated successfully.', 'vhl' ); ?></p></div>
		<?php endif; ?>
		<form action="options.php" method="post">
			<?php settings_fields( 'vhl_theme_options' ); ?>
			<?php do_settings_sections('vhl_options'); ?>
			<p>&nbsp;</p>
			<?php $tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'general' ); ?>
			<input name="vhl_theme_options[submit-<?php echo $tab; ?>]" type="submit" class="button-primary" value="<?php _e( 'Save Settings', 'vhl' ); ?>" />
			<input name="vhl_theme_options[reset-<?php echo $tab; ?>]" type="submit" class="button-secondary" value="<?php _e( 'Reset Defaults', 'vhl' ); ?>" />
		</form>
	</div>
<?php
}

function vhl_admin_options_page_tabs( $current = 'general' ) {
	$current = ( isset ( $_GET['tab'] ) ? $_GET['tab'] : 'general' );
	$tabs = array(
		'general' => __( 'General', 'vhl' ),
		'layout' => __( 'Layout', 'vhl' ),
		'design' => __( 'Design', 'vhl' ),
		'typography' => __( 'Typography', 'vhl' ),
		'seo' => __( 'SEO', 'vhl' )
	);
	$links = array();
	foreach( $tabs as $tab => $name )
		$links[] = "<a class='nav-tab" . ( $tab == $current ? ' nav-tab-active' : '' ) ."' href='?page=vhl_options&tab=$tab'>$name</a>";
	echo '<div id="icon-themes" class="icon32"><br /></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $links as $link )
		echo $link;
	echo '</h2>';
}

function vhl_admin_options_init() {
	global $pagenow;
	if( 'themes.php' == $pagenow && isset( $_GET['page'] ) && 'vhl_options' == $_GET['page'] ) {
		$tab = ( isset ( $_GET['tab'] ) ? $_GET['tab'] : 'general' );
		switch ( $tab ) {
			case 'general' :
				vhl_general_settings_sections();
				break;
			case 'layout' :
				vhl_layout_settings_sections();
				break;
			case 'design' :
				vhl_design_settings_sections();
				break;
			case 'typography' :
				vhl_typography_settings_sections();
				break;
			case 'seo' :
				vhl_seo_settings_sections();
				break;
		}
	}
}

add_action( 'admin_init', 'vhl_admin_options_init' );

function vhl_general_settings_sections() {
	add_settings_section( 'vhl_global_options', __( 'Global Options', 'vhl' ), 'vhl_global_options', 'vhl_options' );
	add_settings_section( 'vhl_home_page_options', __( 'Home Page', 'vhl' ), 'vhl_home_page_options', 'vhl_options' );
	add_settings_section( 'vhl_archive_page_options', __( 'Archive Pages', 'vhl' ), 'vhl_archive_page_options', 'vhl_options' );
	add_settings_section( 'vhl_single_options', __( 'Single Posts', 'vhl' ), 'vhl_single_options', 'vhl_options' );
	add_settings_section( 'vhl_footer_options', __( 'Footer', 'vhl' ), 'vhl_footer_options', 'vhl_options' );
}

function vhl_global_options() {
	add_settings_field( 'vhl_fancy_dropdowns', __( 'Fancy Drop-down Menus', 'vhl' ), 'vhl_fancy_dropdowns', 'vhl_options', 'vhl_global_options' );
	add_settings_field( 'vhl_show_breadcrumbs', __( 'Breadcrumbs', 'vhl' ), 'vhl_show_breadcrumbs', 'vhl_options', 'vhl_global_options' );
	add_settings_field( 'vhl_use_lightbox', __( 'Lightbox', 'vhl' ), 'vhl_use_lightbox', 'vhl_options', 'vhl_global_options' );
	add_settings_field( 'vhl_posts_nav_labels', __( 'Posts Navigation Labels', 'vhl' ), 'vhl_posts_nav_labels', 'vhl_options', 'vhl_global_options' );
}

function vhl_fancy_dropdowns() { ?>
	<label class="description">
		<input name="vhl_theme_options[fancy_dropdowns]" type="checkbox" value="1" <?php checked( vhl_get_option( 'fancy_dropdowns' ) ); ?> />
		<span><?php _e( 'Enable slide and fade effects for drop-down menus', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_show_breadcrumbs() { ?>
	<label class="description">
		<input name="vhl_theme_options[breadcrumbs]" type="checkbox" value="1" <?php checked( vhl_get_option( 'breadcrumbs' ) ); ?> />
		<span><?php _e( 'Show breadcrumbs trail', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_use_lightbox() { ?>
	<label class="description">
		<input name="vhl_theme_options[lightbox]" type="checkbox" value="1" <?php checked( vhl_get_option( 'lightbox' ) ); ?> />
		<span><?php _e( 'Open links to images in a lightbox', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_posts_nav_labels() { ?>
	<select name="vhl_theme_options[posts_nav_labels]">
		<option value="next/prev" <?php selected( 'next/prev', vhl_get_option( 'posts_nav_labels' ) ); ?>><?php _e( 'Next Page', 'vhl' ); ?> / <?php _e( 'Previous Page', 'vhl' ); ?></option>
		<option value="older/newer" <?php selected( 'older/newer', vhl_get_option( 'posts_nav_labels' ) ); ?>><?php _e( 'Older Posts', 'vhl' ); ?> / <?php _e( 'Newer Posts', 'vhl' ); ?></option>
		<option value="earlier/later" <?php selected( 'earlier/later', vhl_get_option( 'posts_nav_labels' ) ); ?>><?php _e( 'Earlier Posts', 'vhl' ); ?> / <?php _e( 'Later Posts', 'vhl' ); ?></option>
		<option value="numbered" <?php selected( 'numbered', vhl_get_option( 'posts_nav_labels' ) ); ?>><?php _e( 'Numbered Pagination', 'vhl' ); ?></option>
	</select>
<?php
}

function vhl_home_page_options() {
	add_settings_field( 'vhl_home_page_layout', __( 'Home Page Layout', 'vhl' ), 'vhl_home_page_layout', 'vhl_options', 'vhl_home_page_options' );
	add_settings_field( 'vhl_home_page_excerpts', __( 'Full posts to display', 'vhl' ), 'vhl_home_page_excerpts', 'vhl_options', 'vhl_home_page_options' );
	add_settings_field( 'vhl_home_page_slider', __( 'Sticky Posts Slider', 'vhl' ), 'vhl_home_page_slider', 'vhl_options', 'vhl_home_page_options' );
}

function vhl_home_page_layout() { ?>
	<label class="description">
		<input name="vhl_theme_options[home_page_layout]" type="radio" <?php checked( 'grid', vhl_get_option( 'home_page_layout' ) ); ?> value="grid" />
		<span><?php _e( 'Grid Layout', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[home_page_layout]" type="radio" <?php checked( 'blog', vhl_get_option( 'home_page_layout' ) ); ?> value="blog" />
		<span><?php _e( 'Blog Layout', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_home_page_excerpts() { ?>
	<label class="description">
		<input name="vhl_theme_options[home_page_excerpts]" type="text" value="<?php echo vhl_get_option( 'home_page_excerpts' ); ?>" size="2" maxlength="2" />
		<span><?php _e( 'Full posts to display before grid (0 = display only teasers)', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_home_page_slider() { ?>
	<label class="description">
		<input name="vhl_theme_options[slider]" type="checkbox" value="<?php echo vhl_get_option( 'slider' ); ?>" <?php checked( vhl_get_option( 'slider' ) ); ?> />
		<span><?php _e( 'Display a slider of sticky posts on the front page', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_archive_page_options() {
	add_settings_field( 'vhl_archive_location', 'Archive Page Location', 'vhl_archive_location', 'vhl_options', 'vhl_archive_page_options' );
}

function vhl_archive_location() { ?>
	<label class="description">
		<input name="vhl_theme_options[location]" type="checkbox" value="<?php echo vhl_get_option( 'location' ); ?>" <?php checked( vhl_get_option( 'location' ) ); ?> />
		<span><?php _e( 'Show current location in archive pages', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_single_options() {
	add_settings_field( 'vhl_show_post_nav', __( 'Inner Post Navigation', 'vhl' ), 'vhl_show_post_nav', 'vhl_options', 'vhl_single_options' );
	add_settings_field( 'vhl_show_social_bookmarks', __( 'Social Bookmarks', 'vhl' ), 'vhl_show_social_bookmarks', 'vhl_options', 'vhl_single_options' );
	add_settings_field( 'vhl_show_author_box', __( 'Author Box', 'vhl' ), 'vhl_show_author_box', 'vhl_options', 'vhl_single_options' );
}

function vhl_show_post_nav() { ?>
	<label class="description">
		<input name="vhl_theme_options[post_nav]" type="checkbox" value="<?php echo vhl_get_option( 'post_nav' ); ?>" <?php checked( vhl_get_option( 'post_nav' ) ); ?> />
		<span><?php _e( 'Show link to next and previous post', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_show_social_bookmarks() { ?>
	<label class="description">
		<input name="vhl_theme_options[facebook]" type="checkbox" value="<?php echo vhl_get_option( 'facebook' ); ?>" <?php checked( vhl_get_option( 'facebook' ) ); ?> />
		<span><?php _e( 'Facebook Like', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[twitter]" type="checkbox" value="<?php echo vhl_get_option( 'twitter' ); ?>" <?php checked( vhl_get_option( 'twitter' ) ); ?> />
		<span><?php _e( 'Twitter Button', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[google]" type="checkbox" value="<?php echo vhl_get_option( 'google' ); ?>" <?php checked( vhl_get_option( 'google' ) ); ?> />
		<span><?php _e( 'Google +1', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[pinterest]" type="checkbox" value="<?php echo vhl_get_option( 'pinterest' ); ?>" <?php checked( vhl_get_option( 'pinterest' ) ); ?> />
		<span><?php _e( 'Pinterest', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_show_author_box() { ?>
	<label class="description">
		<input name="vhl_theme_options[author_box]" type="checkbox" value="<?php echo vhl_get_option( 'author_box' ); ?>" <?php checked( vhl_get_option( 'author_box' ) ); ?> />
		<span><?php _e( 'Display a hcard microformatted box featuring author name, avatar and bio', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_footer_options() {
	add_settings_field( 'vhl_copyright_notice', __( 'Copyright Notice', 'vhl' ), 'vhl_copyright_notice', 'vhl_options', 'vhl_footer_options' );
	add_settings_field( 'vhl_credit_links', __( 'Credit Links', 'vhl' ), 'vhl_credit_links', 'vhl_options', 'vhl_footer_options' );
}

function vhl_copyright_notice() { ?>
	<label class="description">
		<input name="vhl_theme_options[copyright_notice]" type="text" value="<?php echo vhl_get_option( 'copyright_notice' ); ?>" />
		<span><?php _e( 'Text to display in the footer copyright section (%year% = current year, %blogname% = website name)', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_credit_links() { ?>
	<label class="description">
		<input name="vhl_theme_options[theme_credit_link]" type="checkbox" value="<?php echo vhl_get_option( 'theme_credit_link' ); ?>" <?php checked( vhl_get_option( 'theme_credit_link' ) ); ?> />
		<span><?php _e( 'Show theme credit link', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[author_credit_link]" type="checkbox" value="<?php echo vhl_get_option( 'author_credit_link' ); ?>" <?php checked( vhl_get_option( 'author_credit_link' ) ); ?> />
		<span><?php _e( 'Show author credit link', 'vhl' ); ?></span>
	</label><br />
	<label class="description">
		<input name="vhl_theme_options[wordpress_credit_link]" type="checkbox" value="<?php echo vhl_get_option( 'wordpress_credit_link' ); ?>" <?php checked( vhl_get_option( 'wordpress_credit_link' ) ); ?> />
		<span><?php _e( 'Show WordPress credit link', 'vhl' ); ?></span>
	</label>
<?php
}

function vhl_design_settings_sections() {
	add_settings_section( 'vhl_theme_colors', __( 'Color Scheme', 'vhl' ), 'vhl_theme_colors', 'vhl_options' );
	add_settings_section( 'vhl_custom_css', __( 'Custom CSS', 'vhl' ), 'vhl_custom_css', 'vhl_options' );
}

function vhl_theme_colors() {
	add_settings_field( 'vhl_color_scheme', __( 'Choose your color scheme', 'vhl' ), 'vhl_color_scheme', 'vhl_options', 'vhl_theme_colors' );
}

function vhl_custom_css() {
	add_settings_field( 'vhl_user_css', __( 'Enter your custom CSS', 'vhl' ), 'vhl_user_css', 'vhl_options', 'vhl_custom_css' );
}

function vhl_color_scheme() {
	$current_color_scheme = vhl_get_option( 'color_scheme' );
	$color_schemes = array(
		'neutral' => array(
			'name' => 'Neutral',
			'image' => 'neutral.png'
		),
		'sand' => array(
			'name' => 'Sand',
			'image' => 'sand.png'
		),
		'nature' => array(
			'name' => 'Nature',
			'image' => 'nature.png'
		),
		'earth' => array(
			'name' => 'Earth',
			'image' => 'earth.png'
		),
	); ?>
	<script>
		jQuery(document).ready(function($) {
			var label_id = '';
			$('.color-scheme').each(function(){
				if($(this).attr('checked')=='checked')
					label_id = '#label-'+$(this).attr('id');
			});
			if('' != label_id)
				$(label_id).addClass('checked');
			$('.color-scheme-label').click(function() {
				$('.color-scheme-label').removeClass('checked');
				$(this).addClass('checked');
			});
		});
	</script>
	<?php foreach( $color_schemes as $color_scheme => $data ) : ?>
		<label for="<?php echo $color_scheme; ?>" class="color-scheme-label" id="label-<?php echo $color_scheme; ?>"><img src="<?php echo get_template_directory_uri() . '/images/' . $data['image']; ?>" alt="<?php echo $data['name']; ?>" title="<?php echo $data['name']; ?>" />
		<input name="vhl_theme_options[color_scheme]" class="color-scheme" id="<?php echo $color_scheme; ?>" value="<?php echo $color_scheme; ?>" type="radio" <?php checked( $color_scheme, $current_color_scheme ); ?> /></label>
	<?php endforeach;
}

function vhl_user_css() { ?>
	<textarea name="vhl_theme_options[user_css]" cols="70" rows="15" style="width:97%;font-family:monospace;background:#f9f9f9"><?php echo vhl_get_option( 'user_css' ); ?></textarea>
<?php
}

function vhl_layout_settings_sections() {
	add_settings_section( 'vhl_layout', __( 'Default Layout Template', 'vhl' ), 'vhl_layout', 'vhl_options' );
	add_settings_section( 'vhl_layout_dimensions', __( 'Layout Dimensions', 'vhl' ), 'vhl_layout_dimensions', 'vhl_options' );
}

function vhl_layout() {
	add_settings_field( 'vhl_layout_template', __( 'Choose your preferred Layout', 'vhl' ), 'vhl_layout_template', 'vhl_options', 'vhl_layout' );
}

function vhl_layout_dimensions() {
	add_settings_field( 'vhl_dimensions', __( 'Select Layout Dimensions', 'vhl' ), 'vhl_dimensions', 'vhl_options', 'vhl_layout_dimensions' );
	add_settings_field( 'vhl_header_image_height', __( 'Header Image Height', 'vhl' ), 'vhl_header_image_height', 'vhl_options', 'vhl_layout_dimensions' );
}

function vhl_layout_template() {
	$current_layout = vhl_get_option( 'layout' );
	$layouts = array(
		'content-sidebar' => array(
			'name' => 'Content / Sidebar',
			'image' => 'content-sidebar.png'
		),
		'sidebar-content' => array(
			'name' => 'Sidebar / Content',
			'image' => 'sidebar-content.png'
		),
		'sidebar-content-sidebar' => array(
			'name' => 'Sidebar / Content / Sidebar',
			'image' => 'sidebar-content-sidebar.png'
		),
		'no-sidebars' => array(
			'name' => 'No Sidebars',
			'image' => 'no-sidebars.png'
		),
		'full-width' => array(
			'name' => 'Full Width',
			'image' => 'full-width.png'
		),
	); ?>
	<script>
		jQuery(document).ready(function($) {
			var label_id = '';
			$('.layout').each(function(){
				if($(this).attr('checked')=='checked')
					label_id = '#label-'+$(this).attr('id');
			});
			if('' != label_id)
				$(label_id).addClass('checked');
			$('.layout-label').click(function() {
				$('.layout-label').removeClass('checked');
				$(this).addClass('checked');
			});
		});
	</script>
	<?php foreach( $layouts as $layout => $data ) : ?>
		<label for="<?php echo $layout; ?>" class="layout-label" id="label-<?php echo $layout; ?>"><img src="<?php echo get_template_directory_uri() . '/images/' . $data['image']; ?>" alt="<?php echo $data['name']; ?>" title="<?php echo $data['name']; ?>" />
		<input name="vhl_theme_options[layout]" class="layout" id="<?php echo $layout; ?>" value="<?php echo $layout; ?>" type="radio" <?php checked( $layout, $current_layout ); ?> /></label>
	<?php endforeach;
}

function vhl_dimensions() {
	$default_options = vhl_default_options();
	$current_layout = vhl_get_option( 'layout' );
	$sidebar_right_size = $sidebar_left_size = vhl_get_option( 'sidebar_size' );
	if( 'sidebar-content-sidebar' == $current_layout ) {
		$sidebar_right_size = vhl_get_option( 'sidebar_right_size' );
		$sidebar_left_size = vhl_get_option( 'sidebar_left_size' );
	}?>
	<script>
		jQuery(document).ready(function ($) {
			var layout = $('#site').layout({
				applyDefaultStyles: true,
				onresize: function() {
					var state = layout.state;
					var northCurrentSize = state.north.size;
					$('.ui-layout-north').html(northCurrentSize+'px');
					$('#header_height').val(northCurrentSize);
					var centerCurrentSize = 100;
					<?php if( ( 'content-sidebar' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
						var eastCurrentSize = state.east.size + 5.7;
						centerCurrentSize = centerCurrentSize - 2.77 - ( eastCurrentSize * 100 / 640 ).toFixed(2);
						$('.ui-layout-east').html(( eastCurrentSize * 100 / 640 ).toFixed(2)+'%');
						$('#sidebar_<?php if( 'sidebar-content-sidebar' == $current_layout ) : ?>right_<?php endif; ?>size').val('"'+( eastCurrentSize * 100 / 640 ).toFixed(2)+'%'+'"');
					<?php endif; ?>
					<?php if( ( 'sidebar-content' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
						var westCurrentSize = state.west.size + 5.7;
						centerCurrentSize = centerCurrentSize - 2.77 - ( westCurrentSize * 100 / 640 ).toFixed(2);
						$('.ui-layout-west').html(( westCurrentSize * 100 / 640 ).toFixed(2)+'%');
						$('#sidebar_<?php if( 'sidebar-content-sidebar' == $current_layout ) : ?>left_<?php endif; ?>size').val('"'+( westCurrentSize * 100 / 640 ).toFixed(2)+'%'+'"');
					<?php endif; ?>
					$('.ui-layout-center').html(centerCurrentSize.toFixed(2)+'%');
				}
			});
			var state = layout.state;
			layout.sizePane("north", <?php echo vhl_get_option( 'header_height' ); ?>);
			var northCurrentSize = state.north.size;
			$('.ui-layout-north').html(northCurrentSize+'px');
			var centerCurrentSize = 100;
			<?php if( ( 'content-sidebar' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
				layout.sizePane("east", <?php echo $sidebar_right_size; ?>);
				var eastCurrentSize = state.east.size + 5.7;
				var centerCurrentSize = centerCurrentSize - 2.77 - ( eastCurrentSize * 100 / 640 ).toFixed(2);
				$('.ui-layout-east').html(( eastCurrentSize * 100 / 640 ).toFixed(2)+'%');
			<?php endif; ?>
			<?php if( ( 'sidebar-content' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
				layout.sizePane("west", <?php echo $sidebar_left_size; ?>);
				var westCurrentSize = state.west.size + 5.7;
				var centerCurrentSize = centerCurrentSize - 2.77 - ( westCurrentSize * 100 / 640 ).toFixed(2);
				$('.ui-layout-west').html(( westCurrentSize * 100 / 640 ).toFixed(2)+'%');
			<?php endif; ?>
			$('.ui-layout-center').html(centerCurrentSize.toFixed(2)+'%');
		});
	</script>
	<div id="site">
		<div class="ui-layout-center">
		</div>
		<div class="ui-layout-north">North</div>
		<?php if( ( 'content-sidebar' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
			<div class="ui-layout-east">East</div>
		<?php endif; ?>
		<?php if( ( 'sidebar-content' == $current_layout ) || ( 'sidebar-content-sidebar' == $current_layout ) ) : ?>
			<div class="ui-layout-west">West</div>
		<?php endif; ?>
	</div>
	<input name="vhl_theme_options[header_height]" id="header_height" value="<?php echo vhl_get_option( 'header_height' ); ?>" type="hidden" />
	<input name="vhl_theme_options[sidebar_size]" id="sidebar_size" value='<?php echo vhl_get_option( 'sidebar_size' ); ?>' type="hidden" />
	<input name="vhl_theme_options[sidebar_right_size]" id="sidebar_right_size" value='<?php echo vhl_get_option( 'sidebar_right_size' ); ?>' type="hidden" />
	<input name="vhl_theme_options[sidebar_left_size]" id="sidebar_left_size" value='<?php echo vhl_get_option( 'sidebar_left_size' ); ?>' type="hidden" />
<?php
}

function vhl_header_image_height() { ?>
	<input name="vhl_theme_options[header_image_height]" type="text" value="<?php echo vhl_get_option( 'header_image_height' ); ?>" size="4" /> <span class="description">px</span>
<?php
}

function vhl_typography_settings_sections() {
	add_settings_section( 'vhl_fonts', __( 'Font Families', 'vhl' ), 'vhl_fonts', 'vhl_options' );
	add_settings_section( 'vhl_font_sizes', __( 'Font Sizes', 'vhl' ), 'vhl_font_sizes', 'vhl_options' );
	add_settings_section( 'vhl_colors', __( 'Colors', 'vhl' ), 'vhl_colors', 'vhl_options' );
}

function vhl_fonts() {
	add_settings_field( 'vhl_body_font', __( 'Default Font Family', 'vhl' ), 'vhl_body_font', 'vhl_options', 'vhl_fonts' );
	add_settings_field( 'vhl_headings_font', __( 'Headings Font Family', 'vhl' ), 'vhl_headings_font', 'vhl_options', 'vhl_fonts' );
	add_settings_field( 'vhl_content_font', __( 'Body Copy Font Family', 'vhl' ), 'vhl_content_font', 'vhl_options', 'vhl_fonts' );
}

function vhl_body_font() {
	$fonts = vhl_available_fonts(); ?>
	<select name="vhl_theme_options[body_font]">
		<?php foreach( $fonts as $name => $family ) : ?>
			<option value="<?php echo $name; ?>" <?php selected( $name, vhl_get_option( 'body_font' ) ); ?>><?php echo str_replace( '"', '', $family ); ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_headings_font() {
	$fonts = vhl_available_fonts(); ?>
	<select name="vhl_theme_options[headings_font]">
		<?php foreach( $fonts as $name => $family ) : ?>
			<option value="<?php echo $name; ?>" <?php selected( $name, vhl_get_option( 'headings_font' ) ); ?>><?php echo str_replace( '"', '', $family ); ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_content_font() {
	$fonts = vhl_available_fonts(); ?>
	<select name="vhl_theme_options[content_font]">
		<?php foreach( $fonts as $name => $family ) : ?>
			<option value="<?php echo $name; ?>" <?php selected( $name, vhl_get_option( 'content_font' ) ); ?>><?php echo str_replace( '"', '', $family ); ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_font_sizes() {
	add_settings_field( 'vhl_body_font_size', __( 'Default Font Size', 'vhl' ), 'vhl_body_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_body_line_height', __( 'Default Line Height', 'vhl' ), 'vhl_body_line_height', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_h1_font_size', __( 'H1 Font Size', 'vhl' ), 'vhl_h1_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_h2_font_size', __( 'H2 Font Size', 'vhl' ), 'vhl_h2_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_h3_font_size', __( 'H3 Font Size', 'vhl' ), 'vhl_h3_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_h4_font_size', __( 'H4 Font Size', 'vhl' ), 'vhl_h4_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_headings_line_height', __( 'Headings Line Height', 'vhl' ), 'vhl_headings_line_height', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_content_font_size', __( 'Body Copy Font Size', 'vhl' ), 'vhl_content_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_content_line_height', __( 'Body Copy Line Height', 'vhl' ), 'vhl_content_line_height', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_mobile_font_size', __( 'Body Copy Font Size on Mobile Devices', 'vhl' ), 'vhl_mobile_font_size', 'vhl_options', 'vhl_font_sizes' );
	add_settings_field( 'vhl_mobile_line_height', __( 'Body Copy Line Height on Mobile Devices', 'vhl' ), 'vhl_mobile_line_height', 'vhl_options', 'vhl_font_sizes' );
}

function vhl_body_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[body_font_size]" type="text" value="<?php echo vhl_get_option( 'body_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[body_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'body_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_body_line_height() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[body_line_height]" type="text" value="<?php echo vhl_get_option( 'body_line_height' ); ?>" size="4" />
	<select name="vhl_theme_options[body_line_height_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'body_line_height_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_h1_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[h1_font_size]" type="text" value="<?php echo vhl_get_option( 'h1_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[h1_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'h1_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_h2_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[h2_font_size]" type="text" value="<?php echo vhl_get_option( 'h2_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[h2_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'h2_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_h3_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[h3_font_size]" type="text" value="<?php echo vhl_get_option( 'h3_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[h3_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'h3_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_h4_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[h4_font_size]" type="text" value="<?php echo vhl_get_option( 'h4_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[h4_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'h4_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_headings_line_height() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[headings_line_height]" type="text" value="<?php echo vhl_get_option( 'headings_line_height' ); ?>" size="4" />
	<select name="vhl_theme_options[headings_line_height_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'headings_line_height_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_content_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[content_font_size]" type="text" value="<?php echo vhl_get_option( 'content_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[content_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'content_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_content_line_height() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[content_line_height]" type="text" value="<?php echo vhl_get_option( 'content_line_height' ); ?>" size="4" />
	<select name="vhl_theme_options[content_line_height_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'content_line_height_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_mobile_font_size() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[mobile_font_size]" type="text" value="<?php echo vhl_get_option( 'mobile_font_size' ); ?>" size="4" />
	<select name="vhl_theme_options[mobile_font_size_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'mobile_font_size_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_mobile_line_height() {
	$units = array( 'px', 'pt', 'em', '%' ); ?>
	<input name="vhl_theme_options[mobile_line_height]" type="text" value="<?php echo vhl_get_option( 'mobile_line_height' ); ?>" size="4" />
	<select name="vhl_theme_options[mobile_line_height_unit]">
		<?php foreach( $units as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php selected( $unit, vhl_get_option( 'mobile_line_height_unit' ) ); ?>><?php echo $unit; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_colors() {
	add_settings_field( 'vhl_body_color', __( 'Default Font Color', 'vhl' ), 'vhl_body_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_headings_color', __( 'Headings Font Color', 'vhl' ), 'vhl_headings_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_content_color', __( 'Body Copy Font Color', 'vhl' ), 'vhl_content_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_links_color', __( 'Links Color', 'vhl' ), 'vhl_links_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_links_hover_color', __( 'Links Hover Color', 'vhl' ), 'vhl_links_hover_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_menu_color', __( 'Navigation Links Color', 'vhl' ), 'vhl_menu_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_menu_hover_color', __( 'Navigation Links Hover Color', 'vhl' ), 'vhl_menu_hover_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_sidebar_color', __( 'Sidebar Widgets Color', 'vhl' ), 'vhl_sidebar_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_sidebar_title_color', __( 'Sidebar Widgets Title Color', 'vhl' ), 'vhl_sidebar_title_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_sidebar_links_color', __( 'Widgets Links Color', 'vhl' ), 'vhl_sidebar_links_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_footer_color', __( 'Footer Widgets Color', 'vhl' ), 'vhl_footer_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_footer_title_color', __( 'Footer Widgets Title Color', 'vhl' ), 'vhl_footer_title_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_copyright_color', __( 'Footer Color', 'vhl' ), 'vhl_copyright_color', 'vhl_options', 'vhl_colors' );
	add_settings_field( 'vhl_copyright_links_color', __( 'Footer Links Color', 'vhl' ), 'vhl_copyright_links_color', 'vhl_options', 'vhl_colors' );
}

function vhl_body_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[body_color]" type="text" id="body_color" value="<?php echo vhl_get_option( 'body_color' ); ?>" style="color:#fff" />
		<div id="color_picker_body_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function bodypickerUpdate(color) {
			jQuery('#body_color').css("background-color", color);
			jQuery('#body_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#body_color').focus(function() {
				$('#color_picker_body_color').show();
			});
			$('#body_color').blur(function() {
				$('#color_picker_body_color').hide();
			});
			var body_color = $.farbtastic('#color_picker_body_color', bodypickerUpdate);	
			body_color.setColor('<?php echo vhl_get_option( 'body_color' ); ?>');
			body_color.linkTo(bodypickerUpdate);         
		});
	</script>
<?php
}

function vhl_headings_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[headings_color]" type="text" id="headings_color" value="<?php echo vhl_get_option( 'headings_color' ); ?>" style="color:#fff" />
		<div id="color_picker_headings_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function headingspickerUpdate(color) {
			jQuery('#headings_color').css("background-color", color);
			jQuery('#headings_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#headings_color').focus(function() {
				$('#color_picker_headings_color').show();
			});
			$('#headings_color').blur(function() {
				$('#color_picker_headings_color').hide();
			});
			var headings_color = $.farbtastic('#color_picker_headings_color', headingspickerUpdate);	
			headings_color.setColor('<?php echo vhl_get_option( 'headings_color' ); ?>');
			headings_color.linkTo(headingspickerUpdate);         
		});
	</script>
<?php
}

function vhl_content_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[content_color]" type="text" id="content_color" value="<?php echo vhl_get_option( 'content_color' ); ?>" style="color:#fff" />
		<div id="color_picker_content_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function contentpickerUpdate(color) {
			jQuery('#content_color').css("background-color", color);
			jQuery('#content_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#content_color').focus(function() {
				$('#color_picker_content_color').show();
			});
			$('#content_color').blur(function() {
				$('#color_picker_content_color').hide();
			});
			var content_color = $.farbtastic('#color_picker_content_color', contentpickerUpdate);	
			content_color.setColor('<?php echo vhl_get_option( 'content_color' ); ?>');
			content_color.linkTo(contentpickerUpdate);         
		});
	</script>
<?php
}

function vhl_links_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[links_color]" type="text" id="links_color" value="<?php echo vhl_get_option( 'links_color' ); ?>" style="color:#fff" />
		<div id="color_picker_links_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function linkspickerUpdate(color) {
			jQuery('#links_color').css("background-color", color);
			jQuery('#links_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#links_color').focus(function() {
				$('#color_picker_links_color').show();
			});
			$('#links_color').blur(function() {
				$('#color_picker_links_color').hide();
			});
			var links_color = $.farbtastic('#color_picker_links_color', linkspickerUpdate);	
			links_color.setColor('<?php echo vhl_get_option( 'links_color' ); ?>');
			links_color.linkTo(linkspickerUpdate);         
		});
	</script>
<?php
}

function vhl_links_hover_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[links_hover_color]" type="text" id="links_hover_color" value="<?php echo vhl_get_option( 'links_hover_color' ); ?>" style="color:#fff" />
		<div id="color_picker_links_hover_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function hoverpickerUpdate(color) {
			jQuery('#links_hover_color').css("background-color", color);
			jQuery('#links_hover_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#links_hover_color').focus(function() {
				$('#color_picker_links_hover_color').show();
			});
			$('#links_hover_color').blur(function() {
				$('#color_picker_links_hover_color').hide();
			});
			var links_hover_color = $.farbtastic('#color_picker_links_hover_color', hoverpickerUpdate);	
			links_hover_color.setColor('<?php echo vhl_get_option( 'links_hover_color' ); ?>');
			links_hover_color.linkTo(hoverpickerUpdate);         
		});
	</script>
<?php
}

function vhl_menu_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[menu_color]" type="text" id="menu_color" value="<?php echo vhl_get_option( 'menu_color' ); ?>" />
		<div id="color_picker_menu_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function menupickerUpdate(color) {
			jQuery('#menu_color').css("background-color", color);
			jQuery('#menu_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#menu_color').focus(function() {
				$('#color_picker_menu_color').show();
			});
			$('#menu_color').blur(function() {
				$('#color_picker_menu_color').hide();
			});
			var menu_color = $.farbtastic('#color_picker_menu_color', menupickerUpdate);	
			menu_color.setColor('<?php echo vhl_get_option( 'menu_color' ); ?>');
			menu_color.linkTo(menupickerUpdate);         
		});
	</script>
<?php
}

function vhl_menu_hover_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[menu_hover_color]" type="text" id="menu_hover_color" value="<?php echo vhl_get_option( 'menu_hover_color' ); ?>" />
		<div id="color_picker_menu_hover_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function menuhpickerUpdate(color) {
			jQuery('#menu_hover_color').css("background-color", color);
			jQuery('#menu_hover_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#menu_hover_color').focus(function() {
				$('#color_picker_menu_hover_color').show();
			});
			$('#menu_hover_color').blur(function() {
				$('#color_picker_menu_hover_color').hide();
			});
			var menu_hover_color = $.farbtastic('#color_picker_menu_hover_color', menuhpickerUpdate);	
			menu_hover_color.setColor('<?php echo vhl_get_option( 'menu_hover_color' ); ?>');
			menu_hover_color.linkTo(menuhpickerUpdate);         
		});
	</script>
<?php
}

function vhl_sidebar_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[sidebar_color]" type="text" id="sidebar_color" value="<?php echo vhl_get_option( 'sidebar_color' ); ?>" style="color:#fff" />
		<div id="color_picker_sidebar_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function sidebarpickerUpdate(color) {
			jQuery('#sidebar_color').css("background-color", color);
			jQuery('#sidebar_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#sidebar_color').focus(function() {
				$('#color_picker_sidebar_color').show();
			});
			$('#sidebar_color').blur(function() {
				$('#color_picker_sidebar_color').hide();
			});
			var sidebar_color = $.farbtastic('#color_picker_sidebar_color', sidebarpickerUpdate);	
			sidebar_color.setColor('<?php echo vhl_get_option( 'sidebar_color' ); ?>');
			sidebar_color.linkTo(sidebarpickerUpdate);         
		});
	</script>
<?php
}

function vhl_sidebar_title_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[sidebar_title_color]" type="text" id="sidebar_title_color" value="<?php echo vhl_get_option( 'sidebar_title_color' ); ?>" style="color:#fff" />
		<div id="color_picker_sidebar_title_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function sidebartitlepickerUpdate(color) {
			jQuery('#sidebar_title_color').css("background-color", color);
			jQuery('#sidebar_title_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#sidebar_title_color').focus(function() {
				$('#color_picker_sidebar_title_color').show();
			});
			$('#sidebar_title_color').blur(function() {
				$('#color_picker_sidebar_title_color').hide();
			});
			var sidebar_title_color = $.farbtastic('#color_picker_sidebar_title_color', sidebartitlepickerUpdate);	
			sidebar_title_color.setColor('<?php echo vhl_get_option( 'sidebar_title_color' ); ?>');
			sidebar_title_color.linkTo(sidebartitlepickerUpdate);         
		});
	</script>
<?php
}

function vhl_sidebar_links_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[sidebar_links_color]" type="text" id="sidebar_links_color" value="<?php echo vhl_get_option( 'sidebar_links_color' ); ?>" style="color:#fff" />
		<div id="color_picker_sidebar_links_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function sidebar_linkspickerUpdate(color) {
			jQuery('#sidebar_links_color').css("background-color", color);
			jQuery('#sidebar_links_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#sidebar_links_color').focus(function() {
				$('#color_picker_sidebar_links_color').show();
			});
			$('#sidebar_links_color').blur(function() {
				$('#color_picker_sidebar_links_color').hide();
			});
			var sidebar_links_color = $.farbtastic('#color_picker_sidebar_links_color', sidebar_linkspickerUpdate);	
			sidebar_links_color.setColor('<?php echo vhl_get_option( 'sidebar_links_color' ); ?>');
			sidebar_links_color.linkTo(sidebar_linkspickerUpdate);         
		});
	</script>
<?php
}

function vhl_footer_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[footer_color]" type="text" id="footer_color" value="<?php echo vhl_get_option( 'footer_color' ); ?>" />
		<div id="color_picker_footer_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function footerpickerUpdate(color) {
			jQuery('#footer_color').css("background-color", color);
			jQuery('#footer_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#footer_color').focus(function() {
				$('#color_picker_footer_color').show();
			});
			$('#footer_color').blur(function() {
				$('#color_picker_footer_color').hide();
			});
			var footer_color = $.farbtastic('#color_picker_footer_color', footerpickerUpdate);	
			footer_color.setColor('<?php echo vhl_get_option( 'footer_color' ); ?>');
			footer_color.linkTo(footerpickerUpdate);         
		});
	</script>
<?php
}

function vhl_footer_title_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[footer_title_color]" type="text" id="footer_title_color" value="<?php echo vhl_get_option( 'footer_title_color' ); ?>" />
		<div id="color_picker_footer_title_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function footertitlepickerUpdate(color) {
			jQuery('#footer_title_color').css("background-color", color);
			jQuery('#footer_title_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#footer_title_color').focus(function() {
				$('#color_picker_footer_title_color').show();
			});
			$('#footer_title_color').blur(function() {
				$('#color_picker_footer_title_color').hide();
			});
			var footer_title_color = $.farbtastic('#color_picker_footer_title_color', footertitlepickerUpdate);	
			footer_title_color.setColor('<?php echo vhl_get_option( 'footer_title_color' ); ?>');
			footer_title_color.linkTo(footertitlepickerUpdate);         
		});
	</script>
<?php
}

function vhl_copyright_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[copyright_color]" type="text" id="copyright_color" value="<?php echo vhl_get_option( 'copyright_color' ); ?>" style="color:#fff" />
		<div id="color_picker_copyright_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function copyrightpickerUpdate(color) {
			jQuery('#copyright_color').css("background-color", color);
			jQuery('#copyright_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#copyright_color').focus(function() {
				$('#color_picker_copyright_color').show();
			});
			$('#copyright_color').blur(function() {
				$('#color_picker_copyright_color').hide();
			});
			var copyright_color = $.farbtastic('#color_picker_copyright_color', copyrightpickerUpdate);	
			copyright_color.setColor('<?php echo vhl_get_option( 'copyright_color' ); ?>');
			copyright_color.linkTo(copyrightpickerUpdate);         
		});
	</script>
<?php
}

function vhl_copyright_links_color() { ?>
	<div style="position:relative;">
		<input name="vhl_theme_options[copyright_links_color]" type="text" id="copyright_links_color" value="<?php echo vhl_get_option( 'copyright_links_color' ); ?>" style="color:#fff" />
		<div id="color_picker_copyright_links_color" style="display:none; position:absolute; top:-85px; left:172px;"></div>
	</div>
	<script>
		function copyrightlpickerUpdate(color) {
			jQuery('#copyright_links_color').css("background-color", color);
			jQuery('#copyright_links_color').val(color);
		}
		jQuery(document).ready(function($) {
			$('#copyright_links_color').focus(function() {
				$('#color_picker_copyright_links_color').show();
			});
			$('#copyright_links_color').blur(function() {
				$('#color_picker_copyright_links_color').hide();
			});
			var copyright_links_color = $.farbtastic('#color_picker_copyright_links_color', copyrightlpickerUpdate);	
			copyright_links_color.setColor('<?php echo vhl_get_option( 'copyright_links_color' ); ?>');
			copyright_links_color.linkTo(copyrightlpickerUpdate);         
		});
	</script>
<?php
}
function vhl_seo_settings_sections() {
	add_settings_section( 'vhl_home_tags', __( 'Home Page', 'vhl' ), 'vhl_home_tags', 'vhl_options' );
	add_settings_section( 'vhl_archive_tags', __( 'Archive Pages', 'vhl' ), 'vhl_archive_tags', 'vhl_options' );
	add_settings_section( 'vhl_single_tags', __( 'Single Posts &amp; Pages', 'vhl' ), 'vhl_single_tags', 'vhl_options' );
	add_settings_section( 'vhl_other_tags', __( 'Other', 'vhl' ), 'vhl_other_tags', 'vhl_options' );
}

function vhl_home_tags() {
	add_settings_field( 'vhl_home_site_title_tag', __( 'Site Title Tag', 'vhl' ), 'vhl_home_site_title_tag', 'vhl_options', 'vhl_home_tags' );
	add_settings_field( 'vhl_home_site_desc_tag', __( 'Site Description Tag', 'vhl' ), 'vhl_home_site_desc_tag', 'vhl_options', 'vhl_home_tags' );
	add_settings_field( 'vhl_home_post_title_tag', __( 'Post Title Tag', 'vhl' ), 'vhl_home_post_title_tag', 'vhl_options', 'vhl_home_tags' );
}

function vhl_home_site_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[home_site_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'home_site_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_home_site_desc_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[home_desc_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'home_desc_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_home_post_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[home_post_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'home_post_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_archive_tags() {
	add_settings_field( 'vhl_archive_site_title_tag', __( 'Site Title Tag', 'vhl' ), 'vhl_archive_site_title_tag', 'vhl_options', 'vhl_archive_tags' );
	add_settings_field( 'vhl_archive_site_desc_tag', __( 'Site Description Tag', 'vhl' ), 'vhl_archive_site_desc_tag', 'vhl_options', 'vhl_archive_tags' );
	add_settings_field( 'vhl_archive_location_title_tag', __( 'Site Location Title Tag', 'vhl' ), 'vhl_archive_location_title_tag', 'vhl_options', 'vhl_archive_tags' );
	add_settings_field( 'vhl_archive_post_title_tag', __( 'Post Title Tag', 'vhl' ), 'vhl_archive_post_title_tag', 'vhl_options', 'vhl_archive_tags' );
}

function vhl_archive_site_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[archive_site_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'archive_site_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_archive_site_desc_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[archive_desc_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'archive_desc_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_archive_location_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[archive_location_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'archive_location_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_archive_post_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[archive_post_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'archive_post_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_single_tags() {
	add_settings_field( 'vhl_single_site_title_tag', __( 'Site Title Tag', 'vhl' ), 'vhl_single_site_title_tag', 'vhl_options', 'vhl_single_tags' );
	add_settings_field( 'vhl_single_site_desc_tag', __( 'Site Description Tag', 'vhl' ), 'vhl_single_site_desc_tag', 'vhl_options', 'vhl_single_tags' );
	add_settings_field( 'vhl_single_post_title_tag', __( 'Post Title Tag', 'vhl' ), 'vhl_single_post_title_tag', 'vhl_options', 'vhl_single_tags' );
	add_settings_field( 'vhl_single_comments_title_tag', __( 'Comments Title Tag', 'vhl' ), 'vhl_single_comments_title_tag', 'vhl_options', 'vhl_single_tags' );
	add_settings_field( 'vhl_single_respond_title_tag', __( 'Reply Form Title Tag', 'vhl' ), 'vhl_single_respond_title_tag', 'vhl_options', 'vhl_single_tags' );
}

function vhl_single_site_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[single_site_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'single_site_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_single_site_desc_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[single_desc_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'single_desc_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_single_post_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[single_post_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'single_post_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_single_comments_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[single_comments_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'single_comments_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_single_respond_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[single_respond_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'single_respond_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_other_tags() {
	add_settings_field( 'vhl_widget_title_tag', __( 'Widget Title Tag', 'vhl' ), 'vhl_widget_title_tag', 'vhl_options', 'vhl_other_tags' );
}

function vhl_widget_title_tag() {
	$tags = array( 'h1', 'h2', 'h3', 'p', 'div' ); ?>
	<select name="vhl_theme_options[widget_title_tag]">
		<?php foreach( $tags as $tag ) : ?>
			<option value="<?php echo $tag; ?>" <?php selected( $tag, vhl_get_option( 'widget_title_tag' ) ); ?>><?php echo $tag; ?></option>
		<?php endforeach; ?>
	</select>
<?php
}

function vhl_validate_theme_options( $input ) {
	if( isset( $input['submit-general'] ) || isset( $input['reset-general'] ) ) {
		if( ! in_array( $input['home_page_layout'], array( 'grid', 'blog' ) ) )
			$input['home_page_layout'] = vhl_get_option( 'home_page_layout' );
		if( ! is_numeric( absint( $input['home_page_excerpts'] ) ) || $input['home_page_excerpts'] > get_option( 'posts_per_page' )|| '' == $input['home_page_excerpts'] )
			$input['home_page_excerpts'] = vhl_get_option( 'home_page_excerpts' );
		else
			$input['home_page_excerpts'] = absint( $input['home_page_excerpts'] );
		$input['slider'] = ( isset( $input['slider'] ) ? true : false );
		$input['location'] = ( isset( $input['location'] ) ? true : false );
		$input['breadcrumbs'] = ( isset( $input['breadcrumbs'] ) ? true : false );
		$input['lightbox'] = ( isset( $input['lightbox'] ) ? true : false );
		if( ! in_array( $input['posts_nav_labels'], array( 'next/prev', 'older/newer', 'earlier/later', 'numbered' ) ) )
			$input['posts_nav_labels'] = vhl_get_option( 'posts_nav_labels' );
		$input['fancy_dropdowns'] = ( isset( $input['fancy_dropdowns'] ) ? true : false );
		$input['post_nav'] = ( isset( $input['post_nav'] ) ? true : false );
		$input['facebook'] = ( isset( $input['facebook'] ) ? true : false );
		$input['twitter'] = ( isset( $input['twitter'] ) ? true : false );
		$input['google'] = ( isset( $input['google'] ) ? true : false );
		$input['pinterest'] = ( isset( $input['pinterest'] ) ? true : false );
		$input['author_box'] = ( isset( $input['author_box'] ) ? true : false );
		$input['copyright_notice'] = esc_attr( $input['copyright_notice'] );
		$input['theme_credit_link'] = ( isset( $input['theme_credit_link'] ) ? true : false );
		$input['author_credit_link'] = ( isset( $input['author_credit_link'] ) ? true : false );
		$input['wordpress_credit_link'] = ( isset( $input['wordpress_credit_link'] ) ? true : false );
	} elseif( isset( $input['submit-layout'] ) || isset( $input['reset-layout'] ) ) {
		if( ! in_array( $input['layout'], array( 'content-sidebar', 'sidebar-content', 'sidebar-content-sidebar', 'no-sidebars', 'full-width' ) ) )
			$input['layout'] = vhl_get_option( 'layout' );
		$input['header_height'] = absint( $input['header_height'] );
		$input['sidebar_size'] = str_replace( '"', '', $input['sidebar_size'] );
		$input['sidebar_size'] = str_replace( '%', '', $input['sidebar_size'] );
		$input['sidebar_size'] = '"' . filter_var( $input['sidebar_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '%"';
		$input['sidebar_left_size'] = str_replace( '"', '', $input['sidebar_left_size'] );
		$input['sidebar_left_size'] = str_replace( '%', '', $input['sidebar_left_size'] );
		$input['sidebar_left_size'] = '"' . filter_var( $input['sidebar_left_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '%"';
		$input['sidebar_right_size'] = str_replace( '"', '', $input['sidebar_right_size'] );
		$input['sidebar_right_size'] = str_replace( '%', '', $input['sidebar_right_size'] );
		$input['sidebar_right_size'] = '"' . filter_var( $input['sidebar_right_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '%"';
		$input['header_image_height'] = absint( $input['header_image_height'] );
	} elseif( isset( $input['submit-design'] ) || isset( $input['reset-general'] ) ) {
		if( ! in_array( $input['color_scheme'], array( 'neutral', 'sand', 'nature', 'earth' ) ) )
			$input['color_scheme'] = vhl_get_option( 'color_scheme' );
		$input['user_css'] = esc_html( $input['user_css'] );
	} elseif( isset( $input['submit-typography'] ) || isset( $input['reset-typography'] ) ) {
		$fonts = vhl_available_fonts();
		$units = array( 'px', 'pt', 'em', '%' );
		$input['body_font'] = ( array_key_exists( $input['body_font'], $fonts ) ? $input['body_font'] : vhl_get_option( 'body_font' ) );
		$input['headings_font'] = ( array_key_exists( $input['headings_font'], $fonts ) ? $input['headings_font'] : vhl_get_option( 'headings_font' ) );
		$input['content_font'] = ( array_key_exists( $input['content_font'], $fonts ) ? $input['content_font'] : vhl_get_option( 'content_font' ) );
		$input['body_font_size'] = filter_var( $input['body_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['body_font_size_unit'] = ( in_array( $input['body_font_size_unit'], $units ) ? $input['body_font_size_unit'] : vhl_get_option( 'body_font_size_unit' ) );
		$input['body_line_height'] = filter_var( $input['body_line_height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['body_line_height_unit'] = ( in_array( $input['body_line_height_unit'], $units ) ? $input['body_line_height_unit'] : vhl_get_option( 'body_line_height_unit' ) );
		$input['h1_font_size'] = filter_var( $input['h1_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['h1_font_size_unit'] = ( in_array( $input['h1_font_size_unit'], $units ) ? $input['h1_font_size_unit'] : vhl_get_option( 'h1_font_size_unit' ) );
		$input['h2_font_size'] = filter_var( $input['h2_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['h2_font_size_unit'] = ( in_array( $input['h2_font_size_unit'], $units ) ? $input['h2_font_size_unit'] : vhl_get_option( 'h2_font_size_unit' ) );
		$input['h3_font_size'] = filter_var( $input['h3_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['h3_font_size_unit'] = ( in_array( $input['h3_font_size_unit'], $units ) ? $input['h3_font_size_unit'] : vhl_get_option( 'h3_font_size_unit' ) );
		$input['h4_font_size'] = filter_var( $input['h4_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['h4_font_size_unit'] = ( in_array( $input['h4_font_size_unit'], $units ) ? $input['h4_font_size_unit'] : vhl_get_option( 'h4_font_size_unit' ) );
		$input['headings_line_height'] = filter_var( $input['headings_line_height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['headings_line_height_unit'] = ( in_array( $input['headings_line_height_unit'], $units ) ? $input['headings_line_height_unit'] : vhl_get_option( 'headings_line_height_unit' ) );
		$input['content_font_size'] = filter_var( $input['content_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['content_font_size_unit'] = ( in_array( $input['content_font_size_unit'], $units ) ? $input['content_font_size_unit'] : vhl_get_option( 'content_font_size_unit' ) );
		$input['content_line_height'] = filter_var( $input['content_line_height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['content_line_height_unit'] = ( in_array( $input['content_line_height_unit'], $units ) ? $input['content_line_height_unit'] : vhl_get_option( 'content_line_height_unit' ) );
		$input['mobile_font_size'] = filter_var( $input['mobile_font_size'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['mobile_font_size_unit'] = ( in_array( $input['mobile_font_size_unit'], $units ) ? $input['mobile_font_size_unit'] : vhl_get_option( 'mobile_font_size_unit' ) );
		$input['mobile_line_height'] = filter_var( $input['mobile_line_height'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$input['mobile_line_height_unit'] = ( in_array( $input['mobile_line_height_unit'], $units ) ? $input['mobile_line_height_unit'] : vhl_get_option( 'mobile_line_height_unit' ) );
		$input['body_color'] = esc_attr( $input['body_color'] );
		$input['headings_color'] = esc_attr( $input['headings_color'] );
		$input['content_color'] = esc_attr( $input['content_color'] );
		$input['links_color'] = esc_attr( $input['links_color'] );
		$input['links_hover_color'] = esc_attr( $input['links_hover_color'] );
		$input['menu_color'] = esc_attr( $input['menu_color'] );
		$input['menu_hover_color'] = esc_attr( $input['menu_hover_color'] );
		$input['sidebar_color'] = esc_attr( $input['sidebar_color'] );
		$input['sidebar_title_color'] = esc_attr( $input['sidebar_title_color'] );
		$input['sidebar_links_color'] = esc_attr( $input['sidebar_links_color'] );
		$input['footer_color'] = esc_attr( $input['footer_color'] );
		$input['footer_title_color'] = esc_attr( $input['footer_title_color'] );
		$input['footer_links_color'] = esc_attr( $input['footer_links_color'] );
		$input['copyright_color'] = esc_attr( $input['copyright_color'] );
		$input['copyright_links_color'] = esc_attr( $input['copyright_links_color'] );
	} elseif( isset( $input['submit-seo'] ) || isset( $input['reset-seo'] ) ) {
		$tags = array( 'h1', 'h2', 'h3', 'p', 'div' );
		foreach( $input as $key => $tag )
			if( ( 'reset-seo' != $key ) && ! in_array( $tag, $tags ) )
				$input[$key] = vhl_get_option( $key );
	}
	if( isset( $input['reset-general'] ) || isset( $input['reset-layout'] ) || isset( $input['reset-design'] ) || isset( $input['reset-typography'] ) || isset( $input['reset-seo'] ) ) {
		$default_options = vhl_default_options();
		foreach( $input as $name => $value )
			$input[$name] = $default_options[$name];
	}
	$input = wp_parse_args( $input, get_option( 'vhl_theme_options', vhl_default_options() ) );
	return $input;
}