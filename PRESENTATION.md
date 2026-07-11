# GreenRoute — Presentation & Concepts Guide

A plain-language guide to understand the system for your presentation: what it does, how
it is built, and the **new technical concepts** behind it (especially *how routes are
determined*). Read this top-to-bottom once and you'll be able to explain any screen.

---

## 1. What GreenRoute is (in one paragraph)

GreenRoute is a **waste-collection management platform** for Dar es Salaam. Waste
**contractors** register their **clients** (households/businesses), group those clients into
**collection routes**, send **trucks** along each route, and let the **driver** tick off each
stop as *collected / skipped / blocked*. Clients get **live alerts** ("truck is 8 minutes
away", "waste collected"). Everyone sees everything on an interactive **map**.

---

## 2. The four roles (actors)

| Role | What they do | Logs in at |
|---|---|---|
| **Admin** | Approves contractors, oversees the platform | `/admin/login` |
| **Contractor** | Adds clients, builds routes, manages trucks, watches GPS | `/login/contractor` |
| **Client** | Sees their collection schedule, alerts, invoices, chats | `/client/login` |
| **Driver** | Opens a public link on their phone, shares GPS, ticks off stops | `/driver/track/{token}` (no login) |

> The driver page needs **no password** — it is protected by a secret **tracking token** in
> the URL. That is deliberate: drivers are field staff who shouldn't manage accounts.

---

## 3. Technology stack (what to say when asked "what did you use?")

- **Laravel (PHP)** — the backend framework (controllers, models, database, auth).
- **Blade** — Laravel's HTML templating (the `.blade.php` files are the pages).
- **MySQL** — the database.
- **Leaflet.js + OpenStreetMap** — the maps. **Free, no API key** for the map tiles.
- **OpenRouteService (ORS) / OSRM** — external services that turn a list of points into a
  **road-following path**. ORS uses a free key; OSRM is a free public server (fallback).
- **Bootstrap** — page styling.

---

## 4. System structure — the data model

Everything is built from a few core tables. Understand these boxes and you understand the app:

```
        ┌──────────────┐  approves   ┌──────────────┐
        │    ADMIN     │────────────▶│  CONTRACTOR  │  (users.user_type = 'contractor')
        └──────────────┘             └──────┬───────┘
                                            │ owns
             ┌──────────────────────────────┼───────────────────────────────┐
             ▼                              ▼                                ▼
      ┌────────────┐   assigned to   ┌──────────────┐   drives        ┌────────────┐
      │  CLIENTS   │────────────────▶│CONTRACTOR_    │◀───────────────│   TRUCKS   │
      │ (a stop)   │   .route        │ROUTES (a line)│  assigned_route │ (a vehicle)│
      └─────┬──────┘                 └──────────────┘                 └─────┬──────┘
            │ has login                                                     │ produces
            ▼                                                               ▼
      ┌────────────┐                                              ┌──────────────────┐
      │ USER acct  │                                              │  COLLECTION_RUNS │  (one dispatch)
      │('client')  │                                              │  + run_stops     │  (per-client audit)
      └────────────┘                                              └──────────────────┘
```

Key tables and their job:

| Table | Represents | Important columns |
|---|---|---|
| `users` | Every login (admin, contractor, client) | `user_type`, `email`, `password`, `latitude/longitude` |
| `clients` | A collection **stop** owned by a contractor | `contractor_id`, `route`, `latitude/longitude`, `ward` |
| `contractor_routes` | A named **route** | `route_name`, `district`, `ward`, `dumping_site`, `color` |
| `trucks` | A **vehicle** | `assigned_route_id`, `base_latitude/longitude`, `stop_statuses`, `tracking_token` |
| `collection_runs` | One **dispatch** of a truck along a route | `total_stops`, `collected/skipped/blocked_count`, `status` |
| `collection_run_stops` | What happened at **each client** in a run | `client_id`, `status`, `actioned_at` |
| `truck_locations_history` | GPS **breadcrumbs** (for playback) | `latitude`, `longitude`, `recorded_at` |
| `messages` | Alerts/chats to clients | `message_type` (`eta_alert`, `collection_update`) |

---

## 5. ⭐ THE KEY CONCEPT: How a route is determined

This is the part examiners love. A "route" is built in **five layers**:

### Layer 1 — Create the route (contractor decides the *area*)
On **Route Management**, the contractor creates a route with a **name**, a **district/ward**
it covers, a **colour**, and a **dumping site** (where waste is finally dropped).
Example: *"Masaki–Oysterbay Loop"* → covers Kinondoni → dumps at *Pugu Kinyamwezi*.

### Layer 2 — Attach clients to the route (which stops belong to it)
Each client row has a `route` field. Clients get onto a route in two ways:
- **Manually**: the contractor assigns a client to a route.
- **Automatically at signup**: when a client self-registers, the system matches them to a
  contractor **whose route covers that client's ward**, then to the nearest contractor base.
  (See `ContractorMatchingService`.)

So a route's stops = *all clients whose `route` = that route's name and who have coordinates.*

### Layer 3 — Order the stops (the "which stop first?" problem)
A truck can't visit stops in random order — that wastes fuel. We need the **shortest sensible
order**. Visiting N points in the best possible order is the classic
**Travelling Salesman Problem (TSP)** — famously hard to solve *perfectly*.

We use a fast, good-enough shortcut called the **Nearest-Neighbour heuristic**:

> Start at the truck's **base**. Repeatedly go to the **closest stop you haven't visited yet**.
> Continue until all stops are done, then head to the dumping site.

- Code: `TruckController::optimizeOrder()` and `buildRouteWaypoints()`.
- "Closest" is measured with the **Haversine formula** (straight-line distance between two
  latitude/longitude points on the globe — see Glossary). Code: `haversine()`.

The final ordered list is called the **waypoints**:

```
[ Base (yard) ] → [ nearest client ] → [ next nearest ] → … → [ Dumping site ]
```

### Layer 4 — Draw the real road path (not a straight line)
Waypoints are just dots. To draw the **actual streets** the truck will follow, we send those
dots to a **routing engine** (ORS, then OSRM as backup). It returns hundreds of coordinates
that trace real roads. Code: `TruckController::roadGeometry()`.

Two important engineering decisions here (good to mention):
1. **We do this on the server, not the browser.** Browsers sometimes can't reach the routing
   service, which made the path fall back to an ugly straight line. Computing it server-side
   is reliable.
2. **We cache the result** (6 hours). The same route isn't recalculated on every refresh —
   faster and kinder to the free routing service.

> 🐞 *Real bug we fixed:* some old trucks had **no base location saved**, so their base
> defaulted to coordinates `[0, 0]` — a point in the **Atlantic Ocean**. The route was then a
> straight line from the ocean to Dar. The fix: detect a missing base and fall back to the
> contractor's real location. (`resolveTruckBase()`)

### Layer 5 — Estimate arrival times (ETA)
For each upcoming stop we add up the distances along the path and assume an average speed of
**30 km/h (≈ 2 minutes per km)** to produce an ETA. When the next stop's ETA drops to
**≤ 10 minutes**, the client automatically gets a "truck is nearby" alert.
Code: `TruckController::calculateEtas()`.

---

## 6. GPS tracking — how the truck's dot moves

1. The driver opens the tracking link and taps **"Start Location Sharing"**.
2. The phone's GPS sends `latitude/longitude` to the server every ~20 seconds
   (`POST /driver/location/{token}`).
3. The server:
   - saves a **breadcrumb** in `truck_locations_history` (for later playback),
   - recomputes ETAs and fires client alerts if close,
   - **broadcasts** the new position so the contractor's map moves in real time.
4. The contractor map **polls** every 10 seconds and glides the truck marker to its new spot.

**Playback Audit** replays those breadcrumbs on the map (like a dashcam rewind) with
play/pause and 1×/2×/4× speed — useful to prove a truck actually did its round.

---

## 7. Collection run lifecycle — the "did the job get done?" concept

A **collection run** is one trip of one truck along one route. Its life:

```
 assign route ─▶ [in_progress] ─▶ driver ticks each stop ─▶ all stops done ─▶ [completed]
                     │                (collected/skipped/blocked)                 │
                     │                                                            ├─▶ alert contractor's bell
                     └─ switch route before finishing ─▶ [abandoned]             └─▶ driver can start another route
```

- Every tap the driver makes is stored per client in `collection_run_stops`
  (an **audit trail**: who was collected, who was skipped, and *when*).
- When the **last** stop is actioned, the run is finalised, counts are tallied, and the
  **contractor gets an in-app alert**: *"T123 DEN finished Masaki–Oysterbay Loop: 3 collected
  · 1 skipped · 0 blocked."* Code: `finalizeRunIfComplete()`.
- The driver then sees a **summary card** and can **start another route** immediately — no
  need to wait for the office. Contractors review past runs in the **Collection History**
  drawer on the GPS page.

---

## 8. Alerts without SMS costs (the "in-app notification" idea)

Real SMS gateways (Twilio, Africa's Talking) cost money. Instead, GreenRoute delivers the same
messages **inside the app**:
- A **notification bell** (database-backed) for contractors and clients.
- A **Live Alerts feed** on the client dashboard that polls every few seconds and pops a
  toast when the truck is near or waste is collected.
- Automated messages are also styled specially in the client **chat** page so they look like
  system logs, not human messages.

Message types to know: `eta_alert` (truck nearby) and `collection_update` (collected/skipped/blocked).

---

## 9. Glossary of new terms (say these confidently)

| Term | Plain meaning |
|---|---|
| **Waypoint** | One ordered dot on the route (base, a client, or the dump site). |
| **Latitude / Longitude** | A point's coordinates on Earth (e.g. Dar ≈ -6.79, 39.20). |
| **Haversine formula** | Maths to get the straight-line distance between two lat/lng points on a sphere. |
| **Travelling Salesman Problem (TSP)** | "Visit all stops in the shortest total distance." Hard to solve exactly. |
| **Nearest-Neighbour heuristic** | Quick TSP shortcut: always go to the closest unvisited stop next. |
| **Routing engine (ORS / OSRM)** | A service that turns dots into a real road path. |
| **Polyline** | The line drawn on the map made of many coordinates. |
| **Geocoding** | Turning an address ("Masaki, Kinondoni") into lat/lng. |
| **Tracking token** | A secret code in the driver URL that authorises location sharing without a login. |
| **Breadcrumb** | One saved GPS point in history; many form the playback trail. |
| **Polling** | The page asking the server "anything new?" every few seconds. |
| **Broadcast / real-time** | The server pushing an update out the moment it happens. |

---

## 10. Where things live (file map, if asked to show code)

| Concern | File |
|---|---|
| Truck GPS, routes, runs, ETAs, routing | `app/Http/Controllers/TruckController.php` |
| Route optimisation (nearest-neighbour) | `TruckController::optimizeOrder()` / `buildRouteWaypoints()` |
| Road path + caching | `TruckController::roadGeometry()` |
| Run completion + alert | `TruckController::finalizeRunIfComplete()` |
| Contractor GPS map page | `resources/views/gps/index.blade.php` |
| Driver phone terminal | `resources/views/driver/track.blade.php` |
| Map helper (Leaflet) | `public/js/greenroute-map.js` |
| Client auto-match on signup | `app/Services/ContractorMatchingService.php` |
| Routes (URLs) | `routes/web.php` |
| Data tables | `database/migrations/…` |

---

## 11. 🎬 Live demo script (use the DENIS MAUKI account)

**Login — all passwords: `Mauki@2003`**

- **Contractor:** `denismauki@greenroute.co.tz` at `/login/contractor`
- **A client (to show the other side):** `juma.masaki@greenroute.co.tz` at `/client/login`
- **Driver terminal:** open the tracking link for truck **T123 DEN** (below) on a phone/second window.

**Suggested flow (5–7 minutes):**
1. **Contractor → Route Management:** show the **5 routes** and explain Layer 1 (areas + dump site).
2. **Contractor → Clients:** show **20 clients** across Dar, grouped into the routes (Layer 2).
3. **Contractor → GPS Tracker (`/trucks`):** point out truck **T123 DEN** on *Masaki–Oysterbay
   Loop*. The route **follows real roads** base → 4 clients → Pugu dumpsite (Layers 3–4).
   Mention nearest-neighbour ordering and ETA (Layer 5).
4. **Driver terminal:** tap **Start Location Sharing**, then **Collect / Skip / Block** each
   stop. Watch the contractor map markers turn into ✓ / ✗ / ! and the **progress bar** fill.
5. On the **last stop**, show the **Route Completed** card + **Start Another Route**, and the
   **contractor's notification bell** alert.
6. **Contractor → Collection History** drawer: show the finished run with the per-client breakdown.
7. **Client dashboard** (log in as Juma): show the **Live Alerts** feed / notification the
   client received.

**Truck T123 DEN tracking link:**
```
http://localhost:8000/driver/track/jskCKLCGTmgRun8pRfvaAjXQh9FfMRFQ
```

> If the database is ever reset before the presentation, re-seed the demo data with:
> `php artisan tinker --execute="require base_path('database/seed_denis_presentation.php');"`
> (then re-register the truck, or ask the assistant to.)

---

## 12. Three sentences that summarise the whole project

1. **GreenRoute turns a messy waste-collection operation into an organised, trackable system**
   where contractors group clients into optimised routes and dispatch trucks.
2. **The clever part is route determination** — matching clients by area, ordering stops with a
   nearest-neighbour heuristic, and drawing the real road path with a cached routing engine.
3. **Accountability is built in** — live GPS, per-stop collected/skipped/blocked records,
   automatic client alerts, and a completion report for every run.
