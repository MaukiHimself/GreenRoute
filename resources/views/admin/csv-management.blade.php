@extends('layouts.app')

@section('title', 'CSV Import/Export Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">CSV Management</h1>
        <p class="text-gray-600 mt-2">Import and export data in bulk using CSV files</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8" id="statsContainer">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-green-600 text-sm font-semibold">Locations</div>
            <div class="text-2xl font-bold text-green-900" id="stat-locations">-</div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="text-green-600 text-sm font-semibold">Users</div>
            <div class="text-2xl font-bold text-green-900" id="stat-users">-</div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="text-purple-600 text-sm font-semibold">Clients</div>
            <div class="text-2xl font-bold text-purple-900" id="stat-clients">-</div>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="text-orange-600 text-sm font-semibold">Billing Rates</div>
            <div class="text-2xl font-bold text-orange-900" id="stat-billing-rates">-</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Import Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Import Data
            </h2>

            <div class="space-y-6">
                <!-- Import Type Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Import Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="import-btn p-3 border-2 border-green-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition" data-type="locations">
                            <div class="text-sm font-semibold text-gray-700">📍 Locations</div>
                        </button>
                        <button class="import-btn p-3 border-2 border-green-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition" data-type="users">
                            <div class="text-sm font-semibold text-gray-700">👤 Users</div>
                        </button>
                        <button class="import-btn p-3 border-2 border-purple-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition" data-type="clients">
                            <div class="text-sm font-semibold text-gray-700">🏢 Clients</div>
                        </button>
                        <button class="import-btn p-3 border-2 border-orange-200 rounded-lg hover:border-orange-600 hover:bg-orange-50 transition" data-type="billing-rates">
                            <div class="text-sm font-semibold text-gray-700">💰 Billing Rates</div>
                        </button>
                    </div>
                </div>

                <!-- File Upload -->
                <div id="uploadSection" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Choose CSV File</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-500 transition cursor-pointer" id="dropZone">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        <p class="text-gray-600 text-sm">Drag and drop your CSV file here or click to browse</p>
                        <input type="file" id="csvFile" class="hidden" accept=".csv,.txt" />
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Max file size: 20MB</p>
                </div>

                <!-- Preview Section -->
                <div id="previewSection" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Preview</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-64 overflow-auto">
                        <table class="w-full text-sm" id="previewTable">
                            <!-- Populated dynamically -->
                        </table>
                    </div>
                    <div class="mt-3 text-xs text-gray-600">
                        <span id="previewRows">0</span> rows found
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3" id="actionButtons" style="display: none;">
                    <button id="importBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Import Data
                    </button>
                    <button id="resetBtn" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">
                        Cancel
                    </button>
                </div>

                <!-- Status Messages -->
                <div id="statusMessage"></div>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Export Data
            </h2>

            <div class="space-y-3">
                <p class="text-sm text-gray-600 mb-4">Download your data as CSV files for backup or analysis</p>
                
                <button class="export-btn w-full p-4 border-2 border-green-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition text-left" data-type="locations">
                    <div class="font-semibold text-gray-700">📍 Export Locations</div>
                    <div class="text-xs text-gray-500">Download all locations data</div>
                </button>

                <button class="export-btn w-full p-4 border-2 border-green-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition text-left" data-type="users">
                    <div class="font-semibold text-gray-700">👤 Export Users</div>
                    <div class="text-xs text-gray-500">Download all user accounts</div>
                </button>

                <button class="export-btn w-full p-4 border-2 border-purple-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition text-left" data-type="clients">
                    <div class="font-semibold text-gray-700">🏢 Export Clients</div>
                    <div class="text-xs text-gray-500">Download all clients data</div>
                </button>

                <button class="export-btn w-full p-4 border-2 border-orange-200 rounded-lg hover:border-orange-600 hover:bg-orange-50 transition text-left" data-type="billing_rates">
                    <div class="font-semibold text-gray-700">💰 Export Billing Rates</div>
                    <div class="text-xs text-gray-500">Download billing rate configuration</div>
                </button>

                <div id="exportStatus"></div>
            </div>
        </div>
    </div>

    <!-- CSV Format Templates Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">CSV Format Templates</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Locations Template -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3">📍 Locations CSV Format</h3>
                <div class="bg-gray-50 p-3 rounded text-xs font-mono text-gray-700 overflow-x-auto">
                    <pre>region,district,ward,street
ARUSHA,ARUSHA,ARUSHA,Avenue Street
ARUSHA,ARUSHA,SAMPSON,Main Road</pre>
                </div>
                <p class="text-xs text-gray-600 mt-2"><strong>Required columns:</strong> region, district, ward, street</p>
            </div>

            <!-- Users Template -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3">👤 Users CSV Format</h3>
                <div class="bg-gray-50 p-3 rounded text-xs font-mono text-gray-700 overflow-x-auto">
                    <pre>name,email,password,role,phone
John Doe,john@example.com,secret123,admin,255712345678
Jane Smith,jane@example.com,secret456,contractor,255712345679</pre>
                </div>
                <p class="text-xs text-gray-600 mt-2"><strong>Required columns:</strong> name, email, password, role</p>
            </div>

            <!-- Clients Template -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3">🏢 Clients CSV Format</h3>
                <div class="bg-gray-50 p-3 rounded text-xs font-mono text-gray-700 overflow-x-auto">
                    <pre>name,email,phone,region,district,ward,street
ABC Company,contact@abc.com,255712345678,ARUSHA,ARUSHA,ARUSHA,Avenue Street</pre>
                </div>
                <p class="text-xs text-gray-600 mt-2"><strong>Required columns:</strong> name, email, phone</p>
            </div>

            <!-- Billing Rates Template -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-3">💰 Billing Rates CSV Format</h3>
                <div class="bg-gray-50 p-3 rounded text-xs font-mono text-gray-700 overflow-x-auto">
                    <pre>region,district,ward,rate
ARUSHA,ARUSHA,ARUSHA,5000
ARUSHA,ARUSHA,SAMPSON,4500</pre>
                </div>
                <p class="text-xs text-gray-600 mt-2"><strong>Required columns:</strong> region, rate</p>
            </div>
        </div>
    </div>
</div>

<style>
    .import-btn.active {
        border-color: currentColor;
        background-color: rgba(59, 130, 246, 0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedType = null;
    const csvFileInput = document.getElementById('csvFile');
    const dropZone = document.getElementById('dropZone');
    const uploadSection = document.getElementById('uploadSection');
    const previewSection = document.getElementById('previewSection');
    const actionButtons = document.getElementById('actionButtons');
    const statusMessage = document.getElementById('statusMessage');

    // Load statistics
    loadStats();

    // Import type selection
    document.querySelectorAll('.import-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.import-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedType = this.dataset.type;
            uploadSection.style.display = 'block';
            previewSection.style.display = 'none';
            actionButtons.style.display = 'none';
            resetForm();
        });
    });

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#22c55e';
        dropZone.style.backgroundColor = '#f0fdf4';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = 'transparent';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.backgroundColor = 'transparent';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            csvFileInput.files = files;
            previewFile();
        }
    });

    dropZone.addEventListener('click', () => csvFileInput.click());

    csvFileInput.addEventListener('change', previewFile);

    // Preview file
    async function previewFile() {
        const file = csvFileInput.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', selectedType);

        try {
            statusMessage.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded">Loading preview...</div>';

            const response = await fetch('/api/csv/preview', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                displayPreview(data);
                previewSection.style.display = 'block';
                actionButtons.style.display = 'flex';
                statusMessage.innerHTML = '';
            } else {
                statusMessage.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded">${data.message}</div>`;
            }
        } catch (error) {
            statusMessage.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded">Error: ${error.message}</div>`;
        }
    }

    // Display preview table
    function displayPreview(data) {
        const table = document.getElementById('previewTable');
        table.innerHTML = '';

        // Headers
        const headerRow = table.insertRow();
        headerRow.style.fontWeight = 'bold';
        headerRow.style.borderBottom = '2px solid #e5e7eb';
        headerRow.style.backgroundColor = '#f3f4f6';

        data.headers.forEach(header => {
            const cell = headerRow.insertCell();
            cell.textContent = header;
            cell.style.padding = '8px';
        });

        // Data rows (show first 5)
        const sampleSize = Math.min(5, data.sample.length);
        for (let i = 0; i < sampleSize; i++) {
            const row = table.insertRow();
            if (i % 2 === 0) row.style.backgroundColor = '#f9fafb';
            
            data.headers.forEach(header => {
                const cell = row.insertCell();
                cell.textContent = data.sample[i][header] || '-';
                cell.style.padding = '8px';
                cell.style.fontSize = '12px';
            });
        }

        document.getElementById('previewRows').textContent = data.totalRows;
    }

    // Import data
    document.getElementById('importBtn').addEventListener('click', async function() {
        const file = csvFileInput.files[0];
        if (!file) {
            statusMessage.innerHTML = '<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded">Please select a file</div>';
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        const endpoint = selectedType === 'billing-rates' ? 'billing-rates' : selectedType;
        const url = `/api/csv/import/${endpoint}`;

        try {
            statusMessage.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded">Importing... This may take a few minutes.</div>';
            this.disabled = true;

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                statusMessage.innerHTML = `<div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded"><strong>✓ Success!</strong> ${data.message}</div>`;
                setTimeout(() => {
                    resetForm();
                    loadStats();
                }, 2000);
            } else {
                statusMessage.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded"><strong>Error:</strong> ${data.message}</div>`;
            }

            this.disabled = false;
        } catch (error) {
            statusMessage.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded"><strong>Error:</strong> ${error.message}</div>`;
            this.disabled = false;
        }
    });

    // Reset form
    document.getElementById('resetBtn').addEventListener('click', resetForm);

    function resetForm() {
        csvFileInput.value = '';
        uploadSection.style.display = 'none';
        previewSection.style.display = 'none';
        actionButtons.style.display = 'none';
        statusMessage.innerHTML = '';
        document.querySelectorAll('.import-btn').forEach(b => b.classList.remove('active'));
        selectedType = null;
    }

    // Export functionality
    document.querySelectorAll('.export-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const type = this.dataset.type;
            const exportStatus = document.getElementById('exportStatus');
            
            try {
                exportStatus.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded mt-3">Generating export...</div>';
                
                const response = await fetch(`/api/csv/export/${type}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${type}.csv`;
                    a.click();
                    window.URL.revokeObjectURL(url);
                    
                    exportStatus.innerHTML = '<div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded mt-3">✓ Export successful!</div>';
                    setTimeout(() => { exportStatus.innerHTML = ''; }, 3000);
                } else {
                    const data = await response.json();
                    exportStatus.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mt-3">${data.message}</div>`;
                }
            } catch (error) {
                exportStatus.innerHTML = `<div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded mt-3">Error: ${error.message}</div>`;
            }
        });
    });

    // Load and display statistics
    async function loadStats() {
        try {
            const response = await fetch('/api/csv/stats');
            const data = await response.json();

            if (data.success) {
                document.getElementById('stat-locations').textContent = data.stats.locations.toLocaleString();
                document.getElementById('stat-users').textContent = data.stats.users.toLocaleString();
                document.getElementById('stat-clients').textContent = data.stats.clients.toLocaleString();
                document.getElementById('stat-billing-rates').textContent = data.stats.billing_rates.toLocaleString();
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }
});
</script>
@endsection
