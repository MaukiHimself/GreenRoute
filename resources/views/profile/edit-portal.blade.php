<x-dashboard-layout title="Account Settings">
    <x-slot name="nav">
        <ul class="nav nav-pills flex-row">
            @if(auth()->user()->user_type === 'client')
                <li class="nav-item"><a class="nav-link" href="{{ route('client.dashboard') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('client.schedules') }}"><i class="bi bi-calendar3 me-2"></i>Schedules</a></li>
            @elseif(auth()->user()->user_type === 'contractor')
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.contractor') }}"><i class="bi bi-house me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
            @endif
        </ul>
    </x-slot>

    <x-slot name="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ $portalHomeUrl }}">Home</a></li>
        <li class="breadcrumb-item active">Account Settings</li>
    </x-slot>

    <style>
        .portal-profile-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(5, 92, 92, 0.08);
            border: 1px solid #e2e8f0;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .portal-profile-card h2 {
            color: #055c5c;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }
        .portal-profile-card p {
            color: #64748b;
            margin-bottom: 1.25rem;
        }
        .portal-profile-card label {
            font-weight: 600;
            color: #1e293b;
        }
        .portal-profile-card .form-control {
            border-radius: 10px;
            border-color: #cbd5e1;
            padding: 0.65rem 0.9rem;
        }
        .portal-profile-card .form-control:focus {
            border-color: #055c5c;
            box-shadow: 0 0 0 0.2rem rgba(5, 92, 92, 0.15);
        }
        .btn-portal-primary {
            background: linear-gradient(135deg, #055c5c, #087272);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
        }
        .btn-portal-primary:hover { background: #044a4a; color: #fff; }
        .profile-hero {
            background: linear-gradient(135deg, #055c5c, #087272);
            color: #fff;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 700;
        }
    </style>

    <div class="profile-hero d-flex align-items-center gap-3">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div>
            <h1 class="h4 mb-1">{{ $user->name }}</h1>
            <p class="mb-0 opacity-75">{{ $user->email }} · {{ ucfirst($user->user_type) }} account</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="portal-profile-card">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
        <div class="col-lg-6">
            <div class="portal-profile-card">
                @include('profile.partials.update-password-form')
            </div>
            <div class="portal-profile-card border-danger-subtle">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-dashboard-layout>
