=== Plugin Name ===
Contributors: zanderz
Tags: recras, recreation, reservation
Requires at least: 4.3.1
Tested up to: 4.3.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily integrate data from your Recras instance, such as arrangements and contact forms, into your own website.

== Description ==
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as arrangements and contact forms, into your own website.

To get started, go to the Settings -> Recras page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of two shortcodes. To use them, you first need to set your Recras name on the Settings > Recras page.

= Arrangements =
Add the `[recras-arrangement]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the arrangement ID in Recras
* show (required) - can be any of the following: `title`, `persons`, `price_pp_excl_vat`, `price_pp_incl_vat`, `price_total_excl_vat`, `price_total_incl_vat`, `programme`. `program` is included as alias for `programme`.
  * duration - shows the duration of this arrangement (i.e. time between start of first activity and end of last activity)
  * location - shows the starting location of this arrangement
  * persons - shows the minimum number of persons needed for this arrangement
  * price_pp_excl_vat - shows the price per person, excluding VAT
  * price_pp_incl_vat - same as above, but including VAT
  * price_total_excl_vat - shows the total price, excluding VAT
  * price_total_incl_vat - same as above, but including VAT
  * programme - shows the programme as an HTML table. For styling purposes, the table has a `recras-programme` class. For multi-day programmes every `tr` starting on a new day has a `new-day` class
  * title - shows the title of an arrangement
* starttime - only used for `programme`, determines the starting time of an arrangement. If not set, it will default to 12:00
* showheader - only used for `programme`, determines if the header should be shown. Enabled by default, to disable use `false`, `0`, or `no` as value.

Example: `[recras-arrangement id="1" show="title"]` (quotation marks around parameters are optional) will show the title of the arrangement with ID 1.

= Contact forms =
Add the `[recras-contact]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the contact form ID in Recras
* showtitle - show the title of the contact form or not. Enabled by default, to disable use `false`, `0`, or `no` as value.
* arrangement - for forms where the user can select an arrangement, setting this parameter will select the arrangement automatically and hide the field for the user.
* element - show the contact form as definition list (dl - default), ordered list (ol), or table (table).
* showlabels - show the label for each element. Enabled by default, to disable use `false`, `0`, or `no` as value.
* showplaceholders - show the placeholder for each element. Enabled by default, to disable use `false`, `0`, or `no` as value. Placeholders are [not supported](http://caniuse.com/#search=placeholder) in Internet Explorer versions 9 and lower.
* submittext - the text for the form submission button. Defaults to "Send"

Example: `[recras-contact id=42 showtitle=false element="ol" showlabels="0"]` will show the contact form with ID 42, in an ordered list, without title and without label.

== Online booking ==
Add the `[recras-booking]` shortcode anywhere on your site. The following parameters are supported:

* id (required) - corresponds to the contact form ID in Recras

= Styling =
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!

== Installation ==

**Easy installation (preferred)**

1. Install the plugin from the Plugins > Add New page in your WordPress installation.

**Self install**

1. Upload the `recras-wordpress-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
None yet!

== Screenshots ==
1. Example of a programme with the default Twenty Fifteen theme

== Changelog ==

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
* Icons from [Genericons](https://github.com/Automattic/Genericons/) by Automattic, released under the GPL.
