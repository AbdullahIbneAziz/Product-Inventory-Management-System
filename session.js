// Check if user is logged in
function checkLogin() {
    // Debug logging
    console.log('Current pathname:', window.location.pathname);
    console.log('Session state:', {
        isLoggedIn: sessionStorage.getItem('loggedIn'),
        userType: sessionStorage.getItem('userType'),
        outletType: sessionStorage.getItem('outletType')
    });

    // Don't check login status on the login page
    if (window.location.pathname.endsWith('index.html')) {
        return true;
    }
    
    const isLoggedIn = sessionStorage.getItem('loggedIn');
    const userType = sessionStorage.getItem('userType');
    const outletType = sessionStorage.getItem('outletType');
    
    // Basic login check
    if (!isLoggedIn || isLoggedIn !== 'true') {
        console.log('Login check failed - redirecting to login page');
        window.location.href = 'index.html';
        return false;
    }

    // For outlet pages
    if (window.location.pathname.includes('outlet')) {
        // Get the outlet name from URL (banani or shyamoli)
        const urlOutlet = window.location.pathname.includes('banani') ? 'banani' : 
                         window.location.pathname.includes('shyamoli') ? 'shyamoli' : '';
        
        if (outletType !== urlOutlet) {
            console.log('Outlet type mismatch - redirecting to login page');
            window.location.href = 'index.html';
            return false;
        }
    }

    console.log('Login check passed');
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
    console.log('Session set:', {
        loggedIn: sessionStorage.getItem('loggedIn'),
        userType: sessionStorage.getItem('userType'),
        outletType: sessionStorage.getItem('outletType')
    });
}

// Clear session on logout
function logout() {
    sessionStorage.clear();
    window.location.href = 'index.html';
}

// Single event listener for session check
if (!window.location.pathname.endsWith('index.html')) {
    window.addEventListener('DOMContentLoaded', function() {
        checkLogin();
    });
}

// Handle back/forward cache
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        checkLogin();
    }
}); 