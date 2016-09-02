#Wherever Content
### About
A WordPress plugin that allows you to **put reusable content wherever you want**. 
#### But we already have widgets for this! 
Yes, widgets are very nice indeed but a Wherever Content enables you a different and more powerful way to do more tapping into WP’s native post & page distribution manners and content creation capabilities.

Conceptually, it’s an easy to understand **post-like & content-first** distribution approach whereas WP’s widgets-in-sidebars requires you to think in a **post-unlike & content-last** distribution manner. 

Make it a thing of the past where you (or your clients) didn’t knew where and how to change that banner spread across the blog, header image a category archive page, footer logos, etc. One, content searchable Wherever Content post list. 

More specifically and useful (and the why everything started) it enables the use of siteorigin’s [page builder](https://wordpress.org/plugins/siteorigin-panels/) inside a Wherever Content post and to display it wherever you want. No more duplicated layouts! Site-wide page-builder built footers!

Please check it out and give your feedback. We use it for our clients and will publish it to the WP plugin directory if you like it. 

### Using it

1. Add a new Wherever Content from the left admin menu (under Posts). Edit it like a normal post.
2. Edit it’s rules and places in the configuration panel below it’s richtext content editor. It’s easy but extensible and it specifies where (and where not) to show the content. 
3. Publish!

### Custom Wherever Places

By default you can put Wherever Contents in 4 places:

- before, instead and after **the content** of other post and pages.
- before, instead and after **the title** of other post and pages.
- before the **footer** (see get_footer() function in your theme) 
- before the **sidebar** (see get_sidebar() function in your theme)

But you are invited to add **custom places** to your theme wherever you want by registering new places in your theme (like registering menus or sidebars) and declaring where the custom places should display Wherever Contents for that specific place.

#### Registering Custom Places for your theme

1. Open your themes functions.php
2. On an 'init' action register as many places you want with `register_wherever_places( $arguments )` being $arguments an array of arrays, each with the name and a slug for the custom place:

```php
function my_custom_wherever_places() {
	
	/* 
	 * Check if Wherever plugin is activated 
	 * and register_wherever_places() function exists
	 */
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

add_action( 'init', 'my_custom_wherever_places' );
```


#### Specifying the place of the custom place in your theme

1.	Open the theme file you want to place Wherever Contents. 
2.	Use `<?php do_action('wherever_place', '{slug}' ); ?>`  to print out the Wherever content(s) at that place. 

## Example

A common and useful example would be to build a multi-column footer section as a Wherever Content and to display it after the content (or in the footer) of all pages but not on that nice splash screen type front page!

## Requirements

Wherever Content depends on the [Carbon Fields](https://wordpress.org/plugins/carbon-fields/) plugin. Install and activate, if not, the plugin will advice you with a notice to do so.

## Recommendation
Further on we recommend to install siteorigin’s [page builder](https://wordpress.org/plugins/siteorigin-panels/). The Wherever Content plugin has no direct relation nor affiliation to that plugin but we use it a lot and are committed to maintain compatibility.


## Installation

1. Upload the “wherever” folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


##  Changelog

**1.0.2**
Does not include Carbon Fields plugin. Admin notice for the user to install and activate.

**1.0.1**
Initial published version


