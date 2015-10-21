# Recras WordPress plugin

[![Build Status](https://travis-ci.org/Recras/recras-wordpress-plugin.svg?branch=master)](https://travis-ci.org/Recras/recras-wordpress-plugin)

Easily integrate data from your Recras instance, such as arrangements and contact forms, into your own website.

## Usage
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as arrangements and contact forms, into your own website.

To get started, go to the Settings -> Recras page and enter your Recras subdomain. For example, if you log in to Recras at `https://mysite.recras.nl/` then your subdomain is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of two shortcodes. To use them, you first need to set your Recras subdomain on the Settings > Recras page.

### Arrangements

Add the `[arrangement]` shortcode anywhere on your site. The following parameters are supported:
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

Example: `[arrangement id="1" show="title"]` (quotation marks around parameters are optional) will show the title of the arrangement with ID 1.

### Contact forms
Add the `[recras-contact]` shortcode anywhere on your site. The following parameters are supported:
* id (required) - corresponds to the contact form ID in Recras
* showtitle - show the title of the contact form or not. Enabled by default, to disable use `false`, `0`, or `no` as value.
* arrangement - for forms where the user can select an arrangement, setting this parameter will select the arrangement automatically and hide the field for the user.
* element - show the contact form as definition list (dl - default), ordered list (ol), or table (table).
* showlabels - show the label for each element. Enabled by default, to disable use `false`, `0`, or `no` as value.
* showplaceholders - show the placeholder for each element. Enabled by default, to disable use `false`, `0`, or `no` as value. Placeholders are [not supported](http://caniuse.com/#search=placeholder) in Internet Explorer versions 9 and lower.

Example: `[recras-contact id=42 showtitle=false element="ol" showlabels="0"]` will show the contact form with ID 42, in an ordered list, without title and without label.

=== Styling ===
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!

## Installation

Easy installation (preferred):

1. Install the plugin from the Plugins > Add New page in your WordPress installation.

Self install:

1. Upload the `recras-wordpress-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Support

We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](php.net/supported-versions.php).

== Credits ==
* Icons from [Genericons](https://github.com/Automattic/Genericons/) by Automattic, released under the GPL.
