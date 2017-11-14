# Recras WordPress plugin

[![Build Status](https://travis-ci.org/Recras/recras-wordpress-plugin.svg?branch=master)](https://travis-ci.org/Recras/recras-wordpress-plugin)
[![Minimum PHP Version](https://img.shields.io/badge/php->%3D%205.4-8892BF.svg)](https://php.net/)

Easily integrate data from your Recras instance, such as packages and contact forms, into your own website.

## Usage
With this plugin, you can easily integrate data from your [Recras](https://recras.nl/) instance, such as packages and contact forms, into your own website.

To get started, go to the Recras -> Settings page and enter your Recras name. For example, if you log in to Recras at `https://mysite.recras.nl/` then your Recras name is `mysite`. Since currently only publicly available data can be used in this plugin, that's all there is to it! You can now use shortcodes to retrieve data. All data is retrieved via a secured connection (HTTPS) to ensure data integrity. Other than the request parameters, no data is sent to the Recras servers.

This plugin consists of four shortcodes. To use them, you first need to set your Recras name on the Recras -> Settings page.

### Settings
Setting your Recras name is the most important step here. Other settings are:

* Currency symbol - set to € (Euro) by default.
* Decimal separator - set to , (comma) by default. Used in prices such as 100,00.
* Use date/time picker script - please see the Date/Time picker section for details.

### Packages

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
  * title - shows the title of the package
* starttime - only used for `programme`, determines the starting time of a package. If not set, it will default to 12:00
* showheader - only used for `programme`, determines if the header should be shown. Enabled by default, to disable use `false`, `0`, or `no` as value.

Example: `[recras-package id="1" show="title"]` (quotation marks around parameters are optional) will show the title of the package with ID 1.

### Contact forms
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

### Online booking
Add the `[recras-booking]` shortcode anywhere on your site. The following parameters are supported:
* id - corresponds to the contact form ID in Recras

Example: `[recras-booking id=17]` will show the booking form with ID 17.

### Products
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

### Date/Time picker
By default, date and time pickers use whatever the browser has available. Currently (March 2017) Internet Explorer (all versions), Firefox (all versions), and Safari (desktop) do not have native date/time pickers and only see a text field. We have included a modern looking date/time picker that you can enable on the Recras -> Settings page.

### Styling
No custom styling is applied by default, so it will integrate with your site easily. If you want to apply custom styling, see `style.css` for all available classes. Be sure to include these styles in your own theme, this stylesheet is not loaded by the plugin!
Styling for the date/time picker can be based off of `datetimepicker/bootstrap-material-datetimepicker.css`. Be sure to make any changes in your own theme, as this file may be overwritten when a new version of the plugin is released.

### Cache
All data from your Recras is cached for up to 24 hours. If you make important changes, such as increasing the price of a product, you can clear the cache to reflect those changes on your site immediately.

## Installation

Easy installation (preferred):

1. Install the plugin from the Plugins > Add New page in your WordPress installation.

Self install:

1. Upload the `recras-wordpress-plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Using Composer:

1. Type `composer require recras/recras-wordpress-plugin` in your terminal
1. The plugin will automatically be installed in the `/wp-content/plugins/` directory by using Composer Installers
1. Activate the plugin through the 'Plugins' menu in WordPress

## Support

We would appreciate it if you use [our GitHub page](https://github.com/Recras/recras-wordpress-plugin/issues) for bug reports, pull requests and general questions. If you do not have a GitHub account, you can use the Support forum on wordpress.org.

We only support the plugin on the latest version of WordPress (which you should always use anyway!) and only on [actively supported PHP branches](php.net/supported-versions.php).

## Credits
* Icons from [Genericons Neue](https://github.com/Automattic/genericons-neue) by Automattic, released under the GPL.
* Date/Time picker by [T00rk](https://github.com/T00rk/bootstrap-material-datetimepicker), released under the MIT licence.
