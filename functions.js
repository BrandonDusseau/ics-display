// Requests calendar file
function ajaxcall() {
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
	
	xmlhttp.onreadystatechange=function() {
		// Display response on OK
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("content").innerHTML=xmlhttp.responseText;
		}
		
		// Handle HTTP errors
		else if (xmlhttp.readyState==4 && xmlhttp.status != 200) {
			document.getElementById("content").innerHTML = 
				"<div class='day'>\n\t<div class='heading error'>Error</div>\n\t<div class='events'>\n" +
				"\t\t<div class='events-inner'>Failed to load data from server. Please check the network connection.</div>\n" +
				"\t</div>\n</div>\n";
		}
	}
	
	// Send request
	xmlhttp.open("GET","schedule.php?d=" + decache,true);
	xmlhttp.send();
	
	// Request every 60 seconds
	setTimeout("ajaxcall();", 60000);
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
