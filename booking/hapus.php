<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
	redirect('/booking/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$code = trim($_POST['confirm_code'] ?? '');
	if ($code === 'HAPUS') {
		$stmt = $pdo->prepare('DELETE FROM booking WHERE id = :id');
		$stmt->execute(['id' => $id]);
		logActivity($pdo, 'Hapus booking', 'Booking ID: ' . $id);
		redirect('/booking/index.php');
	} else {
		$error = 'Kode tidak cocok. Ketik HAPUS untuk menghapus.';
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Hapus Booking</h1>

		<?php if (!empty($error)): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<p>Untuk menghapus booking ini, ketik kode <strong>HAPUS</strong> lalu klik tombol Hapus.</p>

		<form method="post">
			<label class="field">
				<span>Konfirmasi kode</span>
				<input name="confirm_code" placeholder="Ketik HAPUS" required>
			</label>

			<div class="actions">
				<button class="btn btn-danger" type="submit">Hapus</button>
				<a class="btn btn-secondary" href="/booking/index.php">Batal</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
