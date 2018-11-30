=== Plugin Name ===
Contributors: zanderz
Tags: recras, recreation, reservation
Requires at least: 4.3
Requires PHP: 5.4.0
Tested up to: 4.9
Stable tag: 2.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily integrate data from your Recras instance, such as packages and contact forms, into your own website.

== Description ==
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as packages and contact forms, into your own website.

To get started, go to the Recras → Settings page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of five shortcodes. To use them, you first need to set your Recras name on the Recras → Settings page.

= Settings =
Setting your Recras name is the most important step here. Other settings are:

* Currency symbol - set to € (Euro) by default.
* Decimal separator - set to , (comma) by default. Used in prices such as 100,00.
* Use date/time picker script - please see the Date/Time picker section for details.
* Theme for online booking - which theme the new online booking method will use.
  - "No theme" leaves it up to you to properly style it.
  - "Basic theme" sets some default styling to make it look a bit nicer. You can still override everything with your own CSS.
  - "Recras Blue" is a theme with blue accents

= Packages =
Add the `[recras-package]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the package ID in Recras
* show (required) - can be any of the following:
  * description - shows the long description of this package
  * duration - shows the duration of this package (i.e. time between start of first activity and end of last activity)
  * image_tag - outputs the package image, if present.
  * image_url - gives the package image URL, if present. Any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. If you just want to output the image, use `image_tag` instead. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
  * location - shows the starting location of this package
  * persons - shows the minimum number of persons needed for this package
  * price_pp_excl_vat - shows the price per person, excluding VAT
  * price_pp_incl_vat - same as above, but including VAT
  * price_total_excl_vat - shows the total price, excluding VAT
  * price_total_incl_vat - same as above, but including VAT
  * programme - shows the programme as an HTML table. For styling purposes, the table has a `recras-programme` class. For multi-day programmes every `tr` starting on a new day has a `new-day` class
  * program - alias of `programme`
  * title - shows the title (display name) of the package
* starttime - only used for `programme`, determines the starting time of a package. If not set, it will default to 12:00
* showheader - only used for `programme`, determines if the header should be shown. Enabled by default, to disable use `false`, `0`, or `no` as value.

Example: `[recras-package id="1" show="title"]` (quotation marks around parameters are optional) will show the title of the package with ID 1.

= Contact forms =
Add the `[recras-contact]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the contact form ID in Recras
* showtitle - show the title of the contact form or not. Enabled by default, to disable use `false`, `0`, or `no` as value.
* arrangement - for forms where the user can select a package, setting this parameter will select the package automatically and hide the field for the user.
* element - show the contact form as definition list (dl - default), ordered list (ol), or table (table).
* showlabels - show the label for each element. Enabled by default, to disable use `false`, `0`, or `no` as value.
* showplaceholders - show the placeholder for each element. Enabled by default, to disable use `false`, `0`, or `no` as value. Placeholders are [not supported](http://caniuse.com/#search=placeholder) in Internet Explorer versions 9 and lower.
* submittext - the text for the form submission button. Defaults to "Send"
* redirect - a URL that the user is redirected to, after submitting the form successfully.

Example: `[recras-contact id=42 showtitle=false element="ol" showlabels="0"]` will show the contact form with ID 42, in an ordered list, without title and without label.

= Online booking =
Add the `[recras-booking]` shortcode anywhere on your site. The following parameters are supported:

* id - corresponds to the package ID in Recras
* use_new_library - whether or not you want to use the new online booking method. This may have bugs, so use with care! Since this is a per-shortcode setting, you can create a new page to try this out.
* redirect - a URL that the user is redirected to, after booking successfully. Note: this is only available for the new online booking method.
* autoresize - enabled by default. Disable this if you have more than one Recras iframe on your page. Note: this is only available for the old online booking method.

Example: `[recras-booking id=17]` will show the booking form with package ID 17.

The online booking shortcode adds an iframe to your site, which automatically adjusts its height to fit the content, unless the option "autoresize" is disabled.

= Availability =
Add the `[recras-availability]` shortcode anywhere on your site. The following parameters are supported:

* id - corresponds to the package ID in Recras
* autoresize - disabled by default. Should not be necessary to use. Keep this disabled if you have more than one Recras iframe on your page

The availability shortcode adds an iframe with availability calendar to your site.

= Products =
Add the `[recras-product]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the product ID in Recras
* show (required) - can be any of the following:
  * duration - shows duration of this product, if product has a duration
  * image_tag - outputs the product image, if present.
  * image_url - gives the product image URL, if present. Any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. If you just want to output the image, use `image_tag` instead. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
  * minimum_amount - shows the minimum amount of this product
  * title - shows the title (display name) of the product
  * price_excl_vat - shows the price of the product, excluding VAT
  * price_incl_vat - same as above, but including VAT
  * description - shows the short description of the product
  * description_long - shows the long description of the product, if present.

Example: `[recras-product id="9" show="title"]` will show the title of the product with ID 9.

= Vouchers =
Add the `[recras-vouchers]` shortcode anywhere on your site. The following parameter is supported:
* redirect - a URL that the user is redirected to, after submitting the form successfully.

This will a voucher sales module to your website.

= Date/Time picker =
By default, date and time pickers in contact forms use whatever the browser has available. Currently (November 2018) Internet Explorer (all versions) and Safari (desktop) do not have native date/time pickers and only see a text field. We have included a modern looking date/time picker that you can enable on the Recras → Settings page.

**Note**: this setting only applies to contact forms, not to "new style" online booking.

= Styling =
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `css/style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!
Styling for the date/time picker can be based off of `datetimepicker/bootstrap-material-datetimepicker.css`. Be sure to make any changes in your own theme, as this file may be overwritten when a new version of the plugin is released.

= Cache =
All data from your Recras is cached for up to 24 hours. If you make important changes, such as increasing the price of a product, you can clear the cache to reflect those changes on your site immediately.

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
None yet!

== Screenshots ==
1. Example of a programme with the Twenty Fifteen theme

== Changelog ==

= 2.0.6 =
Update online booking library version:
* Don't scroll to amounts form when package is pre-selected
* Fixed attachments being shown even when "Send standard attachments" was disabled for a package
* Show console warning when you are logged in to the Recras being used

= 2.0.5 =
Update online booking library version:
* Fixed a bunch of minor bugs and inconsistencies
* Show line price based on amount selected

= 2.0.4 =
* Update online booking library version

= 2.0.3 =
* Voucher sales module without pre-selected template wasn't working - fixed
* Update online booking library version:
  * Implement `keuze_enkel` fields in contact form
  * Fix "NaN" price when amount input field was cleared
  * Fix "Programme amounts are invalid" error in some cases
  * Voucher sales showed templates without contact form when logged in - fixed

= 2.0.2 =
* Update online booking library version (check booking size lines for minimum amount)

= 2.0.1 =
Fixed a problem with the previous release

= 2.0.0 =
**Major release** This version might break things. Please read the following carefully:

* Added:
  - Ability to show package/product image tag (instead of bare URL and having to add `<img>` tag manually)
  - Add "Choice - single" field to contact forms
* Fixed:
  - Position of datepicker popup on mobile
  - "Customer type" selection in contact forms
* Changed: the discount and voucher fields for online bookings are now combined. This means there are some backward incompatible CSS changes. If you are **not** using an online booking theme, you might need to make some changes to your CSS when installing this version. Details on these changes can be found in the [changelog for the library](https://github.com/Recras/online-booking-js/blob/master/changelog.md#080-2018-10-29)
* Removed: `[arrangement]` and `[recras-arrangement]` shortcodes. These have been replaced by `[recras-package]` over 1.5 years ago.

= 1.15.2 =
* Update online booking library version (fixes prices sometimes being shown incorrectly)

= 1.15.1 =
* Update online booking library version (fixes online bookings that can only be paid afterwards)

= 1.15.0 =
* Add themes for new online booking method
* Enable "Use new library" by default
* Update online booking library version:
  - Show reasons why 'Book now' button is disabled
  - Fix disabled 'Book now' button after changing date/time
  - Fixes potential race condition

= 1.14.6 =
* Better loading of polyfill
* Update online booking library version (fixes minimum amount of booking size row)

= 1.14.5 =
* No changes. Releasing previous version failed, trying to re-release.

= 1.14.4 =
* Update online booking library version

= 1.14.3 =
* Update online booking library version

= 1.14.2 =
* Fix online booking library not loading properly

= 1.14.1 =
* Update online booking library version

= 1.14.0 =
* Add option to try out the new online booking library

= 1.13.0 =
* Add voucher sales module

= 1.12.3 =
* Fix contact form submission when jQuery is loaded too late

= 1.12.2 =
* Show error instead of crashing when package programme is empty

= 1.12.1 =
* Enable automatic resizing initially for availability calendar

= 1.12.0 =
* Add option to disable automatic resizing of online booking & availability iframes

= Older versions =
See `changelog.md` for the full changelog.

== Upgrade Notice ==
Nothing to report.

== Support ==
We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](php.net/supported-versions.php).

== Credits ==
* Icons from [Ionicons](https://ionicons.com/) by Ionic, released under the MIT licence.
* Date/Time picker by [T00rk](https://github.com/T00rk/bootstrap-material-datetimepicker), released under the MIT licence.
