<?php
/*
 * Uninstall script for blog-in-blog
 *
 * Copyright 2009  Tim Hodson  (email : tim@timhodson.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

if(defined('ABSPATH') && defined('WP_UNINSTALL_PLUGIN')){
    delete_option('bib_show_dots_after');
    delete_option('bib_text_delim');
    delete_option('bib_text_page' );
    delete_option('bib_text_previous' );
    delete_option('bib_text_next' );
    delete_option('bib_style_selected' );
    delete_option('bib_style_not_selected' );
    delete_option('bib_post_template');
    delete_option('bib_more_link_text');
    delete_option('bib_avatar_size');
    delete_option('bib_hide_category_from_rss');
    delete_option('bib_hide_category');
    delete_option('bib_hide_category[]');
    delete_option('bib_meta_keys');
    delete_option('bib_debug');
    delete_option('bib_html');
    delete_option('bib_single');
}

?>
