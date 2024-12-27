function validateLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('error-message');
    
    // Check if credentials match
    if (username === 'admin' && password === 'admin') {
        // Successful login
        errorMessage.style.display = 'none';
        // Set login status in session storage
        sessionStorage.setItem('isLoggedIn', 'true');
        // Redirect to home page
        window.location.replace('home.html');
    } else {
        // Failed login
        errorMessage.style.display = 'block';
        errorMessage.textContent = 'Invalid username or password';
    }
    
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordLink = document.querySelector('.remember-forgot a');
    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Contact Administration for further assistance');
    });
}); 