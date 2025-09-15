<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-2xl">🚛</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Contractor Login</h1>
        <p class="text-gray-600">Sign in to manage your business operations</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.contractor.authenticate') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="user_type" value="contractor">
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Business Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
        </div>
        
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" 
                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="text-green-600 hover:text-green-700 font-medium">
                    Forgot your password?
                </a>
            </div>
        </div>
        
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
            Sign In
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-gray-600">Don't have an account? 
            <a href="{{ route('register.contractor') }}" class="text-green-600 hover:text-green-700 font-medium">Register here</a>
        </p>
    </div>
</x-guest-layout>