// Check if user is logged in
function checkLogin() {
    // Don't check login status on the login page
    if (window.location.pathname.endsWith('index.html')) {
        return true;
    }
    
    const isLoggedIn = sessionStorage.getItem('loggedIn');
    const userType = sessionStorage.getItem('userType');
    const outletType = sessionStorage.getItem('outletType');
    
    // Basic login check
    if (!isLoggedIn) {
        window.location.href = 'index.html';
        return false;
    }

    // For outlet pages
    if (window.location.pathname.includes('outlet')) {
        // Get the outlet name from URL (banani or shyamoli)
        const urlOutlet = window.location.pathname.includes('banani') ? 'banani' : 
                         window.location.pathname.includes('shyamoli') ? 'shyamoli' : '';
        
        if (outletType !== urlOutlet) {
            window.location.href = 'index.html';
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

// Event listeners
document.addEventListener('DOMContentLoaded', checkLogin);
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        checkLogin();
    }
}); 