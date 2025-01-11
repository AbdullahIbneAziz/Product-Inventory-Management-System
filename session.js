// Check if user is logged in
function checkLogin() {
    // Don't check login status on the login page
    if (window.location.pathname.endsWith('index.html')) {
        return true;
    }
    
    const isLoggedIn = sessionStorage.getItem('loggedIn');
    
    // Basic login check - only redirect if definitely not logged in
    if (!isLoggedIn || isLoggedIn !== 'true') {
        // Store the attempted URL to redirect back after login
        sessionStorage.setItem('redirectUrl', window.location.href);
        window.location.replace('index.html');
        return false;
    }

    // For outlet pages
    if (window.location.pathname.includes('outlet')) {
        const userType = sessionStorage.getItem('userType');
        const outletType = sessionStorage.getItem('outletType');
        
        // Get the outlet name from URL (banani or shyamoli)
        const urlOutlet = window.location.pathname.includes('banani') ? 'banani' : 
                         window.location.pathname.includes('shyamoli') ? 'shyamoli' : '';
        
        if (outletType !== urlOutlet) {
            window.location.replace('index.html');
            return false;
        }
    }

    return true;
}

// Set login session
function setLoginSession(userType, outletType) {
    sessionStorage.setItem('loggedIn', 'true');
    if (userType) {
        sessionStorage.setItem('userType', userType);
    }
    if (outletType) {
        sessionStorage.setItem('outletType', outletType);
    }
    
    // Check if there's a redirect URL stored
    const redirectUrl = sessionStorage.getItem('redirectUrl');
    if (redirectUrl) {
        sessionStorage.removeItem('redirectUrl');
        window.location.replace(redirectUrl);
    }
}

// Clear session on logout
function logout() {
    sessionStorage.clear();
    window.location.replace('index.html');
}

// Initialize session check
function initSessionCheck() {
    if (!window.location.pathname.endsWith('index.html')) {
        checkLogin();
    }
}

// Check login status when page loads
window.addEventListener('load', initSessionCheck);

// Prevent back button from breaking the session
window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && 
        window.performance.navigation.type === 2)) {
        initSessionCheck();
    }
}); 