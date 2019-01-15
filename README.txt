=== Plugin Name ===
Contributors: boquiabierto
Donate link: http://wherever.grell.es
Tags: custom content, page builder
Requires at least: 4.3
Tested up to: 4.9.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Put reusable content wherever you want.

=== Description ===

This WordPress plugin will enable content distribution across your site in a post-like way with the power of a flexible set of rules and an expandable set of places which tell WordPress where to show the Wherever Content.


=== Changelog ===

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
