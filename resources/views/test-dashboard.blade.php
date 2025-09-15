<x-dashboard-layout title="Test Dashboard">
    <x-slot name="sidebar">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Test</li>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <h1>Test Dashboard</h1>
            <p>This is a test of the dashboard component.</p>
        </div>
    </div>
</x-dashboard-layout>
