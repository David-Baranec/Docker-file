<?php

require_once("config.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$lang = $_SERVER['QUERY_STRING'];
//var_dump($lang);

$sql = "SELECT id, user_id, date_date, text_text FROM orders";
$stmt = $conn->prepare($sql);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

array_unshift($logs,['id'=>'id','user_id'=>'user_id','date_date'=>'date','text_text'=>'text']);

//#code get from https://www.delftstack.com/howto/php/convert-an-array-into-a-csv-file-in-php/
$delimiter = ','; //parameter for fputcsv
$enclosure = '"'; //parameter for fputcsv
//convert array to csv
$file = fopen('file.csv', 'w+');
foreach ($logs as $data_line) {
    fputcsv($file, $data_line, $delimiter, $enclosure);
}
#//
header('Location:show.php');


?>