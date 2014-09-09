=== Easing Slider "Lite"  ===
Homepage: http://easingslider.com
Contributors: MatthewRuddy
Tags: slideshow, slider, slides, slide, gallery, images, image, responsive, mobile, jquery, javascript, featured, content
Requires at least: 3.5
Tested up to: 3.9
Stable tag: 2.1.4.2

Easing Slider "Lite" is an easy to use slider plugin. Simple and lightweight, built with native WordPress functionality.

== Description ==

Easing Slider "Lite" is an extremely easy to use slider plugin for WordPress. It is built to be lightweight and simple, with absolutely no bloat. It comes with many great features, some of which include:

* Fully responsive & mobile ready
* Lightweight, weighing just 16kb minified including styling
* Bulk image uploading, integrated with new WordPress Media Library
* CSS3 transitions for ultra smooth playback
* Navigation arrows & pagination
* Preloading functionality on page load
* A visual editor for customizing basic styling
* Developer friendly with built-in Javascript events
* Lots of actions & filters for custom functionality

<a href="http://easingslider.com/upgrade-to-pro">Need more sliders? Looking for more features? Upgrade Easing Slider "Pro" here.</a>

Throughly tested on iPhone, iPad and multiple Android devices, Easing Slider "Lite" is the perfect solution for mobile sliders. We've used CSS3 transitions to ensure ultra smooth transitions on all devices. The codebase is also extremely light (just 16kb), which is perfect for those concerned about page loading times.

We've also integrated the new WordPress Media Library workflow to provide a better media management experience. Bulk uploading images to your slider is now easy, requiring just a few clicks.

Last but not least, we've left plenty of opportunity for custom plugin modifications using the WordPress Action & Filter APIs. You can completely create your own external functionality, or modify the plugin to integrate perfectly with your current theme. Awesome!

== Installation ==

= Display a slider =
To display the slider, you can use any of the following methods.

**In a post/page:**
Simply insert the shortcode below into the post/page to display the slider:

`[easingsliderlite]`

**Function in template files (via php):**
To insert the slider into your theme, add the following code to the appropriate theme file:

`<?php if ( function_exists( "easingsliderlite" ) ) { easingsliderlite(); } ?>`

== Frequently Asked Questions ==

= I've upgraded from v1.x and my slides have disappeared. =

Don't sweat! Simply navigate to the <strong>"Edit Slideshow"</strong> admin panel and click the <strong>"Import my Easing Slider v1.x settings"</strong> button. That's it! If you run into any trouble, don't hesitate to open a support topic, or have a look at the screencast below.

<a href="http://cl.ly/1V0V411I1V09">Upgrading from v1.x</a>

= My slider continually loads. What's wrong? =

This can often be caused by a jQuery conflict. Many plugins don't load jQuery correctly and as a result break the plugins that do.

Firstly, disable all of the other plugins you have activated (or as many as you can). If the issue persists, with just Easing Slider "Lite" active, it is more than likely a conflict with the theme.

If the slider works when it is the only plugin active, you're experiencing a plugin conflict. Carefully enable each plugin, one-by-one, checking the slider each time. Keep doing this until you activate the plugin that breaks the slider.

After you've taken these two steps, make a support topic and we will get back to you as soon as you can. Otherwise, feel free to contact the developer(s) of the conflict plugin/theme also. They should also be able to provide you with assistance.

= How do I edit a slide's settings? =

This is easy. When viewing the <strong>"Edit Slideshow"</strong> Easing Slider "Lite" admin panel, you should be able to see your slider images. To edit the settings of an individual slide, simply click it and its settings will appear in a modal window. Simple!

== Screenshots ==

1. The integrated Media Uploader. Use it to add images to your sliders one at a time, or in bulk.
2. "Edit Slideshow" panel. Set your various slider settings here.
3. "Settings" panel. Options for script & style loading, and image resizing features.
4. The slider, in all its glory! Nice and clean, but easy to re-style if needed.
5. Simply click a slide to edit its individual settings. This is the panel you will see.

== Changelog ==

= 2.1.4.2 =
* Fixed widget title filter bug.
* Fixed admin menu button icon CSS margin.
* Updated adverts to reflect site changes.

= 2.1.4.1 =
* Added dashicon to top-level menu.
* Fixed admin menu styling bug when Easing Slider “Pro” was active and using WordPress v3.8+.
* Updated plugin translations .pot file.

= 2.1.4 =
* Fixed bug that broke media uploader in WordPress v3.9.
* Fixed bug that prevented "Customize" panel from loading in WordPress v3.9.

= 2.1.3 =
* Plugin is now fully styled to fit thew new WordPress v3.8+ administration area.
* Fixed a bug that could cause "Add Images" to fail if the selected image doesn't have a thumbnail.

= 2.1.2 =
* Added accordion CSS to fix WordPress 3.6 bugs.
* Fixed clearing and border edge case CSS issues
* Made preloading functionality more reliable.
* Added missing translations.
* Improved legacy functionality class.

= 2.1.1 =
* Fixed all IE bugs. Now working perfectly in IE7+.
* Separated legacy code into its own separate file.
* Added languages file with .pot for translating the plugin.
* Fixed some textual mistakes and commenting errors.
* Fixed backface visbility bugs in Webkit browsers.
* Improved reliability of responsive functionality on plugin initialization.
* Fixed escaping issues related to slashes and quotation marks in strings.
* Improved admin notices functionality: now using WordPress native hooks.

= 2.1 =
* Added "Customize" panel which allows you to make basic slider styling alterations using a new visual editor.
* Reconfigured preloading functionality to fix a bug.
* Added title attribute functionality to images.
* Re-added functionality for script and style registration, making them easier enqueue.
* Fixed backbone templating issues that would render admin area unusable for some users.

= 2.0.1.3 =
* Made some alterations to give a better success rate when upgrading from v1.x.
* Added options to manually import v1.x options, instead of automatically (which often failed and caused major problems).
* Fixed IE7 bugs
* Reconfigured admin script & style functions to hopefully resolve some issues that were preventing them from loading for some users (inexplicably).
* Disable image resizing functionality on activation due to some rare unknown issues. Feel free to use it if you like!

= 2.0.1.2 =
* Fixed backwards compatibility issues with older versions of jQuery

= 2.0.1.1 =
* Fixed script cross origin bug.

= 2.0.1 =
* Fixed bugs with 2.0 release. Reverted name from Riva Slider "Lite" back to Easing Slider (transition did not go as hoped, sorry).
* Fixed CSS rendering issues some users were experiencing.
* Updated plugin upgrade procedures

= 2.0 =
* Too many updates to count. Completely revamped plugin from a clean slate. Hope you enjoy using it as much as I did creating it!

= 1.2.1 =
* Fixed: jQuery re-registering has been removed. Wordpress version of jQuery now used.
* Added: Notification for forthcoming major update.

= 1.2 =
* Changed: Adverts from Premium Slider to Easing Slider Pro.
* Changed: When activated, plugin will now default to 'Custom Images'
* Prepared plugin for major update (coming soon).

= 1.1.9 =
* Fixed: Plugin inconsistancies and Javascript mistakes.
* Changed: Plugin now only deletes slider when uninstalled (rather than de-activated).

= 1.1.8 =
* Fixed: IE9 issues. Slider is now fully functional in IE9.

= 1.1.7 =
* Added: Option to enable or disable jQuery.
* Fixed: Issue with slider appearing above post content when using shortcode.

= 1.1.6 =
* Added: Premium Slider notice.
* Added: Icon to heading on Admin options.

= 1.1.5 =
* Fixed: Mix up between autoPlay & transitionSpeed values in previous versions.

= 1.1.4 =
* Fixed: Added !important to padding & margin values of 0 to make sure slider doesn't inherit theme's css values.

= 1.1.3 =
* Fixed: CSS glitch in admin area.

= 1.1.2 =
* Fixed: Bug with previous version.

= 1.1.1 =
* Added: Option to disable permalinks in 'slider settings'.

= 1.1.0 =
* Added: Ability to add links to images. Images sourced from custom fields link to their respective post.
* Fixed: Edited script.js issue with fade animation.

= 1.0.3 =
* Added: paddingTop & paddingRight settings.
* Fixed: Bottom padding issue when shadow is enabled.
* Changed: Tab name 'Plugin Settings' to 'Usage Settings'.

= 1.0.2 =
* Added: Fade transition. Compatibility problems fixed.
* Fixed: Preloader margin-top with IE only. Used IE hack to add 1 pixel to the top margin to make preloader appear aligned.

= 1.0.1 =
* Fixed: Issues with 'Thematic' theme.
* Fixed: jQuery into noConflict mode to avoid conflictions with various other jQuery plugins.
* Fixed: Parse errors in CSS file.
* Fixed: jQuery version number.
* Removed: Fade transition effect due to compatibility problems & issue with certain themes.