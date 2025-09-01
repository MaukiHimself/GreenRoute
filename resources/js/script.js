// Sample user data for demonstration
const users = {
    client: [
        { email: 'client@example.com', password: 'password123', name: 'John Client' }
    ],
    contractor: [
        { email: 'contractor@example.com', password: 'password123', company: 'Eco Waste Solutions' }
    ]
};

        document.addEventListener('DOMContentLoaded', function() {
            // Page elements
            const loginPage = document.getElementById('login-page');
            const roleSelectionPage = document.getElementById('role-selection-page');
            const clientRegistrationPage = document.getElementById('client-registration-page');
            const contractorRegistrationPage = document.getElementById('contractor-registration-page');
            
            // Login form elements
            const loginForm = document.getElementById('login-form');
            const createAccountLink = document.getElementById('create-account');
            const backToLoginLink = document.getElementById('back-to-login');
            
            // Role selection elements
            const roleOptions = document.querySelectorAll('.role-option');
            const btnContinue = document.getElementById('btn-continue');
            
            // Registration form elements
            const clientForm = document.getElementById('client-form');
            const contractorForm = document.getElementById('contractor-form');
            const clientBackButton = document.getElementById('client-back-button');
            const contractorBackButton = document.getElementById('contractor-back-button');
            
            // Notification element
            const notification = document.getElementById('notification');
            
            let selectedRole = null;
            
            // Event Listeners
            
            // Login form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const username = document.getElementById('login-username').value;
                const password = document.getElementById('login-password').value;
                
                // Basic validation
                if (!username || !password) {
                    showNotification('Please fill all fields', 'error');
                    return;
                }
                
                // Check credentials
                let authenticated = false;
                let userType = '';
                
                for (const type in users) {
                    if (users[type].some(user => 
                        (user.email === username || user.username === username) && 
                        user.password === password)) {
                        authenticated = true;
                        userType = type;
                        break;
                    }
                }
                
                if (authenticated) {
                    showNotification(`Login successful! Redirecting to ${userType} dashboard...`, 'success');
                    // In a real application, you would redirect to the appropriate dashboard
                    // window.location.href = `/${userType}-dashboard.html`;
                } else {
                    showNotification('Invalid username or password', 'error');
                }
            });
            
            // Create account link
            createAccountLink.addEventListener('click', function(e) {
                e.preventDefault();
                loginPage.classList.add('hidden');
                roleSelectionPage.classList.remove('hidden');
            });
            
            // Back to login link
            backToLoginLink.addEventListener('click', function(e) {
                e.preventDefault();
                roleSelectionPage.classList.add('hidden');
                loginPage.classList.remove('hidden');
            });
            
            // Role selection
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    roleOptions.forEach(o => o.classList.remove('selected'));
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Store the selected role
                    selectedRole = this.getAttribute('data-role');
                    
                    // Enable the continue button
                    btnContinue.disabled = false;
                });
            });
            
            // Continue button
            btnContinue.addEventListener('click', function() {
                if (selectedRole) {
                    // Hide the selection screen
                    roleSelectionPage.classList.add('hidden');
                    
                    // Show the appropriate form
                    if (selectedRole === 'client') {
                        clientRegistrationPage.classList.remove('hidden');
                    } else if (selectedRole === 'contractor') {
                        contractorRegistrationPage.classList.remove('hidden');
                    }
                }
            });
            
            // Back buttons
            clientBackButton.addEventListener('click', function() {
                clientRegistrationPage.classList.add('hidden');
                roleSelectionPage.classList.remove('hidden');
            });
            
            contractorBackButton.addEventListener('click', function() {
                contractorRegistrationPage.classList.add('hidden');
                roleSelectionPage.classList.remove('hidden');
            });
            
            // Client form submission
            clientForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const firstName = document.getElementById('client-first-name').value;
                const lastName = document.getElementById('client-last-name').value;
                const email = document.getElementById('client-email').value;
                const phone = document.getElementById('client-phone').value;
                const address = document.getElementById('client-address').value;
                const password = document.getElementById('client-password').value;
                const confirmPassword = document.getElementById('client-confirm-password').value;
                
                // Basic validation
                if (!firstName || !lastName || !email || !phone || !address || !password || !confirmPassword) {
                    showNotification('Please fill all required fields', 'error');
                    return;
                }
                
                if (password !== confirmPassword) {
                    showNotification('Passwords do not match', 'error');
                    return;
                }
                
                // Simulate registration
                simulateRegistration('client', {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    phone: phone,
                    address: address,
                    password: password
                });
            });
            
            // Contractor form submission
            contractorForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const companyName = document.getElementById('company-name').value;
                const repFirstName = document.getElementById('rep-first-name').value;
                const repLastName = document.getElementById('rep-last-name').value;
                const email = document.getElementById('contractor-email').value;
                const phone = document.getElementById('contractor-phone').value;
                const address = document.getElementById('business-address').value;
                const businessLicense = document.getElementById('business-license').value;
                const certificate = document.getElementById('certificate').value;
                const password = document.getElementById('contractor-password').value;
                const confirmPassword = document.getElementById('contractor-confirm-password').value;
                
                // Basic validation
                if (!companyName || !repFirstName || !repLastName || !email || !phone || 
                    !address || !businessLicense || !certificate || !password || !confirmPassword) {
                    showNotification('Please fill all required fields', 'error');
                    return;
                }
                
                if (password !== confirmPassword) {
                    showNotification('Passwords do not match', 'error');
                    return;
                }
                
                // Simulate registration
                simulateRegistration('contractor', {
                    companyName: companyName,
                    repFirstName: repFirstName,
                    repLastName: repLastName,
                    email: email,
                    phone: phone,
                    address: address,
                    businessLicense: businessLicense,
                    certificate: certificate,
                    password: password
                });
            });
            
            // Helper functions
            function showNotification(message, type) {
                notification.textContent = message;
                notification.className = 'notification ' + type;
                notification.classList.remove('hidden');
                
                // Auto hide after 5 seconds
                setTimeout(clearNotification, 5000);
            }
            
            function clearNotification() {
                notification.classList.add('hidden');
            }
            
            function simulateRegistration(userType, userData) {
                // This would be replaced with actual API calls to your backend
                
                // Simulate API call delay
                showNotification('Registering account...', 'success');
                
                setTimeout(function() {
                    // Mock successful registration
                    let message = `${userType === 'client' ? 'Client' : 'Contractor'} registration successful! `;
                    
                    if (userType === 'contractor') {
                        message += 'Your account is pending administrator approval.';
                    } else {
                        message += 'You can now login to your account.';
                    }
                    
                    showNotification(message, 'success');
                    
                    // Add the new user to our sample data
                    users[userType].push({
                        email: userData.email,
                        password: userData.password,
                        name: userData.firstName + ' ' + userData.lastName || 'New User'
                    });
                    
                    // Clear form and go back to login
                    if (userType === 'client') {
                        clientForm.reset();
                        setTimeout(() => {
                            clientRegistrationPage.classList.add('hidden');
                            loginPage.classList.remove('hidden');
                        }, 2000);
                    } else {
                        contractorForm.reset();
                        setTimeout(() => {
                            contractorRegistrationPage.classList.add('hidden');
                            loginPage.classList.remove('hidden');
                        }, 2000);
                    }
                }, 1500);
            }
        });
    });