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

// İl verilerini veritabanından çekme
$ilQuery = "SELECT DISTINCT il FROM resimler";
$ilResult = $conn->query($ilQuery);

// İlçe, köy ve tarla verilerini içerecek boş diziler
$ilceler = array();
$koyler = array();
$tarlalar = array();

if ($ilResult->num_rows > 0) {
    while ($row = $ilResult->fetch_assoc()) {
        $il = $row["il"];

        // İlçe verilerini veritabanından çekme
        $ilceQuery = "SELECT DISTINCT ilce FROM resimler WHERE il = '$il'";
        $ilceResult = $conn->query($ilceQuery);

        // İlçe verilerini ilgili diziye ekleme
        if ($ilceResult->num_rows > 0) {
            $ilceler[$il] = array();
            while ($ilceRow = $ilceResult->fetch_assoc()) {
                $ilce = $ilceRow["ilce"];
                array_push($ilceler[$il], $ilce);

                // Köy verilerini veritabanından çekme
                $koyQuery = "SELECT DISTINCT koy FROM resimler WHERE il = '$il' AND ilce = '$ilce'";
                $koyResult = $conn->query($koyQuery);

                // Köy verilerini ilgili diziye ekleme
                if ($koyResult->num_rows > 0) {
                    $koyler[$ilce] = array();
                    while ($koyRow = $koyResult->fetch_assoc()) {
                        $koy = $koyRow["koy"];
                        array_push($koyler[$ilce], $koy);

                        // Tarla verilerini veritabanından çekme
                        $tarlaQuery = "SELECT DISTINCT tarla FROM resimler WHERE il = '$il' AND ilce = '$ilce' AND koy = '$koy'";
                        $tarlaResult = $conn->query($tarlaQuery);

                        // Tarla verilerini ilgili diziye ekleme
                        if ($tarlaResult->num_rows > 0) {
                            $tarlalar[$koy] = array();
                            while ($tarlaRow = $tarlaResult->fetch_assoc()) {
                                $tarla = $tarlaRow["tarla"];
                                array_push($tarlalar[$koy], $tarla);
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        var ilceler = <?php echo json_encode($ilceler); ?>;
        var koyler = <?php echo json_encode($koyler); ?>;
        var tarlalar = <?php echo json_encode($tarlalar); ?>;

        function showIlce() {
    var ilSelect = document.getElementById("il");
    var ilceSelect = document.getElementById("ilce");
    var secilenIl = ilSelect.value;

    ilceSelect.innerHTML = ""; // İlçe select'in içeriğini temizle

    // Başlangıç seçeneğini ekle
    var baslangicOption = document.createElement("option");
    baslangicOption.text = "İlçe seçiniz";
    baslangicOption.value = "#";
    ilceSelect.add(baslangicOption);

    var ilceOptions = ilceler[secilenIl];
    for (var i = 0; i < ilceOptions.length; i++) {
        var option = document.createElement("option");
        option.text = ilceOptions[i];
        option.value = ilceOptions[i];
        ilceSelect.add(option);
    }
}

function showKoy() {
    var ilceSelect = document.getElementById("ilce");
    var koySelect = document.getElementById("koy");
    var tarlaSelect = document.getElementById("tarla");
    var secilenIlce = ilceSelect.value;

    koySelect.innerHTML = ""; // Köy select'in içeriğini temizle
    tarlaSelect.innerHTML = ""; // Tarla select'in içeriğini temizle

    // Başlangıç seçeneğini ekle
    var baslangicOption = document.createElement("option");
    baslangicOption.text = "Köy seçiniz";
    baslangicOption.value = "#";
    koySelect.add(baslangicOption);

    var koyOptions = koyler[secilenIlce];
    for (var i = 0; i < koyOptions.length; i++) {
        var option = document.createElement("option");
        option.text = koyOptions[i];
        option.value = koyOptions[i];
        koySelect.add(option);
    }
    koySelect.addEventListener("change", showTarla);
}

function showTarla() {
    var koySelect = document.getElementById("koy");
    var tarlaSelect = document.getElementById("tarla");
    var secilenKoy = koySelect.value;

    tarlaSelect.innerHTML = ""; // Tarla select'in içeriğini temizle

    // Başlangıç seçeneğini ekle
    var baslangicOption = document.createElement("option");
    baslangicOption.text = "Tarla seçiniz";
    baslangicOption.value = "#";
    tarlaSelect.add(baslangicOption);

    var tarlaOptions = tarlalar[secilenKoy];
    for (var j = 0; j < tarlaOptions.length; j++) {
        var option = document.createElement("option");
        option.text = tarlaOptions[j];
        option.value = tarlaOptions[j];
        tarlaSelect.add(option);
    }
}


function showData() {
    var ilSelect = document.getElementById("il");
    var ilceSelect = document.getElementById("ilce");
    var koySelect = document.getElementById("koy");
    var tarlaSelect = document.getElementById("tarla");
    var resimDiv = document.querySelector(".resim");
    var infoDiv = document.querySelector(".info");

    var secilenIl = ilSelect.value;
    var secilenIlce = ilceSelect.value;
    var secilenKoy = koySelect.value;
    var secilenTarla = tarlaSelect.value;

    infoDiv.innerHTML = "İl: " + secilenIl + "<br>İlçe: " + secilenIlce + "<br>Köy: " + secilenKoy + "<br>Tarla: " + secilenTarla;

    // Veritabanından resim yolunu al
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "resim_getir.php?il=" + secilenIl + "&ilce=" + secilenIlce + "&koy=" + secilenKoy + "&tarla=" + secilenTarla, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var resimYolu = xhr.responseText;
            var resimEtiketi = document.createElement("img");
            resimEtiketi.src = resimYolu;
            resimEtiketi.alt = "Tarla Resmi";
            resimEtiketi.style.width = "100%"; // Resim genişliğini 300 piksel olarak ayarla
            resimEtiketi.style.height = "auto"; // Resim yüksekliğini 200 piksel olarak ayarla
            resimDiv.innerHTML = ""; // Önceki içeriği temizle
            resimDiv.appendChild(resimEtiketi);
        }
    };
    xhr.send();

    // Veritabanından ada, parsel, m2, no ve id verilerini al
    var xhr2 = new XMLHttpRequest();
    xhr2.open("GET", "veri_getir.php?il=" + secilenIl + "&ilce=" + secilenIlce + "&koy=" + secilenKoy + "&tarla=" + secilenTarla, true);
    xhr2.onreadystatechange = function () {
        if (xhr2.readyState === 4 && xhr2.status === 200) {
            var veri = JSON.parse(xhr2.responseText);
            var ada = veri.ada;
            var parsel = veri.parsel;
            var m2 = veri.m2;
            var no = veri.no;
            var id = veri.id; // Yeni eklenen satır
            var veriDiv = document.querySelector(".veri");
            veriDiv.innerHTML = "Ada: " + ada + "<br>Parsel: " + parsel + "<br>M2: " + m2 + "<br>No: " + no + "<br>ID: " + id; // Güncellenen satır
        }
    };
    xhr2.send();
}
        var button = document.getElementById("bat");
        button.addEventListener("click", showData);
    </script>
</head>

<body>
    
    <h1>Tarla Uygulaması V.1</h1>
    <a href="admin.php" id="but">Admin Sayfasına Git</a>
    <div class="main">
        <div class="nav">
            <select name="il" id="il" class="buyuk" onchange="showIlce()">
                <option value="#">il seçiniz</option>
                <?php
                $ilQuery = "SELECT DISTINCT il FROM resimler";
                $ilResult = $conn->query($ilQuery);

                if ($ilResult->num_rows > 0) {
                    while ($row = $ilResult->fetch_assoc()) {
                        $il = $row["il"];
                        echo "<option value='$il'>$il</option>";
                    }
                }
                ?>
            </select>
            <select name="ilçe" id="ilce" class="buyuk" onchange="showKoy()">
                <option value="#">ilçe seçiniz</option>
            </select>
            <select name="Köy" id="koy" class="buyuk">
                <option value="#">Köy seçiniz</option>
            </select>
            <select name="tarla" id="tarla" class="buyuk">
                <option value="#">Tarla seçiniz</option>
            </select>
            <button id="bat" onclick="showData()">Göster</button>
        </div>
        <div class="con">
        
            <h1>Seçtiniz</h1>
            <div class="info">
            
            </div>
            <h1>Tarla Hakkında Veriler</h1>
            <div class="veri"></div>
            <div class="resim"></div>
        </div>
    </div>
    <footer>
    @pyazilim
    </footer>
   
</body>

</html>

<?php
// Veritabanı bağlantısını kapat
$conn->close();
?>

