# PHP Daily Menu Crawler

https://app.codeship.com/projects/51eb5860-a781-0136-2134-3e86a6997c9d/status?branch=master
[![Coverage Status](https://coveralls.io/repos/github/zerosuxx/php-slim-dm-crawler/badge.svg?branch=master)](https://coveralls.io/github/zerosuxx/php-slim-dm-crawler?branch=master)

Daily menu

Van az iroda közelében néhány étterem, melyek ebédmenüt kínálnak s ezért előszeretettel látogatunk őket.
Készült annó egy Slack bot, ami minden nap összegyűjti ezen éttermek kínálatát és a #bp-daily-menu csatornában
megosztja a velünk az aktuális napon elérhető menüket.

Készítsd el ennek a bot-nak a webalkalmazás verzióját. Legyen egy weboldal, melyen láthatom az adott napi menü kínálatot.

Részletek:

A következő éttermek menüi érdekelnek:
- http://bonnierestro.hu/hu/napimenu/
- https://rendeles.kajahu.com/?dmenu=0
- http://muzikum.hu/heti-menu/
- http://www.vendiaketterem.hu/heti_ajanlat

(Ezeknek a weboldalaknak nincs API-ja, ezért a weboldalak HTML forrásából kell parsolni az adatokat.)

Készüljön egy faék egyszerű webes felület, amin láthatom a mai napi kínálatot,
és vissza tudjam keresni, a múltbéli kínálatot is! Ezt úgy, hogy meg tudjak adni dátum határértékeket (tól-ig), melyeken belül a rendszer keres.

Tippek:
Találj egy lib-et, amivel kényelmesen tudsz HTML-t (XML-t) parse-olni.

Ez egy apró webalkalmazás, tehát célszerű valamilyen micro framework-ot használni. (pl.: https://www.slimframework.com)

Az adatok beszerzése sokiág is eltarthat, ezért ne a weboldal meglátogatásakor próbáljuk meg letölteni azokat az éttermek oldalairól.
Helyette készítsünk egy "job"-ot, melyet ha futtatunk, beszerzi az éttermektől az adatokat és eltárolja őket.
Az így eltárolt adatokat fogja megjeleníteni a weboldalunk.
Az adatok tárolására használj mysql-t.

Az adatokat külső forrásból kapjuk. Megbízhatunk ezekben a külső forrásokban? (Nem.)

Nézzünk utána az XSS, a CSRF és az SQL injection fogalmainak és védekezzünk ellenük! Hogyan védekezzünk: https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet