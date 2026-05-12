<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM karyawan WHERE id = :id');
$stmt->execute(['id' => $id]);
$karyawan = $stmt->fetch();

if (!$karyawan) {
	redirect('/karyawan/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$namaKaryawan = trim($_POST['nama_karyawan'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$divisi = trim($_POST['divisi'] ?? '');

	if ($namaKaryawan === '' || $email === '' || $divisi === '') {
		$error = 'Semua field wajib diisi.';
	} else {
		$duplicate = $pdo->prepare('SELECT id FROM karyawan WHERE LOWER(email) = LOWER(:email) AND id != :id LIMIT 1');
		$duplicate->execute([
			'email' => $email,
			'id' => $id,
		]);

		if ($duplicate->fetch()) {
			$error = 'Email karyawan sudah terdaftar.';
		} else {
			$update = $pdo->prepare('UPDATE karyawan SET nama_karyawan = :nama_karyawan, email = :email, divisi = :divisi WHERE id = :id');
			$update->execute([
				'nama_karyawan' => $namaKaryawan,
				'email' => $email,
				'divisi' => $divisi,
				'id' => $id,
			]);

			redirect('/karyawan/index.php');
		}
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Edit Karyawan</h1>

		<?php if ($error !== ''): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-grid">
				<label class="field">
					<span>Nama Karyawan</span>
					<input type="text" name="nama_karyawan" value="<?= e($_POST['nama_karyawan'] ?? $karyawan['nama_karyawan']) ?>" required>
				</label>
				<label class="field">
					<span>Email</span>
					<input type="email" name="email" value="<?= e($_POST['email'] ?? $karyawan['email']) ?>" required>
				</label>
			</div>

			<label class="field">
				<span>Divisi</span>
				<input type="text" name="divisi" value="<?= e($_POST['divisi'] ?? $karyawan['divisi']) ?>" required>
			</label>

			<div class="actions">
				<button class="btn btn-primary" type="submit">Update</button>
				<a class="btn btn-secondary" href="/karyawan/index.php">Kembali</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
