#Wherever Content
### About
WordPress’s native solution for static and/or reusable contents are – beside the specific theme-options – sidebars and widgets. Managing them is very different to post and pages. Not only the interface is completely different, the users actually need to know and understand the current theme stucture: when and where it uses it’s sidebars. Changing a theme means mostly to learn and rebuild it’s sidebars and widgets. Not to mention repeating same widgets accross different sidebars. 

Wherever Content on the other hand is simple to understand: manage content in a post-like way with an extra info that relates each of them to other site contents and places.

Think of it as a **post-like & content-first** distribution approach whereas WordPress’s widgets-in-sidebars requires to think in a **post-unlike & content-last** distribution manner. 

Changing a banner spread across the blog, header images on a category archive page, footer logos, etc. is much easier in a searchable Wherever Content post-list than in a multiple sidebar widget-hell. 

More specifically and useful – and the why everything started – it enables the use of siteorigin’s [page builder](https://wordpress.org/plugins/siteorigin-panels/) inside a Wherever Content post and to display it wherever you want. This means f.e. no more duplicated layouts and site-wide page-builder built footers.

Please check it out and give your feedback. We use it for our clients and will publish it to the WP plugin directory if you like it. 

### Using it

1. Add a new Wherever Content from the left admin menu (under Posts). Edit it like a normal post.
2. Edit it’s rules and places in the configuration panel below it’s richtext content editor. It’s easy but extensible and it specifies where (and where not) to show the content. 
3. Publish!

### Custom Wherever Places

By default you can put Wherever Contents in 3 places:

- before, instead and after **the content** of other post and pages.
- before the **footer** (see the `get_footer()` function in your theme) 
- before the **sidebar** (see the `get_sidebar()` function in your theme)

But you can add **custom places** to your theme wherever you want by registering new places in your theme (like registering menus or sidebars) and declaring where the custom places should display Wherever Contents for that specific place.

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

**1.0.7**
- Fix hooks
- [Polylang](https://wordpress.org/plugins/polylang/) compatibility

**1.0.6**
- New archive page_type rule

**1.0.5**
- fixes cases where same content in different places is injected multiple times
- fixes loading of siteorigin styles and scripts in admin and frontend

**1.0.4**
- New page parent rule
- Hierachical indeted page selectors

**1.0.3**
- Fixes for contents injected into footer, sidebar and custom places
- Conditional logic for places showing placements only for Content
- Drop support for Title as default place. Too many calls (in meta-tags, `wp_nav_menu()` and `the_title()`).
- Code cleanup
- Added Github Updater plugin compatibility 

**1.0.2**
Does not include Carbon Fields plugin. Admin notice for the user to install and activate.

**1.0.1**
Initial published version


