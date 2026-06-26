<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client Groups by Location</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #047857;
            --secondary-color: #c0392b;
            --white-color: #ffffff;
            --light-bg: #f8f9fa;
            --border-color: #e2e8f0;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1400px;
            padding: 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }
        
        .btn-primary {
            background: var(--primary-color) !important;
            border: none !important;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(5, 92, 92, 0.3);
        }
        
        .btn-primary:hover {
            background: #065f46 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 92, 92, 0.4);
        }
        
        .group-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .group-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }
        
        .group-card.selected {
            border-color: var(--primary-color);
            background: #f0fafa;
        }
        
        .group-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }
        
        .group-checkbox {
            width: 24px;
            height: 24px;
            cursor: pointer;
        }
        
        .group-info {
            flex: 1;
        }
        
        .location-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .group-meta {
            display: flex;
            gap: 2rem;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .client-list {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            display: none;
        }
        
        .group-card.expanded .client-list {
            display: block;
        }
        
        .client-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }
        
        .client-checkbox {
            margin-right: 1rem;
        }
        
        .client-details {
            flex: 1;
        }
        
        .client-name {
            font-weight: 600;
            color: #1e293b;
        }
        
        .client-info {
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1.5rem;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            border-top: 3px solid var(--primary-color);
        }
        
        .action-bar.show {
            display: block;
        }
        
        .action-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .selection-info {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .expand-toggle {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            padding: 0.5rem;
            font-size: 1.2rem;
        }
        
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><i class="bi bi-people-fill me-2"></i>Client Groups by Location</h1>
            <div>
                <span id="groupCount" class="text-muted me-3">{{ count($clientGroups) }} location groups</span>
            </div>
        </div>

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Filter Groups</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchLocation" placeholder="Search by location...">
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="filterRegion">
                        <option value="">All Regions</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                        <i class="bi bi-x-circle me-1"></i>Clear Filters
                    </button>
                </div>
                <div class="col-md-5 text-end">
                    <button class="btn btn-outline-primary" onclick="expandAll()">
                        <i class="bi bi-arrows-expand me-1"></i>Expand All
                    </button>
                    <button class="btn btn-outline-primary" onclick="collapseAll()">
                        <i class="bi bi-arrows-collapse me-1"></i>Collapse All
                    </button>
                    <button class="btn btn-primary" onclick="selectAllGroups()">
                        <i class="bi bi-check-square me-1"></i>Select All Groups
                    </button>
                </div>
            </div>
        </div>

        <!-- Client Groups -->
        <div id="groupsContainer">
            @forelse($clientGroups as $group)
                <div class="group-card" data-location="{{ $group->location_name }}" data-region="{{ $group->region }}">
                    <div class="group-header" onclick="toggleGroup(this)">
                        <input type="checkbox" class="group-checkbox" 
                               data-region="{{ $group->region }}" 
                               data-district="{{ $group->district }}" 
                               data-ward="{{ $group->ward }}" 
                               data-street="{{ $group->street }}"
                               onclick="event.stopPropagation(); updateSelection();">
                        
                        <div class="group-info">
                            <div class="location-name">
                                <i class="bi bi-geo-alt-fill me-2"></i>{{ $group->location_name }}
                            </div>
                            <div class="group-meta">
                                <span><i class="bi bi-people me-1"></i><strong>{{ $group->client_count }}</strong> clients</span>
                                <span class="badge badge-active">{{ $group->status }}</span>
                            </div>
                        </div>
                        
                        <button class="expand-toggle">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                    
                    <div class="client-list">
                        @foreach($group->clients as $client)
                            <div class="client-item">
                                <input type="checkbox" class="client-checkbox" 
                                       value="{{ $client->id }}" 
                                       data-group="{{ $group->location_name }}"
                                       onchange="updateSelection()">
                                <div class="client-details">
                                    <div class="client-name">{{ $client->name }}</div>
                                    <div class="client-info">
                                        <span class="me-3"><i class="bi bi-hash me-1"></i>{{ $client->registration_number ?? 'N/A' }}</span>
                                        <span class="me-3"><i class="bi bi-telephone me-1"></i>{{ $client->phone ?? 'N/A' }}</span>
                                        @if($client->category)
                                            <span class="badge bg-secondary">{{ ucfirst($client->category) }}</span>
                                        @endif
                                    </div>
                                    <div class="client-info">
                                        <i class="bi bi-geo me-1"></i>{{ $client->address }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No client groups found. Clients will be grouped automatically by their site location.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar" id="actionBar">
        <div class="action-content">
            <div class="selection-info">
                <i class="bi bi-check-circle-fill me-2"></i>
                <span id="selectedCount">0</span> clients selected from <span id="selectedGroupCount">0</span> groups
            </div>
            <div class="action-buttons">
                <button class="btn btn-outline-secondary" onclick="clearSelection()">
                    <i class="bi bi-x-circle me-1"></i>Clear Selection
                </button>
                <button class="btn btn-primary" onclick="createSchedule()">
                    <i class="bi bi-calendar-plus me-1"></i>Create Schedule
                </button>
                <button class="btn btn-primary" onclick="createInvoice()">
                    <i class="bi bi-receipt me-1"></i>Create Invoice
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleGroup(header) {
            const card = header.closest('.group-card');
            const icon = card.querySelector('.expand-toggle i');
            card.classList.toggle('expanded');
            
            if (card.classList.contains('expanded')) {
                icon.className = 'bi bi-chevron-up';
            } else {
                icon.className = 'bi bi-chevron-down';
            }
        }
        
        function expandAll() {
            document.querySelectorAll('.group-card').forEach(card => {
                card.classList.add('expanded');
                card.querySelector('.expand-toggle i').className = 'bi bi-chevron-up';
            });
        }
        
        function collapseAll() {
            document.querySelectorAll('.group-card').forEach(card => {
                card.classList.remove('expanded');
                card.querySelector('.expand-toggle i').className = 'bi bi-chevron-down';
            });
        }
        
        function selectAllGroups() {
            document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = true);
            document.querySelectorAll('.client-checkbox').forEach(cb => cb.checked = true);
            updateSelection();
        }
        
        function clearSelection() {
            document.querySelectorAll('.group-checkbox, .client-checkbox').forEach(cb => cb.checked = false);
            updateSelection();
        }
        
        function updateSelection() {
            // Handle group checkbox logic
            document.querySelectorAll('.group-checkbox').forEach(groupCb => {
                const card = groupCb.closest('.group-card');
                const clientCheckboxes = card.querySelectorAll('.client-checkbox');
                const allChecked = Array.from(clientCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(clientCheckboxes).some(cb => cb.checked);
                
                groupCb.checked = allChecked;
                groupCb.indeterminate = someChecked && !allChecked;
                
                if (someChecked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
            
            // Count selections
            const selectedClients = document.querySelectorAll('.client-checkbox:checked').length;
            const selectedGroups = document.querySelectorAll('.group-card.selected').length;
            
            document.getElementById('selectedCount').textContent = selectedClients;
            document.getElementById('selectedGroupCount').textContent = selectedGroups;
            
            // Show/hide action bar
            const actionBar = document.getElementById('actionBar');
            if (selectedClients > 0) {
                actionBar.classList.add('show');
            } else {
                actionBar.classList.remove('show');
            }
        }
        
        // When group checkbox is clicked, select/deselect all clients in that group
        document.querySelectorAll('.group-checkbox').forEach(cb => {
            cb.addEventListener('change', function(e) {
                e.stopPropagation();
                const card = this.closest('.group-card');
                const clientCheckboxes = card.querySelectorAll('.client-checkbox');
                clientCheckboxes.forEach(clientCb => clientCb.checked = this.checked);
                updateSelection();
            });
        });
        
        // Filter functionality
        document.getElementById('searchLocation').addEventListener('input', filterGroups);
        document.getElementById('filterRegion').addEventListener('change', filterGroups);
        
        function filterGroups() {
            const searchText = document.getElementById('searchLocation').value.toLowerCase();
            const selectedRegion = document.getElementById('filterRegion').value;
            
            document.querySelectorAll('.group-card').forEach(card => {
                const location = card.dataset.location.toLowerCase();
                const region = card.dataset.region;
                
                const matchesSearch = location.includes(searchText);
                const matchesRegion = !selectedRegion || region === selectedRegion;
                
                if (matchesSearch && matchesRegion) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function clearFilters() {
            document.getElementById('searchLocation').value = '';
            document.getElementById('filterRegion').value = '';
            filterGroups();
        }
        
        function createSchedule() {
            const selectedClientIds = Array.from(document.querySelectorAll('.client-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedClientIds.length === 0) {
                alert('Please select at least one client');
                return;
            }
            
            // Create a form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/client-groups/create-schedule';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            selectedClientIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'client_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function createInvoice() {
            const selectedClientIds = Array.from(document.querySelectorAll('.client-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedClientIds.length === 0) {
                alert('Please select at least one client');
                return;
            }
            
            // Create a form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/client-groups/create-invoice';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            selectedClientIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'client_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
