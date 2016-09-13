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

=== Description ===

WordPress’s native solution for static and/or reusable contents are – beside the specific theme-options – sidebars and widgets. Managing them is very different to post and pages. Not only the interface is completely different, the users actually need to know and understand the current theme stucture: when and where it uses it’s sidebars. Changing a theme means mostly to learn and rebuild it’s sidebars and widgets. Not to mention repeating same widgets accross different sidebars. 

Wherever Content on the other hand is simple to understand: manage content in a post-like way with an extra info that relates each of them to other site contents and places.

Think of it as a **post-like & content-first** distribution approach whereas WordPress’s widgets-in-sidebars requires to think in a **post-unlike & content-last** distribution manner.

More on the (github repository)[https://github.com/boquiabierto/wherever-content].


=== Changelog ===

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

