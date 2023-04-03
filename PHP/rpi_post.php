<?php 
   include('connection.php'); // Veritabanına bağlanmak için $connect değişkenini içerecen kodu dahil et

   if(isset($_POST['temp'])){ // Raspberry Pi'den temp verisi olup olmadığını kontrol ediyor, bu varsa diğer veriler de vardır
    // veritabanındaki değerleri çekip güncellenecek
    $fetchData = $connect->query("SELECT * FROM sensor_data ORDER BY id LIMIT 1");
    $data = $fetchData->fetch();
    // gelen $_POST verisinde temp,rain,hum ve light değerleri varsa kullan yoksa veritabanından olan eski değerleri kullan
    $temp = isset($_POST['temp']) ? $_POST['temp'] : $data["temp"];
    $rain = isset($_POST['rain']) ? $_POST['rain'] : $data["rain"];
    $hum = isset($_POST['hum']) ? $_POST['hum'] : $data["hum"];
    $ldr = isset($_POST["light"]) ? $_POST['light'] : $data["ldr"];
    
    // Veritabanındaki değerleri güncelle
    $updateData = $connect->prepare("UPDATE sensor_data SET temp = ".$temp.", rain = ".$rain.", hum = ".$hum.", ldr = ".$ldr." WHERE id = 1");
    $updateData->execute(array());
    
    echo "OK"; // OK cevap gönder
    
   }

?>