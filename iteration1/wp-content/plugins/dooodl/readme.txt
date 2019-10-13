=== Dooodl ===
Contributors: noCreativity
Tags: doodle, doodles, guestbook, drawing, Flash, sidebar, images,  fun, paint, creative
Requires at least: 2.7
Tested up to: 4.4
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2PMDN27RRQ9CS
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Dooodl is a fun plugin for your blog that allows your visitors to draw a little doodle and save it to your sidebar together with a little note. 

== Description ==

Demo: See a live version of this plugin in the sidebar of this site: [noCreativity.com](http://nocreativity.com/blog/ "noCreativity.com")

Dooodl is a fun plugin for your blog that allows your visitors to draw a little doodle and save it to your sidebar together with a little note. It's a bit like a guestbook but less boring and more visual aka more fun!

Your visitors will view the latest Dooodl (image, author and title) in the sidebar and will be able to view all others using the Dooodl History Viewer or add one themselves using the Dooodl Creator.

The result? You'll be able to view what people drew on your sidebar over time. It's fun!

Features:

1.  Latest Dooodl is shown as a Widget in the Sidebar along with the author's name, the Dooodl-title and links to the Dooodle Creator and Viewer
2.  Cool Flash viewer to see all of the Dooodls visitors have created.
3.  If the user has no valid Flash Player (iPhone/iPad users) or Javascript disabled, he/she is presented a basic HTML version of the viewer.
4.  A small Flash Dooodl Creator to allow your visitors to draw anything they like.
5.  Highly customizable widget template: Decide how the sidebare widget looks using basic template tags.
6.  Moderation possible: If you would rather see the new Dooodls before they appear on the site, and approve them: There's a checkbox just for that in the settings.
7.  Administration panel for all Dooodls. This enables you to delete Dooodl which aren't acceptable to be shown on the site.
8.  You can even get emails with quick delete/approve links whenever somebody submits a Dooodl.
9.  You can embed the creator and the gallery in a page or post using shortcodes. 

== Installation ==

1. Make sure to first backup your database.
2. Upload the `Dooodl` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the 'Dooodl'-widget to your sidebar or use the shortcodes to embed the creator/gallery in a page or post of your liking.
5. You're set!

== Frequently Asked Questions ==

= I don't like Flash. Do I have to use the Flash gallery viewer? =

No, there's a setting that allows you to disable the Flash gallery and just use the basic HTML version.

= Can people surfing on tablets also create Dooodls on my site? =

Yes, ever since February 2014, there’s an HTML5 version of the creator which should work on all modern browsers. Just make sure you enable the creator V2 if you haven’t already!

= Can I view a live demo somewhere? =

Yes you can! Head over to my blog and see it for yourself: [noCreativity.com](http://nocreativity.com/blog/ "View a live demo of Dooodle in the footer") 

= My Wordpress Theme doesn’t support widgets. Can I still use this plugin? =

Yes you can! At least if you’re ok with opening up some of your theme files and adding a bit of code to where you want the widget to appear. Adding the following where you want the Dooodl-widget to appear should get you going: `if (function_exists("Dooodl_widget")) Dooodl_widget();`

= I would like to embed the creator and/or the gallery in a page or a post instead of linking to them via the widget. Is that possible? =

Yes, it is! You can use these shortcodes to embed either the creator and/or the gallery in a page or a post. 
Simple: `[dooodl_creator]` and `[doood_gallery]`
You can use the width, height and css attributes to have more control over the looks.
Full featured demo: `[dooodl_creator width=“800” height=“600” css=“float:left; margin-top:20px”]` and `[dooodl_gallery width=“800” height=“600” css=“float:left; margin-top:20px”]`

== Screenshots ==

1. This is what the widget in the sidebar looks like. You can make it look any way you want. 
2. The Flash Dooodl History viewer (using Shadowbox to view it as a dialog on your current page).
3. The HTML Dooodl History viewer (using Shadowbox to view it as a dialog on your current page).
4. Viewing a Dooodl in the Flash Dooodl History Viewer.
5. Viewing the message your visitors wrote when they submitted the Dooodl.
6. The Dooodl Creator (using Shadowbox to view it as a dialog on your current page).
7. The form before submitting your image. All fields are optional.
8. The settings page.
9. The Moderation/Management page.
10. The customization page.
11. The Dooodl Dashboard Widget.

== Changelog ==

= 2.0.10 =
* Cleaned up some jQuery setups from the first versions of the plugin. This resolves frontend-plugin interference users were reporting.

= 2.0.9 =
* Fixed a code style issue where older versions of PHP would encounter syntax errors

= 2.0.8 =
* Fixed a bug where mails would no longer be sent after a new Dooodl is published

= 2.0.7 =
* Changed a setting in the migration manager where the batch feature would update 10 Dooodls at a time but seen as some servers don't handle this well, I reduced that number to 1 Dooodl at a time. 

= 2.0.6 =
* Fixed a bug where the Doodls upload folder would not be created and thus the image would be lost upon save.

= 2.0.5 =
* Removed one more short-opening PHP tag that would cause 'unexpected file endings' on some servers.

= 2.0.4 =
* Removed a few short-opening PHP tags that would cause 'unexpected file endings' on some servers.

= 2.0.3 =
* Fixed a bug where you wouldn't be able to edit Dooodl's using the 'Edit post' UI

= 2.0.2 =
* Added a corrected version of the update checker. Plugins that update from 1.x versions should now be redirected to the Dooodl Manager immediately. This should be fine now.
* Added Dashboard Widget information and screenshot

= 2.0.1 =
* Added version checker upon plugin startup as the register-activation hook is no longer fired when using automatic updates.

= 2.0.0 =
* All new WP-Admin Dooodl-management (Powered by Redux and ACF)
* Dooodl migration manager (from old versions of Dooodl to Dooodl 2.0)
* Dooodl is now a post-type in WordPress. Lots of new possibilities for the future!
* The gallery and creator have their own URL's and no longer guide your visitors to your plugin folder
* The Dooodl plugin no longer executes SQL statements directly. One less security threat possible!
* All strings have been implemented using the WordPress translation engine: You can now localize Dooodl!
* The widget has been updated so you can choose wether you want to add a title to the widget or not.
* Made Dooodl HTTP/S unaware.
* Added a WordPress Dashboard Widget

= 1.3.0BETA =
* Added shortcodes to add the creator and the gallery in a page/post. Check out the FAQ to find out how to use them.

= 1.2.3BETA =
* Fixed a bug in the gallery where the creator link would not point to the right directory.

= 1.2.2BETA =
* Fixed a bug where a duplicate function gets loaded and blocks new Dooodls from being saved.

= 1.2.1BETA =
* Fixed a bug where the email notification would not have the new Dooodl attached to it.

= 1.2.0BETA =
* Fixed plenty of minor AMFPHP bugs.
* Built an HTML5 version of the creator
* Added the option to pick the V2 creator or revert back to the V1.
* Necessary update changes in the file structure.

= 1.1.4 =
* Fixed a bug where the intro-text color in the viewer doesn't correctly work.

= 1.1.3 =
* Replaced short opening PHP tags by full PHP tags and added closing tags everywhere for server compatibility.
* Fixed a bug where new installations of the plugin would show an empty widget template by default.

= 1.1.2 =
* Fixed the 'dooodls aren't saved' bug that has been tormenting many users for years. Thanks Fischi for the help!

= 1.1.1 =
* Fixed a bug where Dooodls in the moderation queue would show up in the HTML gallery

= 1.1.0 =
* Lot's of new features
* Moderation is now possible (dooodls have to be approved before they're shown in the sidebar)
* Admin panel to moderate new Dooodls
* Emails upon new Dooodl submission
* Customizable widget look 
* Customizable look of the Flash and HTML Gallery
* Choose between Flash gallery or HTML version
* Added endless scrolling to the HTML gallery. (Previous versions would load all Dooodls at once)
* Updated Database structure (added moderation column)
* Changed WP-admin setup
* Tiny optimizations and changes to accomodate the new Wordpress API's
* The old V1 Flash Creator has been removed as it has been fully replaced by the HTML5 version. 

= 1.0.14 =
* Changed the way the brush in the creator is shown. Before it would just be a colored square which wouldn't be visible if held up above the same colored background. Fixed that now :)
* The background color of the canvas is always random from now on. That means less of the same colors in the rest of the submitted doodles!
* Fixed a little layout bug in the creator.

= 1.0.13 =
* Little Javascript error.
* Note: Ever since Shadowbox-js updated the autoload() doesn't work anymore... 

= 1.0.12 =
* Added stripslashes to the widget. Overlooked that a few times. My bad :)

= 1.0.11 =
* Changed the xml.php output in order to make sure the content is fully validated.

= 1.0.10 =
* Fixed a compatibility issue caused by a action_handler in the plugin. 

= 1.0.9 = 
* `stripslash()`ed the description and the title in the Viewer HTML and XML feeds.

= 1.0.8 = 
* Enabled the plugin to be called using `if (function_exists("Dooodl_widget")) Dooodl_widget();` in themes that are not widget-ready.

= 1.0.7 =
* Minor Bugfixes

= 1.0.6 =
* Added a smaller brush to the Dooodl Creator.

= 1.0.5 =
* Updated and optimized the uninstall procedure to delete the `/uploads/doodls/` folder and its contents if the `Remove Doodls Option` is checked.

= 1.0.4 = 
* Updated the install procedure to add the 1.jpg (the default doodle) to the uploads/doodls/ folder.

= 1.0.3 = 
* Moved the save folder to `/wp_content/uploads/doodls/` to make sure upgrading the plugin doesn't delete the images when updating.
* Code cleaning

= 1.0.2 =
* Added deeplinking
* Added settings link in the plugin list

= 1.0.1 =
* First public version as a plugin

= 1.0 =
* This was the first version (not open to the public)


== Upgrade Notice ==

= 2.0.0 =
This is the biggest Dooodl-update to date! If you encoutner any problems, please report them in the official Dooodl WordPress Plugin Support Forum: [https://wordpress.org/support/plugin/dooodl](https://wordpress.org/support/plugin/dooodl "Dooodl Support Forum")