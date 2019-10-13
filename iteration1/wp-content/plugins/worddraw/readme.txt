=== Plugin Name ===
Contributors: burzak
Donate link: http://burzak.com/?worddraw#donating
Tags: comment, draw, drawing, image, worddraw, drawform
Requires at least: 3.0.0
Tested up to: 3.0.1
Stable tag: trunk

WordDraw enables you and your visitors to draw comments. 

== Description ==

[WordDraw](http://burzak.com/proj/worddraw/ "Project homepage") enables you and your visitors to draw comments. 

There are common tools:

* Pencil
* Pen
* Path
* Paint bucket
* Eraser
* Marquee
* Image
* Text
* Rectangle and ellipse primitives
* Undo and redo

To enable canvas checking box **'Allow drawings'** on **'Edit Post'**.

Note that this is first beta release. Soon we add support for old IEs, new text tool and additional documentation. So please don't blame us strictly :)

All questions and comments please send to the [mailing list](http://groups.google.com/group/drawform "Google groups") and bug report in [issues list](http://code.google.com/p/drawform/issues/list "issues list").

Note that when you disabled plugin, all drawings will be displayed as abrakadabra: `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAAGQCAYAAABYs5LG` ...

== Installation ==

1. Upload directory with plugin content to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Manually add your domain in `proxy.php` to allow users draw images from outer world

Images are stored as text in database, so no special installation required.

== Frequently Asked Questions ==

= How it works? =

Algorithm is pretty simple: a visitor drawing picture then it encoded in [Base64](http://en.wikipedia.org/wiki/Base64 "Base64") and saving in database. After that you can edit or delete it in WordPress admin as ordinary comments. 

Note that when you disabled plugin, all drawings will be displayer as abrakadabra: `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAAGQCAYAAABYs5LG` ...

= What browsers and clients are supported? =

WordDraw will work in most modern browsers. It tested in Internet Explorer 8, Safari (including iPhone/iPad), Firefox, Opera, Chrome. We also planning support IE 6-7-9 after beta release. 

For unsupported clients, canvas will be disabled and replaced with default editor.

= Is there any security issues? =

It seems, nope. WordDraw does not create additional tables, queries or files. Images are encoded in [Base64](http://en.wikipedia.org/wiki/Base64 "Base64") and stored as text in database. So the only problem may be if the browser incorrectly handled these images. But it's unlikely. 

= Can it be extended with my brush/fonts/tools? =

Yes, soon. WordDraw is based on [Drawform](http://burzak.com/proj/drawform/) project. Even more WordDraw is just convenient interface for Drawform. DF is in active development, so they release source code and API documentation soon.

== Screenshots ==

1. Single post with WordDraw enabled 
2. Comment editor in WordPress admin

== Changelog ==

= 0.1-beta =
* Initial public release.

== Upgrade Notice ==
It's first beta release ...

== Presentation ==
Presentation and further info available on [project homepage](http://burzak.com/proj/worddraw/ "Project homepage").
