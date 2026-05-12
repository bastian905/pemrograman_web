<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$bookingList = $pdo->query(
	'SELECT booking.*, ruangan.nama_ruangan, karyawan.nama_karyawan
	 FROM booking
	 INNER JOIN ruangan ON booking.ruangan_id = ruangan.id
	 INNER JOIN karyawan ON booking.karyawan_id = karyawan.id
	 ORDER BY booking.tanggal DESC, booking.jam_mulai DESC'
)->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel actions">
		<a class="btn btn-primary" href="/booking/tambah.php">+ Tambah Booking</a>
		<label style="margin-left:12px; display:inline-flex; align-items:center; gap:8px;">
			<span>Filter tanggal</span>
			<input id="filter-date" type="date">
		</label>
	</section>

	<section class="table-wrap">
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Ruangan</th>
					<th>Karyawan</th>
					<th>Tanggal</th>
					<th>Waktu</th>
					<th>Status</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$bookingList): ?>
					<tr>
						<td colspan="7">Belum ada data booking.</td>
					</tr>
				<?php else: ?>
					<?php foreach ($bookingList as $index => $booking): ?>
						<tr>
							<td><?= $index + 1 ?></td>
							<td><?= e($booking['nama_ruangan']) ?></td>
							<td><?= e($booking['nama_karyawan']) ?></td>
							<td><?= e($booking['tanggal']) ?></td>
							<td><?= e($booking['jam_mulai']) ?> - <?= e($booking['jam_selesai']) ?></td>
							<td><?= e($booking['status']) ?></td>
							<td>
								<div class="actions">
									<a class="btn btn-secondary" href="/booking/edit.php?id=<?= e($booking['id']) ?>">Edit</a>
									<a class="btn btn-danger" href="/booking/hapus.php?id=<?= e($booking['id']) ?>" onclick="return confirm('Hapus booking ini?')">Hapus</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</section>
</main>


<script>
document.addEventListener('DOMContentLoaded', function () {
	const input = document.getElementById('filter-date');
	if (!input) return;
	input.addEventListener('change', function () {
		const value = input.value; // YYYY-MM-DD or empty
		const rows = document.querySelectorAll('.table-wrap tbody tr');
		rows.forEach(r => {
			// find date cell (4th column) index 3
			const td = r.children[3];
			if (!td) return;
			const text = td.textContent.trim();
			if (!value) {
				r.style.display = '';
			} else {
				r.style.display = (text === value) ? '' : 'none';
			}
		});
	});
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php';
