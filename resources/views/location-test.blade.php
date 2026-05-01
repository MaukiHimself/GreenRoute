<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location API Test</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 40px; }
        h1 { color: #333; margin-bottom: 30px; }
        .test-section { margin-bottom: 40px; padding: 20px; background: #f9f9f9; border-radius: 6px; border-left: 4px solid #4CAF50; }
        .test-section h2 { color: #4CAF50; font-size: 18px; margin-bottom: 15px; }
        button { background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #45a049; }
        .result { margin-top: 15px; padding: 15px; background: #e8f5e9; border-radius: 4px; display: none; }
        .result.show { display: block; }
        .result.error { background: #ffebee; color: #c62828; }
        .result.error::before { content: "❌ "; }
        .result.success::before { content: "✅ "; }
        .loading { color: #1976d2; }
        .data-list { margin-top: 10px; max-height: 300px; overflow-y: auto; }
        .data-list li { list-style: none; padding: 8px 0; border-bottom: 1px solid #eee; }
        .data-list li:last-child { border-bottom: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗺️ Location API Test Dashboard</h1>
        
        <div class="test-section">
            <h2>1. Test Database Connection</h2>
            <button onclick="testDbConnection()">Check Locations in Database</button>
            <div id="dbResult" class="result"></div>
        </div>

        <div class="test-section">
            <h2>2. Get All Regions</h2>
            <button onclick="testGetRegions()">Fetch Regions</button>
            <div id="regionsResult" class="result"></div>
        </div>

        <div class="test-section">
            <h2>3. Get Districts by Region</h2>
            <button onclick="testGetDistricts()">Fetch Districts (ARUSHA)</button>
            <div id="districtsResult" class="result"></div>
        </div>

        <div class="test-section">
            <h2>4. Get Wards by District</h2>
            <button onclick="testGetWards()">Fetch Wards (ARUSHA → ARUSHA CBD)</button>
            <div id="wardsResult" class="result"></div>
        </div>

        <div class="test-section">
            <h2>5. Get Streets by Ward</h2>
            <button onclick="testGetStreets()">Fetch Streets (ARUSHA → ARUSHA CBD → SEKEI)</button>
            <div id="streetsResult" class="result"></div>
        </div>

        <div class="test-section">
            <h2>6. Search Locations</h2>
            <button onclick="testSearchLocations()">Search "ARUSHA" (Autocomplete)</button>
            <div id="searchResult" class="result"></div>
        </div>
    </div>

    <script>
        const API_BASE = '/api';

        function showResult(elementId, data, isError = false) {
            const element = document.getElementById(elementId);
            element.classList.add('show', isError ? 'error' : 'success');
            element.classList.remove(isError ? 'success' : 'error');
            
            if (isError) {
                element.textContent = '❌ ' + data;
            } else {
                element.innerHTML = '✅ ' + data;
            }
        }

        function testDbConnection() {
            const elem = document.getElementById('dbResult');
            elem.innerHTML = '<span class="loading">⏳ Checking...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/test`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showResult('dbResult', `Total locations: ${data.total_locations}`, false);
                    } else {
                        showResult('dbResult', data.error || 'Failed to fetch', true);
                    }
                })
                .catch(e => showResult('dbResult', e.message, true));
        }

        function testGetRegions() {
            const elem = document.getElementById('regionsResult');
            elem.innerHTML = '<span class="loading">⏳ Fetching regions...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/regions`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const regions = data.data || [];
                        let html = `<strong>Found ${regions.length} regions:</strong><ul class="data-list">`;
                        regions.slice(0, 10).forEach(r => html += `<li>${r}</li>`);
                        if (regions.length > 10) html += `<li><em>...and ${regions.length - 10} more</em></li>`;
                        html += '</ul>';
                        showResult('regionsResult', html, false);
                    } else {
                        showResult('regionsResult', data.message || 'Failed to fetch', true);
                    }
                })
                .catch(e => showResult('regionsResult', e.message, true));
        }

        function testGetDistricts() {
            const elem = document.getElementById('districtsResult');
            elem.innerHTML = '<span class="loading">⏳ Fetching districts...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/districts?region=ARUSHA`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const districts = data.data || [];
                        let html = `<strong>Districts in ARUSHA (${districts.length}):</strong><ul class="data-list">`;
                        districts.forEach(d => html += `<li>${d}</li>`);
                        html += '</ul>';
                        showResult('districtsResult', html, false);
                    } else {
                        showResult('districtsResult', data.message || 'Failed to fetch', true);
                    }
                })
                .catch(e => showResult('districtsResult', e.message, true));
        }

        function testGetWards() {
            const elem = document.getElementById('wardsResult');
            elem.innerHTML = '<span class="loading">⏳ Fetching wards...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/wards?region=ARUSHA&district=ARUSHA%20CBD`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const wards = data.data || [];
                        let html = `<strong>Wards in ARUSHA CBD (${wards.length}):</strong><ul class="data-list">`;
                        wards.slice(0, 15).forEach(w => html += `<li>${w}</li>`);
                        if (wards.length > 15) html += `<li><em>...and ${wards.length - 15} more</em></li>`;
                        html += '</ul>';
                        showResult('wardsResult', html, false);
                    } else {
                        showResult('wardsResult', data.message || 'Failed to fetch', true);
                    }
                })
                .catch(e => showResult('wardsResult', e.message, true));
        }

        function testGetStreets() {
            const elem = document.getElementById('streetsResult');
            elem.innerHTML = '<span class="loading">⏳ Fetching streets...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/streets?region=ARUSHA&district=ARUSHA%20CBD&ward=SEKEI`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const streets = data.data || [];
                        let html = `<strong>Streets in SEKEI (${streets.length}):</strong><ul class="data-list">`;
                        streets.slice(0, 20).forEach(s => html += `<li>${s}</li>`);
                        if (streets.length > 20) html += `<li><em>...and ${streets.length - 20} more</em></li>`;
                        html += '</ul>';
                        showResult('streetsResult', html, false);
                    } else {
                        showResult('streetsResult', data.message || 'Failed to fetch', true);
                    }
                })
                .catch(e => showResult('streetsResult', e.message, true));
        }

        function testSearchLocations() {
            const elem = document.getElementById('searchResult');
            elem.innerHTML = '<span class="loading">⏳ Searching...</span>';
            elem.classList.remove('error');

            fetch(`${API_BASE}/locations/autocomplete?q=ARUSHA&limit=10`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const results = data.data || [];
                        let html = `<strong>Search results for "ARUSHA" (${results.length}):</strong><ul class="data-list">`;
                        results.forEach(r => html += `<li>${r.value}</li>`);
                        html += '</ul>';
                        showResult('searchResult', html, false);
                    } else {
                        showResult('searchResult', data.message || 'Failed to search', true);
                    }
                })
                .catch(e => showResult('searchResult', e.message, true));
        }

        // Run all tests on page load
        window.addEventListener('load', function() {
            console.log('Page loaded - You can click buttons to test individual endpoints');
        });
    </script>
</body>
</html>
