<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kisi_ekle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Mesaj değişkeni
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form 1: Veritabanına veri ekleme
    if (isset($_POST['add_person'])) {
        $ad = $conn->real_escape_string($_POST['ad']);
        $soyad = $conn->real_escape_string($_POST['soyad']);
        $email = $conn->real_escape_string($_POST['email']);

        $sql = "INSERT INTO kisi_ekle (ad, soyad, email) VALUES ('$ad', '$soyad', '$email')";

        if ($conn->query($sql) === TRUE) {
            $message = "Veri başarıyla eklendi.";
            $messageType = "success";
        } else {
            $message = "Hata: " . $conn->error;
            $messageType = "error";
        }
    }

    // Form 2: Veritabanında kişi arama
    if (isset($_POST['search_person'])) {
        $search_name = $conn->real_escape_string($_POST['search_name']);

        $sql = "SELECT soyad, email FROM kisi_ekle WHERE ad = '$search_name'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $message = "";
            while ($row = $result->fetch_assoc()) {
                $message .= "Ad: $search_name, Soyad: {$row['soyad']}, Email: {$row['email']}<br>";
            }
            $messageType = "success";
        } else {
            $message = "Kayıt bulunamadı.";
            $messageType = "error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişi Ekle ve Ara</title>
    <style>
        body {
            background-color: #e6e6fa;
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #4a4a4a;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 90%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        button {
            background-color: #7b68ee;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #6a5acd;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin: 10px auto;
            border-radius: 5px;
            max-width: 500px;
        }

        .success {
            background-color: #90ee90;
            color: #006400;
        }

        .error {
            background-color: #ffcccb;
            color: #8b0000;
        }
    </style>
</head>
<body>
    <h1>Kişi Ekle ve Ara</h1>

    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Kişi Ekle</h2>
        <form method="POST">
            <div class="form-group">
                <label for="ad">Ad:</label>
                <input type="text" id="ad" name="ad" required>
            </div>

            <div class="form-group">
                <label for="soyad">Soyad:</label>
                <input type="text" id="soyad" name="soyad" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <button type="submit" name="add_person">Ekle</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Kişi Ara</h2>
        <form method="POST">
            <div class="form-group">
                <label for="search_name">Ad:</label>
                <input type="text" id="search_name" name="search_name" required>
            </div>

            <button type="submit" name="search_person">Ara</button>
        </form>
    </div>
</body>
</html>
