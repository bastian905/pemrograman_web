<?php
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
	$stmt = $pdo->prepare('DELETE FROM ruangan WHERE id = :id');
	$stmt->execute(['id' => $id]);
}

redirect('/ruangan/index.php');
