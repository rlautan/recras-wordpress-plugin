# Changelog

## 1.15.2
* Update online booking library version (fixes prices sometimes being shown incorrectly)

## 1.15.1
* Update online booking library version (fixes online bookings that can only be paid afterwards)

## 1.15.0
* Add themes for new online booking method
* Enable "Use new library" by default
* Update online booking library version:
  - Show reasons why 'Book now' button is disabled
  - Fix disabled 'Book now' button after changing date/time
  - Fixes potential race condition

## 1.14.6
* Better loading of polyfill
* Update online booking library version (fixes minimum amount of booking size row)

## 1.14.5
* No changes. Releasing previous version failed, trying to re-release.

## 1.14.4
* Update online booking library version

## 1.14.3
* Update online booking library version

## 1.14.2
* Fix online booking library not loading properly

## 1.14.1
* Update online booking library version

## 1.14.0
* Add option to try out the new online booking library

## 1.13.0
* Add voucher sales module

## 1.12.3
* Fix contact form submission when jQuery is loaded too late

## 1.12.2
* Show error instead of crashing when package programme is empty

## 1.12.1
* Enable automatic resizing initially for availability calendar

## 1.12.0
* Add option to disable automatic resizing of online booking & availability iframes

## 1.11.5
* Fix selection of newsletters in a contact form

## 1.11.4
* Fix 500 error, sorry about that :(

## 1.11.3
* Add explanation why sometimes packages are not available

## 1.11.2
* Revert iframe change from previous version - did more harm than good

## 1.11.1
* Show more helpful errors if something goes wrong
* Fix iframe heights if there is more than one iframe on a page

## 1.11.0
* Added `[recras-availability]` shortcode to show availability calendar
* Rename "arrangement" to "package" to reflect text change in Recras
* Deprecated `[recras-arrangement]` shortcode in favour of `[recras-package]`
* New icons for TinyMCE buttons
* Fix loading icon when submitting a contact form
* Fix empty text on submit button after submitting a contact form

## 1.10.2
Fix detailed description of arrangements

## 1.10.1
Fix available arrangements for a contact form

## 1.10.0
* Don't show seconds in arrangement/product durations
* Use display name instead of internal name for arrangements

## 1.9.1 & 1.9.2
* Fix bug with iframe height

## 1.9.0
* Listen for height-update message

## 1.8.1.1
* Updated "Tested up to" version to 4.7

## 1.8.1
* Fix problem with previous version not loading

## 1.8.0
* Add image URL and description to arrangements
* The plugin is now available on Packagist, which means you can use Composer to install the plugin.
* Various small bug fixes

## 1.7.1
* The Settings page is now hidden if you don't have permission to see it.

## 1.7.0
* The online booking button now allows you to pre-select an arrangement. Only arrangements that are bookable online are included.

## 1.6.1
Fixed a bug with contact form arrangements cache

## 1.6.0
* Simplified emptying caches and added more explanation
* Arrangements in a contact form are now sorted alphabetically
* Added workaround for dropdown placeholders

## 1.5.0
Succesfully submitting a contact form will now empty the form afterwards

## 1.4.0
* Add optional date/time pickers

## 1.3.4
* Fixed redirect URL after clearing cache
* Add placeholders to textareas
* Make "Unknown" the default gender, rather than "Male"
* Fix submitting a contact form on a page that has that same form multiple times

## 1.3.3
* Sort products alphabetically
* Move stuff from Settings to a separate Recras page in the menu

## 1.3.2
* Lowered minimum required WP version
* Applied new classes to date/time inputs

## 1.3.1
Fixed online booking shortcode loading a contact form instead of the booking form

## 1.3.0
* Add caching of all external data
* Add option to use a redirect after submitting a contact form
* Remove cURL requirement (unneeded as of 1.2.1)

## 1.2.1
* Change "keuze" on a contact form from a dropdown to checkboxes (Fixes #5)
* Bypass our own serverside submit script, use XHR instead

## 1.2.0
* Add the following possible properties to products: `description_long`, `duration`, `image_url`, and `minimum_amount`.

## 1.1.0
* Only show arrangements in contact form shortcode editor that belong to that contact form
* Fix some styling issues (WP 4.4 only?)
* Show error message if a contact form does not have a field for arrangements, but one is set anyway (Fixes #3)
* If an invalid arrangement is set for a contact form, show dropdown of arrangements instead of generating an invalid form

## 1.0.0
* Add shortcode for online bookings
* Add shortcode for products
* Change the way arrangement programmes spanning multiple days are shown
* Not all arrangements are available for all contact forms - the plugin now checks if the combination is valid
* Deprecated [arrangement] shortcode in favour of [recras-arrangement]

## 0.17.1
Rename Subdomain to Recras name

## 0.17.0
* When not showing labels, don't show an empty `li`/`td`/`dt` element
* Allow contact form submit button text to be changed

## 0.16.1
Fix invalid HTML when using an `ol` or `table` for the contact form

## 0.16.0
* Don't show asterisk for required fields if labels are disabled
* Show asterisk for required fields in placeholder
* Add option for decimal separator

## 0.15.1
Move files out of assets folder, as WordPress handles this unexpectedly

## 0.15.0
* Add logo for plugin repository
* Fix readme

## 0.14.5
Workaround for array constants, which are not allowed by WordPress SVN

## 0.14.4
Add Composer autoloader to prevent users from having to install Composer

## 0.14.3
Update arrangement duration format

## 0.14.2
Add missing arrangement shortcode button options (duration, location)

## 0.14.1
* Replaced icons with GPL-compatible ones
* Update readme with more information
* Hack around not being allowed to load wp-load.php
* Translation update

## 0.14.0
Add `location` and `duration` options to arrangement shortcode

## 0.13.3
Fix translation not being loaded

## 0.13.2
Add options added in 0.13.0 to the editor shortcode generator button

## 0.13.1
Refactor

## 0.13.0
* Add option to show contact forms as lists or tables
* Add option to hide labels on contact forms
* Placeholders added on contact forms, added option to hide them

## 0.12.1
* Minor language fix
* Update Dutch translation

## 0.12.0
Selection of arrangement and contact form is now possible via a dropdown rather than manually entering the ID

## 0.11.0
WordPress' editors now have a button to insert a contact form without needing to know the syntax!

## 0.10.0
WordPress' editors now have a button to insert an arrangement without needing to know the syntax!

## 0.9.0
* Setting the `arrangement` parameter on a contact form will select this arrangement automatically and hide the field to the user.
* Fix empty option being the last option instead of the first option on arrangement dropdowns

## 0.8.0
If a contact form has an "arrangements" field, show all arrangements in a dropdown

## 0.7.1
Fix translations

## 0.7.0
* Add loading indicator when sending a contact form
* Replace contact form popups with inline text boxes
* Fix placement of error messages on pages with multiple contact forms

## 0.6.2
Fix placement of submit button on contact forms

## 0.6.1
Fix a typo

## 0.6.0
Add option to disable the header of a programme

## 0.5.1
Show notice if cURL is not installed

## 0.5.0
Add shortcode for contact forms

## 0.4.2
Unified CSS class names

## 0.4.1
Proper handling of debug mode

## 0.4.0
Add currency option, defaults to Euro (€)

## 0.3.0
* Add Dutch translation
* Wrap output of the shortcode in `<span>`s with different classes, for styling purposes

## 0.2.1
Don't `die()` on errors, but return error message instead

## 0.2.0
First version!
