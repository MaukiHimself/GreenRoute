# GreenRoute — Windows Setup Guide (No Git / No Laravel Installed)

This guide installs everything from zero on a **Windows PC** that has never had
Git, PHP, Laravel, or anything else. By the end you will run GreenRoute at
`http://localhost:8000`.

---

## 0. How do you get the project files?

GreenRoute is a normal folder of files. Since this PC has no Git, copy the
project folder onto it by **one** of these methods:

- **USB drive / external disk** — copy the whole `greenroute` folder.
- **Cloud / email / WhatsApp** — zip the folder and download it, then unzip.
- **Manual Git (optional)** — see *Optional Step A* at the end if you later
  want to `git clone` instead of copying.

Put the folder somewhere simple, e.g.:

```
C:\greenroute
```

> Tip: avoid paths with spaces or special characters (e.g. `C:\My Documents`).
> `C:\greenroute` is safest.

---

## 1. Install Laragon (gives PHP, MySQL, Composer, Node.js, npm, terminal)

Laragon Full bundles almost everything you need in **one** installer.

1. Go to https://laragon.org/download/ and download **Laragon Full**.
2. Run the installer.
3. When asked for the installation path, use the default:
   `C:\laragon`
4. Finish the install and **launch Laragon**.

In Laragon’s main window click **Start All** (this starts PHP + MySQL).

> Laragon Full already includes: PHP 8.x, MySQL, Composer, Node.js, npm,
> Git, and a terminal (Cmder). You do **not** need to install these separately.

To open the terminal: click the **Terminal** button (or right-click the
Laragon tray icon → Terminal).

---

## 2. Verify the tools are available

In the Laragon Terminal, run each command. Each should print a version number.

```bat
php -v
composer -V
node -v
npm -v
mysql -V
```

If any command is **not found**, open Laragon → **Menu → Tools → Quick add**
and add the missing tool (Composer / Node.js / etc.), then restart Laragon.

Required PHP extensions (Laragon enables these by default — verify with
`php -m`): `bcmath, ctype, curl, dom, fileinfo, gd, json, mbstring, openssl,
pdo_mysql, pdo_sqlite, session, tokenizer, xml, zip, intl`.

---

## 3. Open the project in the terminal

```bat
cd C:\greenroute
```

(List the files to confirm you are in the right place: `dir`. You should see
`artisan`, `composer.json`, `public`, `app`, etc.)

---

## 4. Install PHP dependencies (Composer)

```bat
composer install
```

This downloads Laravel and all PHP packages into a `vendor/` folder. It needs
internet and may take a few minutes. If you see a warning about extensions,
enable them in Laragon and re-run.

---

## 5. Install frontend (Node) dependencies and build assets

```bat
npm install
npm run build
```

`npm install` downloads JavaScript packages; `npm run build` compiles the CSS/JS
into `public/build` so the pages render correctly.

---

## 6. Configure the environment file (`.env`)

The project should already include a `.env` file. If it is missing, create it
from the example:

```bat
copy .env.example .env
```

Now choose a **database**. Two options:

### Option A — SQLite (simplest, no server needed) — RECOMMENDED

Open `C:\greenroute\.env` in Notepad and change the database section to:

```env
DB_CONNECTION=sqlite
# DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD  -> leave blank
```

Then create the empty database file:

```bat
echo. > database\database.sqlite
```

### Option B — MySQL (already running via Laragon)

The default `.env` already points at Laragon’s MySQL, so usually you only need
to create the database:

```bat
mysql -u root -e "CREATE DATABASE IF NOT EXISTS greenroute_orbit;"
```

(Default Laragon MySQL user is `root` with **no password**, matching the
`.env`. If you set a password, update `DB_PASSWORD` in `.env`.)

---

## 7. Generate the app key and run database setup

From the project folder in the Laragon Terminal:

```bat
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

- `key:generate` creates the encryption key (needed for sessions/login).
- `migrate --seed` creates all tables and fills in sample/admin data.
- `storage:link` makes uploaded files publicly accessible.

> If `migrate --seed` fails, run `php artisan migrate:fresh --seed` to rebuild
> the database from scratch (this erases existing data — fine on first setup).

---

## 8. Create an admin account (optional but useful)

```bat
php artisan tinker
```

Then paste:

```php
$admin = new App\Models\User();
$admin->name = 'Administrator';
$admin->email = 'admin@greenroute.com';
$admin->username = 'admin';
$admin->password = bcrypt('Password123!');
$admin->user_type = 'admin';
$admin->status = 'approved';
$admin->email_verified_at = now();
$admin->save();
exit
```

Log in at `http://localhost:8000/admin/login` with `admin` / `Password123!`.

---

## 9. Start the application

```bat
php artisan serve
```

Keep this terminal window open. Open your browser and go to:

```
http://localhost:8000
```

You should see the GreenRoute homepage.

### Login pages
| Page | URL |
|------|-----|
| Admin | `http://localhost:8000/admin/login` |
| Contractor | `http://localhost:8000/login/contractor` |
| Client | `http://localhost:8000/login/client` |
| Register | `http://localhost:8000/register` |

---

## 10. Everyday use (after the first setup)

1. Open **Laragon** → click **Start All**.
2. Open the **Laragon Terminal** → `cd C:\greenroute` → `php artisan serve`.
3. Visit `http://localhost:8000`.

You do **not** need to repeat `composer install` / `npm run build` unless you
update the code.

---

## 11. Map / routing features

The route map uses external APIs. In `.env` you will see:

- `MAPBOX_TOKEN` — get a free token at https://account.mapbox.com
- `HEIGIT_API_KEY` — already provided; used for reachability routing
- `NOMINATIM_USER_AGENT` — needed for OpenStreetMap address search

The map also falls back to OSRM (free, no key) and a straight-line view if the
routing APIs are unreachable, so the app still works offline for demo purposes.

---

## 12. Troubleshooting

| Problem | Fix |
|---|---|
| `php` not recognized | Use the **Laragon Terminal**, not plain CMD. Or add `C:\laragon\bin\php\php-8.x.x` to PATH. |
| Composer out of memory | Run `php -d memory_limit=-1 composer install`. |
| 500 error in browser | Run `php artisan config:clear` and `php artisan cache:clear`; check `php artisan serve` logs. |
| Styles/images broken | Re-run `npm install` then `npm run build`, and `php artisan storage:link`. |
| DB connection error | Confirm Laragon **Start All** is on (MySQL), or switch to SQLite (Step 6, Option A). |
| White page | Set `APP_DEBUG=true` in `.env` and reload to see the real error. |
| Port 8000 busy | Run `php artisan serve --port=8080` and use `http://localhost:8080`. |

---

## Optional Step A — Install Git (only if you want to clone instead of copy)

1. Download Git for Windows from https://git-scm.com/download/win and install
   (accept defaults).
2. In the Laragon Terminal:

   ```bat
   git clone <your-repository-url> C:\greenroute
   cd C:\greenroute
   ```

   Then continue from **Step 4**.

---

## Optional Step B — Run with Apache (instead of `php artisan serve`)

Laragon can serve the site like a real web server:

1. Put the project in `C:\laragon\www\greenroute`.
2. In Laragon click **Start All**.
3. Visit `http://greenroute.test` (Laragon auto-creates the `.test` domain).

This is optional; `php artisan serve` (Step 9) is enough for development and
demos.
