<x-dashboard-layout title="System Support">
    <x-slot name="sidebar">
        @include('components.sidebars.contractor-nav')
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dashboard.contractor') }}">Waste Contractor</a></li>
        <li class="breadcrumb-item active">Support</li>
    </x-slot>

    @include('client_portal.partials.system-support', ['tickets' => $tickets, 'submitRoute' => route('contractor.support.submit')])
</x-dashboard-layout>
