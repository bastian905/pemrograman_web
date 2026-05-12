<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM ruangan WHERE id = :id');
$stmt->execute(['id' => $id]);
$ruangan = $stmt->fetch();

if (!$ruangan) {
	redirect('/ruangan/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$namaRuangan = trim($_POST['nama_ruangan'] ?? '');
	$kapasitas = (int) ($_POST['kapasitas'] ?? 0);
	$lokasi = trim($_POST['lokasi'] ?? '');

	if ($namaRuangan === '' || $kapasitas <= 0 || $lokasi === '') {
		$error = 'Semua field wajib diisi dengan benar.';
	} else {
		$duplicate = $pdo->prepare('SELECT id FROM ruangan WHERE LOWER(nama_ruangan) = LOWER(:nama_ruangan) AND id != :id LIMIT 1');
		$duplicate->execute([
			'nama_ruangan' => $namaRuangan,
			'id' => $id,
		]);

		if ($duplicate->fetch()) {
			$error = 'Ruangan dengan nama yang sama sudah ada.';
		} else {
			$update = $pdo->prepare('UPDATE ruangan SET nama_ruangan = :nama_ruangan, kapasitas = :kapasitas, lokasi = :lokasi WHERE id = :id');
			$update->execute([
				'nama_ruangan' => $namaRuangan,
				'kapasitas' => $kapasitas,
				'lokasi' => $lokasi,
				'id' => $id,
			]);

			redirect('/ruangan/index.php');
		}
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Edit Ruangan</h1>

		<?php if ($error !== ''): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-grid">
				<label class="field">
					<span>Nama Ruangan</span>
					<input type="text" name="nama_ruangan" value="<?= e($_POST['nama_ruangan'] ?? $ruangan['nama_ruangan']) ?>" required>
				</label>
				<label class="field">
					<span>Kapasitas</span>
					<input type="number" name="kapasitas" min="1" value="<?= e($_POST['kapasitas'] ?? $ruangan['kapasitas']) ?>" required>
				</label>
			</div>

			<label class="field">
				<span>Lokasi</span>
				<input type="text" name="lokasi" value="<?= e($_POST['lokasi'] ?? $ruangan['lokasi']) ?>" required>
			</label>

			<div class="actions">
				<button class="btn btn-primary" type="submit">Update</button>
				<a class="btn btn-secondary" href="/ruangan/index.php">Kembali</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
