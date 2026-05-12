<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$namaRuangan = trim($_POST['nama_ruangan'] ?? '');
	$kapasitas = (int) ($_POST['kapasitas'] ?? 0);
	$lokasi = trim($_POST['lokasi'] ?? '');

	if ($namaRuangan === '' || $kapasitas <= 0 || $lokasi === '') {
		$error = 'Semua field wajib diisi dengan benar.';
	} else {
		$duplicate = $pdo->prepare('SELECT id FROM ruangan WHERE LOWER(nama_ruangan) = LOWER(:nama_ruangan) LIMIT 1');
		$duplicate->execute(['nama_ruangan' => $namaRuangan]);

		if ($duplicate->fetch()) {
			$error = 'Ruangan dengan nama yang sama sudah ada.';
		} else {
			$stmt = $pdo->prepare('INSERT INTO ruangan (nama_ruangan, kapasitas, lokasi) VALUES (:nama_ruangan, :kapasitas, :lokasi)');
			$stmt->execute([
				'nama_ruangan' => $namaRuangan,
				'kapasitas' => $kapasitas,
				'lokasi' => $lokasi,
			]);

			redirect('/ruangan/index.php');
		}
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Tambah Ruangan</h1>

		<?php if ($error !== ''): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-grid">
				<label class="field">
					<span>Nama Ruangan</span>
					<input type="text" name="nama_ruangan" value="<?= e($_POST['nama_ruangan'] ?? '') ?>" required>
				</label>
				<label class="field">
					<span>Kapasitas</span>
					<input type="number" name="kapasitas" min="1" value="<?= e($_POST['kapasitas'] ?? '') ?>" required>
				</label>
			</div>

			<label class="field">
				<span>Lokasi</span>
				<input type="text" name="lokasi" value="<?= e($_POST['lokasi'] ?? '') ?>" required>
			</label>

			<div class="actions">
				<button class="btn btn-primary" type="submit">Simpan</button>
				<a class="btn btn-secondary" href="/ruangan/index.php">Kembali</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
