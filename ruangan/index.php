<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$ruanganList = $pdo->query('SELECT * FROM ruangan ORDER BY id DESC')->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page">
	<section class="panel actions">
		<a class="btn btn-primary" href="/ruangan/tambah.php">+ Tambah Ruangan</a>
	</section>

	<section class="card-grid">
		<?php foreach ($ruanganList as $ruangan): ?>
			<article class="card">
				<h2><?= e($ruangan['nama_ruangan']) ?></h2>
				<p><strong>Kapasitas:</strong> <?= e($ruangan['kapasitas']) ?></p>
				<p><strong>Lokasi:</strong> <?= e($ruangan['lokasi']) ?></p>
				<div class="actions">
					<a class="btn btn-secondary" href="/ruangan/edit.php?id=<?= e($ruangan['id']) ?>">Edit</a>
					<a class="btn btn-danger" href="/ruangan/hapus.php?id=<?= e($ruangan['id']) ?>" onclick="return confirm('Hapus ruangan ini?')">Hapus</a>
				</div>
			</article>
		<?php endforeach; ?>
	</section>

	<section class="table-wrap">
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Ruangan</th>
					<th>Kapasitas</th>
					<th>Lokasi</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!$ruanganList): ?>
					<tr>
						<td colspan="5">Belum ada data ruangan.</td>
					</tr>
				<?php else: ?>
					<?php foreach ($ruanganList as $index => $ruangan): ?>
						<tr>
							<td><?= $index + 1 ?></td>
							<td><?= e($ruangan['nama_ruangan']) ?></td>
							<td><?= e($ruangan['kapasitas']) ?></td>
							<td><?= e($ruangan['lokasi']) ?></td>
							<td>
								<div class="actions">
									<a class="btn btn-secondary" href="/ruangan/edit.php?id=<?= e($ruangan['id']) ?>">Edit</a>
									<a class="btn btn-danger" href="/ruangan/hapus.php?id=<?= e($ruangan['id']) ?>" onclick="return confirm('Hapus ruangan ini?')">Hapus</a>
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
