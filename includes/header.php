<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    // default test user (adjust or replace with real auth)
    $_SESSION['user'] = [
        'id' => 1,
        'nama' => 'Admin'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Meeting</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="app-shell">
    <header class="site-header">
        <div>
            <div class="brand">Smart Meeting</div>
            <div class="tagline">Manajemen ruangan, karyawan, dan booking</div>
        </div>
        <nav class="site-nav">
            <a href="/index.php">Dashboard</a>
            <a href="/ruangan/index.php">Ruangan</a>
            <a href="/karyawan/index.php">Karyawan</a>
            <a href="/booking/index.php">Booking</a>
        </nav>
    </header>
    <div class="content-wrap">
