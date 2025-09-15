<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
        <p class="text-gray-600">Please select your user type to continue</p>
    </div>

    <div class="space-y-4">
        <a href="{{ route('login.client') }}" class="group flex items-center justify-between w-full px-6 py-4 bg-white border-2 border-green-100 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200 shadow-sm hover:shadow-md">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <span class="text-xl">👤</span>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-gray-900">Client Login</h3>
                    <p class="text-sm text-gray-600">Access your account and manage services</p>
                </div>
            </div>
            <div class="text-green-600 group-hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('login.contractor') }}" class="group flex items-center justify-between w-full px-6 py-4 bg-white border-2 border-green-100 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200 shadow-sm hover:shadow-md">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <span class="text-xl">🚛</span>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-gray-900">Contractor Login</h3>
                    <p class="text-sm text-gray-600">Manage your business operations</p>
                </div>
            </div>
            <div class="text-green-600 group-hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('login.admin') }}" class="group flex items-center justify-between w-full px-6 py-4 bg-white border-2 border-green-100 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200 shadow-sm hover:shadow-md">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <span class="text-xl">⚙️</span>
                </div>
                <div class="text-left">
                    <h3 class="font-semibold text-gray-900">Admin Login</h3>
                    <p class="text-sm text-gray-600">Access system administration</p>
                </div>
            </div>
            <div class="text-green-600 group-hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <div class="mt-8 text-center">
        <p class="text-gray-600">Don't have an account? 
            <a href="/" class="text-green-600 hover:text-green-700 font-medium">Register here</a>
        </p>
    </div>
</x-guest-layout>