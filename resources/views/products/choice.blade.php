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
    <form method="post" action="">
     @csrf
     @method('POST')
    <div class="container">
       <!-- Role Selection Page -->
        <div id="role-selection-page" class="hidden">
            <div class="header">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <span>AFIA Terminal</span>
                </div>
                <p class="subtitle">Select Your Registration Type</p>
            </div>
            
            <div class="form-container">
                <h2 class="form-title">Type of User...</h2>
                
                <div class="role-selection">
                    <div class="role-option" data-role="client">
                        <div class="role-icon"></div>
                        <div class="role-info">
                            <div class="role-title">Client</div>
                            <div class="role-desc">I receive waste collection services and want to manage my account.</div>
                            
                            <div class="requirements">
                                <div class="requirements-title">You'll need to provide:</div>
                                <ul class="requirement-list">
                                    <li>First and last name</li>
                                    <li>Email address</li>
                                    <li>Phone number</li>
                                    <li>Physical address</li>
                                    <li>Password for your account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="role-option" data-role="contractor">
                        <div class="role-icon"></div>
                        <div class="role-info">
                            <div class="role-title">Waste Contractor</div>
                            <div class="role-desc">I provide waste collection services and want to manage my business operations.</div>
                            
                            <div class="requirements">
                                <div class="requirements-title">You'll need to provide:</div>
                                <ul class="requirement-list">
                                    <li>Company name and address</li>
                                    <li>Representative's name</li>
                                    <li>Business email and phone</li>
                                    <li>Business license number</li>
                                    <li>Certificate of incorporation</li>
                                    <li>Password for your account</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button id="btn-continue" class="btn-continue" disabled>Continue to Registration</button>
                
                <div class="form-footer">
                    Already have an account? <a href="#" id="back-to-login">Login here</a>
                </div>
            </div>
        </div>
    </form>
    <script src="script.js"></script>
        </div>