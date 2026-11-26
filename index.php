
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ROLL BAR | Pančevo</title>
<meta name="description" content="Roll Bar Pančevo - smash burgeri, tortilje, sendviči, salate, finger food i deserti. Kućna dostava.">

<style>
* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
    margin: 0;
    padding: 0;
}

body {
    margin: 0;
    padding: 0;
    width: 100%;
    background: #FF6A00;
    color: #fff;
    font-family: Arial, sans-serif;
}

.page-frame {
    width: 100%;
    min-height: 100vh;
    padding: 12px;
    background: #FF6A00;
}

.site {
    width: 100%;
    max-width: 1472px;
    margin: 0 auto;
    padding: 36px;
    background: #050505;
    color: #fff;
    overflow: hidden;
}

.content {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.logo {
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo img {
    width: 180px;
    max-width: 100%;
    height: auto;
    display: block;
}

h1 {
    text-align: center;
    font-size: 48px;
    margin: 8px 0;
    color: #fff;
    font-weight: 900;
}

.intro {
    text-align: center;
    color: #bbb;
    font-size: 16px;
    line-height: 1.6;
    margin: 0 auto 25px;
    max-width: 650px;
}

.menu-toggle {
    margin: 20px 0 25px;
}

.menu-toggle summary {
    background: #FF6A00;
    color: #fff;
    padding: 20px 25px;
    border-radius: 18px;
    font-size: 20px;
    font-weight: 900;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 5px 20px rgba(255,106,0,.25);
    list-style: none;
}

.menu-toggle summary::-webkit-details-marker {
    display: none;
}

.menu-content {
    background: #0d0d0d;
    padding: 18px;
    border-radius: 18px;
    margin-top: 12px;
}

.menu-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.menu-grid a {
    background: #181818;
    color: #fff;
    text-decoration: none;
    padding: 16px 8px;
    border-radius: 14px;
    text-align: center;
    font-weight: 800;
    display: block;
}

.menu-grid p {
    margin: 0;
}

.delivery {
    display: block;
    background: #FF6A00;
    color: #fff;
    padding: 16px 14px;
    border-radius: 14px;
    text-align: center;
    font-weight: 900;
    font-size: 18px;
    text-decoration: none;
    margin-bottom: 25px;
    box-shadow: 0 5px 18px rgba(255,106,0,.25);
    transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
}

.delivery:hover {
    background: #ff7b1f;
    box-shadow: 0 7px 22px rgba(255,106,0,.35);
}

.delivery:active {
    transform: scale(.97);
}

.delivery:focus-visible {
    outline: 3px solid #fff;
    outline-offset: 3px;
}

.phone {
    text-align: center;
    margin-bottom: 30px;
}

.phone a {
    color: #FF6A00;
    text-decoration: none;
    font-weight: 800;
}

section {
    margin-bottom: 35px;
    scroll-margin-top: 15px;
}

section h2 {
    color: #FF6A00;
    font-size: 30px;
    text-align: center;
    margin: 0 0 15px;
}

.food-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.food-item summary {
    background: #111;
    padding: 16px;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    color: #fff;
    cursor: pointer;
    list-style: none;
}

.food-item summary::-webkit-details-marker {
    display: none;
}

.food-item summary strong:first-child {
    text-align: left;
}

.price {
    color: #FF6A00;
    white-space: nowrap;
}

.description {
    background: #181818;
    padding: 14px;
    border-radius: 14px;
    margin-top: 5px;
    color: #fff;
    text-align: center;
    line-height: 1.6;
    font-weight: 400;
}

.note {
    text-align: center;
    color: #aaa;
    margin: 15px 0 0;
}

.meal-deal {
    background: #181818;
    padding: 14px;
    border-radius: 14px;
    text-align: center;
    margin-top: 10px;
    color: #fff;
    line-height: 1.6;
}

.meal-deal strong {
    color: #FF6A00;
}

.simple-item {
    background: #111;
    padding: 16px;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.simple-item strong:last-child {
    color: #FF6A00;
    white-space: nowrap;
}

.gallery {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

.gallery p {
    margin: 0;
}

.gallery img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    border-radius: 16px;
    display: block;
    cursor: pointer;
}

.footer-cta {
    background: #FF6A00;
    padding: 20px;
    border-radius: 18px;
    text-align: center;
    margin-top: 25px;
    font-size: 20px;
    font-weight: 900;
}

.copyright {
    text-align: center;
    color: #777;
    font-size: 13px;
    margin-top: 20px;
}

@media (max-width: 600px) {
    .page-frame {
        padding: 8px;
    }

    .site {
        padding: 20px 14px;
    }

    h1 {
        font-size: 38px;
    }

    section h2 {
        font-size: 26px;
    }

    .intro {
        font-size: 15px;
    }

    .menu-toggle summary {
        font-size: 18px;
        padding: 18px 12px;
    }

    .food-item summary,
    .simple-item {
        padding: 14px 12px;
        font-size: 14px;
    }

    .description {
        font-size: 14px;
    }

    .gallery img {
        height: 190px;
    }
}
</style>
</head>

<body>
<div class="page-frame">
<div class="site">
<div class="content">

<!-- LOGO -->
<div class="logo">
    <img src="/roll_bar_logo_3d_spin_loop.gif" alt="Roll Bar">
</div>

<h1>ROLL BAR</h1>

<p class="intro">
Smash burgeri, sendviči, tortilje, salate, finger food i deserti.<br>
Sveže, brzo i napravljeno po porudžbini.<br>
KUĆNA DOSTAVA!
</p>

<!-- GLAVNI MENI -->
<details class="menu-toggle">
<summary>☰ &nbsp; POGLEDAJ MENI</summary>
<div class="menu-content">
<div class="menu-grid">
<p><a href="#burgeri">BURGERI</a></p>
<p><a href="#tortile">TORTILJE</a></p>
<p><a href="#sendvici">SENDVIČI</a></p>
<p><a href="#salate">SALATE</a></p>
<p><a href="#fingerfood">FINGER FOOD</a></p>
<p><a href="#pice">PIĆE</a></p>
<p><a href="#sos">SOS</a></p>
<p><a href="#dezerti">DEZERTI</a></p>
</div>
</div>
</details>

<!-- DOSTAVA -->
<a class="delivery" href="https://rollbar013.rs/poruci/">PORUČI OVDE!</a>

<p class="phone">
<a href="tel:+38163817515">063 815 7515</a>
</p>

<!-- TORTILJE -->
<section id="tortile">
<h2>TORTILJE</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>Chicken-Roll</strong><strong class="price">630 RSD</strong></summary>
<div class="description">Tortilja · Roll sos · Piletina · Crvena slatka paprika · Ljubičasti luk · Crvena salsa · Mešavina kupusa</div>
</details>

<details class="food-item">
<summary><strong>Crispy Chicken-Roll</strong><strong class="price">660 RSD</strong></summary>
<div class="description">Tortilja · Roll sos · Panko piletina · Aisberg salata · Mešavina kupusa · Sweet chilli sos · Crvena salsa</div>
</details>

<details class="food-item">
<summary><strong>Mexico-Roll</strong><strong class="price">770 RSD</strong></summary>
<div class="description">Tortilja · Roll sos · Piletina · Crvena slatka paprika · Crvena salsa · Kukuruz · Jalapeño papričice · Mešavina kupusa · Chili sos</div>
</details>

<details class="food-item">
<summary><strong>Greek-Roll</strong><strong class="price">680 RSD</strong></summary>
<div class="description">Tortilja · Greek sos · Piletina · Crvena slatka paprika · Crvena salsa · Aisberg salata · Mešavina kupusa · Ranč sos</div>
</details>

<details class="food-item">
<summary><strong>Beef-Roll</strong><strong class="price">1680 RSD</strong></summary>
<div class="description">Tortilja · Roll sos · Biftek · Crvena slatka paprika · Crvena salsa · Mešavina kupusa · Kukuruz · Čeri paradajz</div>
</details>

<details class="food-item">
<summary><strong>Vege-Roll</strong><strong class="price">530 RSD</strong></summary>
<div class="description">Tortilja · Crvena slatka paprika · Crvena salsa · Kukuruz · Mešavina kupusa · Aisberg salata</div>
</details>

</div>
</section>

<!-- BURGERI -->
<section id="burgeri">
<h2>SMASH BURGERI</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>Extra Smash</strong><strong class="price">200 RSD</strong></summary>
<div class="description">Dodatni komad burger mesa · 100% junetina</div>
</details>

<details class="food-item">
<summary><strong>Classic-Smash</strong><strong class="price">640 RSD</strong></summary>
<div class="description">Zemička · Aisberg salata · Burger sos · Junetina · Kiseli krastavac · Luk</div>
</details>

<details class="food-item">
<summary><strong>CheeseBurger</strong><strong class="price">720 RSD</strong></summary>
<div class="description">Zemička · Aisberg salata · Burger sos · Junetina · Kiseli krastavac · Luk · Čedar sir</div>
</details>

<details class="food-item">
<summary><strong>Smash-Bacon</strong><strong class="price">800 RSD</strong></summary>
<div class="description">Zemička · Senf · Luk · Junetina · Čedar sir · Krastavac · Kečap · Slanina</div>
</details>

<details class="food-item">
<summary><strong>Smash-Jalapeño</strong><strong class="price">800 RSD</strong></summary>
<div class="description">Zemička · Sos · Junetina · Čedar sir · Jalapeño papričice · Aisberg salata · Hrskavi luk</div>
</details>

<details class="food-item">
<summary><strong>Chicken-Classic</strong><strong class="price">620 RSD</strong></summary>
<div class="description">Zemička · Roll sos · Panko piletina · Čedar sir · Aisberg salata · Crvena salsa</div>
</details>

<p class="note">100% JUNETINA · 013</p>

<div class="meal-deal">
<strong>+ OBROK 270 RSD</strong><br>
Pomfrit + Sos + Sok
</div>

</div>
</section>

<!-- SENDVIČI -->
<section id="sendvici">
<h2>SENDVIČI</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>Crispy Chicken Sandwich</strong><strong class="price">450 RSD</strong></summary>
<div class="description">Hrskava piletina · Roll sos · Aisberg salata · Paradaiz salsa · Sweet chilli sos · Mešavina kupusa</div>
</details>

<details class="food-item">
<summary><strong>Cheese Chicken Sandwich</strong><strong class="price">460 RSD</strong></summary>
<div class="description">Hrskava piletina · Roll sos · Aisberg salata · Paradaiz salsa · Čedar sir</div>
</details>

<details class="food-item">
<summary><strong>Sausage Deluxe Sandwich</strong><strong class="price">560 RSD</strong></summary>
<div class="description">Roštiljska kobasica · Mešavina kupusa · Roll sos · Čedar sir · Grilovana paprika i luk · Džem od luka</div>
</details>

<details class="food-item">
<summary><strong>Philly Cheese Sandwich</strong><strong class="price">580 RSD</strong></summary>
<div class="description">Mleveno juneće meso · Čedar sir · Grilovana paprika i luk</div>
</details>

<details class="food-item">
<summary><strong>Fil Chicken Sandwich</strong><strong class="price">470 RSD</strong></summary>
<div class="description">Grilovana piletina · Aisberg salata · Ranč sos · Paradaiz · Kukuruz šećerac</div>
</details>

</div>
</section>

<!-- SALATE -->
<section id="salate">
<h2>SALATE</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>Caesar</strong><strong class="price">860 RSD</strong></summary>
<div class="description">Slanina · Piletina · Aisberg salata · Parmezan · Čeri paradaiz · Dresing</div>
</details>

<details class="food-item">
<summary><strong>Ranch</strong><strong class="price">690 RSD</strong></summary>
<div class="description">Panko piletina · Aisberg salata · Ranč sos · Čeri paradaiz</div>
</details>

<details class="food-item">
<summary><strong>Beef</strong><strong class="price">1680 RSD</strong></summary>
<div class="description">Biftek · Aisberg salata · Mešavina kupusa · Kukuruz · Čeri paradaiz · Dresing</div>
</details>

<details class="food-item">
<summary><strong>Burger</strong><strong class="price">760 RSD</strong></summary>
<div class="description">Junetina · Slanina · Aisberg salata · Krastavac · Čedar sir · Burger sos</div>
</details>

</div>
</section>

<!-- FINGER FOOD -->
<section id="fingerfood">
<h2>FINGER FOOD</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>Chicken-Nuggets</strong><strong class="price">640 RSD</strong></summary>
<div class="description">Pileći nuggets · Pomfrit · Sos</div>
</details>

<details class="food-item">
<summary><strong>Pomfrit Cheddar</strong><strong class="price">420 RSD</strong></summary>
<div class="description">Pomfrit · Čedar sos</div>
</details>

<details class="food-item">
<summary><strong>Onion Rings</strong><strong class="price">260 RSD</strong></summary>
<div class="description">Pohovani kolutići luka</div>
</details>

<details class="food-item">
<summary><strong>Pomfrit</strong><strong class="price">210 RSD</strong></summary>
<div class="description">Hrskavi pomfrit</div>
</details>

</div>
</section>

<!-- PIĆE -->
<section id="pice">
<h2>PIĆE</h2>
<div class="food-list">

<div class="simple-item"><strong>Coca-Cola</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Coca-Cola Zero</strong><strong>130 RSD</strong></div>
<div class="simple-item"><strong>Fanta</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Schweppes</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>NEXT</strong><strong>130 RSD</strong></div>
<div class="simple-item"><strong>Rosa</strong><strong>90 RSD</strong></div>
<div class="simple-item"><strong>Rosa mineralna</strong><strong>90 RSD</strong></div>

</div>
</section>

<!-- SOS -->
<section id="sos">
<h2>SOSEVI</h2>
<div class="food-list">

<div class="simple-item"><strong>Roll sos</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Honey Mustard</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Ranch</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Chilli</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Sweet Chilli</strong><strong>120 RSD</strong></div>
<div class="simple-item"><strong>Heinz</strong><strong>120 RSD</strong></div>

</div>
</section>

<!-- DEZERTI -->
<section id="dezerti">
<h2>DEZERTI</h2>
<div class="food-list">

<details class="food-item">
<summary><strong>White Widow</strong><strong class="price">290 RSD</strong></summary>
<div class="description">Pirinač · Kondenzovano mleko · Bela čokolada · Maline</div>
</details>

</div>
</section>

<!-- GALERIJA -->
<div class="gallery">

<p>
<a href="https://rollbar013.wordpress.com/wp-content/uploads/2026/09/img_20260923_184445_0916061784067207701421.jpg" target="_blank" rel="noopener">
<img src="https://rollbar013.wordpress.com/wp-content/uploads/2026/09/img_20260923_184445_0916061784067207701421.jpg" alt="Roll Bar">
</a>
</p>

<p>
<a href="https://rollbar013.wordpress.com/wp-content/uploads/2026/09/18452-2.jpg" target="_blank" rel="noopener">
<img src="https://rollbar013.wordpress.com/wp-content/uploads/2026/09/18452-2.jpg" alt="Roll Bar">
</a>
</p>

</div>

<!-- ZAVRŠNI DEO -->
<div class="footer-cta">ROLL BAR 013 · PANČEVO</div>

<p class="copyright">© ROLL BAR</p>

</div>
</div>
</div>
</body>
</html>
