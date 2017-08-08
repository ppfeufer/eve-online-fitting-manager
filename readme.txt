=== EVE Online Fitting Manager for WordPress ===
Contributors: ppfeufer
Tags: Eve Online, Fitting, Shortcode, Bootstrap
Requires at least: 4.7
Tested up to: 4.9-alpha
Stable tag: 0.3
License: GPLv3
License URI: https://github.com/ppfeufer/eve-online-fitting-manager/blob/master/LICENSE

EVE Online doctrine fitting management for WordPress

== Description ==
Build your doctrine fitting overview for your corporation, alliance or even coalition in your WordPress website. Manage doctrines and fittings comfortable in your WordPress backend.

== Installation ==
1. [download the archive](https://github.com/ppfeufer/eve-online-fitting-manager/archive/master.zip) or one of the [releases](https://github.com/ppfeufer/eve-online-fitting-manager/releases)
1. unzip it
1. rename the folder to \"eve-online-fitting-manager\" (this step is important)
1. copy the folder into your plugin directory in your WordPress installation.
1. go to your WordPress Backend, select Plugins and activate it
1. use the Settings -> EVE Online Fitting Manager  screen to configure the plugin
1. create a page and select the \"Fittings\" Template for this page
1. as last step go to \"Settings\" -> \"Permalinks\" and hit the \"Save Change\" button in order to make sure everything works just fine

== Screenshots ==
1. Doctrine Overview
2. Doctrine Ships Overview
3. Fitting Details

== Changelog ==
= [v0.3](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.3) - 2017-08-08 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.2...v0.3)
### Fixed
- Some minor JS issues (code optimization)
- Missing row around the market data table

### Changed
- Thumbnails to a more reasonable size, they are now 16:9 (Please use a plugin like [Force Regenerate Thumbnails](https://de.wordpress.org/plugins/force-regenerate-thumbnails/) to update your thumbnails after updating to this plugin version)

= [v0.2](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.2) - 2017-08-05 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1...v0.2)
### Added
* Loading animation while waiting for market data

### Changed
* Load CSS and JS only when it\'s really needed
* Osmium is working again, so it\'s back on the menu
* Fitting detail page now modularized, that means you can now influence which information will be shown

= [v0.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1) - 2017-08-02 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170730...v0.1)
### Added
* First official release
* Copy to clipboard (EFT data and Permalink)
* Fuel to EFT data
* New tab with ship description

### Changed
* Market Data is now loaded via ajax call to boost the performance a bit

= [v0.1-r20170730](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170730) - 2017-07-30 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170724...v0.1-r20170730)
### Added
* Hint when a fitting is not used in any doctrine in frontend
* New Meta Box when editing a fitting: \"Mark fitting as\" Conceptual Fitting, Fitting Idea, Under Discussion

### Changed
* EFT Import and Fitting Information are now tabbed
* Modularized detail template into different templates to make it easier to handle them in the future
* Tech 3 Cruiser now only have 4 subsystems (now that finally the EDK update reflecting these changes is available (thanks to Salvoxia for his permanent work on the EDK stuff) / update your KB first)

= [v0.1-r20170724](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170724) - 2017-07-24 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170719...v0.1-r20170724)
### Added
* Service Slots to Upwell Structures

### Changed
* Introduced dynamic autoloader for classes

### Fixed
* Warning: ksort() expects parameter 1 to be array, null given in FittingHelper class
* Search result is now sorted alphabetically
* Fitting visualization is now responsive
* Plugindir and Pluginpath detection

= [v0.1-r20170719](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170719) - 2017-07-19 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170711...v0.1-r20170719)
### Added
* New option to show the featured images (if you use them) of your fittings in the fitting overview

#### Changed
* Switched Market API to EVE Central
* Major code overhaul
* Visualized an open menu in it\'s caret as well

= [v0.1-r20170711](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170630) - 2017-07-11 =
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170708...v0.1-r20170711)
### Changed
* reworked market data stuff to display prices for ships, fittings and total price

### Fixed
* Javascript for highlighting the current and active doctrines in the sidebar menues
* An issue with the dropdown menu on mobile devices where it was not possible to tap on the submenu items to navigate to them

= [v0.1-r20170127](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170708) - 2017-07-08 =
### Changed
* First \"unofficial\" release, still not considered final or stable or what ever :-)

### Added
* Metabox for EFT fitting
* Templates
* Search function
* EDK killboard database functions
* Imagecache
* Sidebar filter - Doctrines
* Sidebar filter - Shiptypes
* Shortcode `[fittings]`
* Market price informations
