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

function initial(page)
{
	if(page=='alarm')
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
				for (var i = 0; i < data.values.length; i++) {
					if(data.values[i].value==1)
					{
						document.getElementById('31-'+data.values[i].device).checked=true;
					}
					else if(data.values[i].value==0)
					{
						document.getElementById('31-'+data.values[i].device).checked=false;
					}
				}
			//console.log(data.values[0].device);
	    },
	    error: function (data) {
	        console.log(data.responseText);
	    }
		});
	}
}
function connectWebSocket()
{
	if(conn==null)
		//conn = new WebSocket('ws://10.0.1.18:4040');
		//conn = new WebSocket('ws://192.168.88.246:4040');
		conn = new WebSocket('ws://11.22.33.45:4040');
		//conn = new WebSocket('ws://127.0.0.1:4040');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		    Materialize.toast("Conexión Establecida", 2000);
		};
	conn.onmessage = function(e) {
		//{"address":"31","values":[{"device":"1","value":"0"},{"device":"2","value":"0"},{"device":"3","value":"0"},{"device":"4","value":"0"}]}
		//console.log(e.data);
	    var obj = JSON.parse(e.data);
	    //console.log(obj);
	    if(obj.address!=null)
	    {
	    	for (var i = 0; i < obj.values.length; i++) {
				if(obj.values[i].value==2)
				{
					document.getElementById('31-'+obj.values[i].device).checked=true;
				}
				else if(obj.values[i].value==1)
				{
					document.getElementById('31-'+obj.values[i].device).checked=false;
				}
			};
	    }
	    else if(obj.message!=null)
	    {
			appendLog(now, obj.desc, obj.message,obj.priority);
	    }
	};
}
