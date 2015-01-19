<?php
ini_set("display_errors", "true");
error_reporting(E_ERROR);

// Load library for parsing iCal files
require_once("ical-reader.php");

// Provides calendar settings
require_once("config.php");

// Create empty array for all relevant events
$result_array = array();
$failed = false;

// Add both assignments and exams to the array
foreach ($calendar_urls as $name => $url) {
	$this_ical = new ical($url);

	// Skip calendar if it cannot load
	if ($this_ical === false) {
		$failed = true;
		continue;
	}

	$item_count = 0;

	// Loop through events
	foreach($this_ical->events() as $event) {
		// Set array index as date
		$target =  date("Ymd",strtotime($event['DTSTART']));

		// Skip items out of the date range
		if (($days_back != -1 && strtotime($event['DTSTART']) < strtotime("-" . $days_back . " days")) ||
			($days_forward != -1 && strtotime($event['DTSTART']) > strtotime("+" . $days_forward . " days"))) {
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

// Display an error if we failed to read a calendar
if ($failed && $show_ical_errors) {
	echo "<div class='day'>\n\t<div class='heading error'>Error</div>\n\t<div class='events'>\n" .
		"\t\t<div class='events-inner'>Unable to read one or more calendars.</div>\n" .
		"\t</div>\n</div>\n";
}

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

	// Since ICS gives us events in an arbitrary order, rearrange them
	usort($events, function ($a, $b) {
		return date("U", strtotime($a['timestamp'])) - date("U", strtotime($b['timestamp']));
	});

	// Loop through each event on this date
    foreach ($events as $event) {
		// If we've displayed the maximum number of events, stop.
		if ($stop) {
			break;
		}

		// If we have enough items, complete this iteration and stop.
		$item_count++;
		if ($number_of_events != -1 && $item_count == $number_of_events) {
			$stop = true;
		}

		$class = ""; // Default the extra classes on the event to none.

		// Highlight designated calendars, those with the appropriate keyword, and today's events, if configured.
		if ((!empty($highlight_calendars) && in_array($event['calendar'], $highlight_calendars)) ||
			($highlight_today && strtotime($event['timestamp']) < strtotime("tomorrow")
				&& strtotime($event['timestamp']) >= strtotime("today")) ||
			(!empty($highlight_keyword) && strpos($event["desc"], $highlight_keyword) !== false)) {

			$class .= " highlight";
		}

		// Darken designated calendars, those with the appropriate keyword, and past events, if configured.
		if ((!empty($darken_calendars) && in_array($event['calendar'], $darken_calendars)) ||
			($darken_past && strtotime($event['timestamp']) < strtotime("now")) ||
			(!empty($darken_keyword) && strpos($event["desc"], $darken_keyword) !== false)) {

			$class .= " darken";
		}

		// Display the event
		echo "\t\t<div class='events-inner $class'>\n" .
			"\t\t\t<div class='time'>" . $event['time'] . "</div>\n" .
			"\t\t\t<div class='event'>" . $event['title'] . "</div>\n" .
			"\t\t</div>\n";
    }

    echo "\t</div>\n</div>\n";
}

?>
