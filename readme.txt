=== Post Views for Jetpack ===
Contributors: jeherve
Tags: Stats, Views, Post Views, Jetpack
Stable tag: 2.0.0
Requires at least: 6.4
Tested up to: 6.5

Display the number of views for each one of your posts, as recorded by Jetpack Stats.

== Description ==

Display the number of views for each one of your posts, as recorded by Jetpack Stats.

This is still a work in progress, and I would love to know what you'd like this plugin, where you would like to display those post views. [Open a new thread in the support forums](https://wordpress.org/support/plugin/post-views-for-jetpack) to let me know!

== Installation ==

1. Install the Jetpack plugin, and activate the Stats module.
2. Install the Post Views for Jetpack plugin via the WordPress.org plugin directory, or via your dashboard.
3. Activate the plugin.
4. Enjoy! :)

== FAQ ==

= There are currently 4 ways to use the plugin =

1. You can use the `[jp_post_view]` shortcode anywhere in your posts and pages to display the number of views.
2. You can use the "All Time Site Stats" widget to display how many views your site got since you started using Jetpack Stats.
3. You can use the shortcode in your theme files, like so: `<?php echo do_shortcode( '[jp_post_view]' ); ?>`. If you pick that option, I would recommend using a [child theme](https://developer.wordpress.org/themes/advanced-topics/child-themes/) instead of modifying your theme's files.
4. You can use a functionality plugin like [this one](https://wordpress.org/plugins/code-snippets/) to add a custom code snippet to your site without making changes to your theme. In that code snippet, you can decide on which pages the post views should be displayed. In [this example](https://gist.github.com/jeherve/6328c232f91977a6924805d93490c152), the counter will be displayed at the bottom of all posts, only on posts pages.

== Changelog ==

= 2.0.0 =
Release Date: February 2, 2024

* Update the plugin to rely on new Stats functionality introduced in Jetpack 13.0.
* Deprecate the `jp_post_views_cache_duration` filter. The plugin now fully relies on Jetpack's caching mechanism.

= 1.5.0 =
Release Date: February 9, 2023

* Add a new filter, `jp_post_views_cache_duration`, allowing you to change the duration of the local cache for the post views.

= 1.4.0 =
Release Date: December 6, 2022

* Ensure full compatibility with Jetpack version 11.5 and above.

= 1.3.0 =
Release Date: July 1, 2020

* Fully rely on WordPress.org translation packs for translations.

= 1.2.0 =
Release Date: April 2, 2019

* Ensure the number of views is internationalized properly.

= 1.1.0 =
Release Date: November 18, 2016

* Releasing to the WordPress.org plugin directory.

= 1.0.0 =

* Initial release.
