<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sehirler_resimler";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$il = $_GET['il'];
$ilce = $_GET['ilce'];
$koy = $_GET['koy'];
$tarla = $_GET['tarla'];

$resimYolu = ""; // Resim yolunu tutmak için boş bir değişken

// Veritabanından resim yolunu çekme
$resimQuery = "SELECT resim FROM resimler WHERE il = '$il' AND ilce = '$ilce' AND koy = '$koy' AND tarla = '$tarla'";
$resimResult = $conn->query($resimQuery);
if ($resimResult->num_rows > 0) {
    $row = $resimResult->fetch_assoc();
    $resimYolu = $row['resim'];
}

$conn->close();

echo $resimYolu;
?>