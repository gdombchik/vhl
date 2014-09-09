=== Blog-in-Blog ===
Contributors: timhodson
Donate link: http://informationtakesover.co.uk/blog-in-blog-wordpress-plugin/
Tags: categories, blog, hide, cms
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.1.1

This plugin shows posts from a category on any page you like using shortcodes. Create multiple blogs within a blog using a category. Hode posts in a specific category from your homepage.


== Description ==

Blog-in-Blog allows you to use the Wordpress platform for it's CMS features, but still have a blog page on your site. Posts selected by category, post_type, tag or any combination thereof, can be used to feed the 'special' blog page, and can optionally be hidden from the home page.
You can have more than one category hidden from the homepage (not post_types or tags).

You can also use this plugin to show posts on the same page from different categories, post_types or tags, but in several different blocks and using different layout templates.

If you find this plugin useful (especially if it gets you out of a fix in a commercial setting), please feel free to leave feedback via the donate button.

I am grateful for those people who have already bought me a beer :) 

_Important:_ In previous versions of the Blog-in-Blog plugin you might have edited bib_post_template.tpl. If you are upgrading, we will copy this to a textbox so you can edit the template from the plugin page. The bib_post_template.pl file is no longer used and may vanish in a future release.

== Installation ==

How to install the plugin and get it working.

= Briefly: =

1. Upload the `blog-in-blog` directory to the `/wp-content/plugins/` directory (or install via wordpress plugins admin menu)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a shotcode such as `[blog_in_blog category_slug='my-category-slug' num=5]` to a new PAGE (not post) on your site.
1. Optionally, use the admin page to select which category(ies) to hide from the home page.
1. Therefore, any posts with the chosen category are shown only on your new page and not on the home page, so if you use Wordpress as a CMS, you now have a blog within a blog.

_NOTE:_ Don't copy any code from here and paste into the visual editor, as you will be copying some HTML too, and you may not want that. Use the HTML editor to paste you shortcode and make sure it is not wrapped in extra HTML.

= How it works: =

1. Add the shortcode in the body of a new PAGE (not post) to select a number of posts within a specified category.  This page with the shortcode becomes your 'blog in blog' page (you can of course call it what you will). i.e:

    `[blog_in_blog category_slug='my-category-slug' num=10]`

    Yuo must specify the `category_slug` . Optionally specify a number of posts, defaults to 10. There are lots of other parameters you can use (see below).
1. You can hide the pagination of the posts.

    `[blog_in_blog category_slug='my-category-slug' num=10 pagination=off]`
1. You can optionally hide specified (multiple) categories from the normal home page and RSS feeds. (There is an admin page for you to do this)
1. You can optionally specify a template to use for an instance of the blog-in-blog shortcode.

    `[blog_in_blog category_slug='my-category-slug' num=5 template="myfile.tpl"]`

    This template must exist in the `wp-content/uploads` directory or the `wp-content/plugins/blog-in-blog` directory.
1. You can customize some of the look and feel elements of the post display, including your own css style for the pagination, on the blog-in-blog admin page.


= Tips =

1. The category list in the blog-in-blog admin page ONLY shows categories with posts.  You need to create some content first!
1. You can specify the name of the template in the shortcode (applies to that shortcode instance only). First we look in the database for a user template, then we always look in `wp-content/uploads/` (or wherever you have set your uploads folder) for your template file first before looking in `wp-content/plugins/blog-in-blog` (your template will probably be lost when the plugin is upgraded).
1. Using the blog-in-blog shortcode on a page which is designated as a static shortcode will work, BUT pagination will not work as you expect it.  I know this, but have not been able to fix yet. It is all to do with the way that Wordpress builds it's URLs on the fly, and that is very complex!


= What shortcode options are there? =

_NOTE:_ Don't copy any code from here and paste into the visual editor, as you may be copying some HTML too, and you may not want that. Use the HTML editor to paste you shortcode and make sure it is not wrapped in extra HTML.

As a minimum you need the following shortcode:

    `[blog_in_blog category_slug='my-category-slug']`

or to save you typing `[blog_in_blog]` every time...

    `[bib category_slug='my-category-slug']`


*All shortcode paramaters:*

These shortcode parameters can be combined in any way you like (except `post_id`) to target specific posts:

* `category_id=<integer>` The ID of the category you wish to show
* `category_slug=<category_slug>` The slug of the category you wish to show
* `custom_post_type=<post_type>` Posts with a custom post type that you want to show
* `tag_slug=<tag_slug>` Posts of this tag slug will be shown. You can do OR (slug1,slug2,slug3) and AND (slug1+slug2+slug3)
* `author=<author id>` Posts from this author, identified by a numeric author id.
* `author_name=<author user_nicename>` Posts from this author, identified by their user nicename.
* `post_id=<a post id>` If specified only shows a single post. All other selection or sort parameters are ignored.

These parameters affect the pagination display:

* `num=<integer>` Number of posts per page. Defaults to 10 if not set
* `pagination=<on or off>` Defaults to on.

These parameters affect the order of the posts:

* `order_by=<a valid option>` Defaults to date. Valid options are those supported by [Template Tag query_posts()](http://codex.wordpress.org/Template_Tags/query_posts) so things like date, title, rand (for random). Overidden by `custom_order_by`
* `custom_order_by=<custom field>` Name a custom field to order by. If the field contains dates, they must be entered `YYYY-MM-DD` or sorting by date will not work. If you want the dates to show in the template and be formated, you can select the custom field to be formatted using the default date format in Wordpress. If set, overides `order_by`.
* `sort=<sort direction>` Sort (the direction to sort the order by field) can be one of the following values (brackets show direction):
    - Oldest first: `sort=oldest` (ASC)
    - Newest first: `sort=newest` (DESC).
    - Ascending: `sort=ascending` (ASC)
    - Descending: `sort=descending` (DESC)
    - Ascending: `sort=ASC`
    - Descending: `sort=DESC`
    - Default is always newest (DESC) first.

These parameters affect the look of your posts.

* `template=<template_name>` Specify a template name. First we assume that this is a named template in the database.  Then we look for a file in the following locations, in the order shown:
    1. Uploads directory: `WP_CONTENT_DIR/uploads/<your filename>` (or whever your uploads directory is)
    1. Plugin directiry: `WP_CONTENT_DIR/plugins/blog-in-blog/<your filename>`
* `thumbnail_size=<size>` For use with `%post_thumbnail% template tag. Where <size> is one of:
    - thumbnail
    - medium
    - large
    - NxN - Optionally specify a height/width. The longest side of the image will be as specified. (i.e. 134x134 or 182x182)



= What template tags are available? =

* `%post_title%` - The title of the post.
* `%post_permalink%` - The post permalink.
* `%post_id%` - The post id.
* `%post_author%` - The post author.
* `%post_author_avatar%` - The post author's avatar (uses wordpress avatar functionality).
* `%post_categories%` - The categories that the post belongs to.
* `%post_content%` - The content of the post.
* `%post_excerpt%` - The excerpt of the post. Either the excerpt if specifically defined or the `<!--more-->` tag in your content, or the default wordpress excerpt, in that order.
* `%post_comments%` - A comments link.
* `%post_thumbnail%` - The thumbnail of the wordpress featured image for the post. Will supply a full `<img>` tag.
* `%post_tags%` - A list of tags assigned to the post. Will have a link to the wordpress tag space for your site. Delimited by the text delimiter in your blog-in-blog options.
* `%post_date%` - The date of the post as defined by the format string in the admin page.
* `%post_day%` - The day of the month as 2 digits, i.e. `09` or `31`.
* `%post_dw%` - The day of the week short, i.e. `Mon`.
* `%post_dow%` - The day of the week long, i.e. `Monday`.
* `%post_mon%` - The month short, i.e. `Jan`.
* `%post_month%` - The month long, i.e. `January`.
* `%post_m%` - The month numeric leading zero, i.e. `01`.
* `%post_n%` - The month numeric, i.e. `1`.
* `%post_year%` - The year in 4 digits, i.e. `2009`.
* `%post_yr%` - The year in 2 digits, i.e. `09`.
* `%...%` - All custom fields attached to a post can be used in the template. Simply place your custom field name between percent (%) marks,


== Frequently Asked Questions ==

Writing this plugin is not the whole of my life. Therefore if you email, I may not get back to you straight away. 

Additionally the support forums on wordpress don't tell me when there is a new post, so there may be a delay in response.

= How do I get my posts displayed via blog-in-blog to look like the rest of my wacky theme? =

You need to use the template features of blog-in-blog to get your posts to show using the same HTML and CSS structure that your theme uses.  You will need to learn some CSS and HTML then update the template.

I cannot do this for you.

= There is a problem can you help? =

Yes, but only if you have already done the following and sent me the answers.

1. Send me a link to your site where i can see the problem live.
1. Switch to the default wordpress theme
1. Disable all plugins except for blog-in-blog. Does the problem still show?
1. If not, switch back to your theme.

    Does the problem show now? If so, tell me what the theme is.

1. Add other plugins back one by one. After each test to see if the problem is still there.

    Which plugin caused the conflict with blog-in-blog?

= Can I ask you a question? =

Yes, and I will even try to answer your question! If you are atempting to contact me with Skype Chat, I only accept contact resquests if it is obvious you are asking about this plugin (try not to look too much like spam!), so don't be offended if it seems I ignore you. Try an email instead :) .
And I do have a day job too...
[Tim Hodson](http://timhodson.com "Find me...")


= What Translations are available? =

Thanks to the folowing people for providing translations, and feel free to send me yours if you don't see your language listed here.

* Belarusian `be_BY` - [FatCow](http://www.fatcow.com)
* Dutch `nl_NL` - [Mark Strooker](http://ooxo.nl)
* German `de_DE` - Christian Schmitter
* Norwegian `no` - Geir A Granviken
* French `fr_FR` - Stef Walter

The .pot file was extensively rewritten as of version 1.0.3. You may wish to send me your new translations?

== Changelog ==

= 1.1.1 = 

* Fixed: Read more links for post excerpts are now fixed.
* Fixed: Better calculation of offset if hidefirst n posts is used.

= 1.1 =

* Fixed: `%post_excerpt%` should now be fixed and always displaying based on either excerpt, moretag or default in that order.
* Added: Additional css classnames to the prev/next links
* Added: bib-shortcode-in-post infinite loop protection.

= 1.0.9 =

* Added: `hidefirst` shortcode parameter to offset the first n posts in the query.
* Added: `author` and `author_name` selectors fpr posts by an author.
* Fixed: Bug where user templates were not being saved to the database.
* Fixed: Bug where Entity encoded chars in a template were decoded then causing problems on save.
* Fixed: Bug where excerpts were not shown.

= 1.0.8 =

* Fixed: (hopefully) issue with redeclaration of Markdown().

= 1.0.7 =

* Fixed: issues with single post shortcodes.

= 1.0.6 =

* Added: Tidied debug code: now shown in a textarea after the normal output of the plugin.
* Added: `apply_filter()` calls on the_title and the_author template tags.
* Added: User template creation within the database. You don't need to use files ever again!
* Added: You can now use `category_slug=<slug>` in a shortcode if you know the category slug.
* Added: You can now use `tag_slug=<slug>` in a shortcode if you know the tag slug.
* Added: You can now use `custom_post_type=<post_type>` in a shortcode if you know the post type name you want to use.
* Added: You can combine custom_post_type, category_id, category_slug and tag_slug in any combination to select the posts you want.
* Added: Wordpress theme template tag function `blog_in_blog()` added for direct use of the plugin in themes as well as posts/pages.
* Added: Further tidy up of the admin page javascript; now remembers which tab was visible when you last saved changes.

= 1.0.5 =

* Added: Option on Misc tab to turn off the javascript on the admin page, and tidying of HTML to go with it.
* Fixed: Looking for templates in non standard places.

= 1.0.4 =

* Added: Category hiding under Wordpress 3.1 is slightly different, both methods returned for backwards compatability.
* Fixed: POST_ID showing unecessarily. Don't you love debug code!
* Fixed: Markdown clashes with wpautop() `<br>` tags now correct.
* Fixed: We now look in whichever folder your uploads is set to.

= 1.0.3 =

* Added: Uninstallation now removes bib options from database.
* Added: Editing of template in the blog-in-blog admin page. Your existing default template will have been copied here when you upgraded
* Added: This readme.txt as a help tab on the admin page.
* Added: Lots of new explanation in readme.txt and admin page based on your support queries. (please use the new .pot file if you are a translation author)
* Added: French translation.
* Changed: The admin page has been revamped with a tabbed menu.
* Changed: File extension for Dutch translation becomes nl_NL.

= 1.0.2 =

* Added: German translation.

= 1.0.1 =

* Fixed: some minor bugs.

= 1.0 =

Finally released version 1.0!

* Added: Custom fields are now picked up as template tags that are available in the bib_post_template.tpl.
* Added: Sorting on a custom field is now possible. Will sort alpha/numeric or dates.
* Added: Registered `[bib]` as a shortcode to save you typing `[blog_in_blog]` every time!
* Added: `%post_thumbnail%' template tag for those wanting to use the Wordpress featured image functionality.

= 0.9.9.4 =

* Added: `post_id=` option for shortcode, will show a single post based on its post id.

= 0.9.9.3 =

* Fixed: Galleries will now be corectly shown in the output from Blog-in-Blog.

= 0.9.9.2 =

* Trying yet another fix for `apply_fiters()` issues.
* Now multiple `[blog_in_blog]` shortcodes on a single page are supported.

= 0.9.9.1 =

* Tried a different fix for `apply_fiters()` issues. Basically other plugins and themes were calling `the_content` and attempting to reapply the blog-in-blog shortcode, causing max nested loops errors or infinite loops.

= 0.9.9 =

* Fixed issue causing infinite loop with other plugins that call `appy_filters()` on `the_content` which will cause shortcode to be re-applied again and again.

= 0.9.8 =

* Added: `order_by` parameter to choose the field to sort on.
* Added: Debug option on the admin page, blog-in-blog will then dump stuff to your browser window. You can send enable this is you need help.
* FIxed: spacing of tags in `%post_tags%`.
* Updated: translation (pot) file.

= 0.9.7 =

* Added: `%post_tags%` template tag.
* Fixed: pagination now shows when there are more than one categories selected.

= 0.9.6 =

* Fixed: A ridiculously silly bug introduced while implementing the Settings API which meant you couldn't change/initially set the categories hidden from the home page!

= 0.9.5 =

* Added: `%post_excerpt%' tag (will only show anything if you actualy have an excerpt). blog-in-blog already recognises the `<!--more-->` quicktag.

= 0.9.4 =

* Updated: Admin page now uses the Wordpress Settings API.

= 0.9.3 =

* Added: Shortcode sort order option: `[blog_in_blog category_id=3 sort=newest ]` sort can be one of oldest first (ASC) or newest (DESC).  Default is always newest first.

= 0.9.2 =

* Fixed: overwriting of `bib_post_template.tpl`. It is still overwritten but when loading the template we look for the template file in `wp-content/uploads` first. So that's where you should put yours!
* Updated: explanation in the readme.txt

= 0.9 =

* Added: Admin page option to hide categories from feeds.
* Added: More template tags for use in the default template.
* Added: 'Potential overwrite' warning if you are using the default template file.
* Added: Hide page navigation option. (see installation notes)

= 0.8 =

* Added: Include user templates for different blog-in-blog pages. See "Installation" for details.
* Added: Author and Author Avatar are now available in the default template.
* Fixed: page navigation bug when permalinks are used.

= 0.7 = 

* Added: A category tag to the template so that if a post appears in other categories the categories are displayed.

= 0.6 =

* Added: i18n support

= 0.5 =

Further Bug fixes around initial installation: dealing with no categories and no posts to display.

= 0.4 =

* Added: Application of the `<!--more-->` tags to posts.
* Added: Hiding of multiple categories from home page, you can now have as many blog-in-blog pages as you like.
* Fixed: Bugs with empty arrays when blog-in-blog first installed.

= 0.3 =

* Added: Paging of posts if more posts than will fit on page

= 0.2 =

* Early point release with minor bugfixes
