<?php
// Koneksi Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "latihan";

// Proses Input Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $kecamatan = isset($_POST['kecamatan']) ? $_POST['kecamatan'] : '';
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : 0;
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : 0;
    $luas = isset($_POST['luas']) ? floatval($_POST['luas']) : 0;
    $jumlah_penduduk = isset($_POST['jumlah_penduduk']) ? intval($_POST['jumlah_penduduk']) : 0;

    $sql = "INSERT INTO penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE longitude = ?, latitude = ?, luas = ?, jumlah_penduduk = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdddddddd", 
        $kecamatan, $longitude, $latitude, $luas, $jumlah_penduduk,
        $longitude, $latitude, $luas, $jumlah_penduduk
    );

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        #informasi {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Form Input Data Penduduk</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label>Kecamatan:</label>
            <input type="text" name="kecamatan" id="kecamatan" required>
        </div>
        
        <div class="form-group">
            <label>Longitude:</label>
            <input type="text" name="longitude" id="longitude" required>
        </div>
        
        <div class="form-group">
            <label>Latitude:</label>
            <input type="text" name="latitude" id="latitude" required>
        </div>
        
        <div class="form-group">
            <label>Luas (km²):</label>
            <input type="text" name="luas" id="luas" required>
        </div>
        
        <div class="form-group">
            <label>Jumlah Penduduk:</label>
            <input type="text" name="jumlah_penduduk" id="jumlah_penduduk" required>
        </div>
        
        <input type="submit" value="Simpan Data">
    </form>

    <p id="informasi"></p>

    <script>
    function validateForm() {
        var kecamatan = document.getElementById("kecamatan").value;
        var longitude = document.getElementById("longitude").value;
        var latitude = document.getElementById("latitude").value;
        var luas = document.getElementById("luas").value;
        var jumlahPenduduk = document.getElementById("jumlah_penduduk").value;
        var informasi = document.getElementById("informasi");

        if (!kecamatan || !longitude || !latitude || !luas || !jumlahPenduduk) {
            informasi.innerHTML = "Semua field harus diisi!";
            return false;
        }

        if (isNaN(longitude) || isNaN(latitude) || isNaN(luas) || isNaN(jumlahPenduduk)) {
            informasi.innerHTML = "Longitude, Latitude, Luas, dan Jumlah Penduduk harus berupa angka!";
            return false;
        }

        return true;
    }
    </script>
</body>
</html>