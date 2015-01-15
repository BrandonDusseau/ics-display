function ajaxcall() {
	var decache = new Date().getTime();
	var xmlhttp;
	if (window.XMLHttpRequest) {
	  xmlhttp=new XMLHttpRequest();
	  }
	else {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("content").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","schedule.php?d=" + decache,true);
	xmlhttp.send();
	
	setTimeout("ajaxcall();", 60000);
}

function updateClock() {
	var d = new Date();
	
	var d_names = new Array("Sunday", "Monday", "Tuesday",
	"Wednesday", "Thursday", "Friday", "Saturday");

	var m_names = new Array("January", "February", "March", 
	"April", "May", "June", "July", "August", "September", 
	"October", "November", "December");

	var d = new Date();
	var curr_day = d.getDay();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_hour = d.getHours();
	var curr_minute = d.getMinutes();
	var curr_second = d.getSeconds();
	var sup = "";
	var apm = "AM";

	if (curr_date == 1 || curr_date == 21 || curr_date == 31) {
		sup = "st";
	} else if (curr_date == 2 || curr_date == 22) {
		sup = "nd";
	} else if (curr_date == 3 || curr_date == 23) {
		sup = "rd";
	} else {
		sup = "th";
	}
	
	if (curr_minute < 10) {
		curr_minute = "0" + curr_minute;
	}
	
	if (curr_second < 10) {
		curr_second = "0" + curr_second;
	}

	if (curr_hour >= 11) {
		apm = "PM";
	}
	
	if (curr_hour > 12) {
		curr_hour -= 12;
	}
	
	if (curr_hour == 0) {
		curr_hour = 12;
	}

	var datestring = d_names[curr_day] + ", " + m_names[curr_month] + " " + curr_date + 
		sup + "<span style='float:right;'>" + curr_hour + ":" + curr_minute + ":" + 
		curr_second +" " + apm + "</span>";
	document.getElementById("clock").innerHTML=datestring;
	
	setTimeout("updateClock();", 1000);
}

function bootstrap() {
	ajaxcall();
	updateClock();
}
