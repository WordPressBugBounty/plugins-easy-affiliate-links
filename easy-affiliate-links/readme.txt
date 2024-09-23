=== Easy Affiliate Links ===
Contributors: BrechtVds
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QG7KZMGFU325Y
Tags: affiliate, links, cloaking, shortlink
Requires at least: 3.5
Tested up to: 6.6
Stable tag: 3.7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily manage and cloak all your affiliate links.

== Description ==

Easy Affiliate Links helps you manage all the affiliate links on your website. Both cloaked pretty links and regular non-cloaked links. Clicks get tracked for your links automatically.

Learn more on [our website](https://bootstrapped.ventures/easy-affiliate-links/) and in [our knowledge base](https://help.bootstrapped.ventures/collection/133-easy-affiliate-links).

Current features:

*   Compatible with both the Classic Editor and new **Gutenberg** Block Editor
*   Add affiliate links in **Elementor** using their text widget
*   Use regular links or **affiliate HTML code**
*   Create **shortlinks** to optionally cloak your affiliate links
*   Use **ugc and sponsored** attributes for your links
*   Leave specific links uncloaked for **Amazon compatibility**
*   **Automatic text disclaimer** for your affiliate links
*   Easily access your links in the **visual and html editor**
*   Assign **categories** to your links
*   Tracking of monthly and lifetime **click counts**
*   **Import affiliate links** from XML and CSV
*   Ability to **export your links** to XML and CSV
*   Use a CSV export and import to **easily update your links in bulk**

= Easy Affiliate Links Premium =

Looking for some more advanced functionality? We also have the [Easy Affiliate Links Premium](http://bootstrapped.ventures/easy-affiliate-links/get-the-plugin/) add-on available with the following features:

*   Get valuable insights with **click statistics and charts**
*   Automatic **broken links checker** with email notifications
*   Show an **automatic tooltip disclaimer** when hovering over links
*   **Conditional geo or device targeted* links
*   Use **Replacement Links** to (temporarily) swap affiliate links

This plugin is under active development, so just [let us know](https://help.bootstrapped.ventures/article/41-how-can-i-contact-support) if you have any requests at all!

== Installation ==

1. Upload the `easy-affiliate-links` directory (directory included) to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check out [our getting started documentation](https://help.bootstrapped.ventures/category/136-getting-started)

== Frequently asked questions ==

= Can I track affiliate links in Google Analytics? =

We recommend using the free [Google Analytics by Yoast](https://wordpress.org/plugins/google-analytics-for-wordpress/) plugin for this. You'll have to enable "Track outbound click and downloads" on its settings page and set the slug you're using for your affiliate links in the advanced "Set path for internal links to track as outbound links" setting as well.

= Where can I find more information? =

[Our website](https://bootstrapped.ventures/easy-affiliate-links/) and [our knowledge base](https://help.bootstrapped.ventures/collection/133-easy-affiliate-links).

== Screenshots ==

1. Affiliate links integrated into the Gutenberg Block Editor paragraphs
2. Compatible with Classic Editor as well
3. Easily add affiliate links to any post or page by clicking on the icon
4. Full control over each affiliate link's options
5. Powerful overview page to manage, analyze and bulk edit your links

== Changelog ==
= 3.7.4 =
* Fix: Prevent misuse of tools by users that don't have access to that page

= 3.7.3 =
* Fix: Check if user has unfiltered_html capability to prevent some security risks
* Fix: Unicode in CSV export
* Fix: Missing links in CSV export

= 3.7.2 =
* Fix: Searching and ordering by description on the manage page
* Fix: Initial results when no match is found for the selected text
* Fix: Deprecation warning

= 3.7.1 =
* Fix: Sanitizing affiliate link block additional classes

= 3.7.0 =
* Improvement: Set default value for use sponsored/ugc attributes
* Improvement: Setting to change default page size on the manage page
* Fix: | character in destination URL
* Fix: Prevent whitespace at start of destination link
* Fix: Console error log in Customizer
* Fix: Gutenberg deprecations

= 3.6.0 =
* Feature: Elementor text widget compatibility
* Feature: Export links to CSV for bulk editing
* Feature: Update existing links when using the import from CSV feature
* Feature: Link categories in CSV export and import
* Improvement: Quickly view or edit post on the Manage > Usage page
* Improvement: Don't trim spaces when searching on the manage page
* Improvement: Excluded affiliate links from sitemap by default
* Improvement: Update CrawlerDetect
* Improvement: Don't add clicks from excluded IPs to raw data
* Fix: Clicks clean up not loading different pages in some cases
* Fix: Clicks clean up not handling all clicks in one run
* Fix: Recalculate click summary after cleaning up links
* Fix: Prevent other plugins from breaking our API endpoints

= 3.5.0 =
* Feature: Automatic text disclaimer for affiliate links
* Feature: WP Ultimate Post Grid integration
* Feature: Choose post types for "Find Link Usage" tool
* Feature: WP Recipe Maker integration for "Find Link Usage" tool
* Improvement: Insert shortcode at cursor when using classic text editor
* Fix: Manage capability setting problem

= 3.4.1 =
* Feature: Bulk edit sponsored and UGC attributes through the Manage page
* Improvement: Sponsored and UGC columns on the manage page
* Fix: Problem with tracking clicks for uncloaked links

= 3.4.0 =
* Feature: Use HTML code for affiliate link instead of regular URL
* Feature: Easy Affiliate Link block for Block Editor (Gutenberg)
* Feature: Set custom CSS classes for affiliate links
* Improvement: Remove jQuery dependency for public JS
* Fix: Find Link Usage for Block Editor posts
* Fix: TablePress plugin compatibility
* Fix: Icons on Manage page not showing up on some server configurations

= 3.3.0 =
* Feature: Set sponsored and ugc attributes
* Feature: Foundation for integration with our WP Recipe Maker plugin
* Improvement: Better compatibility when using affiliate link in other plugins
* Improvement: Default search for affiliate link when text is selected
* Fix: HTML in affiliate link text
* Fix: Don't register click for excluded roles

= 3.2.0 =
* Feature: Link usage on manage page
* Feature: Change required capability for different admin sections
* Fix: Click reset not reloading manage page
* Fix: Make sure click database exists and is registering clicks

= 3.1.1 =
* Fix: Category filter on manage page
* Fix: Some translations not using correct domain

= 3.1.0 =
* Feature: Plugin hooks for redirect and click
* Feature: Tools page to reset settings
* Improvement: Updated manage page
* Improvement: Bulk edit categories on manage page
* Improvement: Clicks on manage page
* Improvement: Don't hide affiliate link icon under dropdown in Block Editor
* Improvement: Updated check for crawlers
* Fix: Prevent click overview page from breaking
* Fix: Classic Editor Button capability setting
* Fix: Internet Explorer compatibility for modal
* Fix: Plugin activation problem in wp-cli

= 3.0.0 =
* Feature: New manage page with search, filters and bulk editing
* Feature: Import affiliate links from CSV
* Improvement: Better and faster input form
* Improvement: New settings page

= 2.6.7 =
* Fix: Problem when combining regular and affiliate links in Gutenberg

= 2.6.6 =
* Fix: Remove debug code

= 2.6.5 =
* Feature: Compatible with the new Gutenberg editor

= 2.6.4 =
* Fix: Prevent blank page on redirect in some environments

= 2.6.3 =
* Improvement: WordPress 5.0 compatibility
* Improvement: Add no-index meta tag to the redirect page
* Improvement: Try to prevent click register issues from breaking redirect

= 2.6.2 =
* Feature: Setting to store IP address as hash
* Feature: Personal Data Exporter
* Improvement: Privacy policy content
* Improvement: Update CrawlerDetect to latest version
* Improvement: Update BrowserDetect to latest version

= 2.6.1 =
* Fix: Redirect type issue with old affiliate links

= 2.6.0 =
* Feature: Setting for noopener and noreferrer attributes
* Improvement: Always redirect shortlink, even when not actually cloaked, to prevent broken links
* Fix: Problem with default redirect type using 302

= 2.5.0 =
* Feature: Link cloaking is now optional (compatible with Amazon ToS)
* Feature: Use IP ranges when cleaning up clicks
* Feature: Clean up clicks by users with a specific role
* Feature: Ability to remove all clicks
* Improvement: Better handling of accents in shortlink slug

= 2.4.1 =
* Improvement: WordPress 4.8 compatibility
* Fix: Problem with default link target
* Fix: AddThis plugin compatibility problem

= 2.4.0 = 
* Improvement: Add class to affiliate link output
* Improvement: Spacing on manage page to prevent accidental clicks
* Fix: Don't add modal content to WordFence pages

= 2.3.0 =
* Feature: Clean up clicks and exclude IPs
* Improvement: Stay on correct page after reloading datatable
* Improvement: More details on Statistics page
* Improvement: Better compatibility with other plugins for the modal
* Fix: Broken JavaScript dependencies

= 2.2.0 =
* Feature: View details of last click data
* Improvement: Better check for crawlers
* Fix: Datatables errors on some other plugin pages
* Fix: Prevent datatables errors from showing up as alert
* Fix: EAFL Button when visual editor is disabled

= 2.1.0 =
* Feature: Order by lifetime clicks on manage page
* Improvement: Custom database for link clicks
* Improvement: Max width for the description in the overview
* Fix: Escaping for XML export
* Fix: Wordfence compatibility issue

= 2.0.1 =
* Improvement: Affiliate links show up when adding links
* Improvement: Use selected text as default option for affiliate link
* Improvement: Use selected text when creating new link
* Improvement: Automatically flush permalinks
* Fix: Problem with URLs containing encoded characters

= 2.0.0 =
* Plugin built from the ground up with cleaner and leaner code
* Performance improvements
* Support for upcoming add-ons

= 1.4 =
* Feature: Copy shortlink to clipboard button
* Improvement: Use dashicon instead of image in menu
* Fix: Prevent issue with other plugins or themes using Browser
* Fix: PHP7 warnings caused by the VafPress library

= 1.3 =
* Improvement: Streamline link selection process
* Improvement: Setting to disable click counter
* Improvement: Setting to decide what to show in the link column on the admin overview page
* Improvement: Ability to filter link values via plugin hook before saving

= 1.2 =
* Feature: Import affiliate links from XML
* Feature: Export affiliate links to XML
* Feature: Private description field for affiliate links
* Improvement: Ability to reset click counters
* Improvement: selected text in Visual Editor available as text variant
* Fix: Cannot edit shortcode preview in Visual Editor anymore

= 1.1 =
* Feature: Use custom link text in the shortcode
* Feature: Define multiple link text variants
* Setting: Set required capability for seeing the shortcode button
* Fix: Exclude affiliate links from search to prevent redirect
* Fix: A few unwanted PHP notices
* Fix: Cache update after saving an affiliate link
* Fix: @ sign in mailto urls
* Fix: Removed trailing slash in URLs for consistency

= 1.0 =
* Feature: Edit an affiliate link by clicking on it in the visual editor
* Feature: Tracking of monthly and lifetime clicks

= 0.0.1 =
* Very first version of this plugin

== Upgrade notice ==
= 3.7.4 =
Update recommended to fix a potential security risk

= 3.7.3 =
Update recommended to fix a potential security risk

= 3.7.2 =
Update to esnure WordPress 6.3 compatibility

= 3.7.1 =
Update recommended to fix a potential security risk

= 3.7.0 =
Some smaller improvements and fixes

= 3.6.0 =
Big update with new features and improvements

= 3.5.0 =
Some great new features and improvements

= 3.4.1 =
Update to make sure uncloaked links get tracked correctly

= 3.4.0 =
Update for some new features and improvements

= 3.3.0 =
Update for better compatibility with other plugins

= 3.2.0 =
A few improvements and fixes

= 3.1.1 =
Update with 2 smaller fixes

= 3.1.0 =
Update to esnure WordPress 5.3 compatibility

= 3.0.0 =
Update for an upgraded user interface

= 2.6.7 =
Update highly recommend to prevent link bug

= 2.6.6 =
Update recommended when using Gutenberg

= 2.6.5 =
Update to ensure Gutenberg compatibility

= 2.6.4 =
Update recommend to make sure cloaked redirects work

= 2.6.3 =
Update for a few improvements and WordPress 5.0 compatibility

= 2.6.2 =
Update for privacy improvements with the new GDPR regulations

= 2.6.1 =
Update to fix redirect type for old affiliate links

= 2.6.0 =
Update to fix an issue with the redirect type

= 2.5.0 =
Update to be able to output non-cloaked links

= 2.4.1 =
Update to ensure the link target is what you want it to be

= 2.4.0 =
Update to prevent WordFence compatibility issues

= 2.3.0 =
Update required to use our new Statistics add-on

= 2.2.0 =
Update to fix potential issues with other plugins

= 2.1.0 =
Update for some performance improvements

= 2.0.1 =
Minor update with improvements to make sure the plugin works like the previous version

= 2.0.0 =
Update for a performance boost, especially when using lots of affiliate links

= 1.4 =
Update for an easier way to copy the shortlink

= 1.3 =
Update for a few nice improvements and WordPress 4.5 compatibility

= 1.2 =
Update for plenty of new features

= 1.1 =
Update recommend, some new features and bug fixes

= 1.0 =
Update for some new features

= 0.0.1 =
First version, no upgrades needed.