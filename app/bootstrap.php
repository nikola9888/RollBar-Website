<?php
declare(strict_types=1);

date_default_timezone_set('Europe/Belgrade');

const RB_DB_DIR = __DIR__ . '/../private';
const RB_DB_FILE = RB_DB_DIR . '/rollbar_orders.sqlite';

function rb_db(): PDO {
    if (!is_dir(RB_DB_DIR)) {
        throw new RuntimeException('Baza nije inicijalizovana. Pokrenite setup.php.');
    }
    if (!is_file(RB_DB_FILE)) {
        throw new RuntimeException('Baza nije pronađena. Pokrenite setup.php.');
    }
    $pdo = new PDO('sqlite:' . RB_DB_FILE, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}

function rb_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function rb_h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function rb_csrf(): string {
    rb_session();
    if (empty($_SESSION['rb_csrf'])) $_SESSION['rb_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['rb_csrf'];
}

function rb_check_csrf(): void {
    rb_session();
    $token = (string)($_POST['csrf'] ?? '');
    if ($token === '' || !hash_equals((string)($_SESSION['rb_csrf'] ?? ''), $token)) {
        http_response_code(400);
        exit('Nevažeći zahtev. Osvežite stranicu i pokušajte ponovo.');
    }
}

function rb_admin_required(): void {
    rb_session();
    if (empty($_SESSION['rb_admin_id'])) {
        header('Location: index.php');
        exit;
    }
}

function rb_statuses(): array {
    return [
        'new' => 'Nova',
        'accepted' => 'Prihvaćena',
        'preparing' => 'U pripremi',
        'ready' => 'Spremna',
        'sent' => 'Poslata',
        'completed' => 'Završena',
        'rejected' => 'Odbijena'
    ];
}
