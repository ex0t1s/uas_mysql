<?php
include 'koneksi.php';

// Ambil data dari database
$query = "SELECT * FROM tb_mahasiswa ORDER BY id";
$result = mysqli_query($koneksi, $query);

// Cek jika query berhasil
if (!$result) {
    die("Error dalam query: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="style.css">
    <!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<!-- AOS JS -->

<body>
   <header class="navbar">
    <nav class="navbar-nav">
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#skill">Skill</a>
    <a href="#project">Data Kegiatan</a>
    <a href="">Contact</a>
</nav>
<button class="menu-toggle"
onclick="toggleMenu()">
   <i data-feather="menu"></i>
</button>
   </header>

   <!-- main section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="content">
                <h1>Hi, i'm <span>Zulfahmi</span></h1>
                <p>Selamat datang di laman portofolio saya. <br> Semoga informasi yang disajikan dapat memberikan gambaran yang jelas mengenai diri dan karya saya.</p>
                <a href="https://www.instagram.com/phhmiii_"><i data-feather="instagram"></i></a>
                <a href="#"><i data-feather="facebook"></i></a>              
                <a href="https://github.com/ex0t1s"><i data-feather="github"></i></a>
            </div>
            <img src="logo.png" alt="">
        </div>
    </section>
<section class="about" id="about" data-aos="fade-up">
    <h1>About</h1>
    <div class="about-content">
        <p>Saya adalah seorang mahasiswa di Universitas Nahdlatul Ulama Sunan Giri (UNUGIRI) yang memiliki semangat tinggi dalam belajar dan mengembangkan diri. Sejak kecil, saya gemar membaca karena saya percaya bahwa lewat membaca, saya bisa mengenal dunia, memperluas wawasan, dan menambah pengetahuan yang bermanfaat untuk masa depan. Buku-buku yang saya baca sering kali memberi inspirasi dan motivasi untuk terus melangkah maju. Saya memiliki cita-cita untuk menjadi orang sukses, tidak hanya dalam hal materi, tetapi juga dalam hal memberikan kontribusi positif bagi lingkungan sekitar. Bagi saya, kesuksesan sejati adalah ketika kita bisa bermanfaat bagi orang lain, dan karena itu saya terus berusaha untuk belajar, berkembang, dan memperbaiki diri setiap hari.</p>
        <div class="img">
        <img src="poto apik.jpg" alt="">
        </div>
</div>
</section>
<section class="skill" id="skill" data-aos="fade-up">
    <h1>Skill</h1>
    <div class="skill-content">
        <div class="isi">HTML</div>
        <div class="isi">CSS</div>
        <div class="isi">NONE</div>
        <div class="isi">NONE</div>
    </div>
</section>

<section class="project" id="project">
    <div class="container">
        <h2>Data Kegiatan Mahasiswa</h2>
        
        <?php
        // Display status messages
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'sukses') {
                echo '<div class="alert alert-success">✓ Data berhasil diproses!</div>';
            } elseif ($_GET['status'] == 'gagal') {
                $pesan = isset($_GET['pesan']) ? $_GET['pesan'] : 'Terjadi kesalahan!';
                echo '<div class="alert alert-danger">✗ Error: ' . htmlspecialchars($pesan) . '</div>';
            }
        }
        ?>
        
        <div class="header-actions">
            <a href="tambah.php" class="btn btn-success">+ Tambah Data Baru</a>
            <div class="data-count">
                Total data: <?php echo mysqli_num_rows($result); ?> kegiatan
            </div>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <!-- <th width="10%">No</th> -->
                    <th width="15%">No/Id</th>
                    <th width="35%">Nama Kegiatan</th>
                    <th width="25%">Waktu Kegiatan</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <!-- <td><?php echo $no++; ?></td> -->
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['namakgt']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['wktkgt'])); ?></td>
                    <td class="action-buttons">
                        <a href="edit.php?id=<?php echo urlencode($row['id']); ?>" 
                           class="btn btn-warning"
                           title="Edit data">
                            ✏ Edit
                        </a>
                        <a href="hapus.php?id=<?php echo urlencode($row['id']); ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('⚠ Apakah Anda yakin ingin menghapus data kegiatan:\n\n<?php echo addslashes($row['namakgt']); ?>?\n\nData yang dihapus tidak dapat dikembalikan!')"
                           title="Hapus data">
                            🗑 Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">
            <h3>Belum ada data kegiatan</h3>
            <p>Silakan tambah data kegiatan baru dengan mengklik tombol "Tambah Data Baru" di atas.</p>
        </div>
        <?php endif; ?>
    </div>

</section>
 <section class="contact" id="contact">
    <h1>CONTACT</h1>
    <div class="contact-content">
        <div class="contact-item">
            <div class="cart">
                <i data-feather="map-pin"></i>
            </div>
            
        <div>
            <strong>Lokasi</strong><br>
        balen
        </div>
    </div>
        <div class="contact-item">
            <div class="cart">
              <i data-feather="phone"></i>  
            </div>
            
            <div>
                <strong>Telepon</strong><br>
                0865433345678
            </div>
        </div>
        <div class="contact-item">
            <div class="cart">
               <i data-feather="mail"></i> 
            </div>
            
            <div>
                <strong>Email</strong><br>
                kksskkskssksk
            </div>
        </div>
    </div>

    <div class="social-icons">
        <a href="https://www.instagram.com/phhmiii_"><i data-feather="instagram"></i></a>
        <a href="#"><i data-feather="facebook"></i></a>
        <a href="https://github.com/ex0t1s"><i data-feather="github"></i></a>
    </div>
 </section>


 

<script>
    feather.replace();

    function toggleMenu() {
        const navi = document.querySelector('.navbar-nav');
        navi.classList.toggle('show');
    }
    </script>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>