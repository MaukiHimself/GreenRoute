<x-guest-layout>
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-2xl">🚛</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Contractor Registration</h1>
        <p class="text-gray-600">Join our platform to grow your waste management business</p>
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

    <form method="POST" action="{{ route('register.contractor.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="user_type" value="contractor">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" required autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
            
            <div>
                <label for="representative_name" class="block text-sm font-medium text-gray-700 mb-2">Representative Name</label>
                <input id="representative_name" type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Business Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Business Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
        </div>
        
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Business Address</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">Business License Number</label>
                <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
            
            <div>
                <label for="certificate" class="block text-sm font-medium text-gray-700 mb-2">Certificate of Incorporation</label>
                <input id="certificate" type="file" name="certificate" required accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">What you'll get:</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Manage client database</li>
                            <li>Schedule and track pickups</li>
                            <li>Generate invoices and reports</li>
                            <li>Grow your business efficiently</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
            Create Business Account
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-gray-600">Already have an account? 
            <a href="{{ route('login.contractor') }}" class="text-green-600 hover:text-green-700 font-medium">Sign in here</a>
        </p>
    </div>
</x-guest-layout>