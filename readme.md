Recras WordPress plugin
=======================

[![Build Status](https://travis-ci.org/Recras/recras-wordpress-plugin.svg?branch=master)](https://travis-ci.org/Recras/recras-wordpress-plugin)

Easily integrate your Recras data into your own website

Usage
-----
This plugin consists of two shortcodes. To use them, you first need to set your Recras subdomain on the Settings > Recras page.

### Arrangements ###

Add the `[arrangement]` shortcode anywhere on your site. The following parameters are supported:
* id (required) - corresponds to the arrangement ID in Recras
* show (required) - can be any of the following: `title`, `persons`, `price_pp_excl_vat`, `price_pp_incl_vat`, `price_total_excl_vat`, `price_total_incl_vat`, `programme`. `program` is included as alias for `programme`.
  * title - shows the title of an arrangement
  * persons - shows the minimum number of persons needed for this arrangement
  * price_pp_excl_vat - shows the price per person, excluding VAT
  * price_pp_incl_vat - same as above, but including VAT
  * price_total_excl_vat - shows the total price, excluding VAT
  * price_total_incl_vat - same as above, but including VAT
  * programme - shows the programme as an HTML table. For styling purposes, the table has a `recras-programme` class. For multi-day programmes every `tr` starting on a new day has a `new-day` class
* starttime - only used for `programme`, determines the starting time of an arrangement. If not set, it will default to 12:00

Example: `[arrangement id="1" show="title"]` (quotation marks around parameters are optional) will show the title of the arrangement with ID 1.

### Contact forms ###
Add the `[recras-contact]` shortcode anywhere on your site. The following parameters are supported:
* id (required) - corresponds to the contact form ID in Recras
* showtitle - show the title of the contact form or not. Enabled by default, to disable use `false`, `0`, or `no` as value.

Example: `[recras-contact id=42 showtitle=false]` will show the contact form with ID 42, without title.

Installation
------------

Easy installation (preferred):

1. Install the plugin from the Plugins > Add New page in your WordPress installation.

Self install:

1. Upload the `recras-wordpress-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Support
-------

We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.
