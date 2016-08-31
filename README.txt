=== Plugin Name ===
Contributors: boquiabierto
Donate link: http://wherever.grell.es
Tags: custom content, page builder
Requires at least: 4.3
Tested up to: 4.5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Put reusable content wherever you want.

== Description ==

The Wherever Content plugin has been developed to enhance the widget/sidebar solution of WordPress to distribute content through a theme/site. 
It solves the problem of putting the same content in many different places of your site enableling full post content capabilities for each content.
This includes the capability to use f.e. siteorigin’s [page builder](https://wordpress.org/plugins/siteorigin-panels/) inside a wherever content post and display it wherever you want.

== How it works ==


== Custom Wherever Places ==

By default you can put Wherever Contents:
*	before, instead and after the content of other post and pages
*	into the footer and 
*	above the sidebar

But more advanced you can add custom places to your theme wherever you want by registering new places in your theme and declaring where the custom places should display Wherever Contents for that specific place.

= Registering Custom Places for your theme =
1. Open your themes functions.php
2. On the 'init' action register as many places you want with `register_wherever_places( $arguments )` beeing $arguments an array of arrays, each with the name and a slug for the custom place:
`
function my_custom_wherever_places(){
	
	// Check Wherever plugin is activated and register_wherever_places() function exists
	if ( !function_exists( 'register_wherever_places' ) )
		return;
	
	// Register Custom Wherever Places for this theme			
	register_wherever_places( array(
		array(
			'name' => 'Your Place Name',
			'slug' => 'your-place-slug'
		),
		array(
			'name' => 'Your second Place Name',
			'slug' => 'your-second-place-slug'
		)
	) );
	
}
add_action('init', 'my_custom_wherever_places' );
`

= Specifying the place of the custom place in your theme =

1.	Open the theme file you want to place Wherever Contents. 
2.	Use `<?php do_action('wherever_place', 'place-slug' ); ?>` in your code


== Examples ==

A usual and useful example would be to build a multi-column footer section as a Wherever Content and to display it after the content of all pages. 



== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Installation ==

1. Upload the “wherever” folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 1.0.1 =
Initial published version


== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`