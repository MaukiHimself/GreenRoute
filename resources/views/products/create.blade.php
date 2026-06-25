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

    <form method="post" action="{{route('product.store')}}">
     @csrf
     @method('POST')
    <div class="container">
        <!-- Login Form -->
        <div id="login-page">
            <div class="header">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <span>GreenRoute</span>
                </div>
                <p class="subtitle">Login to Your Account</p>
            </div>
            
            <div class="tabs">
                <div class="tab active">Login</div>
            </div>
            
            <div class="form-container">
                
                <form id="login-form">
                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <input type="text" name="username" id="login-username" placeholder="Enter username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" name="password" id="login-password" placeholder='Enter password' required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit">Login</button>
                    </div>
                    
                    <div class="form-footer">
                        <a href="/`register" id="create-account">Don't have an account? Register</a>
                    </div>
                    
                    <div class="form-footer">
                        <a href="#" id="forgot-password">Forgot password?</a>
                    </div>
                </form>
                
                <div id="notification" class="notification hidden"></div>
            </div>
    </div>

</body>
</html>