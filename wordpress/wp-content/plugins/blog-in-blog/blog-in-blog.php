<?php

/*
  Plugin Name: Blog in Blog
  Plugin URI: http://informationtakesover.co.uk/blog-in-blog-wordpress-plugin/
  Description: Create a blog within a blog using a category, post_type or tag. This plugin basically shows selected posts on a page using shortcodes.
  Version: 1.1.1
  Author: Tim Hodson
  Author URI: http://timhodson.com
  Text Domain: blog-in-blog
 */
/*  Copyright 2009  Tim Hodson  (email : tim@timhodson.com)

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
 */

/*
 * [blog_in_blog category_id=1 num=10 template='']
 * Assume most recent first (it's supposed to be a mini blog)
 */

if (!defined('BIB_VERSION'))
    define('BIB_VERSION', '1.1.1');

// Pre-2.6 compatibility
if (!defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL'))
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
if (!defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');


if (!defined('BIB_WP_UPLOADS_DIR')) {
    $uploads = wp_upload_dir();
    define('BIB_WP_UPLOADS_DIR', $uploads['basedir']);
}

include_once( WP_PLUGIN_DIR . "/blog-in-blog/options.php" );

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('blog-in-blog', WP_PLUGIN_DIR . $plugin_dir, $plugin_dir . '/languages');

global $blog_in_blog_opts;

function blog_in_blog_func($atts) {
    global $blog_in_blog_opts;
    global $wp_query;

    bib_write_debug(__FUNCTION__, "Shortcode parameters");
    bib_write_debug(__FUNCTION__,  print_r($atts, TRUE));

    if(! is_page()){
        return wpautop(wptexturize("<strong>ERROR:</strong> Blog-in-Blog shortcodes can only be used in pages, not posts."));
        exit;
    }

    extract(shortcode_atts(array(
                'category_id' => '',
                'category_slug' => '',
                'tag_slug' => '',
                'custom_post_type' => '',
                'author' => '',
                'author_name' => '',
//                'taxonomy' => '',
//                'tax_field' => '',
//                'tax_terms' => '',
//                'tax_operator' => '',
                'num' => '10',
                'order_by' => 'date',
                'template' => '',
                'pagination' => 'on',
                'sort' => 'newest',
                'post_id' => '',
                'custom_order_by' => '',
                'thumbnail_size' => 'thumbnail',
                'hidefirst' => 0
                    ), $atts));

    // set some values from the shortcode
    $blog_in_blog_opts['cat'] = $category_id;
    $blog_in_blog_opts['cat_slug'] = $category_slug;
    $blog_in_blog_opts['tag_slug'] = $tag_slug;
    $blog_in_blog_opts['custom_post_type'] = $custom_post_type;
//    $blog_in_blog_opts['taxonomy'] = $taxonomy;
//    $blog_in_blog_opts['tax_field'] = $tax_field;
//    $blog_in_blog_opts['tax_terms'] = $tax_terms;
//    $blog_in_blog_opts['tax_operator'] = $tax_operator;
    $blog_in_blog_opts['num'] = $num;
    $blog_in_blog_opts['post_order'] = bib_set_post_order($sort);
    $blog_in_blog_opts['order_by'] = $order_by;
    $blog_in_blog_opts['custom_order_by'] = $custom_order_by;
    $blog_in_blog_opts['post_id'] = $post_id;
    $blog_in_blog_opts['pagination'] = $pagination;
    $blog_in_blog_opts['template'] = $template ;
    $blog_in_blog_opts['author'] = $author ;
    $blog_in_blog_opts['author_name'] = $author_name ;
    $blog_in_blog_opts['hidefirst'] = $hidefirst ;

    if(isset ($wp_query->post->ID)){
        $blog_in_blog_opts['host_page'] = $wp_query->post->ID;
        bib_write_debug(__FUNCTION__, "Host page => {$wp_query->post->ID}");
    }  else {
        bib_write_debug(__FUNCTION__,"Host page => (Cannot Set Host page ID)");
    }


    if (strstr($thumbnail_size, 'x')) {
        $blog_in_blog_opts['thumbnail_size'] = split('x', $thumbnail_size);
    } else {
        $blog_in_blog_opts['thumbnail_size'] = $thumbnail_size;
    }

    // set the template if set in shortcode, look in uploads, then plugin dir, then use default.
    if ($template != '') {
        bib_write_debug(__FUNCTION__, "deciding on a template to use: $template");
        // get template string from options
        if (is_array($blog_in_blog_opts['bib_templates'])) {
            foreach($blog_in_blog_opts['bib_templates'] as $k => $v){
                if ($v['template_name'] == $blog_in_blog_opts['template']){
                    bib_write_debug(__FUNCTION__, "using template from database: ".$v['template_name']);
                    $blog_in_blog_opts['bib_post_template'] = ''; // this will force using bib_html from database.
                }else{
                    bib_write_debug(__FUNCTION__, "$template  != ".$v['template_name']);
                }
            }
            // currently no default applied here...
        } 
        if (file_exists(BIB_WP_UPLOADS_DIR . "/" . $template)) {
            $blog_in_blog_opts['bib_post_template'] = BIB_WP_UPLOADS_DIR . "/" . $template;
            echo "<!-- BIB: using template: ".$blog_in_blog_opts['bib_post_template']." -->" ;
            bib_write_debug(__FUNCTION__, "using template ".$blog_in_blog_opts['bib_post_template']);
            
        } else if (file_exists(WP_CONTENT_DIR . '/uploads/' . $template)) {
            $blog_in_blog_opts['bib_post_template'] = WP_CONTENT_DIR . '/uploads/' . $template;
            echo "<!-- BIB: using template: ".$blog_in_blog_opts['bib_post_template']." -->" ;
            bib_write_debug(__FUNCTION__, "using template ".$blog_in_blog_opts['bib_post_template']);
            
        } else if (file_exists(WP_PLUGIN_DIR . "/blog-in-blog/" . $template)) {
            $blog_in_blog_opts['bib_post_template'] = WP_PLUGIN_DIR . "/blog-in-blog/" . $template;
            echo "<!-- BIB: using template: ".$blog_in_blog_opts['bib_post_template']." -->" ;
            bib_write_debug(__FUNCTION__, "using template ".$blog_in_blog_opts['bib_post_template']);
            
        }else{
            $blog_in_blog_opts['bib_post_template'] = ''; // this will force using of bib_html option
            //echo "Cannot find template file <b>$template</b> in either <code>".BIB_WP_UPLOADS_DIR."/</code> or <code>".WP_PLUGIN_DIR."/blog-in-blog/</code>" ;
            bib_write_debug(__FUNCTION__, "template not found ".$blog_in_blog_opts['bib_post_template']);
        }
    } else {
        $blog_in_blog_opts['bib_post_template'] = ''; // this will force using bib_html from database.
        echo "<!-- BIB: using default template from database -->" ;
        bib_write_debug(__FUNCTION__, "defaulting to database template.");
    }

    // get some posts for that category

    $out = ""; // reset output

    // validate selections and give useful responses
    // TODO expand this properly into a separate function...
    if($blog_in_blog_opts['author'] != '' && !is_object(get_user_by('id',$blog_in_blog_opts['author']))){
        $out = "Error: Author with id '{$blog_in_blog_opts['author']}' is not an author in this site.";
        return $out;
    }
    if($blog_in_blog_opts['author_name'] != '' && !is_object(get_user_by('slug',$blog_in_blog_opts['author_name']))){
        $out = "Error: Author with slug '{$blog_in_blog_opts['author_name']}' is not an author in this site.";
        return $out;
    }

    if (isset($wp_query->query['bib_page_offset'])) {
        $blog_in_blog_opts['offset'] = $wp_query->query['bib_page_offset']; //TODO, fix homepage offset issues
        //var_dump($wp_query->query);
        //echo "offset : $nextoffset";
    } else {
        $blog_in_blog_opts['offset'] = 0;
    }

    // get the posts
    $postslist = bib_get_posts();
    // now for each post, populate the data
    if (is_array($postslist)){

        if(count($postslist) <= 0){
            $out = "<strong>Blog in Blog:</strong> There are no posts that match the selection criteria.";
            return $out;
        }

        foreach ($postslist as $post) {

            //var_dump($post);

            setup_postdata($post);
            
            $data['post_object'] = $post ;

            $data['post_id'] = $post->ID;
            // Because some filters are not working in the same context as our individual posts, 
            //  we need to track the current post id in a global variable!
            $blog_in_blog_opts['current_post_id'] = $post->ID;

            $data['post_date'] = date_i18n($blog_in_blog_opts['date_format'], strtotime($post->post_date));
            $data['post_time'] = date_i18n($blog_in_blog_opts['time_format'], strtotime($post->post_date));
            $data['post_day'] = date_i18n('j', strtotime($post->post_date));
            $data['post_dw'] = date_i18n('D', strtotime($post->post_date));
            $data['post_dow'] = date_i18n('l', strtotime($post->post_date));
            $data['post_mon'] = date_i18n('M', strtotime($post->post_date));
            $data['post_month'] = date_i18n('F', strtotime($post->post_date));
            $data['post_m'] = date_i18n('m', strtotime($post->post_date));
            $data['post_n'] = date_i18n('n', strtotime($post->post_date));
            $data['post_year'] = date_i18n('Y', strtotime($post->post_date));
            $data['post_yr'] = date_i18n('y', strtotime($post->post_date));

            $data['post_title'] = apply_filters('the_title', $post->post_title);

            $user = get_userdata($post->post_author);
            $data['post_author'] = apply_filters('the_author', $user->display_name);
            $data['post_author_avatar'] = get_avatar($post->post_author, $blog_in_blog_opts['bib_avatar_size']);

            $data['post_content'] = wpautop(wptexturize($post->post_content));
            $data['post_content'] = bib_process_gallery($data['post_content'], $post->ID);
            
            $data['post_excerpt'] = bib_check_password_protected($post,'post_excerpt');
            
            // this should probably get removed, as we do this in bib_process_moretag()
            //$data['post_excerpt'] = wpautop(wptexturize(get_the_excerpt()));
            
            $data['post_permalink'] = get_permalink($post);
            $data['post_comments'] = bib_process_comments($post->comment_status, $post->comment_count, $data['post_permalink']);
            $data['post_tags'] = bib_get_the_tags($post->ID);

            if (function_exists('get_the_post_thumbnail')) {
                $data['post_thumbnail'] = get_the_post_thumbnail($post->ID, $blog_in_blog_opts['thumbnail_size']);
            } else {
                $data['post_thumbnail'] = '';
            }

            // get categories for this post
            $cats = get_the_category($post->ID);
            $catstr = "";
            if (is_array($cats)){
                foreach ($cats as $v) {
                    $cat_link = get_category_link($v->cat_ID);
                    $catstr .= ' <a href="' . $cat_link . '" title="' . $v->cat_name . '" >' . $v->cat_name . '</a>' . $blog_in_blog_opts['bib_text_delim'];
                }
            }
            $catstr = substr($catstr, 0, strlen($catstr) - strlen($blog_in_blog_opts['bib_text_delim']));
            $data['post_categories'] = $catstr;

            $data = bib_process_moretag($data);

            $out .= bib_parse_template($data); // finally output the data in the template

            wp_reset_postdata();
        }
    }
    if ($blog_in_blog_opts['pagination'] == 'on') {

        $out .= blog_in_blog_page_navi();
        // func - get page navi
    }

    // return the posts data.
    return bib_do_shortcode($out);
}

add_shortcode('blog_in_blog', 'blog_in_blog_func');
add_shortcode('bib', 'blog_in_blog_func');

/**
 * Template tag for blog_in_blog. echos the generated content directly.
 * @param assoc_array $atts attributes that you want to pass to the BIB plugin.
 */
function blog_in_blog($atts){
    echo blog_in_blog_func($atts);
}

/**
 *
 * @global string $blog_in_blog_opts
 * @return array of posts
 */
function bib_get_posts() {
    global $blog_in_blog_opts;

    $params = array();
    if ($blog_in_blog_opts['post_id'] == '') { // for multiposts
        
        if ($blog_in_blog_opts['tag_slug'] != ''){
            $params['tag_slug__in'] = explode(",", $blog_in_blog_opts['tag_slug']);
        }
        if ($blog_in_blog_opts['cat'] != ''){
            $params['category__in'] =  explode(",", $blog_in_blog_opts['cat']);
        }
        if ($blog_in_blog_opts['cat_slug'] != '') {
            $params['category_name'] = $blog_in_blog_opts['cat_slug'];
        }
        if($blog_in_blog_opts['custom_post_type'] != '') {
            $params['post_type'] = $blog_in_blog_opts['custom_post_type'];
        }
        if($blog_in_blog_opts['author'] != '') {
            $params['author'] = $blog_in_blog_opts['author'];
        }
        if($blog_in_blog_opts['author_name'] != '') {
            $params['author_name'] =$blog_in_blog_opts['author_name'];
        }
        if ($blog_in_blog_opts['custom_order_by'] != '') {
            $params['orderby'] = 'meta_value';
            $params['order'] = $blog_in_blog_opts['post_order'];
            $params['meta_key'] = $blog_in_blog_opts['custom_order_by'];
        }else{
            $params['orderby'] = $blog_in_blog_opts['order_by'];
            $params['order'] = $blog_in_blog_opts['post_order'];
        }
//        if ($blog_in_blog_opts['taxonomy'] != ''){
//
//            if($blog_in_blog_opts['tax_operator'] != ''){
//                $operator = $blog_in_blog_opts['tax_operator'];
//            }
//            else
//            {
//                $operator = 'IN';
//            }
//
//            $params['tax_query'] = array(
//                    'taxonomy' => $blog_in_blog_opts['taxonomy'],
//                    'field' => $blog_in_blog_opts['tax_field'],
//                    'terms' => explode(',',$blog_in_blog_opts['tax_terms']),
//                    'operator' => $operator
//                );
//        }

        // apply whatever the case:
        $params['suppress_filters'] = false;

        // adjust the offsett
        if($blog_in_blog_opts['hidefirst'] != '' ){
            if($blog_in_blog_opts['offset'] != ''){
                $params['offset'] = intval($blog_in_blog_opts['hidefirst']) + intval($blog_in_blog_opts['offset']);
            } else {
                $params['offset'] = intval($blog_in_blog_opts['hidefirst']);
            }
        }else{
            $params['offset'] = $blog_in_blog_opts['offset'];
        }

        $params['numberposts'] = $blog_in_blog_opts['num'];

        // get the posts.
        $postslist = get_posts($params);

    }else{ // for single posts
        $postslist[0] = wp_get_single_post($blog_in_blog_opts['post_id']);
        $blog_in_blog_opts['pagination'] = 'off';
    }

    if ($blog_in_blog_opts['bib_debug']) {
        bib_write_debug( __FUNCTION__ , "Params passed to get_posts()");
        bib_write_debug( __FUNCTION__ , print_r($params, true));

        bib_write_debug(__FUNCTION__,"Response from get_posts()");
        bib_write_debug( __FUNCTION__ , print_r($postslist, true));
    }

    return $postslist;
}

/**
 * Think this is actually deprecated. remove in next version.
 * @global string $blog_in_blog_opts
 * @return <type>
 */
function bib_parse_filter(){
    global $blog_in_blog_opts ;

    if($blog_in_blog_opts['custom_post_type'] != '' ){
        return array('post_type'=> $blog_in_blog_opts['custom_post_type'] );
    }

    if($blog_in_blog_opts['category_slug'] != '' ){
        return array('category__in'=> $blog_in_blog_opts['category_slug'] );
    }

    if($blog_in_blog_opts['custom_post_type'] != '' ){
        return array('post_type'=> $blog_in_blog_opts['custom_post_type'] );
    }

}

/**
 * Filter to remove the shortcode to prevent display after other functions have applied the_content filter
 * TODO = probably no longer actually need this function here. it's not called anywhere.
 */
function bib_remove_shortcode($content='') {
    $content = preg_replace("/\[blog_in_blog.*\]/", "", $content);
    //echo "The Content from bib_remove_shortcode:(".$content.")";
    if ($blog_in_blog_opts['bib_debug']) {
        bib_write_debug(__FUNCTION__ ,"Removed the bib shortcode from the_content()");
    }

    return $content;
}

function bib_do_shortcode($content) {
    return do_shortcode($content);
}

/**
 * set the order clause of our query
 */
function bib_set_post_order($order) {
    global $blog_in_blog_opts;

    if (isset($order)) {
        if ($order == "ascending" || $order == "oldest" || $order == "reverse" || $order == "ASC") {
            return 'ASC';
        } else if ($order == "desending" || $order == "newest" || $order == "forward" || $order == "DESC") {
            return 'DESC';
        } else {
            return 'DESC';
        }
    } else {
        return 'DESC';
    }
}

/**
 * parse template string
 */
function bib_parse_template($data) {
    global $blog_in_blog_opts;

    $custom_values = bib_get_custom_fields($data['post_id']);

    // get template string from options
    if (is_array($blog_in_blog_opts['bib_templates'])) {
        foreach($blog_in_blog_opts['bib_templates'] as $k => $v){
            if ($v['template_name'] == $blog_in_blog_opts['template']){
                bib_write_debug(__FUNCTION__, "using template ".$v['template_name']);
                $template = html_entity_decode($v['template_html']);
            }
        }
    }

    if ($blog_in_blog_opts['bib_post_template'] != '' && !isset ($template)) {
        
        bib_write_debug(__FUNCTION__, "have a template to deal with");
        if (file_exists($blog_in_blog_opts['bib_post_template'])) {
            $template = file_get_contents($blog_in_blog_opts['bib_post_template']);
            bib_write_debug(__FUNCTION__, "using template file: ".$blog_in_blog_opts['bib_post_template']);
        } else {
            bib_write_debug(__FUNCTION__, "ERROR: cannot use template file: ".$blog_in_blog_opts['bib_post_template']);
            $template = "<p>Can't use template: {$blog_in_blog_opts['bib_post_template']}<br /> Either it doesn't exist in the database, or it doesn't exist as a file. <a href=\"".get_site_url()."/wp-admin/options-general.php?page=blog_in_blog_options_identifier\">Blog in Blog Admin Page</a></p>";
        }
    } elseif ($blog_in_blog_opts['bib_html'] && !isset ($template)) {
        //echo "not using a template" ;
        bib_write_debug(__FUNCTION__, "using default database template");
        $template = html_entity_decode($blog_in_blog_opts['bib_html']);
    }

    // bib version
    $template = str_replace("%bib_version%", BIB_VERSION, $template);

    // post id
    $template = str_replace("%post_id%", $data['post_id'], $template);

    // dates
    $template = str_replace("%post_date%", $data['post_date'], $template);
    $template = str_replace("%post_time%", $data['post_time'], $template);
    $template = str_replace("%post_day%", $data['post_day'], $template);
    $template = str_replace("%post_dw%", $data['post_dw'], $template);
    $template = str_replace("%post_dow%", $data['post_dow'], $template);
    $template = str_replace("%post_mon%", $data['post_mon'], $template);
    $template = str_replace("%post_month%", $data['post_month'], $template);
    $template = str_replace("%post_m%", $data['post_m'], $template);
    $template = str_replace("%post_n%", $data['post_n'], $template);
    $template = str_replace("%post_year%", $data['post_year'], $template);
    $template = str_replace("%post_yr%", $data['post_yr'], $template);
    $template = str_replace("%post_title%", $data['post_title'], $template);

    // author
    $template = str_replace("%post_author%", $data['post_author'], $template);
    $template = str_replace("%post_author_avatar%", $data['post_author_avatar'], $template);

    // content
    $template = str_replace("%post_content%", $data['post_content'], $template);
    $template = str_replace("%post_excerpt%", $data['post_excerpt'], $template);
    $template = str_replace("%post_thumbnail%", $data['post_thumbnail'], $template);

    // post meta
    $template = str_replace("%post_permalink%", $data['post_permalink'], $template);
    $template = str_replace("%post_categories%", $data['post_categories'], $template);
    $template = str_replace("%post_comments%", $data['post_comments'], $template);
    $template = str_replace("%post_tags%", $data['post_tags'], $template);

    if (is_array($custom_values)) {
        foreach ($custom_values as $key => $value) {
            if ($blog_in_blog_opts['bib_debug']) {
                bib_write_debug(__FUNCTION__,"Custom Vars found");
            }
            if (is_array($value)){
                foreach ($value as $val) {

                    # Check if key should have it's value reformatted
                    if (is_array($blog_in_blog_opts['bib_meta_keys'])) {

                        $key2 = substr(substr($key, 1, strlen($key) - 1), 0, -1);

                        if (in_array($key2, $blog_in_blog_opts['bib_meta_keys'])) {

                            $val = date_i18n($blog_in_blog_opts['date_format'], strtotime($val));
                            if ($blog_in_blog_opts['bib_debug']) {
                               bib_write_debug(__FUNCTION__,"Reformated date");
                            }
                        }
                    }

                    $template = str_replace("$key", $val, $template);
                    if ($blog_in_blog_opts['bib_debug']) {
                        bib_write_debug( __FUNCTION__ , "$key => $val");
                    }
                }
            }
        }
    }
    return $template;
}

function bib_get_custom_fields($post) {

    $out = array();

    $custom_fields = get_post_custom($post);

    if (is_array($custom_fields)){
        foreach ($custom_fields as $key => $value) {
            $key = "%" . $key . "%";
            $out[$key] = $value;
        }
    }
    return $out;
}

/**
 * Process comment data and build a string
 */
function bib_process_comments($cStatus, $cCount, $permalink) {

    $out = '';
    if (( $cStatus == 'open' && $cCount > 0 ) || ( $cStatus == 'closed' && $cCount > 0 )) {

        if(function_exists('_n')){
            $out = '<a href="' . $permalink . '#comments" title="' . __('Comments', 'blog-in-blog') . '" >'
                . sprintf(_n('%d Comment', '%d Comments', $cCount, 'blog-in-blog') . ' &raquo;', $cCount) . '</a>';
        }else{
            $out = '<a href="' . $permalink . '#comments" title="' . __('Comments', 'blog-in-blog') . '" >'
                . sprintf(__ngettext('%d Comment', '%d Comments', $cCount, 'blog-in-blog') . ' &raquo;', $cCount) . '</a>';
        }
    } elseif ($cStatus == 'open') {

        $out = '<a href="' . $permalink . '#respond" title="' . __('Respond', 'blog-in-blog') . '" >'
                . __('Leave a response ', 'blog-in-blog') . '&raquo;</a>';
    } elseif ($cStatus == 'closed') {

        $out .= __('Comments are closed', 'blog-in-blog');
    }


    return $out;
}

/**
 * based on get_the_content() in wp-includes/post-template.php
 */
function bib_process_moretag($data) {
    global $blog_in_blog_opts;
    global $more, $multipage, $page;
    $more = 0;

    $output = '';
    $hasTeaser = false;
    $more_link_text = $blog_in_blog_opts['bib_more_link_text'];

    $data['post_content'] = bib_check_password_protected($data['post_object'],'post_content');

    if (preg_match('/<!--more(.*?)?-->/', $data['post_content'], $matches)) {
        $content = explode($matches[0], $data['post_content'], 2);
        if (!empty($matches[1]) && !empty($more_link_text))
            $more_link_text = strip_tags(wp_kses_no_null(trim($matches[1])));

        $hasTeaser = true;
        //$more = 0;
        bib_write_debug(__FUNCTION__, "FOUND a 'more' tag.");
    } else {
        $content = array(
            $data['post_content']
        );
        bib_write_debug(__FUNCTION__, "NO more tag.");
        // $more = 1;
    }

    if ((false !== strpos($data['post_content'], '<!--noteaser-->')) && ((!$multipage) || ($page == 1))) {
        $stripteaser = 1;
        bib_write_debug(__FUNCTION__, "stripteaser = 1");
    }
    $teaser = $content[0];

    if (($more) && ($stripteaser) && ($hasTeaser)) {
        //    if ( ($more) && ($hasTeaser) )
        bib_write_debug(__FUNCTION__, "Not going to have any sort of teaser.");
        $teaser = '';
    }

    $output .= $teaser;

    if (count($content) > 1) {
        if ($more) {
            bib_write_debug(__FUNCTION__, "Content array is greater than 1 and more is true.");
            $output .= '<span id="more-' . $data['post_id'] . '"></span>' . $content[1];
        } else {
            bib_write_debug(__FUNCTION__, "Creating more link.");
            if (!empty($more_link_text))
                $output .= apply_filters('the_content_more_link', ' <a href="' . $data['post_permalink'] . "#more-{$data['post_id']}'\" class=\"more-link\">$more_link_text</a>", $more_link_text);
            $output = force_balance_tags($output);
        }
    }

    $data['post_content'] = $output;

    
    if($data['post_excerpt'] == ''){
        if(preg_match("/{$more_link_text}/", $output)){
            $data['post_excerpt'] = $output ;
        }else{
            $data['post_excerpt'] = get_the_excerpt();
        }
    } else {
        $data['post_excerpt'] =  apply_filters('excerpt_more', '', $data['post_excerpt'] );
    }

    return $data;
}

/**
 * We need to know the post id of the post we are dealing with and not
 * the post of the page containing the BIB shortcode, so have a global being
 * reset with the current post id each time.
 */
add_filter('excerpt_more', 'bib_filter_excerpt_more' , 99 ,2);
function bib_filter_excerpt_more($more, $excerpt=''){
    //$more isn't actually used, because we want to dump whatever has been applied to more already, and use our own.
    global $post ;
    global $blog_in_blog_opts ;
    bib_write_debug(__FUNCTION__, "Using excerpt more filter");

    bib_write_debug(__FUNCTION__, "The permalink from wordpress is: ".get_permalink($blog_in_blog_opts['current_post_id']));
    bib_write_debug(__FUNCTION__, "The post ID is: ".$blog_in_blog_opts['current_post_id']);

    $output = "$excerpt <a href=\"" . get_permalink($blog_in_blog_opts['current_post_id'])  . "#more-{$blog_in_blog_opts['current_post_id']}'\" class=\"more-link\">".get_option('bib_more_link_text')."</a>";
    bib_write_debug(__FUNCTION__, "Generated this link:{$output}");

    return $output;
}


// this function makes sure that the excerpt is set to a suitable phrase if the post is password protected
function bib_check_password_protected($post,$what='post_excerpt') {

    //var_dump($post);
   $output = $post->$what;
    if (post_password_required($post)) {
        $output = __('This is a protected post.');
        return wpautop(wptexturize($output));
    }

    return wpautop(wptexturize($output));
}



function bib_process_gallery($content, $postid) {
    // if the content contains a gallery shortcode
    // add post_id to attributes
    $content = preg_replace('/(\[gallery.*)\]/', "\\1 id=$postid ]", $content);

    return $content;
}

/**
 * Func to get permalink and make sure there is a ? ready for params.
 * @param $flag boolean flag if we expect to add a param or not?
 */
function bib_get_permalink($flag = true) {
    global $blog_in_blog_opts;
    //global $wp_query;
    //global $wpdb;

// don't know which post is calling the shortcode.
    // This especially problematic when bib is included in a page which is then included in another page!
    // however big problem is identifying if this is the home page. if it is then we need to do something clever.
    bib_write_debug( __FUNCTION__,"Host Page ID: ".$blog_in_blog_opts['host_page']);

    //if ($wp_query->is_home()){
    //    bib_write_debug(__FUNCTION__,"HOME PAGE!!!");
    //}



//    $post_detail = $wpdb->get_row("
//                            select post_name, post_date
//                            from $wpdb->posts
//                            where $wpdb->posts.ID = '{$blog_in_blog_opts['host_page']}'
//                            and $wpdb->posts.post_type='page'
//                            ",
//                            ARRAY_A
//                            );
//    bib_write_debug( __FUNCTION__,"post_name=".print_r($post_detail, true));
//
//    $permalink_structure = get_option('permalink_structure');
//
//    $permalink_structure = str_replace('%year%', date_i18n('Y', strtotime($post_detail['post_date'])), $permalink_structure);
//    $permalink_structure = str_replace('%monthnum%', date_i18n('m', strtotime($post_detail['post_date'])), $permalink_structure);
//    $permalink_structure = str_replace('%postname%', $post_detail['post_name'], $permalink_structure);

    //$perma_link = get_permalink($blog_in_blog_opts['host_page'], true);
    //$perma_link = get_site_url().$permalink_structure;
    //bib_write_debug(__FUNCTION__,$perma_link);

    // get the REQUEST_URI
    $perma_link = $_SERVER['REQUEST_URI'];
    bib_write_debug(__FUNCTION__,$perma_link);


    // if we have previously had an offset, we strip it from the params.
    $perma_link = preg_replace("/[\&]*bib_page_offset\=\d+/", '', $perma_link);
    bib_write_debug(__FUNCTION__,$perma_link);


    // check for existing params /?.*=.*/
    //if not found add ? to end of url
    if (preg_match('/\?.*\=.*/', $perma_link)) {
        if ($blog_in_blog_opts['bib_debug']) {
            bib_write_debug(__FUNCTION__,$perma_link);
        }
        return $perma_link;
    } elseif (preg_match('/\?$/', $perma_link)) {
        if ($flag === FALSE) {
            $perma_link = preg_replace('/\?$/', '', $perma_link);
        }
        if ($blog_in_blog_opts['bib_debug']) {
            bib_write_debug( __FUNCTION__,$perma_link);
        }
        return $perma_link;
    } else {
        $perma_link = $perma_link . "?";
        if ($blog_in_blog_opts['bib_debug']) {
            bib_write_debug( __FUNCTION__,$perma_link);
        }
        return $perma_link;
    }
}

function bib_get_the_tags($postid) {
    global $blog_in_blog_opts;

    $out = '';
    $tags = get_the_tags($postid);

    if (is_array($tags)) {
        foreach ($tags as $tag) {
            //Get the tag name
            $tag_name = $tag->name;
            //Get the tag url
            $tag_url = $tag->slug;
            if (get_option('tag_base')) {
                $the_url = get_bloginfo('url') . '/' . get_option('tag_base');
            } else {
                $the_url = get_bloginfo('url') . '/tag';
            }

            //Start adding all the linked tags into a single string for the next step
            $out = $out . '<a href="' . $the_url . '/' . $tag_url . '/">' . $tag_name . '</a>' . $blog_in_blog_opts['bib_text_delim'] . ' ';
        }

        //strip trailing delim and space.
        $out = substr($out, 0, strlen($out) - (strlen($blog_in_blog_opts['bib_text_delim']) + 1));
    }

    return $out;
}

/**
 * Page navi
 */
function blog_in_blog_page_navi() {
    global $blog_in_blog_opts;

    // count pages in chosen category
    $catposts = bib_get_post_count();
    $num = $blog_in_blog_opts['num'];

    $dlimit = $blog_in_blog_opts['bib_show_dots_after'];
    $elipsis = " ...";
    $page = 0;
    $pages = '';
    $maxpages = floor($catposts / $num);
    $nextoffset = 0;
    $thisloop = 0;
    $match = false;
    $first = false;
    $lastpage = false;
    $precurr = '';
    $prevlink = '';
    $nextlink = '';

    $out = '<div class="bib_page_nav">';

    if ($catposts >= 1) {
        // show page jumps for every $n posts
        for ($i = 0; $i < $catposts; $i++) {

            if ($i % $num == 0) {
                // start a new page, so
                $nextpage = $page++;
                $nextoffset = $thisloop;
                $thisloop++;
                if ($i + 1 == $catposts) {
                    $lastpage = true;
                }

                // check if this is the current page (based on offset, if offset not set is first page selected
                if ($match == false && (!isset($blog_in_blog_opts['offset']) || $thisloop - 1 == $blog_in_blog_opts['offset'])) {

                    $selected = ' bib_selected" style="' . $blog_in_blog_opts['bib_style_selected'] . '"';

                    $poffset = ($nextoffset - $num);
                    $noffset = ($nextoffset + $num);
                    $prevlink = ($nextoffset > 0) ? '<a class="bib_prev_link" href="' . bib_get_permalink() . '&bib_page_offset=' . $poffset . '">' . $blog_in_blog_opts['bib_text_previous'] . '</a> ' : '<span class="bib_prev_link_inactive">' . $blog_in_blog_opts['bib_text_previous'] . '</span> ';
                    $nextlink = ($noffset < $catposts) ? ' <a class="bib_next_link" href="' . bib_get_permalink() . '&bib_page_offset=' . $noffset . '">' . $blog_in_blog_opts['bib_text_next'] . '</a>' : ' <span class="bib_next_link_inactive">' .$blog_in_blog_opts['bib_text_next']. '</span>';

                    $pages[$page]['current'] = true;
                    $match = true;
                } else {
                    $selected = ' bib_not_selected" style="' . $blog_in_blog_opts['bib_style_not_selected'] . '"';
                }

                // if first page has been output
                if ($first == false) {
                    $pout = $blog_in_blog_opts['bib_text_page'] . ' <a href="' . bib_get_permalink(FALSE) . '" class="bib_page_number' . $selected . '" > ' . $page . '</a>' . $blog_in_blog_opts['bib_text_delim'];
                    $pages[$page]['html'] = $pout;
                    $first = true;
                } else {
                    // only output bib_text_delim and page numbers
                    if ($lastpage) {
                        $pout = ' <a href="' . bib_get_permalink() . '&bib_page_offset=' . $nextoffset . '" class="bib_page_number' . $selected . '" >' . $page . '</a>';
                    } else {
                        $pout = ' <a href="' . bib_get_permalink() . '&bib_page_offset=' . $nextoffset . '" class="bib_page_number' . $selected . '" >' . $page . '</a>' . $blog_in_blog_opts['bib_text_delim'];
                    }
                    $pages[$page]['html'] = $pout;
                }
            } else {
                $nextpage = $page;
                $thisloop++;
            }

            bib_write_debug(__FUNCTION__, "$i, nextpage: $nextpage, thisloop: $thisloop, page: $page, nextoffset: $nextoffset, URLoffset: {$blog_in_blog_opts['offset']}");

            if ($thisloop == $nextpage) {
                // do what exactly?
            }
        }
        //	var_dump($pages);
    }

    if (count($pages) > $dlimit) {
        $max = count($pages);

        $elipsisa = '';
        $elipsisb = '';
        $postcurr = '';
        $current = '';
        

        $fp = $pages[1]['html'];
        $lp = $pages[$max]['html'];

        if (is_array($pages)){
            foreach ($pages as $k => $page) {
                if (isset($page['current'])) {

                    if ($k == 1 || $k == 2 || $k == 3 ) {
                        $fp = '';
                        $elipsisa = '';
                    } elseif ($k == 4 ) {
                        // ??? not sure if this condition is actually needed?
                        $elipsisa = '';
                        //$elipsisa = $blog_in_blog_opts['bib_text_delim'];
                    } else {

                        $elipsisa = $elipsis;
                        $fp = substr($fp, 0, $fp - strlen($blog_in_blog_opts['bib_text_delim']));
                    }

                    if (isset($pages[$k - 2]['html'])) {
                        $precurr = $pages[$k - 2]['html'];
                    }
                    if (isset($pages[$k - 1]['html'])) {
                        $precurr .= $pages[$k - 1]['html'];
                    }
                    if (isset ($pages[$k]['html'])){
                        $current = $pages[$k]['html'];
                    }
                    if (isset ($pages[$k + 1]['html'])){
                        $postcurr = $pages[$k + 1]['html'];
                    }
                    //trim bib_text_delim from end of string.
                    if (isset ($pages[$k + 2]['html'])){
                        $postcurr .= substr($pages[$k + 2]['html'], 0, strlen($pages[$k + 2]['html']) - strlen($blog_in_blog_opts['bib_text_delim']));
                    }

                    if ($k == $max || $k == ($max - 1) || $k == ($max - 2)) {
                        $lp = '';
                        $elipsisb = '';
                    } elseif ($k == ($max - 3)) {
                        $elipsisb = $blog_in_blog_opts['bib_text_delim'];
                    } else {

                        $elipsisb = $elipsis;
                    }
                }
                bib_write_debug(__FUNCTION__, "$prevlink | $fp | $elipsisa | $precurr | $current | $postcurr | $elipsisb | $lp  | $nextlink");
            }
        }

        $out .= $prevlink . $fp . $elipsisa . $precurr . $current . $postcurr . $elipsisb . $lp . $nextlink;

    } else {
        $pagesout = '';
        if (is_array($pages)) {
            foreach ($pages as $page) {
                $pagesout .= $page['html'];
            }
        }
        // remove trailing bib_text_delim
        $pagesout = substr($pagesout, 0, strlen($pagesout) - strlen($blog_in_blog_opts['bib_text_delim']));

        $out .= $prevlink . $pagesout . $nextlink;
    }

    $out .= '</div>';
    //	echo htmlspecialchars($out);
    // return HTML
    return $out;
}

/**
 *
 * gets the post count to use in calculating the pagination.
 * @global object $wpdb
 * @global assoc $blog_in_blog_opts
 * @return int
 */
function bib_get_post_count() {
    global $wpdb;
    global $blog_in_blog_opts;

    $post_count = 0;

    $querystr = "
		SELECT count
		FROM $wpdb->term_taxonomy, $wpdb->posts, $wpdb->term_relationships, $wpdb->terms
		WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id
		AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id";

    /**
     * If there are categories
     */
    if ($blog_in_blog_opts['cat'] != '') {
        if (stristr($blog_in_blog_opts['cat'], ',')) {
            $querystr .= "
                    AND $wpdb->term_taxonomy.term_id in ( {$blog_in_blog_opts['cat']} )";
        } else {
            $querystr .= "
                    AND $wpdb->term_taxonomy.term_id = {$blog_in_blog_opts['cat']} ";

        }
    }
    if ($blog_in_blog_opts['cat_slug'] != '') {
        $querystr .= "
                    AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_taxonomy_id
                    AND $wpdb->terms.slug = '{$blog_in_blog_opts['cat_slug']}' ";
    }

    /**
     * If there is a custom post_type involved.
     */
    if ($blog_in_blog_opts['custom_post_type'] != ''){
        $querystr .= "
		AND $wpdb->posts.post_type = '".$blog_in_blog_opts['custom_post_type']."'";
    }

    /**
     * If there is a author involved. TODO CHECK THIS BIT
     */
    if ($blog_in_blog_opts['author'] != '' || $blog_in_blog_opts['author_name'] != '' ){
        
        // do something with the author_name
        if($blog_in_blog_opts['author'] != ''){
        $querystr .= "
		AND $wpdb->posts.post_author = '".$blog_in_blog_opts['author']."'";
        }
        if($blog_in_blog_opts['author_name'] != ''){
            $author = get_user_by('slug',$blog_in_blog_opts['author_name']);
            bib_write_debug(__FUNCTION__, print_r($author,true));
            $querystr .= "
		AND $wpdb->posts.post_author = '".$author->ID."'";
        }
    }

    /**
     * If we are getting custom post types only we just count them (restarts query)
     */
    if ($blog_in_blog_opts['custom_post_type'] != '' 
            && $blog_in_blog_opts['cat'] == ''
            && $blog_in_blog_opts['cat_slug'] == '' ){
        $querystr = "
		SELECT count($wpdb->posts.ID)
		FROM $wpdb->posts
		WHERE $wpdb->posts.post_type = '".$blog_in_blog_opts['custom_post_type']."'";
    }

    /**
     * Always limit to published posts only.
     */
    $querystr .= "
		AND $wpdb->posts.post_status = 'publish'";


    $result = $wpdb->get_var($querystr);

    if ($blog_in_blog_opts['bib_debug']) {
        bib_write_debug(__FUNCTION__, " Query string ");
        bib_write_debug(__FUNCTION__, print_r($querystr, true));
        bib_write_debug(__FUNCTION__, "Result");
        bib_write_debug(__FUNCTION__, print_r($result, true));
    }

    return $result;
}

/**
 * Register our offset parameter
 */
add_filter('query_vars', 'bib_url_params');
function bib_url_params($qvars) {
    $qvars[] = 'bib_page_offset';
    return $qvars;
}

/**
 * Hide the category(ies) chosen to be the blog
 */
function bib_hide_category($wp_query) {

    $c = '';
    $cat = get_option('bib_hide_category');
    $NONE_FLAG = 0;

    if (is_home ()) {
        // hide the categories
        if (is_array($cat)) {
            foreach ($cat as $v) {
                if ($v != "NONE") {
                    $c .= '-' . $v . ',';
                }else{
                    $NONE_FLAG = 1;
                }
            }
            if($NONE_FLAG == 0 ){
                $c = trim($c, ',');
                $wp_query->set('cat', $c);
                $wp_query->set('category__not_in', array_values($cat));
            }
        }
        
    }
    bib_write_debug(__FUNCTION__, "Hide Category:".print_r($cat, true));
    bib_write_debug(__FUNCTION__, "wp_query:".print_r($wp_query, true));
    return $wp_query ;
}

// This must be here.
add_filter('pre_get_posts', 'bib_hide_category');

function bib_hide_category_feed($query) {

    $c = '';
    $NONE_FLAG = 0;
    $cat = get_option('bib_hide_category');
    
    if (get_option('bib_hide_category_from_rss')) {
        if ($query->is_feed) {
            
            if (is_array($cat)) {
                foreach ($cat as $v) {
                    if ($v != "NONE") {
                        $c .= '-' . $v . ',';
                    }else{
                        $NONE_FLAG = 1;
                    }
                }
                if($NONE_FLAG == 0 ){
                    $query->set('cat', $c);
                    $query->set('category__not_in', array_values($cat));
                }
            }
        }
    }
    bib_write_debug(__FUNCTION__, "Hide Category:".print_r($cat, true));
    bib_write_debug(__FUNCTION__, "query:".print_r($query, true));
    return $query;
}

add_filter('pre_get_posts', 'bib_hide_category_feed');

function bib_write_debug($function, $msg) {
    global $blog_in_blog_opts;

    $OPT = get_option('bib_debug');
    if($OPT){
        $msg = "; " . $function . " :: ".$msg."\n" ;

        if(!isset ($blog_in_blog_opts['debug_output'])){
            $blog_in_blog_opts['debug_output'] = "==================== Started Ouput ==================\n";
            $blog_in_blog_opts['debug_output'] .= $msg."\n";
        }
        else {
            $blog_in_blog_opts['debug_output'] .= $msg;
        }
    }
}


add_action('wp_footer','bib_show_debug');
function bib_show_debug() {
    // output debug stuff
    global $blog_in_blog_opts;

    foreach ($blog_in_blog_opts as $key => $value) {
        if($key != 'debug_output'){
            bib_write_debug("OPTION $key", print_r($value,true));
        }
    }
    
    
    $OPT = get_option('bib_debug');
    if ($OPT){
        
        $output = "<br /><h2>BLOG_IN_BLOG DEBUG INFO</h2><small>Turn this off in the 'Misc' section of the blog_in_blog admin page.</small><br /><textarea cols='100' rows='20'>{$blog_in_blog_opts['debug_output']}</textarea>";
        unset ($blog_in_blog_opts['debug_output']);
        echo $output ;  
    }
}


//add_action('all', create_function('', 'var_dump( current_filter() ) ; '));
//add_action('shutdown', create_function('', ' global $wpdb; if(isset($wpdb)) var_dump( $wpdb->queries ); '));
?>
