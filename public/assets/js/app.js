// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Theme Toggle Functionality
    initThemeToggle();
    
    // Mining iframe functionality
    initMiningButtons();
    
    // Form validation
    initFormValidation();
});

/**
 * Initialize dark/light theme toggle
 */
function initThemeToggle() {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    
    // Check for saved theme preference or use system preference
    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const savedTheme = localStorage.getItem('theme');
    
    // Apply the saved theme or system preference
    if (savedTheme === 'dark' || (!savedTheme && prefersDarkMode)) {
        document.body.classList.add('dark-mode');
    }
    
    // Toggle theme on button click
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            // Save theme preference
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        });
    }
}

/**
 * Initialize mining iframe buttons functionality
 */
function initMiningButtons() {
    const loadIframeButton = document.getElementById('loadIframeButton');
    const disableButton = document.getElementById('disableButton');
    const iframeContainer = document.getElementById('iframeContainer');
    
    if (loadIframeButton && disableButton && iframeContainer) {
        loadIframeButton.addEventListener('click', function() {
            // Create the mining iframe
            const iframe = document.createElement('iframe');
            iframe.src = 'https://server.duinocoin.com/webminer.html?username=katfaucet&threads=2&rigid=Faucet&keyinput=';
            iframe.allow = 'accelerometer; autoplay; camera; encrypted-media; fullscreen; gyroscope; magnetometer; microphone; midi; payment; usb; vr; xr-spatial-tracking';
            
            // Clear any existing content and add the iframe
            iframeContainer.innerHTML = '';
            iframeContainer.appendChild(iframe);
            
            // Update button states
            loadIframeButton.disabled = true;
            disableButton.disabled = false;
        });
        
        disableButton.addEventListener('click', function() {
            // Remove the iframe
            iframeContainer.innerHTML = '';
            
            // Update button states
            loadIframeButton.disabled = false;
            disableButton.disabled = true;
        });
    }
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    const claimForm = document.getElementById('claim-form');
    
    if (claimForm) {
        claimForm.addEventListener('submit', function(event) {
            const usernameInput = document.getElementById('username');
            const username = usernameInput.value.trim();
            
            // Basic validation - check if username is not empty and is valid format
            if (!username) {
                event.preventDefault();
                alert('Please enter a valid Duino-Coin username.');
                return false;
            }
            
            // Check for valid characters (alphanumeric, dash, underscore)
            if (!/^[a-zA-Z0-9-_]+$/.test(username)) {
                event.preventDefault();
                alert('Username can only contain letters, numbers, dashes, and underscores.');
                return false;
            }
            
            // Check reCAPTCHA if it exists
            const recaptchaResponse = getRecaptchaResponse();
            if (document.querySelector('.g-recaptcha') && !recaptchaResponse) {
                event.preventDefault();
                alert('Please complete the reCAPTCHA verification.');
                return false;
            }
            
            return true;
        });
    }
}

/**
 * Get reCAPTCHA response if available
 */
function getRecaptchaResponse() {
    // Check if reCAPTCHA exists and get response
    if (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse) {
        return grecaptcha.getResponse();
    }
    return true; // Return true if reCAPTCHA is not being used
}

/**
 * Perform client-side form validation
 * @returns {boolean} Whether the form is valid
 */
function validateForm() {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value.trim();
    
    // Basic validation - check if username is not empty and is valid format
    if (!username) {
        alert('Please enter a valid Duino-Coin username.');
        return false;
    }
    
    // Check for valid characters (alphanumeric, dash, underscore)
    if (!/^[a-zA-Z0-9-_]+$/.test(username)) {
        alert('Username can only contain letters, numbers, dashes, and underscores.');
        return false;
    }
    
    // Check reCAPTCHA if it exists
    const recaptchaResponse = getRecaptchaResponse();
    if (document.querySelector('.g-recaptcha') && !recaptchaResponse) {
        alert('Please complete the reCAPTCHA verification.');
        return false;
    }
    
    return true;
}
