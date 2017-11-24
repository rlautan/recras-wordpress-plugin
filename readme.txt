=== Plugin Name ===
Contributors: zanderz
Tags: recras, recreation, reservation
Requires at least: 4.3
Requires PHP: 5.4.0
Tested up to: 4.9.0
Stable tag: 1.11.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily integrate data from your Recras instance, such as packages and contact forms, into your own website.

== Description ==
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as packages and contact forms, into your own website.

To get started, go to the Recras -> Settings page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of four shortcodes. To use them, you first need to set your Recras name on the Recras -> Settings page.

= Settings =
Setting your Recras name is the most important step here. Other settings are:

* Currency symbol - set to € (Euro) by default.
* Decimal separator - set to , (comma) by default. Used in prices such as 100,00.
* Use date/time picker script - please see the Date/Time picker section for details.

= Packages =
Add the `[recras-package]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the package ID in Recras
* show (required) - can be any of the following: `title`, `persons`, `price_pp_excl_vat`, `price_pp_incl_vat`, `price_total_excl_vat`, `price_total_incl_vat`, `programme`. `program` is included as alias for `programme`.
  * description - shows the long description of this package
  * duration - shows the duration of this package (i.e. time between start of first activity and end of last activity)
  * image_url - gives the package image URL, if present. Note: any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
  * location - shows the starting location of this package
  * persons - shows the minimum number of persons needed for this package
  * price_pp_excl_vat - shows the price per person, excluding VAT
  * price_pp_incl_vat - same as above, but including VAT
  * price_total_excl_vat - shows the total price, excluding VAT
  * price_total_incl_vat - same as above, but including VAT
  * programme - shows the programme as an HTML table. For styling purposes, the table has a `recras-programme` class. For multi-day programmes every `tr` starting on a new day has a `new-day` class
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

* id - corresponds to the contact form ID in Recras

Example: `[recras-booking id=17]` will show the booking form with ID 17.

The online booking shortcode adds an iframe to your site, which automatically adjusts its height to fit the content.

= Products =
Add the `[recras-product]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the product ID in Recras
* show (required) - can be any of the following: `title`, `description`, `price_excl_vat`, `price_incl_vat`.
  * duration - shows duration of this product, if product has a duration
  * image_url - gives the product image URL, if present. Note: any surrounding HTML/CSS, such as an `<img>` tag or `background-image` attribute will have to be written manually for maximum flexibility. When using quotation marks, be sure to use different marks in the shortcode and the surrounding code, or the image will not show.
  * minimum_amount - shows the minimum amount of this product
  * title - shows the title (display name) of the product
  * price_excl_vat - shows the price of the product, excluding VAT
  * price_incl_vat - same as above, but including VAT
  * description - shows the short description of the product
  * description_long - shows the long description of the product, if present.

Example: `[recras-product id="9" show="title"]` will show the title of the product with ID 9.

= Date/Time picker =
By default, date and time pickers use whatever the browser has available. Currently (March 2017) Internet Explorer (all versions), Firefox (all versions), and Safari (desktop) do not have native date/time pickers and only see a text field. We have included a modern looking date/time picker that you can enable on the Recras -> Settings page.

= Styling =
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!
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
1. Example of a programme with the default Twenty Fifteen theme

== Changelog ==

= 1.11.3 =
* Add explanation why sometimes packages are not available

= 1.11.2 =
* Revert iframe change from previous version - did more harm than good

= 1.11.1 =
* Show more helpful errors if something goes wrong
* Fix iframe heights if there is more than one iframe on a page

= 1.11.0 =
* Added `[recras-availability]` shortcode to show availability calendar
* Rename "arrangement" to "package" to reflect text change in Recras
* Deprecated `[recras-arrangement]` shortcode in favour of `[recras-package]`
* New icons for TinyMCE buttons
* Fix loading icon when submitting a contact form
* Fix empty text on submit button after submitting a contact form

= 1.10.2 =
Fix detailed description of arrangements

= 1.10.1 =
Fix available arrangements for a contact form

= 1.10.0 =
* Don't show seconds in arrangement/product durations
* Use display name instead of internal name for arrangements

= 1.9.1 & 1.9.2 =
* Fix bug with iframe height

= 1.9.0 =
* Listen for height-update message

= 1.8.1.1 =
* Updated "Tested up to" version to 4.7

= 1.8.1 =
* Fix problem with previous version not loading

= 1.8.0 =
* Add image URL and description to arrangements
* The plugin is now available on Packagist, which means you can use Composer to install the plugin.
* Various small bug fixes

= 1.7.1 =
* The Settings page is now hidden if you don't have permission to see it.

= 1.7.0 =
* The online booking button now allows you to pre-select an arrangement. Only arrangements that are bookable online are included.

= 1.6.1 =
Fixed a bug with contact form arrangements cache

= 1.6.0 =
* Simplified emptying caches and added more explanation
* Arrangements in a contact form are now sorted alphabetically
* Added workaround for dropdown placeholders

= 1.5.0 =
Succesfully submitting a contact form will now empty the form afterwards

= 1.4.0 =
* Add optional date/time pickers

= 1.3.4 =
* Fixed redirect URL after clearing cache
* Add placeholders to textareas
* Make "Unknown" the default gender, rather than "Male"
* Fix submitting a contact form on a page that has that same form multiple times

= 1.3.3 =
* Sort products alphabetically
* Move stuff from Settings to a separate Recras page in the menu

= 1.3.2 =
* Lowered minimum required WP version
* Applied new classes to date/time inputs

= 1.3.1 =
Fixed online booking shortcode loading a contact form instead of the booking form

= 1.3.0 =
* Add caching of all external data
* Add option to use a redirect after submitting a contact form
* Remove cURL requirement (unneeded as of 1.2.1)

= 1.2.1 =
* Change "keuze" on a contact form from a dropdown to checkboxes (Fixes #5)
* Bypass our own serverside submit script, use XHR instead

= 1.2.0 =
* Add the following possible properties to products: `description_long`, `duration`, `image_url`, and `minimum_amount`.

= 1.1.0 =
* Only show arrangements in contact form shortcode editor that belong to that contact form
* Fix some styling issues (WP 4.4 only?)
* Show error message if a contact form does not have a field for arrangements, but one is set anyway (Fixes #3)
* If an invalid arrangement is set for a contact form, show dropdown of arrangements instead of generating an invalid form

= 1.0.0 =
* Add shortcode for online bookings
* Add shortcode for products
* Change the way arrangement programmes spanning multiple days are shown
* Not all arrangements are available for all contact forms - the plugin now checks if the combination is valid
* Deprecated [arrangement] shortcode in favour of [recras-arrangement]

= 0.17.1 =
Rename Subdomain to Recras name

= 0.17.0 =
* When not showing labels, don't show an empty `li`/`td`/`dt` element
* Allow contact form submit button text to be changed

= 0.16.1 =
Fix invalid HTML when using an `ol` or `table` for the contact form

= 0.16.0 =
* Don't show asterisk for required fields if labels are disabled
* Show asterisk for required fields in placeholder
* Add option for decimal separator

= 0.15.1 =
Move files out of assets folder, as WordPress handles this unexpectedly

= 0.15.0 =
* Add logo for plugin repository
* Fix readme

= 0.14.5 =
Workaround for array constants, which are not allowed by WordPress SVN

= 0.14.4 =
Add Composer autoloader to prevent users from having to install Composer

= 0.14.3 =
Update arrangement duration format

= 0.14.2 =
Add missing arrangement shortcode button options (duration, location)

= 0.14.1 =
* Replaced icons with GPL-compatible ones
* Update readme with more information
* Hack around not being allowed to load wp-load.php
* Translation update

= 0.14.0 =
Add `location` and `duration` options to arrangement shortcode

= 0.13.3 =
Fix translation not being loaded

= 0.13.2 =
Add options added in 0.13.0 to the editor shortcode generator button

= 0.13.1 =
Refactor

= 0.13.0 =
* Add option to show contact forms as lists or tables
* Add option to hide labels on contact forms
* Placeholders added on contact forms, added option to hide them

= 0.12.1 =
* Minor language fix
* Update Dutch translation

= 0.12.0 =
Selection of arrangement and contact form is now possible via a dropdown rather than manually entering the ID

= 0.11.0 =
WordPress' editors now have a button to insert a contact form without needing to know the syntax!

= 0.10.0 =
WordPress' editors now have a button to insert an arrangement without needing to know the syntax!

= 0.9.0 =
* Setting the `arrangement` parameter on a contact form will select this arrangement automatically and hide the field to the user.
* Fix empty option being the last option instead of the first option on arrangement dropdowns

= 0.8.0 =
If a contact form has an "arrangements" field, show all arrangements in a dropdown

= 0.7.1 =
Fix translations

= 0.7.0 =
* Add loading indicator when sending a contact form
* Replace contact form popups with inline text boxes
* Fix placement of error messages on pages with multiple contact forms

= 0.6.2 =
Fix placement of submit button on contact forms

= 0.6.1 =
Fix a typo

= 0.6.0 =
Add option to disable the header of a programme

= 0.5.1 =
Show notice if cURL is not installed

= 0.5.0 =
Add shortcode for contact forms

= 0.4.2 =
Unified CSS class names

= 0.4.1 =
Proper handling of debug mode

= 0.4.0 =
Add currency option, defaults to Euro (€)

= 0.3.0 =
* Add Dutch translation
* Wrap output of the shortcode in `<span>`s with different classes, for styling purposes

= 0.2.1 =
Don't `die()` on errors, but return error message instead

= 0.2.0 =
First version!

== Upgrade Notice ==
Nothing to report.

== Support ==
We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](php.net/supported-versions.php).

== Credits ==
* Icons from [Genericons Neue](https://github.com/Automattic/genericons-neue) by Automattic, released under the GPL.
* Date/Time picker by [T00rk](https://github.com/T00rk/bootstrap-material-datetimepicker), released under the MIT licence.
