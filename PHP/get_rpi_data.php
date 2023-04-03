<?php 
   include('connection.php'); // Veritabanına bağlanma kodu dahit et

   if(isset($_GET['get'])){ // Güncel verileri almak için websayfasından gelen ajax http GET request kontrol et
    $fetchData = $connect->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1");
    $data = $fetchData->fetch();
    
    echo json_encode($data); // $data'yi gönder

    // Yağmur olup olmadığı bilgisini tutan rain sütünü sıfırla
    // Böylece her veri güncelemesinde bildirim gelmez
    $updateData = $connect->prepare("UPDATE sensor_data SET rain = 0 WHERE id = 1");
    $updateData->execute(array());
   }

?>