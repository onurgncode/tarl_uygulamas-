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

// Veri ekleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $il = $_POST["il"];
    $ilce = $_POST["ilce"];
    $koy = $_POST["koy"];
    $tarla = $_POST["tarla"];
    $ada = $_POST["ada"];
    $parsel = $_POST["parsel"];
    $m2 = $_POST["m2"];
    $no = $_POST["no"];
    $resim = $_FILES["resim"];

    // Dosyanın geçici yolunu ve hedef yolunu belirle
    $dosyaTempYolu = $resim["tmp_name"];
    $dosyaAdi = basename($resim["name"]);
    $dosyaHedefYolu = "img/" . $dosyaAdi;

    // Dosyayı hedef klasöre kopyala
    if (move_uploaded_file($dosyaTempYolu, $dosyaHedefYolu)) {
        // Örnek bir SQL sorgusu ile veriyi veritabanına ekleyelim
        $sql = "INSERT INTO resimler (il, ilce, koy, tarla, ada, parsel, m2, no, resim) VALUES ('$il', '$ilce', '$koy', '$tarla', '$ada', '$parsel', '$m2', '$no', 'img/$dosyaAdi')";

        if ($conn->query($sql) === TRUE) {
            echo "Veri başarıyla eklendi";
            header("refresh:1;url=admin.php");
            exit; // Daha fazla kodun çalışmasını engelle
        } else {
            echo "Veri eklenirken hata oluştu: " . $conn->error;
        }
    } else {
        echo "Resim yüklenirken hata oluştu";
    }
}

// Veri silme işlemi
if (isset($_GET["sil"])) {
    $id = $_GET["sil"];

    if (isset($_GET["confirm"]) && $_GET["confirm"] == "yes") {
        // Silme işlemini onayladıktan sonra veriyi sil
        $sql = "DELETE FROM resimler WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Veri başarıyla silindi";
            header("refresh:1;url=admin.php");
            exit; // Daha fazla kodun çalışmasını engelle
        } else {
            echo "Veri silinirken hata oluştu: " . $conn->error;
            header("refresh:1;url=admin.php");
            exit; // Daha fazla kodun çalışmasını engelle
        }
    } else {
        // Silme işlemi için onay isteği göster
        echo "Bu veriyi gerçekten silmek istiyor musunuz?";
        echo "<a href='?sil=" . $id . "&confirm=yes'>Evet</a> / ";
        echo "<a href='index.php'>Hayır</a>";
        exit;
    }
}

// Veri güncelleme işlemi
if (isset($_POST["guncelle"])) {
    $id = $_POST["id"];
    $parsel = $_POST["parsel"];
    $resim = $_FILES["resim"];

    // Önce resmi güncelle
    if (!empty($resim["name"])) {
        $dosyaTempYolu = $resim["tmp_name"];
        $dosyaAdi = basename($resim["name"]);
        $dosyaHedefYolu = "img/" . $dosyaAdi;

        if (move_uploaded_file($dosyaTempYolu, $dosyaHedefYolu)) {
            // Örnek bir SQL sorgusu ile resmi güncelleyelim
            $sql = "UPDATE resimler SET parsel = '$parsel', resim = 'img/$dosyaAdi' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                echo "Veri başarıyla güncellendi";
            } else {
                echo "Veri güncellenirken hata oluştu: " . $conn->error;
            }
        } else {
            echo "Resim yüklenirken hata oluştu";
        }
    } else {
        // Sadece parsel bilgisini güncelle
        $sql = "UPDATE resimler SET parsel = '$parsel' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Veri başarıyla güncellendi";
        } else {
            echo "Veri güncellenirken hata oluştu: " . $conn->error;
        }
    }
}

// Veritabanından verileri al
$sql = "SELECT * FROM resimler";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veri Ekleme</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table{
            margin:auto;
            padding:auto;
        }
    </style>
</head>

<body>
    
<h1>Tarla Uygulaması V.1 Admin</h1>
<a href="index.php" id="but">Uygulamaya Git</a>
    
    <div class="ortala">
    <h1>Veri Ekleme</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
        <label for="il">İl:</label>
        <input type="text" name="il" id="il" class="text" required><br><br>

        <label for="ilce">İlçe:</label>
        <input type="text" name="ilce" id="ilce" class="text"  required><br><br>

        <label for="koy">Köy:</label>
        <input type="text" name="koy" id="koy" class="text"  required><br><br>

        <label for="tarla">Tarla:</label>
        <input type="text" name="tarla" id="tarla" class="text"  required><br><br>

        <label for="ada">Ada:</label>
        <input type="text" name="ada" id="ada" class="text"  required><br><br>

        <label for="parsel">Parsel:</label>
        <input type="text" name="parsel" id="parsel" class="text" required><br><br>

        <label for="m2">M2:</label>
        <input type="text" name="m2" id="m2" class="text"  required><br><br>

        <label for="no">No:</label>
        <input type="text" name="no" id="no" class="text"  required><br><br>

        <label for="resim">Resim:</label>
        <input type="file" name="resim" id="resim" class="but"  required><br><br>

        <input type="submit" value="Veri Ekle" id="bat">
    </form>
    </div>
    <div class="ortalama2">
    <h1>Veri Silme ve Güncelleme</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <label for="search">Sorgulama İçin ID Girin:</label>
        <input type="text" name="search" id="search" class="text" required>
        <input type="submit" value="Sorgula" id="bat" class="pac">
    </form>

    <?php
    // Kullanıcının veri girdiğinde sorguyu gerçekleştir
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST["search"];

        // Veritabanında sorguyu gerçekleştir
        $sql = "SELECT * FROM resimler WHERE id = $search";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>
    <table id="ayar">
        <tr>
            <th>ID</th>
            <th>İl</th>
            <th>İlçe</th>
            <th>Köy</th>
            <th>Tarla</th>
            <th>Ada</th>
            <th>Parsel</th>
            <th>M2</th>
            <th>No</th>
            <th>Resim</th>
            <th>İşlemler</th>
        </tr>
        <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["il"]; ?></td>
            <td><?php echo $row["ilce"]; ?></td>
            <td><?php echo $row["koy"]; ?></td>
            <td><?php echo $row["tarla"]; ?></td>
            <td><?php echo $row["ada"]; ?></td>
            <td><?php echo $row["parsel"]; ?></td>
            <td><?php echo $row["m2"]; ?></td>
            <td><?php echo $row["no"]; ?></td>
            <td><img src="<?php echo $row["resim"]; ?>" width="100" height="100"></td>
            <td>
                <a href="?sil=<?php echo $row["id"]; ?>" class="link">Sil</a> <br><br>
                <a href="guncelle.php?id=<?php echo $row["id"]; ?>" class="link">Güncelle</a>
            </td>
        </tr>
    </table>
    <?php
        } else {
            echo "Veri bulunamadı";
        }
    }
    ?>
    </div>
    <footer>
    @pyazilim
    </footer>
</body>

</html>