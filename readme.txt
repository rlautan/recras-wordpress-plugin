=== Plugin Name ===
Contributors: zanderz
Tags: recras, recreation, reservation
Requires at least: 4.9
Requires PHP: 5.4.0
Tested up to: 5.2
Stable tag: 2.4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily integrate data from your Recras instance, such as packages and contact forms, into your own website.

== Description ==
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as packages and contact forms, into your own website.

To get started, go to the Recras → Settings page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of six "widgets". To use them, you first need to set your Recras name (see paragraph above).
- Availability calendar
- Contact forms
- Online booking
- Packages
- Products
- Voucher sales

Widgets can be added to your site in three ways. Using Gutenberg blocks (since WordPress 5.0 or using the [Gutenberg plugin](https://wordpress.org/plugins/gutenberg/)), using the buttons in the "classic editor", or by entering the shortcode manually.

= Settings =
Setting your Recras name is the most important step here. Other settings are:

* Currency symbol - set to € (Euro) by default.
* Decimal separator - set to , (comma) by default. Used in prices such as 100,00.
* Use calendar widget - please see the Date/Time section below for details.
* Theme for online booking - which theme the new online booking method will use.
  - "No theme" leaves it up to you to properly style it.
  - "Basic theme" sets some default styling to make it look a bit nicer. You can still override everything with your own CSS.
  - "Recras Blue" is a theme with blue accents
* Enable Google Analytics integration - enabling this will send online booking and voucher sales events to Google Analytics

= Packages =
Packages can be added using the Recras/Package block (Gutenberg) or using the ![packages](editor/package.svg) icon in the Classic Editor. You can also manually add the `[recras-package]` shortcode.

The following options are available:
* Package - **required** what package to use
* Property to show - **required** what property to show. This can be any of the following:
  * Description - the long description of this package
  * Duration - the duration of this package (i.e. time between start of first activity and end of last activity)
  * Image tag - the package image, if present.
  * Minimum number of persons - the minimum number of persons needed for this package
  * Price p.p. excl. VAT - the price per person, excluding VAT
  * Price p.p. incl. VAT - same as above, but including VAT
  * Programme - the programme as an HTML table. For styling purposes, the table has a `recras-programme` class. For multi-day programmes every `tr` starting on a new day has a `new-day` class
  * Starting location - the starting location name of this package
  * Title - the title (display name) of the package
  * Total price excl. VAT - shows the total price, excluding VAT
  * Total price incl. VAT - same as above, but including VAT
  * Relative image URL - gives the package image URL, if present. Any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. If you just want to output the image, use "Image tag" instead. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
* Start time - only visible when "Programme" is selected - determines the starting time of a package. If not set, it will default to 00:00
* Show header? - only visible when "Programme" is selected - determines if the header should be shown. Enabled by default

= Contact forms =
Contact forms can be added using the Recras/Contact form block (Gutenberg) or using the ![contact form](editor/contact.svg) icon in the Classic Editor. You can also manually add the `[recras-contact]` shortcode.

The following options are available:
* Contact form - **required** what form to use
* Show title? - show the title of the contact form or not. Enabled by default
* Show labels? - show the label for each element. Enabled by default. **Note** showing labels is highly recommended. It is good for accessibility, and when they are not used it can lead to confusing results with radio buttons.
* Show placeholders? - show the placeholder for each element. Enabled by default
* Package - for forms where the user can select a package, setting this parameter will select the package automatically and hide the field for the user.
* HTML element - show the contact form as definition list (default), ordered list, or table (not recommended for accessibility reasons).
* Element for single choices - show fields where a single choice is made (i.e. Customer type) as drop-down list (default) or radio buttons.
* Submit button text - the text for the form submission button. Defaults to "Send"
* Redirect after submission - a page/post that the user is redirected to, after submitting the form successfully.

= Online booking =
Online booking can be integrated using the Recras/Online booking block (Gutenberg) or using the ![online booking](editor/online-booking.svg) icon in the Classic Editor. You can also manually add the `[recras-booking]` shortcode.

The following options are available:
* Pre-filled package - entering a package here will skip the package selection step
* Use new method? - whether or not you want to use the new online booking method.
* Preview times in programme - whether or not you want to preview times in the programme. Note: this is only available for the new online booking method.
* Pre-fill amounts - **Note** this is only available for the new online booking method, and required a pre-filled package. When enabled, this gives you the ability to pre-fill the amounts form. This can be useful, for example, for packages where you always have a fixed amount.
* Redirect after submission - a page/post that the user is redirected to, after booking successfully. Note: this is only available for the new online booking method.
* Auto resize iframe - enabled by default. Disable this if you have more than one Recras iframe on your page. Note: this is only available for the old online booking method.

= Availability =
Availability calendars can be added using the Recras/Availability calendar block (Gutenberg) or using the ![availability calendar](editor/availability.svg) icon in the Classic Editor. You can also manually add the `[recras-availability]` shortcode.

The following options are available:
* Package - what package to use for the availability calendar
* Auto resize iframe - enabled by default. Disable this if you have more than one Recras iframe on your page

= Products =
Products can be added using the Recras/Product block (Gutenberg) or using the ![product](editor/product.svg) icon in the Classic Editor. You can also manually add the `[recras-product]` shortcode.

The following options are available:
* Product - **required** what product to use
* Property to show - **required** what property to show. This can be any of the following:
  * Description (long) - the long description of the product, if present.
  * Description (short) - the short description of the product
  * Duration - the duration of this product, if product has a duration
  * Image tag - outputs the product image, if present.
  * Image URL - gives the product image URL, if present. Any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. If you just want to output the image, use "Image tag" instead. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
  * Minimum amount - the minimum amount of this product
  * Price (incl. VAT) - the price of the product, including VAT
  * Title - the title (display name) of the product

= Voucher sales =
Voucher sales can be integrated using the Recras/Vouchers block (Gutenberg) or using the ![voucher sales](editor/vouchers.svg) icon in the Classic Editor. You can also manually add the `[recras-vouchers]` shortcode.

The following options are available:
* Voucher template - when selected, this will skip the template selection step
* Redirect after submission - a page/post that the user is redirected to, after submitting the form successfully.

= Date/Time picker =
By default, date and time pickers in contact forms use whatever the browser has available. Currently (April 2019) Internet Explorer (all versions) and Safari (desktop) do not have native date/time pickers and only see a text field. We have included a modern looking date picker that you can enable on the Recras → Settings page. For time inputs, a proper fallback is included.

**Note**: this setting only applies to standalone contact forms, not to contact forms used during "new style" online booking.

= Styling =
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `css/style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!
Styling for the date/time picker can be based off of `datetimepicker/bootstrap-material-datetimepicker.css`. Be sure to make any changes in your own theme, as this file may be overwritten when a new version of the plugin is released.

= Cache =
All data from your Recras is cached for up to 24 hours. If you make important changes, such as increasing the price of a product, you can clear the cache to reflect those changes on your site immediately.

= Google Analytics integration =
You can enable Google Analytics integration by checking "Enable Google Analytics integration?" on the Recras Settings page. This will only work if there is a global `ga` JavaScript object. This should almost always be the case, but if you find out it doesn't work, please contact us!

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
We do not support page builders at this time and have no plans to do so.

= Does the plugin support network installations? =
Yes it does. You can set different Recras names (all settings, for that matter) for each site.

== Screenshots ==

1. Example of a programme with the Twenty Fifteen theme
2. Example of package information, generated from Recras data
3. The Recras blocks in Gutenberg

== Changelog ==

= master =
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

= 2.3.9 =
* Update online booking library version. This fixed "customer type" fields in contact forms used during online bookings.

= 2.3.8 =
* Update online booking library version:
  - Styling fix for Internet Explorer
  - Add missing maximum value for the booking size field

= 2.3.7 =
* Update online booking library version. This fixes new online booking in IE.

= 2.3.6 =
* Update online booking library version. This fixes the sending of Google Analytics events.

= 2.3.5 =
* Fix online booking/voucher sales when using them from Advanced Custom Fields or similar solutions
* Update online booking library version:
  - Add message when entering an quantity more than the maximum of a line
  - If there is only one available timeslot for the selected date, select it automatically

= 2.3.4 =
* Fix default contact form setting for Gutenberg contact form block
* Fix certain Gutenberg toggles on re-edit

= 2.3.3 =
* Fix new online booking in IE

= 2.3.2 =
* Package block only showed packages that were bookable online - fixed
* Voucher templates are now cached along with everything else
* Fixed headings of programmes that span multiple days

= 2.3.1 =
* Fix missing "Start time" and "Show header" options in Package block

= 2.3.0 =
* Add Google Analytics integration
* Add ability to pre-fill amounts form

= Older versions =
See `changelog.md` for the full changelog.

== Upgrade Notice ==
See changelog. We use semantic versioning for the plugin.

== Support ==
We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](https://secure.php.net/php.net/supported-versions.php).

== Credits ==
* Icons from [Dashicons](https://github.com/WordPress/dashicons) by WordPress, released under the GPLv2 licence.
* Date/Time picker by [T00rk](https://github.com/T00rk/bootstrap-material-datetimepicker), released under the MIT licence.
