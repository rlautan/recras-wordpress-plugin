Recras WordPress plugin
=======================

[![Build Status](https://travis-ci.org/Recras/recras-wordpress-plugin.svg?branch=master)](https://travis-ci.org/Recras/recras-wordpress-plugin)

Easily integrate your Recras data into your own website

Usage
-----

Add the [arrangement] shortcode anywhere on your site. The following parameters are supported:
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
