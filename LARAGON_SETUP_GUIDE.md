# GreenRoute — Run Locally with Laragon (Windows) + Keep Updated with Git

For a Windows PC that already has **Git** and **Laragon** installed, and the
repository already cloned.

---

## Part 1 — First-time setup (do once)

### Step 1. Put the project where Laragon can see it (optional but tidy)
Laragon likes projects in `C:\laragon\www`. If you cloned elsewhere, either move
the folder there or just work from where it is — both work since we use
`php artisan serve`.

### Step 2. Start Laragon services
Open Laragon → click **Start All**. This starts **MySQL** (and Apache, which we
won't use). The MySQL that Laragon starts is what the app will connect to.

### Step 3. Check the tools are available
Open the Laragon **Terminal** (Menu → Terminal, or the "Terminal" button — it has
PHP/Composer/Node pre-wired), `cd` into the project folder, then:

```bash
php -v        # must be 8.2 or higher
composer -V
node -v
mysql --version
```

If PHP is below 8.2: Laragon Menu → PHP → Version → pick 8.2+ (download one via
Menu → Tools → Quick add if none listed).

### Step 4. Install PHP dependencies
```bash
composer install
```

### Step 5. Install JS dependencies and build assets
```bash
npm install
npm run build
```

### Step 6. Create the database
```bash
mysql -u root -e "CREATE DATABASE greenroute_orbit;"
```
(Laragon's default MySQL user is `root` with **no password**. You can also use
HeidiSQL from Laragon's menu and create `greenroute_orbit` by right-click →
Create new → Database.)

### Step 7. Create your `.env` file
`.env` is never in Git (it holds machine-specific secrets), so each PC needs its own:

```bash
copy .env.example .env
php artisan key:generate
```

Then open `.env` in a text editor and confirm/set:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=greenroute_orbit
DB_USERNAME=root
DB_PASSWORD=
```

**Also copy the `HEIGIT_API_KEY=...` line from the old computer's `.env`**
(near the bottom, under "HEIGIT API for reachability routing") — without it the
map falls back to the public OSRM server, which still works but is slower.

### Step 8. Create the tables
```bash
php artisan migrate
```

### Step 9. Load demo data (admin account + presentation data)
```bash
php artisan db:seed --class=CreateAdminSeeder
php artisan tinker --execute="require base_path('database/seed_denis_presentation.php');"
```

### Step 10. Run it
```bash
php artisan serve
```
Open **http://localhost:8000** in the browser. Keep this terminal window open —
closing it stops the server. (Ctrl+C stops it deliberately.)

Log in with the demo contractor: `denismauki@greenroute.co.tz` / `Mauki@2003`.

---

## Part 2 — Updating when the code changes (do every time)

### `git fetch` vs `git pull` — which one?

**Use `git pull`.** The difference:

| Command | What it does |
|---|---|
| `git fetch` | Downloads new commits from GitHub but **does not touch your files**. Look-but-don't-change. |
| `git pull` | = `git fetch` **+ merge**: downloads *and* applies the changes to your files. |

`git fetch` is only useful when you want to *inspect* what changed before
applying it (`git fetch` then `git log HEAD..origin/main`). For normal updating,
`git pull` is the command.

### The update routine

On the **old computer** (where changes are made): commit and push first —
otherwise there is nothing to pull:
```bash
git add -A
git commit -m "describe the change"
git push
```

On the **new PC**:
```bash
git pull
```

Then, depending on what changed (when in doubt, run all four — they are harmless):

```bash
composer install      # if composer.json/composer.lock changed (new PHP packages)
npm install && npm run build   # if package.json or CSS/JS assets changed
php artisan migrate            # if database/migrations/ has new files  ← most common!
php artisan view:clear         # if Blade pages look stale/broken
```

**`php artisan migrate` is the one people forget.** New features often add
database columns (e.g. truck tare weight, waste kg fields). After a pull, if you
see errors like *"Unknown column 'weight_kg'"*, you forgot to migrate.

### If `git pull` complains about local changes
You edited files locally that the pull wants to overwrite. Either throw your
local edits away:
```bash
git checkout -- .
git pull
```
or stash them temporarily:
```bash
git stash
git pull
git stash pop
```

---

## Quick daily cheat-sheet

```bash
# start working
Laragon → Start All
cd path\to\greenroute
git pull
php artisan migrate
php artisan serve      # → http://localhost:8000
```
