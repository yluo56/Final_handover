=== Plugin Name ===
Contributors: randytayler
Donate link: http://drawblog.com/
Tags: comments, draw, doodle, sketch, drawblog
Requires at least: 3.0.1
Tested up to: 3.5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lets commenters add a drawing or doodle on a picture from a given post.

== Description ==

DrawBlog lets those who comment on your blog add a quick drawing to accompany their comment. They can
select colors or pen widths, then use the mouse to doodle a picture on a small canvas. 

Additionally, they can choose an image from your blog post and copy it to the canvas, and doodle on 
top of it. 

The height and width of the canvas is editable in the admin settings, as is some of the wording for 
the plugin (like the link that toggles the canvas on and off).

If users choose, they can uncheck the box that says "Include this drawing with my comment", and the 
picture won't be saved when they submit their comment.

NOTE: DrawBlog is not yet compatible with Jetpack's comments module, nor with Disqus. Workin' on it.

== Installation ==

1. Upload the contents of the "drawblog" directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in your WordPress admin area.

That's it. It automatically hooks into the `comment_form` hook. If that hook is not used in your
theme, you can edit `drawblog.php` to add the DrawBlog area where you'd like.

== Frequently Asked Questions ==

= Can you undo? =

No. That would be extremely tricky. Maybe later.

= Can you erase? =

Sort of. The eraser button puts you in eraser mode, with the eraser size based on the same width
buttons above. But all you're actually doing is painting a white circle on top of your art.

= It's not showing the images from my post! What's up with that? =

You may need to manually set the name of the class your theme uses to designate a post. See the advanced
options in the admin area.

= The cursor isn't aligned right with the pencil - when I draw it's not where it should be. =

I've seen this happen in Firefox if you're logged in as an admin, but there may be other conditions
where it occurs, especially in the myriad themes that exist. Is it happening to your users, or only
to a logged-in administrator? Maybe it's nothing to worry about. Otherwise, email me.

= It's broken! Help! =

Happy to! I hope to have some help forums up at [DrawBlog.com](http://drawblog.com) soon, but in
the meantime you can just email me directly at [drawblog@randytayler.com](mailto:drawblog@randytayler.com).

== Screenshots ==

1. The comment box, with the DrawBlog link below it. (screenshot-1.jpg).
2. The DrawBlog area expanded after clicking on the link (screenshot-2.jpg).
3. The canvas, after clicking on an image from the post -- my daughter, circa 2010 -- and doodling
on it (screenshot-3.jpg).

== Changelog ==
= 0.90 =
* Added ability to have default images for commenters and posts
* Added ability to show the canvas by default instead of needing to click a link

= 0.82 =
* Added ability to draw a picture when creating a post.

= 0.81 =
* Tightened security
* Added a debug information generation form in the plugin settings
* Added option to always show canvas
* Bug fix - will now let you save settings despite not having an API key

= 0.80 =
* Better support - read, *actual* support - for mobile browsers (premium only)
* Better customizability of various fields.

= 0.78 =
* Touch (mobile) support for premium users
* Faster drawing for Firefox users

= 0.77 =
* Fix to bug where post content class wasn't saving from admin area
* Added default post content class for Custom-Community theme

= 0.76 =
* Fix to bug preventing forms from submitting if a picture hadn't been drawn

= 0.75 =
* Major fix to directory structure. Upgrading DrawBlog deleted the old directory, which included the 
images folder, so all images would be lost. Going forward this shouldn't happen.

= 0.7 =
* Added pencil/eraser mode
* Fixed Firefox bug where it couldn't draw

= 0.62 =
* Fix to problem when WP installed in other than root directory

= 0.61 =
* Correction - pulling out of folder. Silly goose.

= 0.6 =
* Fixed bug where initial images directory wasn't present
* Added passing of domain name when verifying authorization/api key, to validate the key is coming from
a valid domain

= 0.5 =
* Initial WordPress submission.

== Upgrade Notice ==
= 0.90 =
* Added ability to have default images for commenters and posts
* Added ability to show the canvas by default instead of needing to click a link

= 0.82 =
* Added ability to draw a picture when creating a post.

= 0.81 =
* Tightened security
* Added a debug information generation form in the plugin settings
* Added option to always show canvas
* Bug fix - will now let you save settings despite not having an API key

= 0.80 =
* Better support - read, *actual* support - for mobile browsers (premium only)
* Better customizability of various fields.

= 0.78 =
* Touch (mobile) support for premium users
* Faster drawing for Firefox users

= 0.77 =
* Fix to bug where post content class wasn't saving from admin area
* Added default post content class for Custom-Community theme

= 0.76 =
* Fix to bug preventing forms from submitting if a picture hadn't been drawn

= 0.75 =
* Major fix to directory structure. Upgrading DrawBlog deleted the old directory, which included the 
images folder, so all images would be lost. Going forward this shouldn't happen.

= 0.7 =
* Added pencil/eraser mode
* Fixed Firefox bug where it couldn't draw

= 0.62 =
* Fix to problem when WP installed in other than root directory

= 0.61 =
* Release correction

= 0.6 =
* Initial Beta release

= 0.5 =
* Initial WordPress submission

== Premium users ==

I hope to add a "premium" version for a small fee. For more information, visit [DrawBlog.com](http://drawblog.com), 
or email me at [drawblog@randytayler.com](mailto:drawblog@randytayler.com).
