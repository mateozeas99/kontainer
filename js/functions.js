var conn=null;

function callClient(id, device, value){
	var data = {"id":id,"device":device,"value":value};
	$.ajax({
		url: '../api/v1/send',
		headers: {
			"Authorization": "Basic " + btoa(user + ":" + apiKey)
		},
		contentType: 'application/json',
		type: 'POST',
		dataType: "json",
		data: JSON.stringify(data),
		success: function (data) {
			console.log(data);
        },
        error: function (data) {
            console.log(data.responseText);
        }
	});
}

function connectWebSocket()
{
	if(conn==null)
		conn = new WebSocket('ws://192.168.0.100:4040');
		//conn = new WebSocket('ws://127.0.0.1:4040');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		    Materialize.toast("Conexión Establecida", 2000);
		};
	conn.onmessage = function(e) {
		console.log(e.data);
		var obj = JSON.parse(e.data)[0];
		if(obj.address=='41')
		{
			if(obj.status=='0' || obj.status=='2')
			{
				document.getElementById("inputAlarm").checked=false;
				document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
			}
			else if(obj.status=='1')
			{
				document.getElementById("inputAlarm").checked=true;
				document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
			}
			else if(obj.status=='3')
			{
				document.getElementById("panic").firstChild.nodeValue = "Sirena Sonando";
				document.getElementById("inputAlarm").checked=true;
			}
			var magnetic = parseInt(obj.magnetic).toString(2);
			magnetic = "0000000".substr(0,7-magnetic.length)+magnetic;
			//for (var i = 0 ; i < $('input[id*="mag"]').length; i++) {
			for (var i = 0 ; i <= 6; i++){
				if($('input[id*="mag"+i]')!=null)
					$($('input[id*="mag"]')[i]).attr('checked',magnetic[magnetic.length-i-1]=='1');
			}
		}
	};
}
function updateClock ( )
{
  var currentTime = new Date ( );

  var currentHours = currentTime.getHours ( );
  var currentMinutes = currentTime.getMinutes ( );
  var currentSeconds = currentTime.getSeconds ( );

  // Pad the minutes and seconds with leading zeros, if required
  currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  // Choose either "AM" or "PM" as appropriate
  var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  // Convert the hours component to 12-hour format if needed
  currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  // Convert an hours component of "0" to "12"
  currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  // Compose the string for display
  var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;

  // Update the time display
  document.getElementById("clock").firstChild.nodeValue = currentTimeString;
}
function disableAlarm()
{
	// Get password
  var pass = document.getElementById("passwordInput").value;
  callAlarm(41,97,pass);

}
function callAlarm(id, command, password)
{
	var data = {"id":id,"command":command,"password":password};
		$.ajax({
			url: '../api/v1/alarm',
			headers: {
				"Authorization": "Basic " + btoa(user + ":" + apiKey)
			},
			contentType: 'application/json',
			type: 'POST',
			dataType: "json",
			data: JSON.stringify(data),
			success: function (data) {
				console.log(data);
	        },
	        error: function (data) {
	            console.log(data.responseText);
	        }
		});
}
function status()
{
	$.ajax({
			url: '../api/v1/status',
			headers: {
				"Authorization": "Basic " + btoa(user + ":" + apiKey)
			},
			contentType: 'application/json',
			type: 'GET',
			dataType: "json",
			success: function (data) {
				//var obj = JSON.parse(data);
				if(data[0].address=='41')
				{
					if(data[0].status=='0' || data[0].status=='2')
					{
						document.getElementById("inputAlarm").checked=false;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
					}
					else if(data[0].status=='1')
					{
						document.getElementById("inputAlarm").checked=true;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
					}
					else if(data[0].status=='3')
					{
						document.getElementById("inputAlarm").checked=true;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Sonando";
					}
					var magnetic = parseInt(data[0].magnetic).toString(2);
					magnetic = "0000000".substr(0,7-magnetic.length)+magnetic;
				}

	        },
	        error: function (data) {
	            console.log(data.responseText);
	        }
		});
}
function callWeather()
{

	$.getJSON("http://api.openweathermap.org/data/2.5/forecast/daily?q=cuenca&mode=json&units=metric&cnt=10&APPID=0c6b3e0d3340500fccb2922d17252d14",function(result){
    //console.log(result);
    document.getElementById("city").firstChild.nodeValue = result.city.name + ' ' + result.list[0].temp.day+'º';
    });
}