// Provides callback for AJAX call
var updateView = function(result, response) {
	// How often to update (in seconds)
	var interval = 60;
	var tempInterval = interval;

	// Set up references to loading indicator
	var loadtext = document.getElementById("loadertext");
	var loadbox = document.getElementById("loader");

	// If the AJAX call was successful
	if (result == true) {

		// Read into a buffer and display, ignoring any passed in buffer
		document.getElementById("content").innerHTML = response;

		// Hide the loading indicator and reset the update interval
		loadtext.innerHTML = "";
		loader.style.display = "none";
		tempInterval = interval;

	// AJAX call failed
	} else {

		// Display the passed in buffer if available, to avoid losing calendar
		if (typeof(response) != "undefined") {
			document.getElementById("content").innerHTML = response;
		}

		// Add some text to the loading animation and display it.
		loadtext.innerHTML = "Connection error. Retrying...";
		loader.style.display = "block";

		// Loop more quickly to recover from error condition
		tempInterval = 5;
	}

	// Request on an interval
	setTimeout(function() { ajaxcall(response) }, tempInterval * 1000);
}

// Requests calendar file
function ajaxcall(buf) {

	// Generate cache breaker
	var decache = new Date().getTime();

	// Make AJAX request
	var xmlhttp;
	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	}
	else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function() {
	var result = false;
	var response = "";

	// Display response on OK
	if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		result = true;
		response = xmlhttp.responseText;

		// Send the result to the callback
		updateView(result, response);
	}

	// Handle HTTP errors
	else if (xmlhttp.readyState==4 && xmlhttp.status != 200) {
		result = false;
		response = buf;

		// Send the result to the callback
		updateView(result, response);
	}
	}

	// Send request
	xmlhttp.open("GET","schedule.php?d=" + decache, true);
	xmlhttp.send();
}

// Displays the current date and time
function updateClock() {
	var d = new Date();
	var curr_date = d.getDate();

	// Select a suffix for the date
	var sup = "";
	if (curr_date == 1 || curr_date == 21 || curr_date == 31) {
		sup = "st";
	} else if (curr_date == 2 || curr_date == 22) {
		sup = "nd";
	} else if (curr_date == 3 || curr_date == 23) {
		sup = "rd";
	} else {
		sup = "th";
	}

	// Set options for date display
	var date_options = {
		weekday: "long", month: "long", day: "numeric"
	};

	// Set options for time display
	var time_options = {
		hour: "numeric", minute: "2-digit", second: "2-digit", hour12: true
	};

	// Generate the date string and add it to the document
	var datestring = d.toLocaleDateString("en-US", date_options) + sup + "<span style=\"float:right;\">" +
	d.toLocaleTimeString("en-US", time_options) + "</span>";
	document.getElementById("clock").innerHTML=datestring;

	// Repeat every second
		setTimeout("updateClock();", 1000);
	}

	// Begins loops
	function bootstrap() {
	ajaxcall();
	updateClock();
}
