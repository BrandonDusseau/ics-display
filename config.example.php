<?php
$number_of_events = 7;	// Number of events to display
$days_back = 7;			// Number of days to show in the past
$days_forward = 14;		// Number of days to show in the future

$highlight_today = false;				// Whether to highlight events occurring today
$highlight_calendars = array("cal1");	// Calendars to always highlight
$highlight_keyword = "#PRI";			// Items with this keyword in the description will be highlighted. Use an empty string to disable this.

$darken_past = true;					// Whether to darken incomplete items in the past
$darken_calendars = array();			// Calendars to always darken
$darken_keyword = "#DARK";				// Items with this keyword in the description will be darkened. Use an empty string to disable this.

$skip_string = "#CMP";					// If this string is present in an item's description, it will be skipped. Use an empty string to disable this.

$show_ical_errors = true;				// Displays an error if an iCal feed has failed to be read

// Calendar URLs - use private ICS links from Google Calendar or another calendar service.
// The key of each array entry will specify the calendar name used in the settings above.
$calendar_urls = array(
	"cal1" => "http://www.example.com/cal1.ics",
	"cal2" => "http://www.example.com/cal2.ics"
);

?>