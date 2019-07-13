# Changelog

## 1.2.2 - 12 July 2019
 * Added support for time zone override
 * Upgraded iCal library
 * **PHP requirement has been raised to 5.3.9**

## 1.2.1 - 13 December 2016
 * Fixed connection error graphic not appearing sometimes (#1).

## 1.2.0 - 13 December 2016
 * Upgraded iCal library
 * Added support for recurring events (#2)

## 1.1.2 - 4 March 2015
 * Darkened colors on both dark events and dark highlighted events for
   consistency with darkened timestamp.

## 1.1.1 - 4 March 2015
 * Added "darkening" of title for highlighted events.

## 1.1.0 - 26 January 2015
 * Added calendar caching; if calendar is loaded and connection is lost,
   calendar will remain present until the page is reloaded.
 * Removed bulky error message and added notification at bottom right.
 * If connection is lost, the script attempts to reload the calendar at a
   shorter interval.
 * Added proper DOCTYPE and character encoding to HTML document
 * AJAX request now uses callbacks to allow for more flexibility.
