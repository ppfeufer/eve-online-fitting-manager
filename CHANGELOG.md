# Change Log

## [In Development](https://github.com/ppfeufer/eve-online-fitting-manager/tree/development)
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.15.0...HEAD)
- in development

## [v0.15.0](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.15.0) - 2019-10-18
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.5...v0.15.0)
### Added
- Fleet Roles
- Post title will now be set to "Shiptype, Fittingname" if left empty/blank on saving your post

## [v0.14.5](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.5) - 2019-08-31
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.4...v0.14.5)
### Fixed
- Parsing from items with old names failed to get a propper EVE item ID and stopped with a nasty error message. (Issue: #48)
- Paresing of fittings that include ``[Empty High Slot]`` lines was broken and is now fixed as well. (Discovered while working on issue #48)

## [v0.14.4](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.4) - 2019-06-11
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.3...v0.14.4)
### Fixed
- Hotfix for new ESI client and cache database table

## [v0.14.3](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.3) - 2019-06-11
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.2...v0.14.3)
### Hotfix
- Backwards compatibility with older ESI clients on update

## [v0.14.2](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.2) - 2019-06-11
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.1...v0.14.2)
### Changed
- Code updated to work with the new release of the ESI Client

## [v0.14.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.1) - 2019-02-01
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.14.0...v0.14.1)
### Fixed
- An issue that on rare occasions the database cache tables are not created properly after moving the WordPress installation and somehow losing the cache tables. Reactivating the plugin should do the trick now.

## [v0.14.0](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.14.0) - 2018-12-27
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.13.4...v0.14.0)

This is a complete rewrite to utilize ESI, so there is no longer a need for a local EDK installation in order to get this plugin to work. Before you update, it is recommended that you backup your fittings you have already.

### Added
- ESI Client
- Database cache table for market, so we don't pollute the wp_options table to much

### Changed
- EVE Central market API deactivated, since it's is still dead
- EVE Marketer is now the default market API
- Namespaces to match WordPress's folder structure (Plugin » Plugins)
- Updated image server URL

### Fixed
- Formatted doctrine description

### Removed
- Osmium is long dead, time to remove it. RIP Osmium, you will be missed.
- Image cache, to much hassle, let's just use CCP's image server instead

## [v0.13.4](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.13.4) - 2018-05-09
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.13.3...v0.13.4)
### Added
- Option for using image cache or CCPs image server

### Fixed
- Buttons automatically arranged in fitting view
- Number of ships displayed at once is no longer limited by WordPress settings (this should fix issue #37)

## [v0.13.3](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.13.3) - 2017-10-30
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.13.2...v0.13.3)
### Fixed
- Cached images will be renewed when file size is 0 (broken images)

### Added
- Athanor and Tatara to the list of Upwell structures

## [v0.13.2](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.13.2) - 2017-10-17
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.13.1...v0.13.2)
### Changed
- Ship types overview now as well in the new look
- Moved some code into its own template

### Fixed
- Images from "Render" endpoint now using the right cache directory

## [v0.13.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.13.1) - 2017-10-11
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.13...v0.13.1)
### Fixed
- Ships are now in alphabetical order again

## [v0.13](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.13) - 2017-10-10
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.12...v0.13)
### Changed
- Tweaked the overview of ships in a doctrine a bit to make it more clear when ships belong to a "sub" doctrine like Logis for example.

## [v0.12](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.12) - 2017-09-16
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.11...v0.12)
### Added
- Settings for Market API

### Fixed
- Translations

## [v0.11](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.11) - 2017-08-24
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.10...v0.11)
### Changed
- Moved cache directory to a more common place

## [v0.10](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.10) - 2017-08-23
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.9.1...v0.10)
### Added
- Widget to search the fittings
- Widget to navigate through the doctrines
- Widget to navigate through the ship types

### Changed
- Sidebar for the fitting pages is now a widget area
- Content of the former static sidebar is now available in 3 widgets

## [v0.9.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.9.1) - 2017-08-22
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.9...v0.9.1)
### Fixed
- An issue determining the image server end point

## [v0.9](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.9) - 2017-08-22
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.8...v0.9)
### Changed
- Merged 2 classes into 1, so it makes more sense

## [v0.8](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.8) - 2017-08-20
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.7...v0.8)
### Added
- Implants and Booster to EFT data

## [v0.7](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.7) - 2017-08-18
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.6...v0.7)
### Changed
- More reliable way to create the cache directories

### Fixed
- Error: Class `WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\EveOnlineFittingManager\Libs\Database` not found

## [v0.6](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.6) - 2017-08-18
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.5.1...v0.6)
### Changed
- Switched codebase to short array syntax
- Restructured file base (makes more sense now ...)

### Removed
- Last fragments of am earlier change

## [v0.5.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.5.1) - 2017-08-15
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.5...v0.5.1)
### Changed
- Ship description now formatted in proper paragraphs

### Removed
- A check that doesn't make sense in the way we are working with it

## [v0.5](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.5) - 2017-08-13
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.4...v0.5)
### Fixed
- PHP Fatal error: Class 'WordPress\\Plugin\\EveOnlineKillboardWidget\\Singleton\\AbstractSingleton' not found

## [v0.4](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.4) - 2017-08-12
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.3...v0.4)
### Changed
- Writing empty index.php files into our cache directories, so the directory listing doesn't work there
- CacheHelper file permissions for dummy index.php

## [v0.3](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.3) - 2017-08-08
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.2...v0.3)
### Fixed
- Some minor JS issues (code optimization)
- Missing row around the market data table

### Changed
- Thumbnails to a more reasonable size, they are now 16:9 (Please use a plugin like [Force Regenerate Thumbnails](https://de.wordpress.org/plugins/force-regenerate-thumbnails/) to update your thumbnails after updating to this plugin version)

## [v0.2](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.2) - 2017-08-05
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1...v0.2)
### Added
- Loading animation while waiting for market data

### Changed
- Load CSS and JS only when it's really needed
- Osmium is working again, so it's back on the menu
- Fitting detail page now modularized, that means you can now influence which information will be shown

## [v0.1](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1) - 2017-08-02
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170730...v0.1)
### Added
- First official release
- Copy to clipboard (EFT data and Permalink)
- Fuel to EFT data
- New tab with ship description

### Changed
- Market Data is now loaded via ajax call to boost the performance a bit

## [v0.1-r20170730](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170730) - 2017-07-30
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170724...v0.1-r20170730)
### Added
- Hint when a fitting is not used in any doctrine in frontend
- New Meta Box when editing a fitting: "Mark fitting as" Conceptual Fitting, Fitting Idea, Under Discussion

### Changed
- EFT Import and Fitting Information are now tabbed
- Modularized detail template into different templates to make it easier to handle them in the future
- Tech 3 Cruiser now only have 4 subsystems (now that finally the EDK update reflecting these changes is available (thanks to Salvoxia for his permanent work on the EDK stuff) / update your KB first)

## [v0.1-r20170724](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170724) - 2017-07-24
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170719...v0.1-r20170724)
### Added
- Service Slots to Upwell Structures

### Changed
- Introduced dynamic autoloader for classes

### Fixed
- Warning: ksort() expects parameter 1 to be array, null given in FittingHelper class
- Search result is now sorted alphabetically
- Fitting visualization is now responsive
- Plugindir and Pluginpath detection

## [v0.1-r20170719](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170719) - 2017-07-19
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170711...v0.1-r20170719)
### Added
- New option to show the featured images (if you use them) of your fittings in the fitting overview

#### Changed
- Switched Market API to EVE Central
- Major code overhaul
- Visualized an open menu in it's caret as well

## [v0.1-r20170711](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170630) - 2017-07-11
[Full Changelog](https://github.com/ppfeufer/eve-online-fitting-manager/compare/v0.1-r20170708...v0.1-r20170711)
### Changed
- reworked market data stuff to display prices for ships, fittings and total price

### Fixed
- Javascript for highlighting the current and active doctrines in the sidebar menues
- An issue with the dropdown menu on mobile devices where it was not possible to tap on the submenu items to navigate to them

## [v0.1-r20170127](https://github.com/ppfeufer/eve-online-fitting-manager/releases/tag/v0.1-r20170708) - 2017-07-08
### Changed
- First "unofficial" release, still not considered final or stable or what ever :-)

### Added
- Metabox for EFT fitting
- Templates
- Search function
- EDK killboard database functions
- Imagecache
- Sidebar filter - Doctrines
- Sidebar filter - Shiptypes
- Shortcode ```[fittings]```
- Market price informations
