 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Login Form</h1>
    <div>
        @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <form method="post" action="{{ route('register2') }}">
     @csrf
     @method('POST')
    <div class="container">
 <!-- Client Registration Form -->
        <div id="client-registration-page" class="hidden">
            <div class="header">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <span>AFIA Terminal</span>
                </div>
                <p class="subtitle">Client Registration</p>
            </div>
            
            <div class="form-container">
                <form id="client-form">
                    <h2 class="form-title">Client Registration</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="client-first-name">First Name </label>
                            <input type="text" id="client-first-name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="client-last-name">Last Name </label>
                            <input type="text" id="client-last-name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-email">Email Address </label>
                        <input type="email" id="client-email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-phone">Phone Number </label>
                        <input type="tel" id="client-phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-address">Physical Address </label>
                        <input type="text" id="client-address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-reg-number">Registration Number (if provided)</label>
                        <input type="text" id="client-reg-number">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="client-password">Password </label>
                            <input type="password" id="client-password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="client-confirm-password">Confirm Password </label>
                            <input type="password" id="client-confirm-password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Register as Client</button>
                    <button type="button" class="btn-back" id="client-back-button">Back to Selection</button>
                </form>
            </div>
        </div>
        
        <!-- Contractor Registration Form -->
        <div id="contractor-registration-page" class="hidden">
            <div class="header">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <span>AFIA Terminal</span>
                </div>
                <p class="subtitle">Contractor Registration</p>
            </div>
            
            <div class="form-container">
                <form id="contractor-form">
                    <h2 class="form-title">Waste Contractor Registration</h2>
                    
                    <div class="form-group">
                        <label for="company-name">Company Name </label>
                        <input type="text" id="company-name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rep-first-name">Representative First Name </label>
                            <input type="text" id="rep-first-name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="rep-last-name">Representative Last Name </label>
                            <input type="text" id="rep-last-name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="contractor-email">Business Email </label>
                        <input type="email" id="contractor-email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contractor-phone">Business Phone </label>
                        <input type="tel" id="contractor-phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="business-address">Business Address </label>
                        <input type="text" id="business-address" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="business-license">Business License No. </label>
                            <input type="text" id="business-license" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="certificate">Certificate of Incorporation </label>
                            <input type="text" id="certificate" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contractor-password">Password </label>
                            <input type="password" id="contractor-password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contractor-confirm-password">Confirm Password </label>
                            <input type="password" id="contractor-confirm-password" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Register as Contractor</button>
                    <button type="button" class="btn-back" id="contractor-back-button">Back to Selection</button>
                </form>
            </div>
        </div>
    </div>
                </form>
                <script src="script.js"></script>
</div>
</div>
</body>
</html>
