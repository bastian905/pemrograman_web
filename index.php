<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>

<main class="page">
    <section class="hero">
        <h1>Dashboard Smart Meeting</h1>
        <p>Gunakan menu di atas untuk mengelola data ruangan, karyawan, dan booking.</p>
    </section>

    <section class="card-grid">
        <a class="card" href="/ruangan/index.php">
            <h2>Ruangan</h2>
            <p>Kelola daftar ruang rapat dan kapasitasnya.</p>
        </a>
        <a class="card" href="/karyawan/index.php">
            <h2>Karyawan</h2>
            <p>Simpan data pengguna yang melakukan booking.</p>
        </a>
        <a class="card" href="/booking/index.php">
            <h2>Booking</h2>
            <p>Pantau jadwal pemakaian ruangan.</p>
        </a>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php';
