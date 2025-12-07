# Laravel Sustav za Upravljanje Završnim i Diplomskim Radovima

Aplikacija za upravljanje završnim i diplomskim radovima s prijavama studenata i sustavom prioriteta.

## Zahtjevi

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (uključen u PHP)

## Instalacija

### 1. Kloniraj repozitorij

```bash
git clone <repository-url>
cd radovi
```

### 2. Instaliraj dependency-je

```bash
composer install
npm install
```

### 3. Kopiraj .env file

```bash
cp .env.example .env
```

### 4. Generiraj application key

```bash
php artisan key:generate
```

### 5. Kreiraj SQLite bazu podataka

```bash
touch database/database.sqlite
```

### 6. Pokreni migracije i seedere

```bash
php artisan migrate --seed
```

Ova komanda će:
- Kreirati sve potrebne tablice u bazi
- Dodati test korisnike (admin, nastavnik, 2 studenta)
- Inicijalizirati bazu sa potrebnim podacima

### 7. Build frontend assets

```bash
npm run build
```

**Za development:**
```bash
npm run dev
```

### 8. Pokreni aplikaciju

```bash
php artisan serve
```

Aplikacija će biti dostupna na: `http://localhost:8000`

## Test korisnici

Nakon pokretanja seedera, dostupni su sljedeći korisnici:

| Email | Password | Uloga |
|-------|----------|-------|
| admin@example.com | password | Admin |
| nastavnik@example.com | password | Nastavnik |
| student@example.com | password | Student |
| student2@example.com | password | Student |

## Funkcionalnosti

### Admin
- Upravljanje ulogama korisnika
- Pregled svih radova
- Pristup svim funkcionalnostima

### Nastavnik
- Dodavanje novih završnih/diplomskih radova
- Uređivanje i brisanje vlastitih radova
- Pregled prijava studenata na vlastite radove
- Prihvaćanje prijava studenata (samo prioritet 1)
- Automatsko odbijanje ostalih prijava nakon prihvaćanja

### Student
- Pregled dostupnih radova
- Prijava na do 5 radova s prioritetima (1-5)
- Svaki rad mora imati različit prioritet
- Pregled prihvaćenog rada na dashboardu
- Otkazivanje prijava

## Napomene

- Baza podataka (SQLite) se automatski kreira i puni test podacima
- Student može prijaviti maksimalno 5 radova
- Svaki rad mora imati različit prioritet (1-5)
- Nastavnik može prihvatiti samo prijave s prioritetom 1
- Kada nastavnik prihvati prijavu, sve ostale prijave na isti rad se automatski odbijaju
- Aplikacija podržava hrvatski i engleski jezik

## Multilingvalna podrška

Aplikacija podržava:
- Hrvatski (hr) - zadani jezik
- Engleski (en)

Jezik se može mijenjati preko navigacije u aplikaciji.

## Razvojno okruženje

Za razvoj možete pokrenuti:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server (hot reload)
npm run dev
```

### Migracije fail
Provjerite da li je `.env` file ispravno konfiguriran:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

## Git ignore

SQLite baza (`database/database.sqlite`) **JE uključena** u `.gitignore`, tako da neće biti pushana. Svaki korisnik mora pokrenuti `php artisan migrate --seed` nakon kloniranja.

Ako želite uključiti primjer baze u repo, maknite `database/*.sqlite` iz `.gitignore` filea.

