<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-green-600">Administrator Dashboard</h1>
                            <p class="text-gray-600">System Management & Oversight</p>
                        </div>
                        <div class="text-right">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Notifications: 3</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Performance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Contractors</h3>
                    <div class="text-2xl font-bold text-gray-800" id="contractorCount">5</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Clients</h3>
                    <div class="text-2xl font-bold text-gray-800" id="clientCount">24</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Active Routes</h3>
                    <div class="text-2xl font-bold text-gray-800">8</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">System Status</h3>
                    <div class="text-lg font-bold text-green-600">Online</div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-green-600 mb-4">Pending Tasks</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold mb-2">Verify Contractor</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span>John's Waste Services</span>
                                    <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Verify</button>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span>Clean City Solutions</span>
                                    <button class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">Verify</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Update Route</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span>Route A - Downtown</span>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Update</button>
                                </div>
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span>Route B - Suburbs</span>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Contractor Tracking Map -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-green-600">Real-time Contractor Tracking</h2>
                        <div class="text-sm text-gray-600">
                            Last updated: <span id="lastUpdate">Loading...</span>
                        </div>
                    </div>
                    
                    <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800">Active Contractors</h3>
                            <p class="text-2xl font-bold text-blue-600" id="activeContractors">0</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800">Total Locations</h3>
                            <p class="text-2xl font-bold text-green-600" id="totalLocations">0</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800">Last Update</h3>
                            <p class="text-sm text-yellow-600" id="lastLocationUpdate">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.leaflet-assets')

    <script>
        let mapCtx;
        
        GreenRouteMap.whenReady(function () {
            mapCtx = GreenRouteMap.createMap('map', { lat: -6.7924, lng: 39.2083, zoom: 10 });
            loadContractorLocations();
            setInterval(loadContractorLocations, 30000);
        });
        
        function loadContractorLocations() {
            fetch('/admin/contractors/locations')
                .then(response => response.json())
                .then(data => {
                    if (!mapCtx) return;

                    GreenRouteMap.clearMarkers(mapCtx);
                    const points = [];

                    data.forEach(contractor => {
                        const lat = parseFloat(contractor.latitude);
                        const lng = parseFloat(contractor.longitude);
                        points.push({ lat, lng });

                        GreenRouteMap.addMarker(mapCtx, lat, lng, {
                            title: contractor.name,
                            popup: `<strong>${contractor.name}</strong><br>Last seen: ${new Date(contractor.updated_at).toLocaleString()}`,
                        });
                    });

                    if (points.length > 0) {
                        GreenRouteMap.fitBounds(mapCtx, points);
                    }
                    
                    updateStats(data);
                    document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
                })
                .catch(error => {
                    console.error('Error loading contractor locations:', error);
                    document.getElementById('activeContractors').textContent = '0';
                    document.getElementById('totalLocations').textContent = '0';
                });
        }
        
        function updateStats(data) {
            document.getElementById('activeContractors').textContent = data.length;
            document.getElementById('totalLocations').textContent = data.length;
            
            if (data.length > 0) {
                const latest = data.reduce((prev, current) => 
                    new Date(current.updated_at) > new Date(prev.updated_at) ? current : prev
                );
                document.getElementById('lastLocationUpdate').textContent = 
                    new Date(latest.updated_at).toLocaleString();
            }
        }
    </script>
</x-app-layout>