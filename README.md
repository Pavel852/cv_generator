# PB CV Generator v1.0.3

Bilingvní (CZ/EN) generátor životopisu s **online náhledem**, **vědeckou sekcí** (publikace, patenty, granty),
drag&drop **přesouváním** položek a exportem do **PDF** (3 šablony: 2 světlé, 1 tmavá).

> Autor: **PB (c) 2025**

## Co je nového ve v1.0.1
- Oprava: načítání jsPDF (bez destrukturování; fallback, když CDN/SRI selže).
- Menší jméno, lepší rozvržení A4, vyladěné šablony.
- Uložení pod **stejným klíčem**, pokud už je CV načteno/uloženo (save.php přijímá `?key=...` nebo `edit_key` v JSON).

## Rychlý start (PHP 7.0)
1. Rozbalte archiv do webového serveru (Apache/Nginx).
2. Zajistěte zápis do složky `data/` pro PHP.
3. Otevřete `cv_generator_v1_0_1.html` v prohlížeči.
4. Vyplňte formulář, vpravo sledujte náhled.
5. Klikněte **Stáhnout PDF & uložit** – server vygeneruje / použije **klíč k editaci** a data uloží jako JSON.
6. Pro úpravy později načtěte přes **Odemknout** (`load.php?key=...`).

### Struktura
```
pb_cv_generator_v1_0_1/
├── cv_generator_v1_0_1.html
├── save.php
├── load.php
└── data/
    └── .htaccess
```

## v1.0.3
- Překlady doplněny pro nové prvky (Zoom, rychlé vstupy tagů) v CZ/EN.
- Náhled defaultně na 80%.
- Přidán rychlý tagový vstup pro **Dovednosti** vč. hvězdiček a návrhů (CZ/EN).
