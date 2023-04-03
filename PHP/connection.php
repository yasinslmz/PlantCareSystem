<?php 

try{
$connect=new PDO("mysql:host=localhost;dbname=diaspome_smartplantcare;charset=utf8","diaspome_alituma","alitumay@");
if($connect){
    
}
else{
    echo "error";
}
}
catch (PDOException $e)
    {
        echo "Error".$e;
    }

?>