# Route Optimization & Minimum Distance — Technical Documentation

## Overview

GreenRoute uses a **Nearest Neighbour (Greedy TSP Heuristic)** algorithm to find the shortest practical collection route for a waste contractor.  
The full path is always structured as:

```
Contractor Base  →  Client Stops (optimised order)  →  Dumping Site
```

---

## 1. Core Algorithm — Nearest Neighbour

### What it is

The Nearest Neighbour algorithm is a greedy heuristic for the **Travelling Salesman Problem (TSP)**. It does not guarantee a globally optimal route, but in practice it produces a route within 20–25 % of optimal while running in O(n²) time — fast enough for the typical 5–50 stop collections in this system.

### Why it was chosen over exact methods

| Method | Time complexity | Optimality | Practical for n > 20 |
|---|---|---|---|
| Brute-force (all permutations) | O(n!) | Perfect | ✗ |
| Dynamic programming (Held-Karp) | O(n² 2ⁿ) | Perfect | ✗ |
| **Nearest Neighbour (used here)** | **O(n²)** | **~80 %** | **✓** |
| Christofides | O(n³) | ~87 % | ✓ |
| Genetic Algorithm | Varies | ~90–95 % | ✓ |

For waste-collection routes with ≤ 50 stops the nearest-neighbour result is good enough, and the road-routing API (OpenRouteService/OSRM) then traces the true driving path along real streets.

---

## 2. Step-by-Step: How the Route is Built

### Step 1 — Collect client coordinates

**Backend** (`RouteOptimizationController.php`):

```php
$clients = Client::where('contractor_id', Auth::id())
    ->whereNotNull('latitude')
    ->whereNotNull('longitude')
    ->get();
```

Clients without GPS are excluded (or geocoded first via the `GeocodeClientJob` batch job).

**Frontend** (`route-management/show.blade.php`): clients are passed as a JSON blob to JavaScript:

```js
const clients = @json($clients);
```

Clients missing coordinates are geocoded on the fly via Nominatim before the algorithm runs:

```js
const geocoded = await geocodeClients(clientsWithoutCoords);
clientsWithCoords.push(...geocoded);
```

---

### Step 2 — Seed the starting point

The algorithm needs a starting node. Two cases:

1. **Contractor base is set** (`User.latitude / User.longitude`): pick the client closest to the base as stop #1.
2. **No base set**: start from the first client in the list (arbitrary).

```js
// JS: find client nearest to contractor base
function nearestToBase(base, clients) {
    let best = null, bestDist = Infinity;
    clients.forEach(c => {
        const d = GreenRouteMap.haversineKm(base.lat, base.lng, c.lat, c.lng);
        if (d < bestDist) { bestDist = d; best = c; }
    });
    return best;
}
```

---

### Step 3 — Run Nearest Neighbour

**PHP implementation** (`RouteOptimizationController@calculateOptimalRoute`):

```php
private function calculateOptimalRoute($clients)
{
    $route    = [];
    $unvisited = $clients->toArray();

    // Start from first client (seed)
    $first = array_shift($unvisited);
    $route[]    = $first;
    $currentLat = $first['latitude'];
    $currentLng = $first['longitude'];

    while (!empty($unvisited)) {
        $nearestIndex = 0;
        $minDistance  = $this->calculateDistance(
            $currentLat, $currentLng,
            $unvisited[0]['latitude'], $unvisited[0]['longitude']
        );

        // Scan all remaining unvisited clients
        for ($i = 1; $i < count($unvisited); $i++) {
            $d = $this->calculateDistance(
                $currentLat, $currentLng,
                $unvisited[$i]['latitude'], $unvisited[$i]['longitude']
            );
            if ($d < $minDistance) {
                $minDistance  = $d;
                $nearestIndex = $i;
            }
        }

        // Visit the nearest client
        $nearest    = $unvisited[$nearestIndex];
        $route[]    = $nearest;
        $currentLat = $nearest['latitude'];
        $currentLng = $nearest['longitude'];
        array_splice($unvisited, $nearestIndex, 1);
    }

    return $route;
}
```

**JavaScript equivalent** (used in the map view):

```js
function optimizeRoute(points, base) {
    if (!points || points.length === 0) return [];

    let unvisited = [...points];
    const ordered = [];

    // Seed: client nearest to base (or first client)
    let current = base ? nearestToBase(base, unvisited) : unvisited[0];
    unvisited = unvisited.filter(p => p !== current);
    ordered.push(current);

    while (unvisited.length > 0) {
        let nearest = null, minDist = Infinity;
        unvisited.forEach(p => {
            const d = GreenRouteMap.haversineKm(current.lat, current.lng, p.lat, p.lng);
            if (d < minDist) { minDist = d; nearest = p; }
        });
        ordered.push(nearest);
        unvisited = unvisited.filter(p => p !== nearest);
        current = nearest;
    }

    return ordered;
}
```

---

### Step 4 — Calculate straight-line distance (Haversine)

The **Haversine formula** gives the great-circle (as-the-crow-flies) distance between two GPS points on Earth.

**Formula:**

```
a = sin²(Δlat/2) + cos(lat1) · cos(lat2) · sin²(Δlon/2)
c = 2 · atan2(√a, √(1−a))
d = R · c          (R = 6371 km)
```

**PHP:**

```php
private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
{
    $R    = 6371; // Earth radius in km
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) ** 2
       + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c; // km
}
```

**JavaScript** (`public/js/greenroute-map.js`):

```js
function haversineKm(lat1, lng1, lat2, lng2) {
    const R    = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a    = Math.sin(dLat / 2) ** 2
               + Math.cos(lat1 * Math.PI / 180)
               * Math.cos(lat2 * Math.PI / 180)
               * Math.sin(dLng / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}
```

---

### Step 5 — Compute total route distance

Sum of all sequential Haversine legs:

```php
private function calculateTotalDistance(array $route): float
{
    $total = 0;
    for ($i = 0; $i < count($route) - 1; $i++) {
        $total += $this->calculateDistance(
            $route[$i]['latitude'],  $route[$i]['longitude'],
            $route[$i+1]['latitude'], $route[$i+1]['longitude']
        );
    }
    return round($total, 2); // km
}
```

> **Note:** this is a straight-line estimate. The actual road distance (Step 6) is always longer.

---

### Step 6 — Trace real road path on the map

Once the client order is known, the optimised coordinates are sent to a routing API to get the real driving path.

`GreenRouteMap.drawRoadRoute(ctx, points, apiKey)` in `public/js/greenroute-map.js`:

**Tier 1 — OpenRouteService (ORS):**

```js
const response = await fetch(
  'https://api.openrouteservice.org/v2/directions/driving-car/geojson',
  {
    method: 'POST',
    headers: { Authorization: apiKey, 'Content-Type': 'application/json' },
    body: JSON.stringify({
      coordinates: points.map(p => [p.lng, p.lat]),
      preference: 'shortest',   // ← minimise road distance
      units: 'km'
    })
  }
);
const geojson = await response.json();
L.geoJSON(geojson, { style: { color: BRAND, weight: 5 } }).addTo(ctx.map);
// Returns: { distance (km), duration (s) }
```

ORS returns GeoJSON with the exact road geometry. The `summary.distance` is the real driven distance in km, shown in the "Route Distance" badge.

**Tier 2 — OSRM fallback (free, no key):**

```js
const url = `https://router.project-osrm.org/route/v1/driving/
    ${points.map(p => `${p.lng},${p.lat}`).join(';')}
    ?overview=full&geometries=geojson`;
const data  = await fetch(url).then(r => r.json());
const route = data.routes[0];
L.geoJSON(route.geometry, { style: { color: BRAND, weight: 5 } }).addTo(ctx.map);
// Returns: { distance (metres ÷ 1000 = km), duration (s) }
```

**Tier 3 — Direct polyline fallback:**  
If both APIs are unreachable (offline or rate-limited), a dashed straight-line polyline is drawn and the Haversine total is displayed as "(direct)".

---

### Step 7 — Append the Dumping Site

The dumping site (from `config/dumping_sites.php`) is always the final destination:

```js
if (dumpingSite) {
    fullPath.push({ lat: dumpingSite.lat, lng: dumpingSite.lng });
    // A red trash icon marker is placed at dumpingSite coords
}
```

The road route covers the full array `[base, ...clients, dumpingSite]`.

---

## 3. Data Flow Diagram

```
Client GPS (browser Geolocation API / Nominatim geocode)
        │
        ▼
clients table  (latitude, longitude, route, route_sequence)
        │
        ▼
RouteOptimizationController::calculateOptimalRoute()
  └─ Nearest Neighbour (Haversine distances)
        │
        ▼
Ordered client array  →  JavaScript optimizeRoute()
  └─ seeded from contractor base
        │
        ▼
GreenRouteMap.drawRoadRoute()
  ├─ OpenRouteService API  (preference: shortest, returns GeoJSON)
  ├─ OSRM fallback         (free, returns GeoJSON)
  └─ Haversine polyline    (offline fallback)
        │
        ▼
Leaflet map   (distance badge updated with real road km)
```

---

## 4. Geocoding Pipeline (address → coordinates)

When a client has no GPS coordinates yet:

1. **Client portal**: browser `Geolocation.watchPosition()` with `enableHighAccuracy: true`, up to 20 attempts targeting ±20 m accuracy.
2. **Manual search**: `POST /location/geocode` → `LocationController@geocodeAddress` → Nominatim (`nominatim.openstreetmap.org/search`) → result cached in `geocode_caches` table.
3. **Batch geocode**: `GeocodeClientJob` queued job processes all clients missing coordinates against Nominatim (respects 1 req/sec rate limit).

---

## 5. How to Implement This From Scratch (Step-by-Step)

### Prerequisites
- Leaflet.js (`npm install leaflet` or CDN)
- An ORS API key (free tier: [openrouteservice.org](https://openrouteservice.org)) — optional; OSRM works without one
- Client GPS data stored in your database

### Step 1 — Store GPS for every client

```sql
ALTER TABLE clients ADD latitude DECIMAL(10,7) NULL;
ALTER TABLE clients ADD longitude DECIMAL(10,7) NULL;
```

Use the browser Geolocation API to capture and save them:

```js
navigator.geolocation.getCurrentPosition(pos => {
    document.getElementById('lat').value = pos.coords.latitude;
    document.getElementById('lng').value = pos.coords.longitude;
});
```

### Step 2 — Run Nearest Neighbour on the backend

```php
function nearestNeighbour(array $clients, ?array $start = null): array
{
    $route     = [];
    $unvisited = $clients;
    $current   = $start ?? array_shift($unvisited);

    while (!empty($unvisited)) {
        usort($unvisited, fn($a, $b) =>
            haversine($current, $a) <=> haversine($current, $b)
        );
        $current   = array_shift($unvisited);
        $route[]   = $current;
    }
    return $route;
}
```

### Step 3 — Initialise a Leaflet map

```js
const map = L.map('map').setView([-6.7924, 39.2083], 12);
L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png')
 .addTo(map);
```

### Step 4 — Place numbered markers for each stop

```js
orderedClients.forEach((client, i) => {
    const icon = L.divIcon({ html: `<div class="stop-badge">${i + 1}</div>` });
    L.marker([client.lat, client.lng], { icon })
     .addTo(map)
     .bindPopup(client.name);
});
```

### Step 5 — Draw the road route via ORS

```js
const coords = orderedClients.map(c => [c.lng, c.lat]);

fetch('https://api.openrouteservice.org/v2/directions/driving-car/geojson', {
    method: 'POST',
    headers: { Authorization: YOUR_ORS_KEY, 'Content-Type': 'application/json' },
    body: JSON.stringify({ coordinates: coords, preference: 'shortest', units: 'km' })
})
.then(r => r.json())
.then(geojson => {
    L.geoJSON(geojson, { style: { color: '#047857', weight: 4 } }).addTo(map);
    const km = geojson.features[0].properties.summary.distance;
    console.log('Road distance:', km.toFixed(1), 'km');
});
```

### Step 6 — Append dumping site and fit bounds

```js
// Add final destination marker
L.marker([dumpSite.lat, dumpSite.lng]).addTo(map).bindPopup('Dumping site');

// Zoom map to fit everything
const allPoints = [base, ...orderedClients, dumpSite];
map.fitBounds(L.latLngBounds(allPoints.map(p => [p.lat, p.lng])), { padding: [40, 40] });
```

---

## 6. Key Files in This Project

| File | Role |
|---|---|
| `app/Http/Controllers/RouteOptimizationController.php` | PHP nearest-neighbour + Haversine |
| `public/js/greenroute-map.js` | Leaflet wrapper, `drawRoadRoute()`, `haversineKm()` |
| `resources/views/route-management/show.blade.php` | JS `optimizeRoute()`, full Base→Clients→Dump path |
| `resources/views/client_portal/location.blade.php` | Client location page, privacy-safe map |
| `app/Http/Controllers/LocationController.php` | Geocoding, reverse-geocoding via Nominatim |
| `config/dumping_sites.php` | Dar es Salaam dumping site coordinates |
| `app/Models/ContractorRoute.php` | Route entity (name, colour, dumping site) |
| `app/Models/Client.php` | Client entity with GPS fields and route assignment |

---

## 7. Limitations & Possible Improvements

| Current limitation | Possible improvement |
|---|---|
| Nearest Neighbour gives ~80 % optimal routes | Replace with 2-opt local search for 5–10 % gain with minimal extra code |
| Straight-line Haversine used for algorithm sorting | Use ORS distance matrix API for road-distance-aware sorting |
| Single dumping site per route | Allow multiple dumping site visits (for very long routes) |
| No time windows | Add pickup time constraints (e.g. only between 07:00–12:00) |
| Sequential geocoding | Batch geocoding via ORS matrix API for large client lists |
