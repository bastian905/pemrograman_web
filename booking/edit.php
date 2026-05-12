<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$ruanganList = $pdo->query('SELECT id, nama_ruangan FROM ruangan ORDER BY nama_ruangan')->fetchAll();
$karyawanList = $pdo->query('SELECT id, nama_karyawan FROM karyawan ORDER BY nama_karyawan')->fetchAll();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM booking WHERE id = :id');
$stmt->execute(['id' => $id]);
$booking = $stmt->fetch();

if (!$booking) {
	redirect('/booking/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$ruanganId = (int) ($_POST['ruangan_id'] ?? 0);
	$karyawanId = (int) ($_POST['karyawan_id'] ?? 0);
	$tanggal = trim($_POST['tanggal'] ?? '');
	$jamMulai = trim($_POST['jam_mulai'] ?? '');
	$jamSelesai = trim($_POST['jam_selesai'] ?? '');
	$keterangan = trim($_POST['keterangan'] ?? '');
	$status = trim($_POST['status'] ?? 'Menunggu');

	if ($ruanganId <= 0 || $karyawanId <= 0 || $tanggal === '' || $jamMulai === '' || $jamSelesai === '') {
		$error = 'Ruangan, karyawan, tanggal, dan waktu wajib diisi.';
	} else {
		// prevent exact duplicate on other record
		$dup = $pdo->prepare('SELECT COUNT(*) as cnt FROM booking WHERE ruangan_id = :ruangan_id AND tanggal = :tanggal AND jam_mulai = :jam_mulai AND jam_selesai = :jam_selesai AND id != :id');
		$dup->execute([
			'ruangan_id' => $ruanganId,
			'tanggal' => $tanggal,
			'jam_mulai' => $jamMulai,
			'jam_selesai' => $jamSelesai,
			'id' => $id,
		]);
		if ((int) $dup->fetchColumn() > 0) {
			$error = 'Booking duplikat: sudah ada booking persis dengan waktu dan ruangan yang sama.';
		} else {
			// check time overlap excluding this id
			$conflict = $pdo->prepare('SELECT booking.*, karyawan.nama_karyawan, karyawan.divisi FROM booking JOIN karyawan ON booking.karyawan_id = karyawan.id WHERE booking.ruangan_id = :ruangan_id AND booking.tanggal = :tanggal AND booking.id != :id AND NOT (booking.jam_selesai <= :jam_mulai OR booking.jam_mulai >= :jam_selesai) LIMIT 1');
			$conflict->execute([
				'ruangan_id' => $ruanganId,
				'tanggal' => $tanggal,
				'id' => $id,
				'jam_mulai' => $jamMulai,
				'jam_selesai' => $jamSelesai,
			]);
			$conflictRow = $conflict->fetch();
			if ($conflictRow) {
				$error = sprintf("Maaf, ruangan sudah digunakan oleh Divisi %s (oleh %s) untuk agenda: %s pada %s %s-%s", e($conflictRow['divisi']), e($conflictRow['nama_karyawan']), e($conflictRow['keterangan'] ?? '-'), e($conflictRow['tanggal']), e($conflictRow['jam_mulai']), e($conflictRow['jam_selesai']));
			} else {
				$update = $pdo->prepare('UPDATE booking SET ruangan_id = :ruangan_id, karyawan_id = :karyawan_id, tanggal = :tanggal, jam_mulai = :jam_mulai, jam_selesai = :jam_selesai, keterangan = :keterangan, status = :status WHERE id = :id');
				$update->execute([
					'ruangan_id' => $ruanganId,
					'karyawan_id' => $karyawanId,
					'tanggal' => $tanggal,
					'jam_mulai' => $jamMulai,
					'jam_selesai' => $jamSelesai,
					'keterangan' => $keterangan,
					'status' => $status,
					'id' => $id,
				]);
				logActivity($pdo, 'Ubah booking', 'Booking ID: ' . $id);
				redirect('/booking/index.php');
			}
		}
	}
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel">
		<h1>Edit Booking</h1>

		<?php if ($error !== ''): ?>
			<div class="alert"><?= e($error) ?></div>
		<?php endif; ?>

		<form method="post">
			<div class="form-grid">
				<label class="field">
					<span>Ruangan</span>
					<select name="ruangan_id" required>
						<option value="">Pilih ruangan</option>
						<?php foreach ($ruanganList as $ruangan): ?>
							<option value="<?= e($ruangan['id']) ?>" <?= (string) ($ruangan['id'] ?? '') === (string) ($_POST['ruangan_id'] ?? $booking['ruangan_id']) ? 'selected' : '' ?>>
								<?= e($ruangan['nama_ruangan']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="field">
					<span>Karyawan</span>
					<select name="karyawan_id" required>
						<option value="">Pilih karyawan</option>
						<?php foreach ($karyawanList as $karyawan): ?>
							<option value="<?= e($karyawan['id']) ?>" <?= (string) ($karyawan['id'] ?? '') === (string) ($_POST['karyawan_id'] ?? $booking['karyawan_id']) ? 'selected' : '' ?>>
								<?= e($karyawan['nama_karyawan']) ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>

			<div class="form-grid">
				<label class="field">
					<span>Tanggal</span>
					<input type="date" name="tanggal" value="<?= e($_POST['tanggal'] ?? $booking['tanggal']) ?>" required>
				</label>
				<label class="field">
					<span>Status</span>
					<select name="status">
						<?php foreach (['Menunggu', 'Disetujui', 'Ditolak', 'Selesai'] as $item): ?>
							<option value="<?= e($item) ?>" <?= $item === ($_POST['status'] ?? $booking['status']) ? 'selected' : '' ?>><?= e($item) ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>

			<div class="form-grid">
				<label class="field">
					<span>Jam Mulai</span>
					<input type="time" name="jam_mulai" value="<?= e($_POST['jam_mulai'] ?? $booking['jam_mulai']) ?>" required>
				</label>
				<label class="field">
					<span>Jam Selesai</span>
					<input type="time" name="jam_selesai" value="<?= e($_POST['jam_selesai'] ?? $booking['jam_selesai']) ?>" required>
				</label>
			</div>

			<label class="field">
				<span>Keterangan</span>
				<textarea name="keterangan"><?= e($_POST['keterangan'] ?? $booking['keterangan']) ?></textarea>
			</label>

			<div class="actions">
				<button class="btn btn-primary" type="submit">Update</button>
				<a class="btn btn-secondary" href="/booking/index.php">Kembali</a>
			</div>
		</form>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
