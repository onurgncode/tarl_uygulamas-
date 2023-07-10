<?php
// Veritabanı bağlantısı yapılacak
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sehirler_resimler";

// Veritabanı bağlantısı oluşturma
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

$il = $_GET['il'];
$ilce = $_GET['ilce'];
$koy = $_GET['koy'];
$tarla = $_GET['tarla'];

// Verileri veritabanından çekme
$query = "SELECT ada, parsel, m2, no, id FROM resimler WHERE il = '$il' AND ilce = '$ilce' AND koy = '$koy' AND tarla = '$tarla'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $veri = array(
        'ada' => $row['ada'],
        'parsel' => $row['parsel'],
        'm2' => $row['m2'],
        'no' => $row['no'],
        'id' => $row['id']
    );
    echo json_encode($veri);
} else {
    echo json_encode(null);
}

// Veritabanı bağlantısını kapat
$conn->close();
?>