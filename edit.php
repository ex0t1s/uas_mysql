<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    echo "<script>alert('ID tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Ambil data berdasarkan ID terlebih dahulu
$sql = "SELECT * FROM tb_mahasiswa WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Proses update data ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $namakgt = mysqli_real_escape_string($koneksi, $_POST['namakgt']);
    $wktkgt = mysqli_real_escape_string($koneksi, $_POST['wktkgt']);

    if (empty($namakgt) || empty($wktkgt)) {
        $error = "Nama Kegiatan dan Waktu Kegiatan tidak boleh kosong!";
    } else {
        // Query update dengan field yang sesuai
        $sql = "UPDATE tb_mahasiswa SET namakgt=?, wktkgt=? WHERE id=?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $namakgt, $wktkgt, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
            exit;
        } else {
            $error = "Error: " . mysqli_error($koneksi);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        textarea {
            resize: vertical;
            height: 80px;
        }
        
        input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .error {
            color: red;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffe6e6;
            border: 1px solid #ff0000;
            border-radius: 5px;
        }
        
        .required {
            color: red;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Mahasiswa</h2>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            
            <div class="form-group">
                <label for="id_display">ID</label>
                <input type="text" id="id_display" value="<?php echo htmlspecialchars($row['id']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="namakgt">Nama Kegiatan <span class="required">*</span></label>
                <input type="text" id="namakgt" name="namakgt" value="<?php echo htmlspecialchars($row['namakgt']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="wktkgt">Waktu Kegiatan <span class="required">*</span></label>
                <textarea id="wktkgt" name="wktkgt" required><?php echo htmlspecialchars($row['wktkgt']); ?></textarea>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update Data</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <script>
        // Konfirmasi sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin mengupdate data ini?')) {
                e.preventDefault();
            }
        });
        
        // Auto focus pada field pertama yang bisa diedit
        document.getElementById('namakgt').focus();
    </script>
</body>
</html>