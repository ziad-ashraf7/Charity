// CharityWorks Authentication Functions

// User object structure
const userStructure = {
    id: null,
    name: '',
    email: '',
    type: '', // 'donor', 'admin', etc.
    profile: {
        phone: '',
        address: '',
        profileImage: ''
    },
    donations: [],
    campaigns: []
};

// Check if user is logged in
function isLoggedIn() {
    return localStorage.getItem('charityworks_user') !== null;
}

// Login function
function login(email, password) {
    // In a real implementation, this would make an API call to verify credentials
    // For demo purposes, we'll simulate a successful login
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            if (email && password) {
                const user = {
                    ...userStructure,
                    id: Math.floor(Math.random() * 10000),
                    name: email.split('@')[0],
                    email: email,
                    type: 'donor'
                };
                localStorage.setItem('charityworks_user', JSON.stringify(user));
                resolve(user);
            } else {
                reject(new Error('Invalid credentials'));
            }
        }, 1000);
    });
}

// Logout function
function logout() {
    localStorage.removeItem('charityworks_user');
    window.location.href = 'index.html';
}

// Register function
function register(userData) {
    // In a real implementation, this would make an API call to create a new user
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            if (userData.email && userData.password && userData.name) {
                const user = {
                    ...userStructure,
                    id: Math.floor(Math.random() * 10000),
                    name: userData.name,
                    email: userData.email,
                    type: 'donor'
                };
                localStorage.setItem('charityworks_user', JSON.stringify(user));
                resolve(user);
            } else {
                reject(new Error('Missing required fields'));
            }
        }, 1000);
    });
}

// Update profile function
function updateProfile(profileData) {
    return new Promise((resolve, reject) => {
        const userData = JSON.parse(localStorage.getItem('charityworks_user'));
        if (!userData) {
            reject(new Error('User not logged in'));
            return;
        }

        const updatedUser = {
            ...userData,
            name: profileData.name || userData.name,
            profile: {
                ...userData.profile,
                phone: profileData.phone || userData.profile.phone,
                address: profileData.address || userData.profile.address
            }
        };

        localStorage.setItem('charityworks_user', JSON.stringify(updatedUser));
        resolve(updatedUser);
    });
}

// Change language function
function changeLanguage(lang) {
    localStorage.setItem('charityworks_language', lang);
    return lang;
}

// Get current language
function getCurrentLanguage() {
    return localStorage.getItem('charityworks_language') || 'en';
}

// Initialize auth - check for logged in user
function initAuth() {
    const userData = localStorage.getItem('charityworks_user');
    if (userData) {
        const user = JSON.parse(userData);
        // Update UI for logged in user
        updateAuthUI(user);
    } else {
        // Update UI for guest
        updateAuthUI(null);
    }
}

// Update UI based on auth state
function updateAuthUI(user) {
    const loginLinks = document.querySelectorAll('.login-link');
    const logoutLinks = document.querySelectorAll('.logout-link');
    const profileLinks = document.querySelectorAll('.profile-link');
    const registerLinks = document.querySelectorAll('.register-link');
    const userNameElements = document.querySelectorAll('.user-name');

    if (user) {
        // User is logged in
        loginLinks.forEach(link => link.style.display = 'none');
        registerLinks.forEach(link => link.style.display = 'none');
        logoutLinks.forEach(link => link.style.display = 'block');
        profileLinks.forEach(link => link.style.display = 'block');
        userNameElements.forEach(el => {
            el.textContent = user.name;
            el.style.display = 'inline-block';
        });
    } else {
        // User is not logged in
        loginLinks.forEach(link => link.style.display = 'block');
        registerLinks.forEach(link => link.style.display = 'block');
        logoutLinks.forEach(link => link.style.display = 'none');
        profileLinks.forEach(link => link.style.display = 'none');
        userNameElements.forEach(el => el.style.display = 'none');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initAuth);