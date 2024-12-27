// Check if user is logged in
function checkLogin() {
    // Don't check login status on the login page
    if (window.location.pathname.includes('index.html')) {
        return;
    }
    
    const isLoggedIn = sessionStorage.getItem('isLoggedIn');
    if (!isLoggedIn) {
        // Clear history and force redirect
        window.location.replace('index.html');
        return false;
    }
    return true;
}

// Set login status
function setLoginStatus() {
    sessionStorage.setItem('isLoggedIn', 'true');
}

// Clear login status and redirect to login page
function logout() {
    sessionStorage.removeItem('isLoggedIn');
    // Force redirect to login
    window.location.replace('index.html');
}

// Prevent back navigation after logout
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        checkLogin();
    }
});

// Handle back button navigation
window.addEventListener('popstate', function() {
    if (!checkLogin()) {
        window.location.replace('index.html');
    }
});

// Initial check on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkLogin);
} else {
    checkLogin();
}

// Disable browser back button
history.pushState(null, '', location.href);
window.onpopstate = function () {
    history.pushState(null, '', location.href);
    checkLogin();
}; 