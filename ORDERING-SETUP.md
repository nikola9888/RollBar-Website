# Roll Bar – online poručivanje

Ovaj dodatak je odvojen od postojećeg sajta. **Ne menja index.php, postojeći dizajn, logo niti galeriju.**

## Fajlovi za Plesk

Kopirajte samo nove fajlove/foldere u postojeći httpdocs:

- app/
- poruci/
- admin/
- setup.php

Ne zamenjujte postojeći httpdocs/index.php.

## Prvo podešavanje

1. U Plesku kopirajte navedene fajlove u httpdocs.
2. Otvorite https://rollbar013.rs/setup.php.
3. Postavite administratorsku lozinku (minimum 12 karaktera). Korisničko ime je admin.
4. Kada se potvrdi da je podešavanje završeno, obrišite setup.php sa servera.
5. Otvorite https://rollbar013.rs/admin/ i prijavite se.
6. Stranica za kupce je https://rollbar013.rs/poruci/.

## Baza

Aplikacija koristi SQLite, koji je već dostupan u PHP instalaciji. Baza se kreira van javnog httpdocs direktorijuma, na putanji ../private/rollbar_orders.sqlite.

PHP nalog na IIS-u mora imati dozvolu za kreiranje foldera i upis u njega. Ako setup prijavi problem sa dozvolama, napravite folder private u direktorijumu sajta (jedan nivo iznad httpdocs) i dodelite PHP/IIS korisniku dozvolu Modify.

## Funkcije

- Dostava ili lično preuzimanje
- Korpa i količine
- Ime, telefon, adresa za dostavu i napomena
- Gotovinsko plaćanje
- Broj porudžbine i potvrda kupcu
- Administratorski panel, promena statusa i istorija poslednjih 200 porudžbina
- Automatska provera novih porudžbina i zvučno obaveštenje u otvorenom administratorskom panelu

Napomena: cena dostave se ne obračunava jer iznos nije definisan; potvrđuje se telefonom. SMS/WhatsApp obaveštenja nisu uključena.
