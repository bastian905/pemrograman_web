<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$karyawanList = $pdo->query('SELECT * FROM karyawan ORDER BY id DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel actions">
		<a class="btn btn-primary" href="/karyawan/tambah.php">+ Tambah Karyawan</a>
	</section>

	<section class="table-wrap">
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Karyawan</th>
					<th>Email</th>
					<th>Divisi</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$karyawanList): ?>
					<tr>
						<td colspan="5">Belum ada data karyawan.</td>
					</tr>
				<?php else: ?>
					<?php foreach ($karyawanList as $index => $karyawan): ?>
						<tr>
							<td><?= $index + 1 ?></td>
							<td><?= e($karyawan['nama_karyawan']) ?></td>
							<td><?= e($karyawan['email']) ?></td>
							<td><?= e($karyawan['divisi']) ?></td>
							<td>
								<div class="actions">
									<a class="btn btn-secondary" href="/karyawan/edit.php?id=<?= e($karyawan['id']) ?>">Edit</a>
									<a class="btn btn-danger" href="/karyawan/hapus.php?id=<?= e($karyawan['id']) ?>" onclick="return confirm('Hapus karyawan ini?')">Hapus</a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php';
