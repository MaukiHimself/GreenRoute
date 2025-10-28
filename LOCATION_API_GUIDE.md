# Tanzania Location API Guide

## Overview
This guide documents the Location API endpoints for accessing Tanzania's geographic data (68,593 locations across 28 regions).

## Database Structure
- **Regions**: 28 (e.g., ARUSHA, DAR-ES-SALAAM, MWANZA)
- **Districts**: Multiple per region
- **Wards**: Multiple per district
- **Streets**: Multiple per ward
- **Total Records**: 68,593

---

## API Endpoints

### 1. Get All Regions
Returns a list of all available regions in Tanzania.

**Endpoint:** `GET /api/locations/regions`

**Response:**
```json
{
  "success": true,
  "data": [
    "ARUSHA",
    "DAR-ES-SALAAM",
    "DODOMA",
    "GEITA",
    "IRINGA",
    "..."
  ]
}
```

**cURL Example:**
```bash
curl -X GET http://localhost:8000/api/locations/regions
```

---

### 2. Get Districts by Region
Returns districts for a specific region.

**Endpoint:** `GET /api/locations/districts`

**Query Parameters:**
- `region` (required): Region name

**Response:**
```json
{
  "success": true,
  "data": [
    "ARUSHA CBD",
    "ARUSHA",
    "ARUMERU",
    "MONDULI",
    "LONGIDO",
    "KARATU",
    "NGORONGORO"
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/locations/districts?region=ARUSHA"
```

---

### 3. Get Wards by District
Returns wards for a specific district within a region.

**Endpoint:** `GET /api/locations/wards`

**Query Parameters:**
- `region` (required): Region name
- `district` (required): District name

**Response:**
```json
{
  "success": true,
  "data": [
    "SEKEI",
    "KATI",
    "KALOLENI",
    "LEVOLOSI",
    "NGARENARO",
    "..."
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/locations/wards?region=ARUSHA&district=ARUSHA%20CBD"
```

---

### 4. Get Streets by Ward
Returns streets for a specific ward within a district.

**Endpoint:** `GET /api/locations/streets`

**Query Parameters:**
- `region` (required): Region name
- `district` (required): District name
- `ward` (required): Ward name

**Response:**
```json
{
  "success": true,
  "data": [
    "SANAWARI",
    "NAUREI",
    "MTAA WA AICC",
    "MTAA WA MAHAKAMANI",
    "NAURA",
    "..."
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/locations/streets?region=ARUSHA&district=ARUSHA%20CBD&ward=SEKEI"
```

---

### 5. Search Locations
Search for locations by keyword across all fields.

**Endpoint:** `GET /api/locations/search`

**Query Parameters:**
- `keyword` (required, min 2 chars): Search term

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "region": "ARUSHA",
      "district": "ARUSHA CBD",
      "ward": "KATI",
      "street": "BONDENI"
    },
    {
      "region": "DAR-ES-SALAAM",
      "district": "ILALA CBD",
      "ward": "MCHIKICHINI",
      "street": "MSIMBAZI BONDENI"
    }
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/locations/search?keyword=BONDENI"
```

---

## Frontend Integration Examples

### Vue.js/React Example

```javascript
// LocationDropdowns.vue / LocationDropdowns.jsx

export default {
  data() {
    return {
      regions: [],
      districts: [],
      wards: [],
      streets: [],
      
      selectedRegion: '',
      selectedDistrict: '',
      selectedWard: '',
      selectedStreet: '',
      
      loading: false
    }
  },
  
  mounted() {
    this.loadRegions();
  },
  
  methods: {
    async loadRegions() {
      this.loading = true;
      try {
        const response = await fetch('/api/locations/regions');
        const data = await response.json();
        this.regions = data.data;
      } catch (error) {
        console.error('Error loading regions:', error);
      } finally {
        this.loading = false;
      }
    },
    
    async onRegionChange() {
      this.districts = [];
      this.wards = [];
      this.streets = [];
      this.selectedDistrict = '';
      this.selectedWard = '';
      this.selectedStreet = '';
      
      if (!this.selectedRegion) return;
      
      this.loading = true;
      try {
        const response = await fetch(
          `/api/locations/districts?region=${encodeURIComponent(this.selectedRegion)}`
        );
        const data = await response.json();
        this.districts = data.data;
      } catch (error) {
        console.error('Error loading districts:', error);
      } finally {
        this.loading = false;
      }
    },
    
    async onDistrictChange() {
      this.wards = [];
      this.streets = [];
      this.selectedWard = '';
      this.selectedStreet = '';
      
      if (!this.selectedDistrict) return;
      
      this.loading = true;
      try {
        const response = await fetch(
          `/api/locations/wards?region=${encodeURIComponent(this.selectedRegion)}&district=${encodeURIComponent(this.selectedDistrict)}`
        );
        const data = await response.json();
        this.wards = data.data;
      } catch (error) {
        console.error('Error loading wards:', error);
      } finally {
        this.loading = false;
      }
    },
    
    async onWardChange() {
      this.streets = [];
      this.selectedStreet = '';
      
      if (!this.selectedWard) return;
      
      this.loading = true;
      try {
        const response = await fetch(
          `/api/locations/streets?region=${encodeURIComponent(this.selectedRegion)}&district=${encodeURIComponent(this.selectedDistrict)}&ward=${encodeURIComponent(this.selectedWard)}`
        );
        const data = await response.json();
        this.streets = data.data;
      } catch (error) {
        console.error('Error loading streets:', error);
      } finally {
        this.loading = false;
      }
    }
  }
}
```

### HTML Template

```html
<div class="location-form">
  <div class="form-group">
    <label>Region *</label>
    <select 
      v-model="selectedRegion" 
      @change="onRegionChange"
      :disabled="loading"
      required
    >
      <option value="">-- Select Region --</option>
      <option v-for="region in regions" :key="region" :value="region">
        {{ region }}
      </option>
    </select>
  </div>
  
  <div class="form-group">
    <label>District *</label>
    <select 
      v-model="selectedDistrict" 
      @change="onDistrictChange"
      :disabled="!selectedRegion || loading"
      required
    >
      <option value="">-- Select District --</option>
      <option v-for="district in districts" :key="district" :value="district">
        {{ district }}
      </option>
    </select>
  </div>
  
  <div class="form-group">
    <label>Ward *</label>
    <select 
      v-model="selectedWard" 
      @change="onWardChange"
      :disabled="!selectedDistrict || loading"
      required
    >
      <option value="">-- Select Ward --</option>
      <option v-for="ward in wards" :key="ward" :value="ward">
        {{ ward }}
      </option>
    </select>
  </div>
  
  <div class="form-group">
    <label>Street (Optional)</label>
    <select 
      v-model="selectedStreet"
      :disabled="!selectedWard || loading"
    >
      <option value="">-- Select Street --</option>
      <option v-for="street in streets" :key="street" :value="street">
        {{ street }}
      </option>
    </select>
  </div>
  
  <div v-if="loading" class="loading">
    Loading...
  </div>
</div>
```

---

## Plain JavaScript Example (Vanilla JS)

```javascript
class LocationSelector {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    this.init();
  }
  
  init() {
    this.createDropdowns();
    this.loadRegions();
  }
  
  createDropdowns() {
    this.container.innerHTML = `
      <div class="location-selector">
        <select id="region-select" class="form-control">
          <option value="">-- Select Region --</option>
        </select>
        
        <select id="district-select" class="form-control" disabled>
          <option value="">-- Select District --</option>
        </select>
        
        <select id="ward-select" class="form-control" disabled>
          <option value="">-- Select Ward --</option>
        </select>
        
        <select id="street-select" class="form-control" disabled>
          <option value="">-- Select Street --</option>
        </select>
      </div>
    `;
    
    // Add event listeners
    document.getElementById('region-select').addEventListener('change', (e) => {
      this.loadDistricts(e.target.value);
    });
    
    document.getElementById('district-select').addEventListener('change', (e) => {
      const region = document.getElementById('region-select').value;
      this.loadWards(region, e.target.value);
    });
    
    document.getElementById('ward-select').addEventListener('change', (e) => {
      const region = document.getElementById('region-select').value;
      const district = document.getElementById('district-select').value;
      this.loadStreets(region, district, e.target.value);
    });
  }
  
  async loadRegions() {
    try {
      const response = await fetch('/api/locations/regions');
      const data = await response.json();
      
      const select = document.getElementById('region-select');
      data.data.forEach(region => {
        const option = document.createElement('option');
        option.value = region;
        option.textContent = region;
        select.appendChild(option);
      });
    } catch (error) {
      console.error('Error loading regions:', error);
    }
  }
  
  async loadDistricts(region) {
    const districtSelect = document.getElementById('district-select');
    const wardSelect = document.getElementById('ward-select');
    const streetSelect = document.getElementById('street-select');
    
    // Reset dependent dropdowns
    districtSelect.innerHTML = '<option value="">-- Select District --</option>';
    wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
    streetSelect.innerHTML = '<option value="">-- Select Street --</option>';
    districtSelect.disabled = true;
    wardSelect.disabled = true;
    streetSelect.disabled = true;
    
    if (!region) return;
    
    try {
      const response = await fetch(`/api/locations/districts?region=${encodeURIComponent(region)}`);
      const data = await response.json();
      
      data.data.forEach(district => {
        const option = document.createElement('option');
        option.value = district;
        option.textContent = district;
        districtSelect.appendChild(option);
      });
      
      districtSelect.disabled = false;
    } catch (error) {
      console.error('Error loading districts:', error);
    }
  }
  
  async loadWards(region, district) {
    const wardSelect = document.getElementById('ward-select');
    const streetSelect = document.getElementById('street-select');
    
    // Reset dependent dropdowns
    wardSelect.innerHTML = '<option value="">-- Select Ward --</option>';
    streetSelect.innerHTML = '<option value="">-- Select Street --</option>';
    wardSelect.disabled = true;
    streetSelect.disabled = true;
    
    if (!district) return;
    
    try {
      const response = await fetch(
        `/api/locations/wards?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}`
      );
      const data = await response.json();
      
      data.data.forEach(ward => {
        const option = document.createElement('option');
        option.value = ward;
        option.textContent = ward;
        wardSelect.appendChild(option);
      });
      
      wardSelect.disabled = false;
    } catch (error) {
      console.error('Error loading wards:', error);
    }
  }
  
  async loadStreets(region, district, ward) {
    const streetSelect = document.getElementById('street-select');
    
    streetSelect.innerHTML = '<option value="">-- Select Street --</option>';
    streetSelect.disabled = true;
    
    if (!ward) return;
    
    try {
      const response = await fetch(
        `/api/locations/streets?region=${encodeURIComponent(region)}&district=${encodeURIComponent(district)}&ward=${encodeURIComponent(ward)}`
      );
      const data = await response.json();
      
      data.data.forEach(street => {
        const option = document.createElement('option');
        option.value = street;
        option.textContent = street;
        streetSelect.appendChild(option);
      });
      
      streetSelect.disabled = false;
    } catch (error) {
      console.error('Error loading streets:', error);
    }
  }
  
  getSelectedLocation() {
    return {
      region: document.getElementById('region-select').value,
      district: document.getElementById('district-select').value,
      ward: document.getElementById('ward-select').value,
      street: document.getElementById('street-select').value
    };
  }
}

// Usage:
// const locationSelector = new LocationSelector('location-container');
```

---

## Testing the API

### Test with cURL

```bash
# 1. Get all regions
curl http://localhost:8000/api/locations/regions

# 2. Get districts in ARUSHA
curl "http://localhost:8000/api/locations/districts?region=ARUSHA"

# 3. Get wards in ARUSHA CBD
curl "http://localhost:8000/api/locations/wards?region=ARUSHA&district=ARUSHA%20CBD"

# 4. Get streets in SEKEI ward
curl "http://localhost:8000/api/locations/streets?region=ARUSHA&district=ARUSHA%20CBD&ward=SEKEI"

# 5. Search for locations
curl "http://localhost:8000/api/locations/search?keyword=BONDENI"
```

### Test with Laravel Tinker

```php
php artisan tinker

// Count total locations
DB::table('tbl_locations')->count();

// Get all regions
DB::table('tbl_locations')->distinct()->pluck('region');

// Get districts in ARUSHA
DB::table('tbl_locations')->where('region', 'ARUSHA')->distinct()->pluck('district');

// Search for BONDENI
DB::table('tbl_locations')->where('street', 'LIKE', '%BONDENI%')->get();
```

---

## Error Handling

All endpoints return validation errors in the following format:

```json
{
  "message": "The region field is required.",
  "errors": {
    "region": [
      "The region field is required."
    ]
  }
}
```

---

## Performance Notes

- All queries use database indexes on `region`, `district`, `ward`, and `street` columns
- Queries are optimized with `distinct()` to avoid duplicates
- Search endpoint is limited to 50 results to prevent overwhelming responses
- Consider implementing caching for frequently accessed regions/districts

---

## Next Steps

1. ✅ **API Routes Created** - All location endpoints are ready
2. ✅ **Database Populated** - 68,593 locations imported
3. 🔨 **Frontend Integration** - Use the examples above to create cascading dropdowns
4. 🔨 **Client Registration** - Integrate location dropdowns into client signup forms
5. 🔨 **Contractor Dashboard** - Allow contractors to filter clients by location
6. 🔨 **Route Optimization** - Use location data for waste collection routing

---

## Support

For issues or questions, refer to:
- Laravel Documentation: https://laravel.com/docs
- Database migration: `database/migrations/2025_10_27_194905_create_tbl_locations_table.php`
- Controller: `app/Http/Controllers/LocationController.php`
- Routes: `routes/api.php` (lines 91-108)
