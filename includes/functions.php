<?php
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function currentUser(): array|null
{
    return $_SESSION['user'] ?? null;
}

function logActivity(PDO $pdo, string $aksi, string $target = ''): void
{
    $user = currentUser();
    $userId = $user['id'] ?? null;
    $userName = $user['nama'] ?? ($user['name'] ?? 'Guest');

    $stmt = $pdo->prepare('INSERT INTO t_log_aktivitas (user_id, user_name, aksi, target, created_at) VALUES (:user_id, :user_name, :aksi, :target, :created_at)');
    $stmt->execute([
        'user_id' => $userId,
        'user_name' => $userName,
        'aksi' => $aksi,
        'target' => $target,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}