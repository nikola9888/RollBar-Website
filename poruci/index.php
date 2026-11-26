<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';

$products = [
    ['Chicken-Roll',630],['Crispy Chicken-Roll',660],['Mexico-Roll',770],['Greek-Roll',680],['Beef-Roll',1680],['Vege-Roll',530],
    ['Extra Smash',200],['Classic-Smash',640],['CheeseBurger',720],['Smash-Bacon',800],['Smash-Jalapeño',800],['Chicken-Classic',620],['+ OBROK (uz burger)',270],
    ['Crispy Chicken Sandwich',450],['Cheese Chicken Sandwich',460],['Sausage Deluxe Sandwich',560],['Philly Cheese Sandwich',580],['Fil Chicken Sandwich',470],
    ['Caesar',860],['Ranch salata',690],['Beef salata',1680],['Burger salata',760],
    ['Chicken-Nuggets',640],['Pomfrit Cheddar',420],['Onion Rings',260],['Pomfrit',210],
    ['Coca-Cola',120],['Coca-Cola Zero',130],['Fanta',120],['Schweppes',120],['NEXT',130],['Rosa',90],['Rosa mineralna',90],
    ['Roll sos',120],['Honey Mustard',120],['Ranch sos',120],['Chilli sos',120],['Sweet Chilli',120],['Heinz',120],
    ['White Widow',290]
];
$byName = [];
foreach ($products as $p) $byName[$p[0]] = $p[1];
$errors = [];
$confirmation = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    rb_session();
    rb_check_csrf();
    $name = trim((string)($_POST['customer_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $type = (string)($_POST['order_type'] ?? 'delivery');
    $address = trim((string)($_POST['address'] ?? ''));
    $note = trim((string)($_POST['note'] ?? ''));
    $rawItems = json_decode((string)($_POST['cart_json'] ?? ''), true);
    if ($name === '' || strlen($name) > 100) $errors[] = 'Unesite ime i prezime.';
    if (!preg_match('/^[0-9+\s().-]{6,25}$/', $phone)) $errors[] = 'Unesite ispravan broj telefona.';
    if (!in_array($type, ['delivery','pickup'], true)) $errors[] = 'Izaberite dostavu ili lično preuzimanje.';
    if ($type === 'delivery' && ($address === '' || strlen($address) > 250)) $errors[] = 'Unesite adresu za dostavu.';
    if (strlen($note) > 500) $errors[] = 'Napomena može imati najviše 500 karaktera.';
    if (!is_array($rawItems) || count($rawItems) < 1 || count($rawItems) > 50) $errors[] = 'Korpa je prazna ili neispravna.';
    $clean = []; $total = 0;
    if (is_array($rawItems)) {
        foreach ($rawItems as $item) {
            $product = (string)($item['name'] ?? '');
            $qty = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT);
            if (!isset($byName[$product]) || $qty === false || $qty < 1 || $qty > 30) { $errors[] = 'Jedna od stavki u korpi nije ispravna.'; break; }
            $price = $byName[$product];
            $clean[] = [$product,$price,$qty,$price*$qty];
            $total += $price*$qty;
        }
    }
    if (!$errors) {
        try {
            $pdo = rb_db();
            $pdo->beginTransaction();
            $code = 'RB' . date('ymd') . strtoupper(bin2hex(random_bytes(3)));
            $now = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO orders(order_code,customer_name,phone,order_type,address,note,total,status,created_at,updated_at) VALUES(?,?,?,?,?,?,?,'new',?,?)");
            $stmt->execute([$code,$name,$phone,$type,$type === 'delivery' ? $address : '',$note,$total,$now,$now]);
            $orderId = (int)$pdo->lastInsertId();
            $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id,product_name,unit_price,quantity,line_total) VALUES(?,?,?,?,?)');
            foreach ($clean as $item) $itemStmt->execute([$orderId,$item[0],$item[1],$item[2],$item[3]]);
            $pdo->commit();
            $confirmation = ['code'=>$code,'total'=>$total];
        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            $errors[] = 'Porudžbina trenutno nije mogla da bude poslata. Pozovite 063 815 7515.';
        }
    }
}
$csrf = rb_csrf();
$groups = [
'TORTILJE'=>array_slice($products,0,6),
'SMASH BURGERI'=>array_slice($products,6,7),
'SENDVIČI'=>array_slice($products,13,5),
'SALATE'=>array_slice($products,18,4),
'FINGER FOOD'=>array_slice($products,22,4),
'PIĆE'=>array_slice($products,26,7),
'SOSEVI'=>array_slice($products,33,6),
'DEZERTI'=>array_slice($products,39,1)
];
?>
<!doctype html><html lang="sr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Poruči online | Roll Bar 013</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#ff6a00;color:#fff;font:15px Arial,sans-serif}.wrap{max-width:1100px;margin:auto;padding:10px}.panel{background:#050505;padding:24px;border-radius:8px;min-height:100vh}.top{text-align:center;margin-bottom:22px}.top a{color:#ff6a00;text-decoration:none}.top h1{margin:8px 0;color:#fff;font-size:36px}.top p{color:#aaa}.layout{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(280px,1fr);gap:22px}.group{margin-bottom:24px}.group h2{color:#ff6a00;border-bottom:1px solid #333;padding-bottom:9px;font-size:22px}.product{display:flex;align-items:center;justify-content:space-between;gap:10px;background:#141414;border-radius:10px;padding:12px;margin:8px 0}.product b{display:block}.price{color:#ff6a00;white-space:nowrap;font-weight:bold}.add{background:#ff6a00;color:#fff;border:0;border-radius:8px;padding:9px 12px;font-weight:bold;cursor:pointer;transition:transform .16s ease,background .16s ease,box-shadow .16s ease;box-shadow:0 3px 0 #a94300}.add:active,.add.is-adding{transform:translateY(3px) scale(.96);background:#d95400;box-shadow:0 0 0 #a94300}.add.is-added{animation:addPop .34s ease}.cart-title{display:flex;align-items:center;justify-content:space-between;gap:10px}.cart-count{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:28px;padding:0 8px;border-radius:20px;background:#ff6a00;color:#fff;font-size:13px;font-weight:bold}.cart.bump{animation:cartBump .38s ease}@keyframes addPop{0%{transform:scale(.96)}55%{transform:scale(1.07)}100%{transform:scale(1)}}@keyframes cartBump{0%,100%{transform:scale(1)}45%{transform:scale(1.035)}}.toast{position:fixed;left:50%;bottom:24px;z-index:9999;transform:translate(-50%,18px);opacity:0;pointer-events:none;background:#171717;color:#fff;border:1px solid #ff6a00;border-left:5px solid #ff6a00;border-radius:12px;padding:13px 18px;box-shadow:0 8px 28px rgba(0,0,0,.45);font-weight:bold;max-width:calc(100% - 28px);width:max-content;transition:opacity .22s ease,transform .22s ease}.toast.show{opacity:1;transform:translate(-50%,0)}.toast small{display:block;color:#bbb;font-weight:normal;margin-top:3px}.cart,.checkout{background:#141414;border:1px solid #333;border-radius:14px;padding:18px;margin-bottom:16px}.cart{position:sticky;top:12px;align-self:start}.cart h2,.checkout h2{margin:0 0 14px;color:#ff6a00}.cart-row{display:grid;grid-template-columns:1fr auto auto;gap:8px;align-items:center;border-bottom:1px solid #333;padding:9px 0}.qty{display:flex;align-items:center;gap:7px}.qty button{background:#333;color:white;border:0;border-radius:5px;padding:5px 9px}.remove{background:none;border:0;color:#ff9870}.total{display:flex;justify-content:space-between;font-size:20px;font-weight:bold;padding-top:14px}.field{margin:12px 0}.field label{display:block;margin-bottom:6px;color:#ddd}.field input,.field textarea,.field select{width:100%;padding:12px;border-radius:8px;border:1px solid #444;background:#080808;color:#fff;font:inherit}.field textarea{min-height:75px;resize:vertical}.submit{width:100%;background:#ff6a00;color:#fff;border:0;border-radius:9px;padding:15px;font-size:16px;font-weight:bold}.hint{color:#aaa;font-size:13px;line-height:1.5}.notice{padding:13px;border-radius:9px;background:#24160e;border:1px solid #ff6a00;margin:12px 0}.err{color:#ff9870}.success{text-align:center;padding:20px}.success h2{color:#ff6a00}.back{color:#ff6a00}.empty{color:#999;padding:12px 0}@media(max-width:760px){.layout{grid-template-columns:1fr}.cart{position:static;order:2}.top h1{font-size:30px}.panel{padding:16px}.product{padding:10px}.group h2{font-size:20px}}
</style></head><body><div class="wrap"><main class="panel">
<header class="top"><a href="/">← Nazad na Roll Bar</a><h1>PORUČI ONLINE</h1><p>Izaberi omiljenu hranu i pošalji porudžbinu.</p></header>
<?php if ($confirmation): ?><section class="success notice"><h2>Porudžbina je poslata!</h2><p>Broj porudžbine: <b><?=rb_h($confirmation['code'])?></b></p><p>Ukupno: <b><?=number_format($confirmation['total'],0,',','.')?> RSD</b></p><p>Plaćanje je pouzećem u gotovini. Potvrdu i vreme preuzimanja/dostave dobićete telefonom.</p><a class="back" href="/">Povratak na sajt</a></section>
<?php else: ?>
<?php foreach ($errors as $error): ?><div class="notice err"><?=rb_h($error)?></div><?php endforeach; ?>
<div class="layout"><div class="menu">
<?php foreach ($groups as $group=>$items): ?><section class="group"><h2><?=rb_h($group)?></h2>
<?php foreach ($items as $item): ?><article class="product"><div><b><?=rb_h($item[0])?></b><span class="price"><?=number_format($item[1],0,',','.')?> RSD</span></div><button class="add" type="button" data-name="<?=rb_h($item[0])?>" data-price="<?=$item[1]?>">DODAJ</button></article><?php endforeach; ?>
<?php if ($group==='SMASH BURGERI'): ?><p class="hint">Dodatak + OBROK 270 RSD (pomfrit + sos + sok) dodaje se u korpu posebno, uz burger.</p><?php endif; ?>
</section><?php endforeach; ?></div>
<aside><section class="cart" id="cartPanel"><h2 class="cart-title">Tvoja korpa <span class="cart-count" id="cartCount">0</span></h2><div id="cartItems" class="empty">Korpa je prazna.</div><div class="total"><span>Ukupno</span><span id="cartTotal">0 RSD</span></div></section>
<section class="checkout"><h2>Podaci za porudžbinu</h2><form id="orderForm" action="/poruci/index.php" method="post"><input type="hidden" name="csrf" value="<?=rb_h($csrf)?>"><input type="hidden" name="cart_json" id="cartJson">
<div class="field"><label for="customer_name">Ime i prezime</label><input id="customer_name" name="customer_name" maxlength="100" required></div>
<div class="field"><label for="phone">Broj telefona</label><input id="phone" name="phone" type="tel" maxlength="25" required></div>
<div class="field"><label for="order_type">Način preuzimanja</label><select id="order_type" name="order_type"><option value="delivery">Dostava</option><option value="pickup">Lično preuzimanje</option></select></div>
<div class="field" id="addressField"><label for="address">Adresa za dostavu</label><input id="address" name="address" maxlength="250"></div>
<div class="field"><label for="note">Napomena (opciono)</label><textarea id="note" name="note" maxlength="500" placeholder="Sprat, ulaz, posebna napomena..."></textarea></div>
<p class="hint">Plaćanje: gotovina. Cena dostave, ako postoji, potvrđuje se telefonom.</p>
<button class="submit" type="submit">POŠALJI PORUDŽBINU</button></form></section></aside></div>
<?php endif; ?>
</main></div>
<div id="cartToast" class="toast" role="status" aria-live="polite"></div>
<script>
const cart={};
let toastTimer;
const money=n=>n.toLocaleString('sr-RS')+' RSD';
function showCartToast(name){const toast=document.getElementById('cartToast');if(!toast)return;toast.innerHTML='✓ '+escapeHtml(name)+' je dodat u korpu<small>Proizvod je uspešno dodat.</small>';toast.classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>toast.classList.remove('show'),2300);}
function animateCart(){const panel=document.getElementById('cartPanel');if(!panel)return;panel.classList.remove('bump');void panel.offsetWidth;panel.classList.add('bump');}
function drawCart(){
 const root=document.getElementById('cartItems');if(!root)return;
 const entries=Object.entries(cart).filter(([,v])=>v.qty>0);
 if(!entries.length){root.className='empty';root.textContent='Korpa je prazna.';}
 else{root.className='';root.innerHTML=entries.map(([name,v])=>'<div class="cart-row"><span>'+escapeHtml(name)+'<br><small>'+money(v.price)+'</small></span><span class="qty"><button type="button" data-act="minus" data-name="'+escapeHtml(name)+'">−</button>'+v.qty+'<button type="button" data-act="plus" data-name="'+escapeHtml(name)+'">+</button></span><button class="remove" type="button" data-act="remove" data-name="'+escapeHtml(name)+'">×</button></div>').join('');}
 const total=entries.reduce((s,[,v])=>s+v.price*v.qty,0);document.getElementById('cartTotal').textContent=money(total);const count=entries.reduce((s,[,v])=>s+v.qty,0);const countEl=document.getElementById('cartCount');if(countEl)countEl.textContent=count;
 document.getElementById('cartJson').value=JSON.stringify(entries.map(([name,v])=>({name,qty:v.qty})));
}
function escapeHtml(s){return s.replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
document.querySelectorAll('.add').forEach(b=>b.addEventListener('click',()=>{const n=b.dataset.name,p=Number(b.dataset.price);if(!cart[n])cart[n]={price:p,qty:0};if(cart[n].qty>=30){showCartToast('Maksimalna količina je 30 komada');return;}cart[n].qty++;drawCart();b.classList.remove('is-added','is-adding');void b.offsetWidth;b.classList.add('is-adding');setTimeout(()=>{b.classList.remove('is-adding');b.classList.add('is-added');},150);setTimeout(()=>b.classList.remove('is-added'),520);showCartToast(n);animateCart();}));
document.getElementById('cartItems')?.addEventListener('click',e=>{const b=e.target.closest('button[data-act]');if(!b)return;const n=b.dataset.name;if(b.dataset.act==='plus'&&cart[n].qty<30)cart[n].qty++;if(b.dataset.act==='minus')cart[n].qty--;if(b.dataset.act==='remove')cart[n].qty=0;if(cart[n].qty<=0)delete cart[n];drawCart();});
const type=document.getElementById('order_type'),addr=document.getElementById('address'),addressField=document.getElementById('addressField');
function updateAddress(){const delivery=type.value==='delivery';addressField.hidden=!delivery;addr.required=delivery;}type.addEventListener('change',updateAddress);updateAddress();
document.getElementById('orderForm')?.addEventListener('submit',e=>{if(!Object.keys(cart).length){e.preventDefault();alert('Dodajte bar jedan proizvod u korpu.');}});
</script></body></html>
