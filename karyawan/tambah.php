<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$namaKaryawan = trim($_POST['nama_karyawan'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$divisi = trim($_POST['divisi'] ?? '');

	if ($namaKaryawan === '' || $email === '' || $divisi === '') {
		$error = 'Semua field wajib diisi.';
	} else {
		$duplicate = $pdo->prepare('SELECT id FROM karyawan WHERE LOWER(email) = LOWER(:email) LIMIT 1');
		$duplicate->execute(['email' => $email]);

		if ($duplicate->fetch()) {
			$error = 'Email karyawan sudah terdaftar.';
		} else {
			$stmt = $pdo->prepare('INSERT INTO karyawan (nama_karyawan, email, divisi) VALUES (:nama_karyawan, :email, :divisi)');
			$stmt->execute([
				'nama_karyawan' => $namaKaryawan,
				'email' => $email,
				'divisi' => $divisi,
			]);

			redirect('/karyawan/index.php');
		}
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Tambah Karyawan</h1>

		<?php if ($error !== ''): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-grid">
				<label class="field">
					<span>Nama Karyawan</span>
					<input type="text" name="nama_karyawan" value="<?= e($_POST['nama_karyawan'] ?? '') ?>" required>
				</label>
				<label class="field">
					<span>Email</span>
					<input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
				</label>
			</div>

			<label class="field">
				<span>Divisi</span>
				<input type="text" name="divisi" value="<?= e($_POST['divisi'] ?? '') ?>" required>
			</label>

			<div class="actions">
				<button class="btn btn-primary" type="submit">Simpan</button>
				<a class="btn btn-secondary" href="/karyawan/index.php">Kembali</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
