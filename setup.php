<?php
declare(strict_types=1);
require_once __DIR__ . '/app/bootstrap.php';
rb_session();

$error = '';
$success = false;
try {
    $already = is_file(RB_DB_FILE);
} catch (Throwable $e) {
    $already = false;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already) {
    rb_check_csrf();
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm'] ?? '');
    if (strlen($password) < 12) {
        $error = 'Lozinka mora imati najmanje 12 karaktera.';
    } elseif ($password !== $confirm) {
        $error = 'Lozinke se ne poklapaju.';
    } else {
        try {
            if (!is_dir(RB_DB_DIR) && !mkdir(RB_DB_DIR, 0770, true) && !is_dir(RB_DB_DIR)) {
                throw new RuntimeException('PHP nema dozvolu da napravi privatni folder.');
            }
            $pdo = new PDO('sqlite:' . RB_DB_FILE, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec('PRAGMA foreign_keys = ON');
            $pdo->exec("CREATE TABLE admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at TEXT NOT NULL
            )");
            $pdo->exec("CREATE TABLE orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_code TEXT NOT NULL UNIQUE,
                customer_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                order_type TEXT NOT NULL CHECK(order_type IN ('delivery','pickup')),
                address TEXT NOT NULL DEFAULT '',
                note TEXT NOT NULL DEFAULT '',
                total INTEGER NOT NULL,
                status TEXT NOT NULL DEFAULT 'new',
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL
            )");
            $pdo->exec("CREATE TABLE order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER NOT NULL,
                product_name TEXT NOT NULL,
                unit_price INTEGER NOT NULL,
                quantity INTEGER NOT NULL,
                line_total INTEGER NOT NULL,
                FOREIGN KEY(order_id) REFERENCES orders(id) ON DELETE CASCADE
            )");
            $stmt = $pdo->prepare('INSERT INTO admins(username,password_hash,created_at) VALUES(?,?,?)');
            $stmt->execute(['admin', password_hash($password, PASSWORD_DEFAULT), date('Y-m-d H:i:s')]);
            $success = true;
            $already = true;
        } catch (Throwable $e) {
            $error = 'Podešavanje nije uspelo: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html><html lang="sr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Roll Bar – podešavanje poručivanja</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#080808;color:#fff;font:16px Arial,sans-serif;display:grid;min-height:100vh;place-items:center;padding:20px}.panel{width:min(100%,460px);background:#151515;border:1px solid #333;border-top:4px solid #ff6a00;border-radius:16px;padding:26px}h1{margin:0 0 8px;color:#ff6a00}p{color:#bbb;line-height:1.5}label{display:block;margin:16px 0 7px}input{width:100%;padding:13px;border-radius:9px;border:1px solid #444;background:#080808;color:#fff}button{margin-top:20px;width:100%;padding:14px;border:0;border-radius:9px;background:#ff6a00;color:#fff;font-weight:bold;font-size:16px}.error{color:#ff8a70}.ok{color:#9be29b}
</style></head><body><main class="panel"><h1>ROLL BAR 013</h1><h2>Podešavanje poručivanja</h2>
<?php if ($already): ?><p class="ok">Sistem je već podešen. Iz bezbednosnih razloga setup više nije dostupan.</p><p>Obrišite fajl <b>setup.php</b> sa servera.</p>
<?php elseif ($success): ?><p class="ok">Administratorski nalog je napravljen. Korisničko ime je <b>admin</b>.</p><p>Obrišite fajl <b>setup.php</b> sa servera, pa otvorite <b>/admin/</b>.</p>
<?php else: ?><p>Postavite administratorsku lozinku. Korisničko ime će biti <b>admin</b>. Lozinka se čuva kao bezbedan hash.</p>
<?php if ($error): ?><p class="error"><?=rb_h($error)?></p><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=rb_h(rb_csrf())?>">
<label for="password">Nova lozinka (najmanje 12 karaktera)</label><input id="password" name="password" type="password" minlength="12" required autocomplete="new-password">
<label for="confirm">Ponovite lozinku</label><input id="confirm" name="confirm" type="password" minlength="12" required autocomplete="new-password">
<button type="submit">KREIRAJ ADMIN NALOG</button></form><?php endif; ?></main></body></html>
