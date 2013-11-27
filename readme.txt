=== LS IceCast ONAIR ===
Contributors: ladislav.soukup@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_number=LS%20IceCast%20OnAir%20%5bWP%2dPlugin%5d&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: onair, radio, icecast, song, current,shortcode
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 1.1.1

Shortcode to display onair song fetched from IceCast server (v2).

== Description ==

Simple WordPress plugin to display onair song fetched from IceCast server (v2).
You just need to setup icecast server address and publish point name (via admin panel). Data are fetched using CRON.
Insert using shortcode to any post or directly to template using do_shortcode();

= Usage example =
[icecast live=0]

= Parameters =
live:
	0 - simple text
	1 - live update via JavaScript
	
= NOTE =
You need to update your IceCast web folder with "xml.xsl" file (included with plugin). See Installation for more information...

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to plugin settings and set server address and publishpoint name

= Icecast server configuration: =
You need to update your IceCast configuration with one file "xml.xsl" (embeded with this plugin).
Just upload this file to "/web" folder of your IceCast installation dir. This will open XML stats to everyone... XML will output public data as the default IceCast page do.
This will allow this plugin to read publish point info without IceCast admin password. This is mainly for security of your IceCast instalation.

== Screenshots ==

1. Settings of plugin

== Changelog ==

= 1.1.1 =

- error handling fix
- added error reporting

= 1.0 =

initial push