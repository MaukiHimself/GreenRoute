<x-dashboard-layout title="System Support">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('client.invoices') }}"><i class="bi bi-receipt me-2"></i>Invoices</a></li>
            <li class="nav-item"><a class="nav-link active" href="{{ route('client.support') }}"><i class="bi bi-headset me-2"></i>Support</a></li>
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ $portalHomeUrl }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Client</a></li>
        <li class="breadcrumb-item active">Support</li>
    </x-slot>

    @include('client_portal.partials.system-support', ['tickets' => $tickets, 'submitRoute' => route('client.support.submit')])
</x-dashboard-layout>
