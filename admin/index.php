<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';
rb_session();
$error = '';
$loggedIn = !empty($_SESSION['rb_admin_id']);
try { $pdo = rb_db(); } catch (Throwable $e) { $pdo = null; $error = $e->getMessage(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    rb_check_csrf();
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'login' && !$loggedIn) {
        $blockedUntil = (int)($_SESSION['rb_login_blocked_until'] ?? 0);
        if ($blockedUntil > time()) { $error = 'Previše pokušaja. Pokušajte ponovo za nekoliko minuta.'; }
        else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $stmt = $pdo->prepare('SELECT id,password_hash FROM admins WHERE username=? LIMIT 1');
        $stmt->execute([$username]); $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['rb_admin_id'] = (int)$admin['id'];
            $loggedIn = true;
            header('Location: index.php'); exit;
        }
        $error = 'Pogrešno korisničko ime ili lozinka.';
            $_SESSION['rb_login_tries'] = (int)($_SESSION['rb_login_tries'] ?? 0) + 1;
            if ($_SESSION['rb_login_tries'] >= 5) { $_SESSION['rb_login_blocked_until'] = time() + 900; $_SESSION['rb_login_tries'] = 0; }
        }
    } elseif ($action === 'logout' && $loggedIn) {
        $_SESSION = []; session_regenerate_id(true); header('Location: index.php'); exit;
    } elseif ($action === 'status' && $loggedIn) {
        $id = filter_var($_POST['order_id'] ?? null, FILTER_VALIDATE_INT);
        $status = (string)($_POST['status'] ?? '');
        if ($id && array_key_exists($status, rb_statuses())) {
            $etaRaw = trim((string)($_POST['eta_minutes'] ?? ''));
            $eta = ($etaRaw !== '' && ctype_digit($etaRaw)) ? min(240,(int)$etaRaw) : null;
            $stmt = $pdo->prepare('UPDATE orders SET status=?,eta_minutes=?,updated_at=? WHERE id=?');
            $stmt->execute([$status,$eta,date('Y-m-d H:i:s'),$id]);
        }
        header('Location: index.php'); exit;
    }
}
$loggedIn = !empty($_SESSION['rb_admin_id']);
$orders = [];
if ($loggedIn && $pdo) {
    $orders = $pdo->query('SELECT * FROM orders ORDER BY CASE status WHEN "new" THEN 0 WHEN "accepted" THEN 1 WHEN "preparing" THEN 2 WHEN "ready" THEN 3 WHEN "sent" THEN 4 ELSE 5 END, id DESC LIMIT 200')->fetchAll();
    $itemStmt = $pdo->prepare('SELECT product_name,unit_price,quantity,line_total FROM order_items WHERE order_id=? ORDER BY id');
    foreach ($orders as &$order) { $itemStmt->execute([$order['id']]); $order['items'] = $itemStmt->fetchAll(); } unset($order);
}
$lastId = 0; foreach ($orders as $o) $lastId = max($lastId,(int)$o['id']);
?>
<!doctype html><html lang="sr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Roll Bar – administracija</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#090909;color:#fff;font:14px Arial,sans-serif}.wrap{max-width:1150px;margin:auto;padding:18px}.head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;border-bottom:2px solid #ff6a00;padding:8px 0 18px}.head h1{color:#ff6a00;margin:0}.btn,button{background:#ff6a00;color:#fff;border:0;border-radius:7px;padding:9px 13px;font-weight:bold;cursor:pointer}.panel{max-width:440px;margin:10vh auto;background:#171717;padding:25px;border-radius:14px;border-top:4px solid #ff6a00}.panel input{display:block;width:100%;padding:12px;margin:8px 0 16px;background:#080808;color:#fff;border:1px solid #444;border-radius:7px}.error{color:#ff9275}.order{background:#171717;border:1px solid #333;border-left:4px solid #ff6a00;border-radius:12px;padding:16px;margin:15px 0}.meta{color:#aaa;line-height:1.7}.items{margin:12px 0;padding:10px;background:#0d0d0d;border-radius:8px;line-height:1.8}.actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}.actions select{background:#080808;color:#fff;border:1px solid #555;padding:9px;border-radius:7px}.total{color:#ff6a00;font-size:19px;font-weight:bold}.status{font-weight:bold;color:#ffb078}.empty{color:#aaa;padding:30px;text-align:center}.refresh{color:#aaa;font-size:12px}
</style></head><body><div class="wrap">
<?php if (!$pdo): ?><section class="panel"><h1>ROLL BAR 013</h1><p class="error"><?=rb_h($error)?></p><p>Proverite da li je pokrenut setup.php i da li PHP ima dozvolu za privatni folder.</p></section>
<?php elseif (!$loggedIn): ?><section class="panel"><h1>ROLL BAR 013</h1><h2>Administracija porudžbina</h2><?php if($error):?><p class="error"><?=rb_h($error)?></p><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=rb_h(rb_csrf())?>"><label>Korisničko ime</label><input name="username" value="admin" autocomplete="username" required><label>Lozinka</label><input type="password" name="password" autocomplete="current-password" required><button name="action" value="login">PRIJAVA</button></form></section>
<?php else: ?><header class="head"><div><h1>ROLL BAR 013</h1><div>Administracija porudžbina</div></div><form method="post"><input type="hidden" name="csrf" value="<?=rb_h(rb_csrf())?>"><button name="action" value="logout">ODJAVA</button></form></header><p class="refresh">Automatsko osvežavanje na 15 sekundi. Nove porudžbine se zvučno oglašavaju dok je ova stranica otvorena.</p>
<?php if(!$orders):?><div class="empty">Još nema porudžbina.</div><?php endif;?>
<?php foreach($orders as $o):?><article class="order" data-order-id="<?=$o['id']?>"><div class="head"><div><h2><?=rb_h($o['order_code'])?></h2><div class="meta"><?=rb_h($o['created_at'])?> · <span class="status"><?=rb_h(rb_statuses()[$o['status']]??$o['status'])?></span></div></div><div class="total"><?=number_format((int)$o['total'],0,',','.')?> RSD</div></div>
<?php if (!empty($o['eta_minutes'])): ?><p class="status">Procena: <?= (int)$o['eta_minutes'] ?> min</p><?php endif; ?><p><b><?=rb_h($o['customer_name'])?></b> · <a style="color:#ff6a00" href="tel:<?=rb_h($o['phone'])?>"><?=rb_h($o['phone'])?></a><br><?= $o['order_type']==='delivery' ? 'Dostava: '.rb_h($o['address']) : 'Lično preuzimanje' ?></p>
<div class="items"><?php foreach($o['items'] as $it):?><?=rb_h($it['product_name'])?> × <?= (int)$it['quantity']?> — <?=number_format((int)$it['line_total'],0,',','.')?> RSD<br><?php endforeach;?></div>
<?php if($o['note']!==''):?><p>Napomena: <?=nl2br(rb_h($o['note']))?></p><?php endif;?>
<form class="actions" method="post"><input type="hidden" name="csrf" value="<?=rb_h(rb_csrf())?>"><input type="hidden" name="order_id" value="<?=$o['id']?>"><input type="number" name="eta_minutes" min="1" max="240" placeholder="ETA min." value="<?=!empty($o['eta_minutes'])?(int)$o['eta_minutes']:''?>" style="width:105px;background:#080808;color:#fff;border:1px solid #555;padding:9px;border-radius:7px"><select name="status"><?php foreach(rb_statuses() as $key=>$label):?><option value="<?=rb_h($key)?>" <?=$o['status']===$key?'selected':''?>><?=rb_h($label)?></option><?php endforeach;?></select><button name="action" value="status">SAČUVAJ STATUS</button></form></article><?php endforeach;?>
<?php endif;?></div>
<?php if($loggedIn):?><script>
const currentMax=<?= (int)$lastId ?>;let audioReady=false;let audioCtx=null;
document.addEventListener('click',()=>{audioReady=true;try{audioCtx=audioCtx||new(window.AudioContext||window.webkitAudioContext)();}catch(e){}},{once:true});
function beep(){if(!audioReady||!audioCtx)return;const osc=audioCtx.createOscillator(),gain=audioCtx.createGain();osc.connect(gain);gain.connect(audioCtx.destination);osc.frequency.value=880;gain.gain.value=.12;osc.start();osc.stop(audioCtx.currentTime+.22);}
setInterval(async()=>{try{const r=await fetch('new-count.php?after='+currentMax,{cache:'no-store'});const d=await r.json();if(d.new_count>0){beep();location.reload();}}catch(e){}},15000);
</script><?php endif;?></body></html>
