<?php
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

// Veriyi güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $il = $_POST["il"];
    $ilce = $_POST["ilce"];
    $koy = $_POST["koy"];
    $tarla = $_POST["tarla"];
    $ada = $_POST["ada"];
    $parsel = $_POST["parsel"];
    $m2 = $_POST["m2"];
    $no = $_POST["no"];
    $resim = $_FILES["resim"];

    // Önce resmi güncelle
    if (!empty($resim["name"])) {
        $dosyaTempYolu = $resim["tmp_name"];
        $dosyaAdi = basename($resim["name"]);
        $dosyaHedefYolu = "img/" . $dosyaAdi;

        if (move_uploaded_file($dosyaTempYolu, $dosyaHedefYolu)) {
            // Örnek bir SQL sorgusu ile resmi güncelleyelim
            $sql = "UPDATE resimler SET il = '$il', ilce = '$ilce', koy = '$koy', tarla = '$tarla', ada = '$ada', parsel = '$parsel', m2 = '$m2', no = '$no', resim = 'img/$dosyaAdi' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                echo "Veri başarıyla güncellendi";
                header("refresh:1;url=guncelle.php?id=" . $id);
                exit; // Daha fazla kodun çalışmasını engelle
            } else {
                echo "Veri güncellenirken hata oluştu: " . $conn->error;
            }
        } else {
            echo "Resim yüklenirken hata oluştu";
        }
    } else {
        // Sadece diğer bilgileri güncelle
            $sql = "UPDATE resimler SET il = '$il', ilce = '$ilce', koy = '$koy', tarla = '$tarla', ada = '$ada', parsel = '$parsel', m2 = '$m2', no = '$no' WHERE id = $id";


        if ($conn->query($sql) === TRUE) {
            echo "Veri başarıyla güncellendi";
        } else {
            echo "Veri güncellenirken hata oluştu: " . $conn->error;
        }
    }
}

// İstenilen ID'ye sahip veriyi veritabanından al
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Örnek bir SQL sorgusu ile veriyi seçelim
    $sql = "SELECT * FROM resimler WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Veri bulunamadı";
        exit;
    }
} else {
    echo "Geçersiz ID parametresi";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veri Güncelleme</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<h1>Tarla Uygulaması V.1 Güncelle</h1>
<a href="index.php" id="but">Uygulamaya Git</a>
<a href="admin.php" id="but">Admin Sayfasına Git</a>
    <div class="cof">
    <h1>Veri Güncelleme</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"] . "?id=" . $row["id"]; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row["id"]; ?>">
        <label for="il">İl:</label><br>
        <input type="text" name="il" id="il" value="<?php echo $row["il"]; ?>" required><br><br>

        <label for="ilce">İlçe:</label>
        <input type="text" name="ilce" id="ilce" value="<?php echo $row["ilce"]; ?>" required><br><br>

        <label for="koy">Köy:</label>
        <input type="text" name="koy" id="koy" value="<?php echo $row["koy"]; ?>" required><br><br>

        <label for="tarla">Tarla:</label>
        <input type="text" name="tarla" id="tarla" value="<?php echo $row["tarla"]; ?>" required><br><br>

        <label for="ada">Ada:</label>
        <input type="text" name="ada" id="ada" value="<?php echo $row["ada"]; ?>" required><br><br>

        <label for="parsel">Parsel:</label>
        <input type="text" name="parsel" id="parsel" value="<?php echo $row["parsel"]; ?>" required><br><br>

        <label for="m2">M2:</label>
        <input type="text" name="m2" id="m2" value="<?php echo $row["m2"]; ?>" required><br><br>

        <label for="no">No:</label>
        <input type="text" name="no" id="no" value="<?php echo $row["no"]; ?>" required><br><br>

        <label for="resim">Resim:</label>
        <input type="file" name="resim" id="resim"><br><br>

        <input type="submit" value="Güncelle" id="bat">
        </form>
        </div>
        <footer>
    @pyazilim
    </footer>
</body>
</html>