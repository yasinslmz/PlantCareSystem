<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Plant Care System</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Laila&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script type="text/javascript" src="js/buttons.js"></script>
	
</head>	
<body>
		<div class="maindiv">
		
		<div class="headdiv">
			<img class="logo" src="pics/logo.png" alt="" width="90px">
			<h1>Plant Care System</h1>
			<div id="alert-div">
				
			</div>
		</div>
		
		<div class="menudiv">
		<ul class="ul">
				<li><a href="#">About Project</a></li>
				<li><a href="#">Gallery</a></li>
				<li><a href="index.php">Home Page</a></li>
		</ul>
		</div>
		<div class="interactivesdiv">
			
			<!--Interactif modif J-->
			<div class="interactive1">
				<div>
					<p>Soil Moisture: <span id="humidity_value">0</span> %</p>
			    </div>
				<h3>Water Control</h3>
				<div class="t_switch">
					<label class="switch">
						<input type="checkbox" id="water_toggle">
						<span class="slider round"></span>
					</label>
				</div>
			</div>

			<!--Interactif modif J-->
			<div class="interactive2">
				<h3>Roof Control</h3>
				<div class="t_switch">
					<label class="switch">
						<input type="checkbox" id="roof_toggle">
						<span class="slider round"></span>
					</label>
				</div>
			</div>

			<!--Interactif modif J-->
			<div class="interactive3">
				<h3>Air Control</h3>
				<div class="t_switch">
					<label class="switch">
						<input type="checkbox" id="fan_toggle">
						<span class="slider round"></span>
					</label>
				</div>
				<h3>Fan Speed</h3>
				<div class="r_button">
					<input type="radio" id="fan_high" name="fan_speed" value='100' onclick="handleFanSpeed(this);"><label for="fan_high"> High</label><br />
					<input type="radio" id="fan_normal" checked="checked" name="fan_speed" value='40' onclick="handleFanSpeed(this);"/><label for="fan_normal"> Normal</label><br />
					<input type="radio" id="fan_low" name="fan_speed" value='20' onclick="handleFanSpeed(this);"/><label for="fan_low"> Low</label>
				</div>
			</div>

			<!--Interactif modif J-->
			<div class="interactive4">
				<h3>Light Control</h3>
				<div class="t_switch">
					<label class="switch">
						<input type="checkbox" id="light_toggle">
						<span class="slider round"></span>
					</label>
				</div>
				<h3>Light Brightness</h3>
				<div class="r_button">
					<input type="radio" id="light_high" name="led_brightness" value='100' onclick="handleLight(this);"><label for="light_high"> High</label><br />
					<input type="radio" id="light_normal" checked="checked" name="led_brightness" value='80' onclick="handleLight(this);"/><label for="light_normal"> Medium</label><br />
					<input type="radio" id="light_low" name="led_brightness" value='60' onclick="handleLight(this);"/><label for="light_low"> Low</label>
				</div>
			</div>

		</div>
		<div class="resimdiv"><img src="pics/cicek.jpg" style= "height: 560px; width: 100%;" alt=""></div>
		<div class="footerdiv"><h3>All rights reserved</h3><h5>Any suggestions? <a href="mailto:plantcaresystem.01@gmail.com?subject=Suggestion;&body=I have a suggestion for you:" style="text-decoration: none;">Contact us</a></h5></div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script type="text/javascript">

		// Raspberry Pi server url
		var raspberry_pi_server_url = "http://bdb9eaabe933.ngrok.io";
		// Bildirimleri gösterilecreği div
		var alertDiv = document.getElementById("alert-div");
		// durum değişkenleri
		// İlk başta tüm cihazlar kapalı durumda
		var fan_status = 0, water_status = 0, roof_status = 0, led_status = 0;
		var fan_speed = 60; // fan default speed
		var led_brightness = 80; // led default brightness
		var ideal_hum = 14; // nem eşik değeri

		// Functions
		// RPi'e cihaz kontrolü yapmak için veri gönderme fonksiyonu
		function sendToRaspberry(path,params){
			$.ajax({
              type : 'GET',
              url : raspberry_pi_server_url+path+"?"+params,
              success: function(msg){
                
                console.log(msg);
              
              },
              error: function(xhr, status, error){
      
                 alertDiv.innerHTML = '<div class="alert alert-danger"> <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> Failed to send data!</div>';
                 
             }
            });
		}

		// EVENT LISTENERS
		// Butonlara event listener bağlanı, basıldığında aç kapa yapacak
		document.getElementById("water_toggle").addEventListener("change", function (e) {
			   var action = "";
				if (e.target.checked) { // buton açık konumda ise
					water_status = 1;
					action = "on"; // açılacak
				}
				else{ // değilse
					water_status = 0;
					action = "off"; // kapanacak
				}
				var params = "action="+action;
				var path = "/api/motor";
				sendToRaspberry(path,params);
			});
			
		document.getElementById("roof_toggle").addEventListener("change", function (e) {
				var action = "";
				if (e.target.checked) {
					roof_status = 1;
					action = "on";
				}
				else{
					roof_status = 0;
					action = "off";
				}
				var params = "action="+action;
				var path = "/api/servo";
				sendToRaspberry(path,params);
			});
			
		document.getElementById("fan_toggle").addEventListener("change", function (e) {
				var action = "";
				if (e.target.checked) {
					fan_status = 1;
					action = "on";
				}
				else{
					fan_status = 0;
					action = "off";
				}
				var params = "action="+action+"&fan_speed="+fan_speed+"&fan_status="+fan_status;
				var path = "/api/fan";
				sendToRaspberry(path,params);
			});
			
		document.getElementById("light_toggle").addEventListener("change", function (e) {
				var action = "";
				if (e.target.checked) {
					led_status = 1;
					action = "on";
				}
				else{
					led_status = 0;
					action = "off";
				}
				var params = "action="+action+"&led_brightness="+led_brightness+"&led_status="+led_status;
				var path = "/api/led";
				sendToRaspberry(path,params);
			});
			
		// Fan speed ayarla
		function handleFanSpeed(fanSpeed) {
			fan_speed = fanSpeed.value;
            var params = "action=on&fan_speed="+fan_speed+"&fan_status="+fan_status;
			var path = "/api/fan";
			sendToRaspberry(path,params);
        }
        
        // led ışık seviyesi ayarla
        function handleLight(light) {
            led_brightness = light.value;
            var params = "action=on&led_brightness="+led_brightness+"&led_status="+led_status;
			var path = "/api/led";
			sendToRaspberry(path,params);
        }
        
        function stopAlert(el,sensor){
            el.parentElement.style.display = 'none';
            switch(sensor){
                case 'rain': roof_status = 1; break;
                case 'water': water_status = 1; break;
                case 'fan': fan_status = 1; break;
                case 'light': led_status = 1; break;
            }
        }
        
        // Sensör verileri database'dan her iki saniyede çekilecek ve yeni verilere göre bildirim gösterilebilir
        function updateData() {
    
		  $.ajax({
		    url: 'get_rpi_data.php?get=1',
		    method:'GET',
		    success: function(response) {
		        var data = JSON.parse(response);
		        console.log(data);
		        var temp = data.temp;
		        var rain = data.rain;
		        var hum = data.hum;
		        var ldr = data.ldr;
		        
		        // verileri kontrol et ve bildirim göster
		        // eğer cihaz durumu 0 ise, yani kapalı ise bildirim göster, değilse gösterme
		        // Böylece sürekli bildirim gelmesin
		        if(rain==1){
		        	if(roof_status == 0){
		        	    
		        		var alert = `<div class="alert alert-danger"> <span class="closebtn" onclick="stopAlert(this,'rain')">&times;</span> It is raining. Do you want to open the roof? <img src="/pics/rain.jpg"/></div>`;
		            	document.getElementById("alert-div").innerHTML = alert;
		        	}
		        	
		        }

		        if(temp >= 30 && fan_status ==0){
		        	var alert = `<div class="alert alert-danger"> <span class="closebtn" onclick="stopAlert(this,'fan')">&times;</span> The Temperature is high. Do you want to turn on the fan? </div>`;
		            document.getElementById("alert-div").innerHTML = alert;
		        }
		        
		         if(ldr < 200 && led_status ==0){
		        	var alert = `<div class="alert alert-danger"> <span class="closebtn" onclick="stopAlert(this,'light')">&times;</span> The room is dark. Do you want to turn on the light? </div>`;
		            document.getElementById("alert-div").innerHTML = alert;
		        }
		        
		         if(hum < ideal_hum && water_status ==0){
		        	var alert = `<div class="alert alert-danger"> <span class="closebtn" onclick="stopAlert(this,'water')">&times;</span> The soil moisture is lower than ideal. Do you want to turn on the water motor? </div>`;
		            document.getElementById("alert-div").innerHTML = alert;
		        }

		        document.getElementById('humidity_value').innerHTML = hum;
		        
		  
		    },
		    complete: function() {
		      // Schedule the next request when the current one's complete
		      setTimeout(updateData, 2000); // iki saniye bekle ve tekrarla
		    },
		    cache: false
		  });
		};

		(function start() {
			setTimeout(updateData, 2000); // Burası site açıldıktan sonra hemen çalışacak
		})();
        
	</script>
</body>
</html>
