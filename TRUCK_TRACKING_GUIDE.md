# Truck/Vehicle Tracking Guide for GreenRoute

## Overview

GreenRoute has a complete truck/vehicle tracking system built-in! You can register trucks, track their locations in real-time on a map, and monitor daily distances traveled.

---

## 🚛 How to Track Trucks in GreenRoute

### Step 1: Access the GPS Tracker

**URL:** `/trucks` (or navigate to **GPS Tracker** from your contractor dashboard)

This page is available for **Contractor users** only.

---

### Step 2: Register Your Trucks

On the GPS Tracker page, you'll see a **"Register New Truck"** form. Fill in:

- **Plate Number** - Vehicle license plate (e.g., "T 123 ABC")
- **Driver Name** - Name of the driver operating the truck
- **Driver Phone** - Driver's contact phone number
- **Truck Type** - Select from:
  - Small Truck
  - Medium Truck
  - Large Truck

Click **"Register Truck"** to save.

---

### Step 3: View Your Trucks on the Map

Once registered, your trucks appear in the **"Registered Trucks"** list on the left side. Each truck shows:

- Plate number
- Driver name and phone
- Truck type badge
- **Online/Offline status** (based on last location update)
- **Daily distance traveled** (in km)
- **Track** and **Simulate** buttons

---

### Step 4: Update Truck Location

#### Option A: Manual Location Update (Simulate)

Click the **"Simulate"** button next to any truck to update its location with random coordinates. This is useful for testing the system.

#### Option B: Real GPS Integration (For Production)

To track real trucks, you need to send location updates to the API endpoint:

**Endpoint:** `POST /trucks/{truckId}/location`

**Request Body:**
```json
{
  "latitude": -6.7924,
  "longitude": 39.2083
}
```

**Example using JavaScript (for a mobile app or GPS device):**
```javascript
async function updateTruckLocation(truckId, lat, lng) {
  const response = await fetch(`/trucks/${truckId}/location`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      latitude: lat,
      longitude: lng
    })
  });
  
  return await response.json();
}
```

**Example using PHP (for a server-side GPS tracker):**
```php
<?php
function updateTruckLocation($truckId, $lat, $lng, $apiToken) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://your-greenroute-domain.com/trucks/{$truckId}/location");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      'latitude' => $lat,
      'longitude' => $lng
  ]));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'X-CSRF-TOKEN: ' . $apiToken
  ]);
  
  $response = curl_exec($ch);
  curl_close($ch);
  
  return json_decode($response, true);
}
?>
```

---

### Step 5: Track Specific Trucks

Click the **"Track"** button next to any truck to zoom the map to that truck's current location. The map will center on the truck and show a popup with:

- Plate number
- Driver details
- Distance traveled today
- Last update time

---

### Step 6: Monitor Live Locations

The map automatically refreshes every **30 seconds** to show updated truck positions. You can also click the **"Refresh"** button in the map header to manually update.

---

## 📊 Truck Tracking Features

### Real-Time Map Display
- All active trucks shown as markers on the map
- Click any marker to see truck details
- Color-coded status (Online = green, Offline = gray)

### Distance Tracking
- Automatically calculates distance between location updates
- Shows daily distance traveled per truck
- Uses Haversine formula for accurate calculations

### Status Monitoring
- **Online**: Truck updated location within last 10 minutes
- **Offline**: No location update for 10+ minutes

---

## 🔧 Technical Implementation Details

### Database Schema

The `trucks` table stores:

| Column | Type | Description |
|--------|------|-------------|
| `id` | int | Primary key |
| `contractor_id` | int | Owner (contractor user) |
| `plate_number` | string | License plate |
| `driver_name` | string | Driver's name |
| `driver_phone` | string | Driver's phone |
| `truck_type` | string | small/medium/large |
| `status` | string | active/inactive |
| `current_latitude` | decimal | Current GPS latitude |
| `current_longitude` | decimal | Current GPS longitude |
| `previous_latitude` | decimal | Previous latitude (for distance calc) |
| `previous_longitude` | decimal | Previous longitude |
| `daily_distance` | decimal | Total km traveled today |
| `last_updated` | datetime | Last location update time |

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/trucks` | View GPS tracker page |
| `POST` | `/trucks` | Register new truck |
| `POST` | `/trucks/{id}/location` | Update truck location |
| `GET` | `/trucks/locations` | Get all truck locations (JSON) |

### Controller Methods

**TruckController.php:**

- `index()` - Display GPS tracker page
- `store()` - Register new truck
- `updateLocation()` - Update truck GPS coordinates
- `getLocations()` - Return all trucks with locations (JSON)
- `calculateDistance()` - Calculate distance between two points

---

## 🌍 Integration with Reachability Routing

Once you implement the **leaflet.reachability** plugin (see `LEAFLET_REACHABILITY_IMPLEMENTATION_GUIDE.md`), you can:

1. **Show Service Areas**: Click a truck to see its reachable area based on driving time/distance
2. **Optimize Routes**: Plan efficient collection routes considering truck locations
3. **Monitor Coverage**: See which areas are covered by your trucks

### Example: Show Truck Reachability

```javascript
function showTruckReachability(truckId) {
  const truck = trucks.find(t => t.id === truckId);
  if (!truck || !mapCtx) return;
  
  // Remove existing reachability layer
  if (currentReachabilityLayer) {
    GreenRouteMap.removeReachabilityLayer(currentReachabilityLayer);
  }
  
  // Add reachability layer (10-minute driving range)
  currentReachabilityLayer = GreenRouteMap.addReachabilityLayer(mapCtx, {
    apiKey: 'your_heigit_api_key',
    profile: 'driving',
    range: 600, // 10 minutes in seconds
    rangeType: 'time'
  });
  
  // Compute reachability from truck location
  GreenRouteMap.computeReachability(truck.current_latitude, truck.current_longitude)
    .then(result => {
      showNotification('Truck service area displayed', 'success');
    })
    .catch(error => {
      showNotification('Failed to calculate service area', 'error');
    });
}
```

---

## 📱 Mobile GPS Integration

To track trucks from a mobile device:

### Option 1: Simple Web App

Create a simple HTML page for drivers:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Truck GPS Tracker</title>
</head>
<body>
    <h2>Update Location</h2>
    <input type="text" id="truckId" placeholder="Truck ID">
    <button onclick="updateLocation()">Send Location</button>
    
    <script>
        function updateLocation() {
            const truckId = document.getElementById('truckId').value;
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    fetch(`/trucks/${truckId}/location`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Location updated!');
                        }
                    });
                });
            } else {
                alert('Geolocation not supported');
            }
        }
        
        // Auto-update every 30 seconds
        setInterval(updateLocation, 30000);
    </script>
</body>
</html>
```

### Option 2: Native Mobile App

Use React Native, Flutter, or native Android/iOS to:
1. Get device GPS coordinates
2. Send updates to `/trucks/{id}/location` endpoint
3. Handle offline caching if needed

---

## 🛠️ Troubleshooting

### Trucks Not Showing on Map

**Check:**
1. Truck has `current_latitude` and `current_longitude` values
2. Browser console for JavaScript errors
3. Network tab for failed API calls

### Location Not Updating

**Check:**
1. CSRF token is included in request
2. User is authenticated as the truck owner
3. Truck ID exists and belongs to the user

### Distance Not Calculating

**Check:**
1. Previous location exists (`previous_latitude`, `previous_longitude`)
2. Location updates are being sent correctly
3. Daily distance resets at midnight (you may need to implement this)

---

## 🚀 Quick Start Commands

```bash
# 1. Access GPS Tracker page
# Open browser to: http://localhost:8000/trucks

# 2. Register a test truck (via form on page)

# 3. Simulate location update
# Click "Simulate" button next to truck

# 4. View on map
# Click "Track" button to zoom to truck

# 5. Test API endpoint
curl -X POST http://localhost:8000/trucks/1/location \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_token" \
  -d '{"latitude":-6.7924,"longitude":39.2083}'
```

---

## 📋 Summary

✅ **Truck tracking is fully implemented** in GreenRoute  
✅ **No additional setup required** - just register trucks and start tracking  
✅ **Real-time map display** with Leaflet.js  
✅ **Automatic distance calculation** between updates  
✅ **API endpoints** ready for mobile/GPS device integration  
✅ **Compatible with reachability routing** for advanced features  

To get started, simply log in as a contractor and visit `/trucks`!
