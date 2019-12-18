=== Recras WordPress plugin ===
Contributors: zanderz
Tags: recras, recreation, reservation
Requires at least: 4.9
Requires PHP: 5.6.0
Tested up to: 5.3
Stable tag: 3.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily integrate data from your Recras instance, such as packages and contact forms, into your own website.

== Description ==
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as packages and contact forms, into your own website.

To get started, go to the Recras → Settings page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. That's all there is to it! You can now use widgets to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of the following "widgets". To use them, you first need to set your Recras name (see paragraph above).
* Availability calendar
* Contact forms
* Online booking
* Packages
* Products
* Voucher sales
* Voucher info

Widgets can be added to your site in three ways. Using Gutenberg blocks (recommended, since WordPress 5.0 or using the [Gutenberg plugin](https://wordpress.org/plugins/gutenberg/)), using the buttons in the "classic editor", or by entering the shortcode manually (discouraged).

= Date/Time picker =
By default, date and time pickers in contact forms use whatever the browser has available. Currently (September 2019) Internet Explorer (all versions) and Safari (desktop) do not have native date/time pickers and only see a text field. We have included a modern looking date picker that you can enable on the Recras → Settings page. For time inputs, a proper fallback is included.

**Note**: this setting only applies to standalone contact forms, not to contact forms used during "new style" online booking.

= Styling =
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `css/style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!
For styling the date picker, we refer you to the [Pikaday repository](https://github.com/Pikaday/Pikaday). Be sure to make any changes in your own theme or using WordPress' own Customizer.

= Cache =
All data from your Recras is cached for up to 24 hours. If you make important changes, such as increasing the price of a product, you can clear the cache to reflect those changes on your site immediately.

= Google Analytics integration =
You can enable basic Google Analytics integration by checking "Enable Google Analytics integration?" on the Recras Settings page. This will only work if there is a global `ga` JavaScript object. This should almost always be the case, but if you find out it doesn't work, please contact us!

== Installation ==

**Easy installation (preferred)**

1. Install the plugin from the Plugins > Add New page in your WordPress installation.

**Self install**

1. Download the zip file containing the plugin and extract it somewhere to your hard drive
1. Upload the `recras-wordpress-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

**Using Composer**

1. Type `composer require recras/recras-wordpress-plugin` in your terminal
1. The plugin will automatically be installed in the `/wp-content/plugins/` directory by using Composer Installers
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Do you support Gutenberg? =
Yes, since version 2.2.0! Please make sure you use the latest version of the plugin and please report any bugs you encounter.

= Do you support Visual Composer, Brizy, etc. ? =
We do not support page builders and have no plans to do so.

= Does the plugin support network installations? =
Yes it does. You can set different Recras names (all settings, for that matter) for each site.

== Screenshots ==

1. Example of a programme with the Twenty Fifteen theme
2. Example of package information, generated from Recras data
3. The Recras blocks in Gutenberg

== Changelog ==

= 3.2.1 =
* Update online booking library version: fix error when trying to book a product that has no material

= 3.2.0 =
* Update online booking library version: show error when input is higher than allowed
* It is now possible to show a selection of packages during online booking

= 3.1.2 =
* Support pre-filling package in contact forms using GET parameter "package"
* Fix layout of contact form when presented as table without labels

= 3.1.1 =
* Fix whitespace in online booking/voucher sales causing problems in edge cases
* Update online booking library version: this fixes 'require X per Y' requirements (instead of just 1 per Y)
* Fix "Error: no ID set" when only having a single contact form/package/product/voucher template in Gutenberg blocks

= 3.1.0 =
* Support pre-filling package in online booking using GET parameter "package"
* Improve online booking styling in Internet Explorer
* When a contact form has a required package field, and there is only one package, pre-fill it

= 3.0.3 =
* Defer loading of JS polyfill & JS library
* Improve product loading & add info text when no/not all products are found

= 3.0.2 =
* Fix duration and programme of some packages

= 3.0.1 =
* Packages in contact forms use internal name instead of display name - fixed

= 3.0.0 =
* Include widget previews for WordPress 5.3
* Online booking theme didn't set the version properly - fixed
* Update online booking library version:
  - Show discount fields straight from the start, not after entering date
  - Styling adjustment
  - Fix position of styling in the `head`, making overriding styles easier
  - Fix checking discount codes/vouchers
* Improve online booking styling in Edge
* Small online booking styling fixes/changes in both integrated themes

**BREAKING CHANGES**
* Because of technical changes for the styling, this release contains breaking changes if you use the new online booking method and have custom CSS changes for this. If you don't use the new online booking method, or use one of the theme integrated into the plugin ("Basic theme" or "Recras Blue"), this is a safe upgrade. For more (technical) info, please refer to [the upgrade documentation of the library](https://github.com/Recras/online-booking-js/blob/master/upgrading.md).
* The plugin now requires PHP 5.6 or higher (though PHP 7.3 is highly recommended)

= 2.4.9 =
* Make readme shorter and move documentation to page within WordPress
* Update online booking library version. This adds a small header to the quantity form and placeholders for its inputs
* Small styling improvements for online booking themes

= 2.4.8 =
* Make plugin more robust when no Recras name has been set yet
* Small accessibility improvement

= 2.4.7 =
* Make time input increase/decrease time in steps of 5 minutes
* Clarify online booking methods

= 2.4.6 =
* Update online booking library version. This fixes new online booking in IE when invalid tags are used in online booking texts.
  - This shouldn't affect most people, most notably it caused problems when using the Google Analytics domain linker.

= 2.4.5 =
* Fix for package duration/programme not showing in some edge cases

= 2.4.4 =
* Update online booking library version. This fixes the availability check for packages with "booking size" in some edge cases

= 2.4.3 =
* Fix for empty non-required "booking - package" field in contact forms

= 2.4.2 =
* Not selecting a pre-filled package with new online booking was broken - fixed
* Add info messages for packages not showing up

= 2.4.1 =
* Styling fix for "Recras Blue" theme
* Make Gutenberg translations work

= 2.4.0 =
* Date/Time input update:
  - Localise date/time placeholders
  - Remove time picker
  - Replace date picker (saves over 110 kB, 1 DNS request, and 4 HTTP requests)
* Add some German translations
* Add ability to show voucher information

= Older versions =
See [the full changelog](https://github.com/Recras/recras-wordpress-plugin/blob/master/changelog.md) for older versions.

== Upgrade Notice ==
See changelog. We use semantic versioning for the plugin.

== Support ==
We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](https://www.php.net/supported-versions.php).

== Credits ==
* Icons from [Dashicons](https://github.com/WordPress/dashicons) by WordPress, released under the GPLv2 licence.
* Date picker is [Pikaday](https://github.com/Pikaday/Pikaday), released under the BSD/MIT licence.
