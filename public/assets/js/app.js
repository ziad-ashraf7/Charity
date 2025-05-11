// CharityWorks App.js - Main application functionality

// User Authentication
const auth = {
    isLoggedIn: false,
    currentUser: null,
    
    // Login functionality
    login: function(email, password) {
        // This would connect to a backend service in production
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (email && password) {
                    this.isLoggedIn = true;
                    this.currentUser = {
                        email: email,
                        name: email.split('@')[0],
                        donorType: 'individual',
                        donationsCount: 0
                    };
                    localStorage.setItem('charityworks_user', JSON.stringify(this.currentUser));
                    localStorage.setItem('charityworks_loggedin', 'true');
                    resolve(this.currentUser);
                } else {
                    reject(new Error('Invalid credentials'));
                }
            }, 1000);
        });
    },
    
    // Register functionality
    register: function(userData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (userData.email && userData.password && userData.name) {
                    this.isLoggedIn = true;
                    this.currentUser = {
                        email: userData.email,
                        name: userData.name,
                        donorType: userData.donorType || 'individual',
                        donationsCount: 0
                    };
                    localStorage.setItem('charityworks_user', JSON.stringify(this.currentUser));
                    localStorage.setItem('charityworks_loggedin', 'true');
                    resolve(this.currentUser);
                } else {
                    reject(new Error('Missing required fields'));
                }
            }, 1000);
        });
    },
    
    // Logout functionality
    logout: function() {
        return new Promise((resolve) => {
            setTimeout(() => {
                this.isLoggedIn = false;
                this.currentUser = null;
                localStorage.removeItem('charityworks_user');
                localStorage.removeItem('charityworks_loggedin');
                resolve(true);
            }, 500);
        });
    },
    
    // Update user profile
    updateProfile: function(userData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (!this.isLoggedIn) {
                    reject(new Error('Not logged in'));
                    return;
                }
                
                this.currentUser = { ...this.currentUser, ...userData };
                localStorage.setItem('charityworks_user', JSON.stringify(this.currentUser));
                resolve(this.currentUser);
            }, 800);
        });
    },
    
    // Check login status on page load
    checkLoginStatus: function() {
        const isLoggedIn = localStorage.getItem('charityworks_loggedin') === 'true';
        const userData = localStorage.getItem('charityworks_user');
        
        if (isLoggedIn && userData) {
            this.isLoggedIn = true;
            this.currentUser = JSON.parse(userData);
            return true;
        }
        return false;
    }
};

// Donation Management
const donations = {
    history: [],
    cart: [],
    
    // Make a donation
    makeDonation: function(donationData) {
        return new Promise((resolve, reject) => {
            if (!auth.isLoggedIn) {
                reject(new Error('Please login to make a donation'));
                return;
            }
            
            setTimeout(() => {
                const donation = {
                    id: 'DON' + Date.now(),
                    amount: donationData.amount,
                    campaign: donationData.campaign || 'General Fund',
                    date: new Date().toISOString(),
                    status: 'completed',
                    paymentMethod: donationData.paymentMethod || 'credit_card',
                    donor: auth.currentUser.email
                };
                
                // Update local storage with donation history
                const history = JSON.parse(localStorage.getItem('charityworks_donations') || '[]');
                history.push(donation);
                localStorage.setItem('charityworks_donations', JSON.stringify(history));
                this.history = history;
                
                // Update user donation count
                auth.currentUser.donationsCount = (auth.currentUser.donationsCount || 0) + 1;
                localStorage.setItem('charityworks_user', JSON.stringify(auth.currentUser));
                
                resolve(donation);
            }, 1200);
        });
    },
    
    // Cancel donation
    cancelDonation: function(donationId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const history = JSON.parse(localStorage.getItem('charityworks_donations') || '[]');
                const index = history.findIndex(d => d.id === donationId);
                
                if (index === -1) {
                    reject(new Error('Donation not found'));
                    return;
                }
                
                history[index].status = 'cancelled';
                localStorage.setItem('charityworks_donations', JSON.stringify(history));
                this.history = history;
                
                resolve(history[index]);
            }, 800);
        });
    },
    
    // Track donation status
    trackDonation: function(donationId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const history = JSON.parse(localStorage.getItem('charityworks_donations') || '[]');
                const donation = history.find(d => d.id === donationId);
                
                if (!donation) {
                    reject(new Error('Donation not found'));
                    return;
                }
                
                resolve(donation);
            }, 500);
        });
    },
    
    // Calculate Zakah
    calculateZakah: function(assets) {
        // Basic Zakah calculation (2.5% of assets)
        return assets * 0.025;
    },
    
    // Add to donation cart
    addToCart: function(item) {
        this.cart.push(item);
        localStorage.setItem('charityworks_cart', JSON.stringify(this.cart));
        return this.cart;
    },
    
    // View cart
    viewCart: function() {
        this.cart = JSON.parse(localStorage.getItem('charityworks_cart') || '[]');
        return this.cart;
    },
    
    // Get donation history
    getDonationHistory: function() {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to view donation history'));
        }
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const history = JSON.parse(localStorage.getItem('charityworks_donations') || '[]')
                    .filter(donation => donation.donor === auth.currentUser.email);
                this.history = history;
                resolve(history);
            }, 500);
        });
    },
    
    // Generate donation report
    generateDonationReport: function(startDate, endDate) {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to generate reports'));
        }
        
        return new Promise((resolve) => {
            setTimeout(() => {
                let history = this.history;
                
                if (startDate || endDate) {
                    history = history.filter(donation => {
                        const donationDate = new Date(donation.date).getTime();
                        const isAfterStart = startDate ? donationDate >= new Date(startDate).getTime() : true;
                        const isBeforeEnd = endDate ? donationDate <= new Date(endDate).getTime() : true;
                        return isAfterStart && isBeforeEnd;
                    });
                }
                
                const total = history.reduce((sum, donation) => 
                    donation.status === 'completed' ? sum + parseFloat(donation.amount) : sum, 0);
                
                resolve({
                    donations: history,
                    totalAmount: total,
                    count: history.length,
                    startDate,
                    endDate,
                    generatedAt: new Date().toISOString()
                });
            }, 800);
        });
    }
};

// Campaign and Event Management
const campaigns = {
    campaigns: [],
    events: [],
    
    // Create campaign
    createCampaign: function(campaignData) {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to create campaigns'));
        }
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const campaign = {
                    id: 'CAM' + Date.now(),
                    title: campaignData.title,
                    description: campaignData.description,
                    goal: parseFloat(campaignData.goal),
                    raised: 0,
                    startDate: campaignData.startDate || new Date().toISOString(),
                    endDate: campaignData.endDate,
                    creator: auth.currentUser.email,
                    status: 'active'
                };
                
                const campaigns = JSON.parse(localStorage.getItem('charityworks_campaigns') || '[]');
                campaigns.push(campaign);
                localStorage.setItem('charityworks_campaigns', JSON.stringify(campaigns));
                this.campaigns = campaigns;
                
                resolve(campaign);
            }, 1000);
        });
    },
    
    // Close campaign
    closeCampaign: function(campaignId) {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to close campaigns'));
        }
        
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const campaigns = JSON.parse(localStorage.getItem('charityworks_campaigns') || '[]');
                const index = campaigns.findIndex(c => c.id === campaignId);
                
                if (index === -1) {
                    reject(new Error('Campaign not found'));
                    return;
                }
                
                campaigns[index].status = 'closed';
                localStorage.setItem('charityworks_campaigns', JSON.stringify(campaigns));
                this.campaigns = campaigns;
                
                resolve(campaigns[index]);
            }, 800);
        });
    },
    
    // Add event
    addEvent: function(eventData) {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to add events'));
        }
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const event = {
                    id: 'EVT' + Date.now(),
                    title: eventData.title,
                    description: eventData.description,
                    date: eventData.date,
                    time: eventData.time,
                    location: eventData.location,
                    organizer: auth.currentUser.email,
                    status: 'upcoming'
                };
                
                const events = JSON.parse(localStorage.getItem('charityworks_events') || '[]');
                events.push(event);
                localStorage.setItem('charityworks_events', JSON.stringify(events));
                this.events = events;
                
                resolve(event);
            }, 1000);
        });
    },
    
    // View this month's events
    getMonthEvents: function() {
        return new Promise((resolve) => {
            setTimeout(() => {
                const events = JSON.parse(localStorage.getItem('charityworks_events') || '[]');
                const now = new Date();
                const thisMonth = now.getMonth();
                const thisYear = now.getFullYear();
                
                const monthEvents = events.filter(event => {
                    const eventDate = new Date(event.date);
                    return eventDate.getMonth() === thisMonth && 
                           eventDate.getFullYear() === thisYear;
                });
                
                resolve(monthEvents);
            }, 500);
        });
    },
    
    // Get all campaigns
    getAllCampaigns: function() {
        return new Promise((resolve) => {
            setTimeout(() => {
                const campaigns = JSON.parse(localStorage.getItem('charityworks_campaigns') || '[]');
                this.campaigns = campaigns;
                resolve(campaigns);
            }, 500);
        });
    },
    
    // Get event drive/directions
    getEventDetails: function(eventId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const events = JSON.parse(localStorage.getItem('charityworks_events') || '[]');
                const event = events.find(e => e.id === eventId);
                
                if (!event) {
                    reject(new Error('Event not found'));
                    return;
                }
                
                resolve({
                    ...event,
                    directions: `Directions to ${event.location}: Sample directions would be provided here.`,
                    materials: [
                        { name: 'Event Flyer', url: 'assets/downloads/sample-flyer.pdf' },
                        { name: 'Schedule', url: 'assets/downloads/sample-schedule.pdf' }
                    ]
                });
            }, 600);
        });
    }
};

// Feedback & Communication
const communication = {
    // Send feedback
    sendFeedback: function(feedbackData) {
        return new Promise((resolve) => {
            setTimeout(() => {
                const feedback = {
                    id: 'FDB' + Date.now(),
                    name: feedbackData.name,
                    email: feedbackData.email,
                    message: feedbackData.message,
                    date: new Date().toISOString(),
                    type: 'feedback'
                };
                
                const messages = JSON.parse(localStorage.getItem('charityworks_messages') || '[]');
                messages.push(feedback);
                localStorage.setItem('charityworks_messages', JSON.stringify(messages));
                
                resolve({ success: true, message: 'Feedback sent successfully' });
            }, 800);
        });
    },
    
    // Send message via email
    sendMessage: function(messageData) {
        return new Promise((resolve) => {
            setTimeout(() => {
                const message = {
                    id: 'MSG' + Date.now(),
                    name: messageData.name,
                    email: messageData.email,
                    subject: messageData.subject,
                    message: messageData.message,
                    date: new Date().toISOString(),
                    type: 'contact'
                };
                
                const messages = JSON.parse(localStorage.getItem('charityworks_messages') || '[]');
                messages.push(message);
                localStorage.setItem('charityworks_messages', JSON.stringify(messages));
                
                resolve({ success: true, message: 'Message sent successfully' });
            }, 800);
        });
    },
    
    // Join us request
    joinUs: function(joinData) {
        return new Promise((resolve) => {
            setTimeout(() => {
                const request = {
                    id: 'JOIN' + Date.now(),
                    name: joinData.name,
                    email: joinData.email,
                    phone: joinData.phone,
                    skills: joinData.skills,
                    availability: joinData.availability,
                    message: joinData.message,
                    date: new Date().toISOString(),
                    status: 'pending'
                };
                
                const requests = JSON.parse(localStorage.getItem('charityworks_join_requests') || '[]');
                requests.push(request);
                localStorage.setItem('charityworks_join_requests', JSON.stringify(requests));
                
                resolve({ success: true, message: 'Request submitted successfully' });
            }, 800);
        });
    }
};

// Content & Information
const content = {
    // FAQ data
    getFAQ: function() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        question: "How can I make a donation?",
                        answer: "You can make a donation through our website using credit card, PayPal, or bank transfer. You can also donate in person at our office locations."
                    },
                    {
                        question: "Is my donation tax-deductible?",
                        answer: "Yes, all donations are tax-deductible as we are a registered non-profit organization."
                    },
                    {
                        question: "How is my donation used?",
                        answer: "Your donations go directly to our various programs supporting education, healthcare, and disaster relief efforts, with only 5% used for administrative costs."
                    },
                    {
                        question: "Can I specify where I want my donation to go?",
                        answer: "Yes, you can specify which campaign or cause you'd like your donation to support during the checkout process."
                    },
                    {
                        question: "How can I volunteer with your organization?",
                        answer: "You can apply to volunteer through our 'Join Us' page, specifying your skills and availability."
                    },
                    {
                        question: "Do you accept non-monetary donations?",
                        answer: "Yes, we accept goods, services, and time as donations. Please contact us for more details."
                    }
                ]);
            }, 300);
        });
    },
    
    // About us data
    getAboutUs: function() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    mission: "To empower communities through sustainable development and humanitarian aid",
                    vision: "A world where all people have access to the resources they need to thrive",
                    history: "Founded in 2005, CharityWorks has been serving communities worldwide for over 15 years.",
                    impact: {
                        beneficiaries: 1500000,
                        countries: 45,
                        projects: 320
                    },
                    team: [
                        { name: "Jane Smith", role: "Executive Director" },
                        { name: "John Davis", role: "Operations Manager" },
                        { name: "Sarah Wilson", role: "Development Director" }
                    ]
                });
            }, 400);
        });
    },
    
    // Social media links
    getSocialMedia: function() {
        return [
            { name: "Facebook", url: "https://facebook.com/charityworks" },
            { name: "Twitter", url: "https://twitter.com/charityworks" },
            { name: "Instagram", url: "https://instagram.com/charityworks" },
            { name: "LinkedIn", url: "https://linkedin.com/company/charityworks" },
            { name: "YouTube", url: "https://youtube.com/charityworks" }
        ];
    },
    
    // Location data
    getLocations: function() {
        return [
            {
                name: "Headquarters",
                address: "123 Charity Lane, New York, NY 10001",
                phone: "+1 (555) 123-4567",
                email: "info@charityworks.org",
                coordinates: { lat: 40.7128, lng: -74.0060 }
            },
            {
                name: "West Coast Office",
                address: "456 Hope Street, San Francisco, CA 94105",
                phone: "+1 (555) 987-6543",
                email: "westcoast@charityworks.org",
                coordinates: { lat: 37.7749, lng: -122.4194 }
            }
        ];
    },
    
    // Media content
    getMediaContent: function(type) {
        return new Promise((resolve) => {
            setTimeout(() => {
                if (type === 'photos') {
                    resolve([
                        { id: 1, title: "Community Garden Project", url: "assets/img/gallery/post_1.png" },
                        { id: 2, title: "School Building", url: "assets/img/gallery/post_2.png" },
                        { id: 3, title: "Healthcare Initiative", url: "assets/img/gallery/post_3.png" },
                        { id: 4, title: "Clean Water Access", url: "assets/img/gallery/post_4.png" },
                        { id: 5, title: "Education Program", url: "assets/img/gallery/post_5.png" },
                        { id: 6, title: "Food Distribution", url: "assets/img/gallery/post_6.png" }
                    ]);
                } else if (type === 'videos') {
                    resolve([
                        { id: 1, title: "Our Mission", url: "https://www.youtube.com/embed/example1" },
                        { id: 2, title: "2021 Impact Report", url: "https://www.youtube.com/embed/example2" },
                        { id: 3, title: "Volunteer Stories", url: "https://www.youtube.com/embed/example3" }
                    ]);
                } else if (type === 'activities') {
                    resolve([
                        { 
                            id: 1, 
                            title: "Community Health Drive", 
                            date: "2023-01-15",
                            description: "Provided free health checkups and medicine to over 500 people",
                            image: "assets/img/gallery/socialEvents1.png"
                        },
                        { 
                            id: 2, 
                            title: "School Renovation Project", 
                            date: "2023-02-20",
                            description: "Completed renovation of 3 classrooms benefiting 150 students",
                            image: "assets/img/gallery/socialEvents2.png"
                        },
                        { 
                            id: 3, 
                            title: "Food Distribution Campaign", 
                            date: "2023-03-10",
                            description: "Distributed food packages to 300 families affected by drought",
                            image: "assets/img/gallery/socialEvents3.png"
                        }
                    ]);
                } else {
                    resolve([]);
                }
            }, 500);
        });
    }
};

// Miscellaneous
const misc = {
    // Blood donation
    registerBloodDonation: function(donationData) {
        if (!auth.isLoggedIn) {
            return Promise.reject(new Error('Please login to registerView for blood donation'));
        }
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const donation = {
                    id: 'BLOOD' + Date.now(),
                    name: donationData.name || auth.currentUser.name,
                    email: donationData.email || auth.currentUser.email,
                    bloodType: donationData.bloodType,
                    phone: donationData.phone,
                    date: donationData.date,
                    time: donationData.time,
                    location: donationData.location,
                    status: 'scheduled'
                };
                
                const donations = JSON.parse(localStorage.getItem('charityworks_blood_donations') || '[]');
                donations.push(donation);
                localStorage.setItem('charityworks_blood_donations', JSON.stringify(donations));
                
                resolve({ success: true, donation });
            }, 800);
        });
    },
    
    // Language preferences
    changeLanguage: function(language) {
        localStorage.setItem('charityworks_language', language);
        return Promise.resolve({ success: true, language });
    },
    
    getCurrentLanguage: function() {
        return localStorage.getItem('charityworks_language') || 'en';
    }
};

// Initialize app on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check login status
    auth.checkLoginStatus();
    
    // Update UI based on login status
    updateUIForAuthStatus();
    
    // Initialize any forms or interactive elements
    initForms();
    
    // Set up event listeners for auth-related UI elements
    setupAuthListeners();
    
    // Initialize language preference
    const currentLang = misc.getCurrentLanguage();
    document.documentElement.setAttribute('lang', currentLang);
});

// Update UI based on authentication status
function updateUIForAuthStatus() {
    const isLoggedIn = auth.isLoggedIn;
    const authLinks = document.querySelectorAll('.auth-link');
    const profileLinks = document.querySelectorAll('.profile-link');
    const guestLinks = document.querySelectorAll('.guest-link');
    
    authLinks.forEach(link => {
        link.style.display = isLoggedIn ? 'block' : 'none';
    });
    
    profileLinks.forEach(link => {
        link.style.display = isLoggedIn ? 'block' : 'none';
        
        // Update profile link text if user is logged in
        if (isLoggedIn && auth.currentUser) {
            const nameSpan = link.querySelector('.user-name');
            if (nameSpan) {
                nameSpan.textContent = auth.currentUser.name;
            }
        }
    });
    
    guestLinks.forEach(link => {
        link.style.display = isLoggedIn ? 'none' : 'block';
    });
}

// Initialize forms for JS validation and submission
function initForms() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            communication.sendMessage({
                name,
                email,
                subject,
                message
            })
            .then(response => {
                showAlert('success', 'Your message has been sent successfully!');
                contactForm.reset();
            })
            .catch(error => {
                showAlert('danger', 'There was an error sending your message. Please try again.');
            });
        });
    }
    
    // Add initialization for other forms as needed
}

// Set up event listeners for auth-related elements
function setupAuthListeners() {
    // Logout button
    const logoutBtn = document.querySelectorAll('.logout-btn');
    logoutBtn.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            auth.logout().then(() => {
                updateUIForAuthStatus();
                showAlert('success', 'You have been logged out successfully!');
                
                // Redirect to home page after logout
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 1500);
            });
        });
    });
    
    // Language selection
    const langSelects = document.querySelectorAll('#select1');
    langSelects.forEach(select => {
        select.addEventListener('change', function(e) {
            const lang = e.target.value || 'en';
            misc.changeLanguage(lang).then(() => {
                // Reload page to apply language change
                window.location.reload();
            });
        });
    });
}

// Helper function to show alerts/notifications
function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    // Find a suitable container for the alert
    const container = document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 500);
    }, 5000);
}