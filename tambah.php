<?php
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi data dari form
    $id = mysqli_real_escape_string($koneksi, trim($_POST['id']));
    $namakgt = mysqli_real_escape_string($koneksi, trim($_POST['namakgt'])); 
    $wktkgt = mysqli_real_escape_string($koneksi, trim($_POST['wktkgt']));
    
    // Validasi input
    if (empty($id) || empty($namakgt) || empty($wktkgt)) {
        $error = "Semua field harus diisi!";
    } else {
        // Cek apakah ID sudah ada
        $cek_query = "SELECT id FROM tb_mahasiswa WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $cek_query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $error = "ID '$id' sudah terdaftar! Silakan gunakan ID yang berbeda.";
        } else {
            // Insert data baru
            $insert_query = "INSERT INTO tb_mahasiswa (id, namakgt, wktkgt) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $id, $namakgt, $wktkgt);
            
            if (mysqli_stmt_execute($stmt)) {
                // Redirect ke index.php dengan status sukses
                header("Location: index.php?status=sukses");
                exit();
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
            }
        }
        
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
    }
}

// Reset form jika ada error
$form_id = isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '';
$form_namakgt = isset($_POST['namakgt']) ? htmlspecialchars($_POST['namakgt']) : '';
$form_wktkgt = isset($_POST['wktkgt']) ? htmlspecialchars($_POST['wktkgt']) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kegiatan</title>
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
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        
        textarea {
            resize: vertical;
            height: 100px;
            font-family: Arial, sans-serif;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
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
            transition: background-color 0.3s;
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
            color: #721c24;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
        }
        
        .success {
            color: #155724;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        
        .required {
            color: red;
        }
        
        .form-info {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #004085;
        }
        
        .char-count {
            text-align: right;
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
        <h2>Tambah Data Kegiatan Mahasiswa</h2>
        
        <div class="form-info">
            <strong>ℹ Informasi:</strong><br>
            Silakan isi semua field yang bertanda bintang (*) untuk menambahkan data kegiatan baru.
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error">
                <strong>✗ Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success">
                <strong>✓ Sukses:</strong> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="tambahForm">
            <div class="form-group">
                <label for="id">ID Kegiatan <span class="required">*</span></label>
                <input type="text" 
                       id="id" 
                       name="id" 
                       value="<?php echo $form_id; ?>" 
                       required
                       placeholder="Masukkan ID unik untuk kegiatan"
                       maxlength="20">
                <small style="color: #666;">ID harus unik dan tidak boleh sama dengan yang sudah ada</small>
            </div>
            
            <div class="form-group">
                <label for="namakgt">Nama Kegiatan <span class="required">*</span></label>
                <input type="text" 
                       id="namakgt" 
                       name="namakgt" 
                       value="<?php echo $form_namakgt; ?>" 
                       required
                       placeholder="Masukkan nama kegiatan"
                       maxlength="255">
            </div>
            
            <div class="form-group">
                <label for="wktkgt">Waktu Kegiatan <span class="required">*</span></label>
                <textarea id="wktkgt" 
                          name="wktkgt" 
                          required
                          placeholder="Masukkan waktu/jadwal kegiatan (contoh: Senin, 08:00-10:00 WIB)"
                          maxlength="500"><?php echo $form_wktkgt; ?></textarea>
                <div class="char-count">
                    <span id="charCount">0</span>/500 karakter
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Data</button>
                <a href="index.php" class="btn btn-secondary">↩ Kembali</a>
            </div>
        </form>
    </div>

    <script>
        // Auto focus pada field pertama
        document.getElementById('id').focus();
        
        // Character count untuk textarea
        const textarea = document.getElementById('wktkgt');
        const charCount = document.getElementById('charCount');
        
        function updateCharCount() {
            const count = textarea.value.length;
            charCount.textContent = count;
            
            if (count > 450) {
                charCount.style.color = '#dc3545';
            } else if (count > 400) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#666';
            }
        }
        
        textarea.addEventListener('input', updateCharCount);
        updateCharCount(); // Initial count
        
        // Form validation
        document.getElementById('tambahForm').addEventListener('submit', function(e) {
            const id = document.getElementById('id').value.trim();
            const namakgt = document.getElementById('namakgt').value.trim();
            const wktkgt = document.getElementById('wktkgt').value.trim();
            
            if (!id || !namakgt || !wktkgt) {
                e.preventDefault();
                alert('⚠ Semua field harus diisi!');
                return false;
            }
            
            if (!confirm('✓ Apakah Anda yakin ingin menyimpan data kegiatan ini?')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Trim whitespace on blur
        document.querySelectorAll('input[type="text"], textarea').forEach(function(element) {
            element.addEventListener('blur', function() {
                this.value = this.value.trim();
            });
        });
    </script>
</body>
</html>