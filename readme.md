
## About
This WordPress plugin will enable content distribution across your site in a post-like way with the power of a flexible set of rules and an expandable set of places which tell WordPress where to show the Wherever Content on your site.

## Installation
**Option A:** install through the WordPress “Add new” plugin interface:
1. [Download](https://github.com/unfresco/wherever-content/archive/master.zip) the zip file from this repo
2. Upload through the interface
3. Activate

**Option B:** install through the [github-updater](https://github.com/afragen/github-updater) plugin searching for “wherever-content” by unfresco


## Quickstart
1. Add a new Wherever Content from the left admin menu (under Posts). Edit it like a normal post.
2. Edit it’s rules and places in the configuration panel below WP’s richtext/block editor. 
3. Publish!

## Custom Wherever Places
By default you can decide Wherever Contents to show in 3 places:

- before, instead and after **the content** of other post and pages (see the `the_content()` function in your theme).
- before the **footer** (see the `get_footer()` function in your theme).
- before the **sidebar** (see the `get_sidebar()` function in your theme).

But you can add **custom places** to your theme wherever you want by registering new places in your themes function.php (like registering menus or sidebars) and declaring in your theme files where the custom places should display Wherever Content.

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
- The theme must contain the `wp_head()` function.

## Why
WordPress’s native solution for static and/or reusable contents are – beside the specific theme-options – sidebars and widgets. Managing them is very different to post and pages. Not only the interface is completely different, the users actually need to know and understand the current theme’s internal structure: when and where it uses it’s sidebars, footers, navigations, etc. Changing a theme means mostly to learn and rebuild it’s widgets and widget areas. Not to mention repeating same widgets across different sidebars. 

Wherever Content on the other hand is simple to understand: manage content in a post-like way with a configuration that relates each of them to your site’s contents and places.

Changing a banner spread across the blog, header images on a category archive page, footer logos, etc. is much easier in a searchable Wherever Content post-list than in the usual multiple sidebar widget-hell. 

More specifically, useful and tested – and the why everything started – it enables the use of siteorigin’s [page builder](https://wordpress.org/plugins/siteorigin-panels/) inside a Wherever Content post and to display it wherever you want. This means f.e. no more duplicated layouts and site-wide page-builder built footers.

##  Changelog
**3.0.0**
- Update carbon-fields to version 3+

**2.2.1**
- Fix first-time setup of default places to happen on creating a new post 

**2.2.0**
- Optimize queries through transients
- Optimize requests with native function calls instead of expensive carbon field functions
- Optimize by reducing hooks execution to only when it is needed
- Tweaks on dependency management
- Add editing option for disabeling wpautop on wherever post content
- Impove polylang compatibility

**2.1.12**
- match release tag to release asset spelling for it to work with github-updater

**2.1.11**
- release with included submodules as part of the github release assets (see: https://github.com/afragen/github-updater/issues/349)

**2.1.10**
- fix submodule config

**2.1.9**
- Changes in dependency loading

**2.1.8**
- migration from boquiabierto to unfresco user also in github-updater config

**2.1.7**
- Fix Polylang plugin compatibility when taxonomy sync is enabled
- Improved handling of DB options interaction

**2.1.6**
- Fix carbon fields initialization errors

**2.1.5**
- Filter based rules setup in admin and public
- New template type location rules with check for 404, authors and search
- Fix Blog (is_home()) template type  

**2.1.4**
- UI guidance editing rules (switch to JS based)

**2.1.3**
- UI guidance editing places

**2.1.2**
- code refactoring
- place select options based on current registered places

**2.1.1**
- code refactoring
- localisation update for spanish

**2.1.0**
- Gutenberg compatible
- New settings page with options for disabling Gutenberg and SiteOrigin page builder optimization

**2.0.0**
- Distribution now includes Carbon fields framework (v2.2.0). If you depend on Carbon Fields 1.6 download the 1.0.12 version.
- Improved default setup of rules and places with guidance

**1.0.12**
- execution optimization

**1.0.11**
- various query optimizations

**1.0.10**
- localisation of default place terms
- new menu icon

**1.0.9**
- Fix Wherever Content CSS classes on wrapper tags
- Added filter hooks for CSS classes on the wrapper tag:
    - 'wherever_content_wrapper_classes' applies to all wrapper tags,
    - 'wherever_content_wrapper_classes_place_[place]' applies only to containers of specified place (f.e. content)
    - 'wherever_content_wrapper_classes_placement_[placement]' applies only to containers of specified placement (before, instead or after)
    - 'wherever_content_wrapper_classes_id_' applies only to containers of specified Wherever post id
- Contents are now processed on the wp_head hook which trades dependency on site-origin pane’s script and styles for the theme-dependent but usually available wp_head() theme function.
- Update spanish translation

**1.0.8**
- Fix carbon field hooks (carbon fields 1.5 won’t initiate on init any more)
- Add spanish translation

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
