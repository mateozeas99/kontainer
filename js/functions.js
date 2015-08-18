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

function connectWebSocket(ip)
{
	if(conn==null)
		conn = new WebSocket('ws://'+ip+':4040');
		//conn = new WebSocket('ws://192.168.0.100:4040');
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
				$('#panic').removeClass( "red-text").addClass("green-text");
			}
			else if(obj.status=='1')
			{
				document.getElementById("inputAlarm").checked=true;
				document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
				$('#panic').removeClass( "red-text").addClass("green-text");
			}
			else if(obj.status=='3')
			{
				document.getElementById("panic").firstChild.nodeValue = "Sirena Sonando";
				$('#panic').removeClass( "green-text").addClass("red-text");
				document.getElementById("inputAlarm").checked=true;
			}
			var magnetic = parseInt(obj.magnetic).toString(2);
			var movement = parseInt(obj.movement).toString(2);
			var extra = parseInt(obj.extra).toString(2);
			magnetic = "0000000".substr(0,7-magnetic.length)+magnetic;
			movement = "0000000".substr(0,7-movement.length)+movement;
			extra = "0000000".substr(0,7-extra.length)+extra;
			//Magnetics
			for (var i = 0 ; i < $('input[id*="mag"]').length; i++) {
					$($('input[id*="mag"]')[i]).prop('checked',magnetic[magnetic.length-i-1]=='1');
			}
			//Magnetics
			for (var j = 0 ; j < $('input[id*="mov"]').length; j++) {
					$($('input[id*="mov"]')[j]).prop('checked',movement[movement.length-j-1]=='1');
			}
			//Extra
			for (var k = 0 ; k < $('input[id*="ext"]').length; k++) {
					$($('input[id*="ext"]')[k]).prop('checked',extra[extra.length-k-1]=='1');
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
				console.log(data);
				//var obj = JSON.parse(data);
				if(data[0].address==41)
				{
					if(data[0].status==0 || data[0].status==2)
					{
						document.getElementById("inputAlarm").checked=false;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
						$('#panic').removeClass( "red-text").addClass("green-text");
					}
					else if(data[0].status==1)
					{
						document.getElementById("inputAlarm").checked=true;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Normal";
						$('#panic').removeClass( "red-text").addClass("green-text");
					}
					else if(data[0].status==3)
					{
						document.getElementById("inputAlarm").checked=true;
						document.getElementById("panic").firstChild.nodeValue = "Sirena Sonando";
						$('#panic').removeClass( "green-text").addClass("red-text");
					}
					var magnetic = parseInt(data[0].magnetic).toString(2);
					var movement = parseInt(data[0].movement).toString(2);
					var extra = parseInt(data[0].extra).toString(2);
					magnetic = "0000000".substr(0,7-magnetic.length)+magnetic;
					movement = "0000000".substr(0,7-movement.length)+movement;
					extra = "0000000".substr(0,7-extra.length)+extra;
					//Magnetics
					for (var i = 0 ; i < $('input[id*="mag"]').length; i++) {
							$($('input[id*="mag"]')[i]).prop('checked',magnetic[magnetic.length-i-1]=='1');
					}
					//Magnetics
					for (var j = 0 ; j < $('input[id*="mov"]').length; j++) {
							$($('input[id*="mov"]')[j]).prop('checked',movement[movement.length-j-1]=='1');
					}
					//Extra
					for (var k = 0 ; k < $('input[id*="ext"]').length; k++) {
							$($('input[id*="ext"]')[k]).prop('checked',extra[extra.length-k-1]=='1');
					}
				}

	        },
	        error: function (data) {
	            console.log(data.responseText);
	        }
		});
}
function users(username)
{
	if(username)
	{
		console.log(username);
	}
	else
	{
		$.ajax({
			url: '../api/v1/users',
			headers: {
				"Authorization": "Basic " + btoa(user + ":" + apiKey)
			},
			contentType: 'application/json',
			type: 'GET',
			dataType: "json",
			success: function (data) {
				console.log(data);
				for(var i=0;i<data.length;i++)
				{
					console.log(data[i]);
					appendUser(data[i].USERNAME,data[i].NAME,data[i].LAST,data[i].EMAIL,data[i].GROUP);
				}
	        },
	        error: function (data) {
	            console.log(data.responseText);
	        }
		});
	}
}
function callWeather()
{

	$.getJSON("http://api.openweathermap.org/data/2.5/forecast/daily?q=cuenca&mode=json&units=metric&cnt=10&APPID=0c6b3e0d3340500fccb2922d17252d14",function(result){
    //console.log(result);
    document.getElementById("city").firstChild.nodeValue = result.city.name + ' ' + result.list[0].temp.day+'º';
    });
}
function appendUser(username,name,last,email,group)
{
	var tableRef = document.getElementById('tableUsers').getElementsByTagName('tbody')[0];

  	// Insert a row in the table at row index 0
  	var newRow   = tableRef.insertRow(0);

  	// Insert a cell in the row at index 0
  	var newCellUsernam  = newRow.insertCell(0);
  	var newCellName  = newRow.insertCell(1);
  	var newCellLast  = newRow.insertCell(2);
  	var newCellEmail  = newRow.insertCell(3);
  	var newCellGroup  = newRow.insertCell(4);

  	newCellName.className='hide-on-med-and-down';
  	newCellLast.className='hide-on-med-and-down';
  	newCellEmail.className='hide-on-med-and-down';

  	// Append a text node to the cell
  	var newTextUsername  = document.createTextNode(username);
  	var newTextName  = document.createTextNode(name);
  	var newTextLast  = document.createTextNode(last);
  	var newTextEmail  = document.createTextNode(email);
  	var newTextGroup  = document.createTextNode(group);
  	//
  	newCellUsernam.appendChild(newTextUsername);
  	newCellName.appendChild(newTextName);
  	newCellLast.appendChild(newTextLast);
  	newCellEmail.appendChild(newTextEmail);
  	newCellGroup.appendChild(newTextGroup);
  	/*if(group==3)
  	{
  		newCellDate.style.color = "green";
  		newCellDesc.style.color = "green";
  		newCellMsg.style.color = "green";
  	}
  	else if(priority==2)
  	{
		newCellDate.style.color = "#ffc107";
  		newCellDesc.style.color = "#ffc107";
  		newCellMsg.style.color = "#ffc107";
  	}
  	else if(priority==1)
  	{
  		newCellDate.style.color = "red";
  		newCellDesc.style.color = "red";
  		newCellMsg.style.color = "red";
  		Materialize.toast(msg, 4000);
  	}*/
}
function checkDays(id,value)
{
	if(id=="allDays")
	{
		if(value=="all")
		{
			document.getElementById("sunday").checked=true;
			document.getElementById("sunday").disabled=true;
			document.getElementById("monday").checked=true;
			document.getElementById("monday").disabled=true;
			document.getElementById("tuesday").checked=true;
			document.getElementById("tuesday").disabled=true;
			document.getElementById("wednesday").checked=true;
			document.getElementById("wednesday").disabled=true;
			document.getElementById("thursday").checked=true;
			document.getElementById("thursday").disabled=true;
			document.getElementById("friday").checked=true;
			document.getElementById("friday").disabled=true;
			document.getElementById("saturday").checked=true;
			document.getElementById("saturday").disabled=true;
		}
		else if(value=="none")
		{
			document.getElementById("sunday").checked=false;
			document.getElementById("sunday").disabled=false;
			document.getElementById("monday").checked=false;
			document.getElementById("monday").disabled=false;
			document.getElementById("tuesday").checked=false;
			document.getElementById("tuesday").disabled=false;
			document.getElementById("wednesday").checked=false;
			document.getElementById("wednesday").disabled=false;
			document.getElementById("thursday").checked=false;
			document.getElementById("thursday").disabled=false;
			document.getElementById("friday").checked=false;
			document.getElementById("friday").disabled=false;
			document.getElementById("saturday").checked=false;
			document.getElementById("saturday").disabled=false;
		}
	}
}