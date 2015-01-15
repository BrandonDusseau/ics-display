<?php
require "ical-reader.php";

$number_of_events = 7;	// Number of events to display
$days_back = 7;			// Number of days to show in the past
$days_forward = 14;		// Number of days to show in the future

$highlight_today = false;						// Whether to highlight events occurring today
$highlight_calendars = array("exams");			// Calendars to always highlight
$darken_past = false;							// Whether to darken incomplete items in the past
$darken_calendars = array();		// Calendars to always darken
$skip_string = "#CMP";								// If this string is present in an item's description, it will be skipped. Use an empty string to disable this.

// Set up calendar URLs - use private ICS links from Google Calendar
$calendar_urls = array(
	"assignments" => "***REMOVED***",
	"exams" => "***REMOVED***"
);

// Create empty array for all relevant events
$result_array = array();

// Add both assignments and exams to the array
foreach ($calendar_urls as $name => $url) {
	$this_ical = new ical($url);
	
	$item_count = 0;
	
	// Loop through events
	foreach($this_ical->events() as $event) {
		// Set array index as date
		$target =  date("Ymd",strtotime($event['DTSTART']));
		
		// Skip items out of the date range
		if (strtotime($event['DTSTART']) < strtotime("-" . $days_back . " days") ||
			strtotime($event['DTSTART']) > strtotime("+" . $days_forward . " days")) {
			continue;
		}
		
		// If the item is marked completed, ignore it
		if (!empty($skip_string) && strpos($event["DESCRIPTION"], $skip_string) !== false) {
			continue;
		}
	
		// Create empty array object for the date if it does not yet exist
		if (empty($result_array[$target])) { 
			$result_array[$target] = array();
		}
		
		// Add to the array index
		array_push($result_array[$target], array(
			"time" => date("g:i a",strtotime($event['DTSTART'])),
			"date" => date("l, F j", strtotime($event['DTSTART'])),
			"timestamp" => $event['DTSTART'],
			"desc" => $event["DESCRIPTION"],
			"title" => $event["SUMMARY"],
			"calendar" => $name
		));
	}
}

// Sort the remaining array of events by key to put dates in ascending order
ksort($result_array);

$item_count = 0;
$stop = false;
// Cycle through each date and display it
foreach ($result_array as $events) {
	// If we've displayed the maximum number of events, stop.
	if ($stop) {
		break;
	}
	
	// Display the container for the date
    echo "<div class='day'>\n" .
		"\t<div class='heading'>". $events[0]['date'] ."</div>\n" .
		"\t<div class='events'>\n";
	
	// Since ICS gives us events in reverse, reverse the array
	$events = array_reverse($events);
	
	// Loop through each event on this date
    foreach ($events as $event) {
		// If we've displayed the maximum number of events, stop.
		if ($stop) {
			break;
		}
	
		// If we have enough items, complete this iteration and stop.
		$item_count++;
		if ($item_count == $number_of_events) {
			$stop = true;
		}
		
		$class = ""; // Default the extra classes on the event to none.
		
		// Highlight designated calendars
		if (!empty($higlight_calendars) && in_array($event['calendar'], $highlight_calendars)) {
			$class .= " highlight";
		}
		
		// highlight events occurring today
		if ($highlight_today && strtotime($event['timestamp']) < strtotime("tomorrow") 
			&& strtotime($event['timestamp']) >= strtotime("today")) {
			$class .= " highlight";
		}
		
		// Darken designated calendars
		if (!empty($darken_calendars) && in_array($event['calendar'], $darken_calendars)) {
			$class .= " darken";
		}
		
		// Darken events in the past
		if ($darken_past && strtotime($event['timestamp']) < strtotime("now")) {
			$class .= " darken";
		}
		
		// Display the event
		echo "\t\t<div class='events-inner $class'>\n";
		echo "\t\t\t<div class='time'>" . $event['time'] . "</div>\n";
		echo "\t\t\t<div class='event'>" . $event['title'] . "</div>\n";
		echo "\t\t</div>\n";
    }
	
    echo "\t</div>\n</div>\n";
}

?>
