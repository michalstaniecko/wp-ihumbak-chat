/**
 * iHumbak Chat Widget
 * Frontend JavaScript for the chat widget functionality
 */

(function() {
    'use strict';

    // Widget state
    const widget = {
        isOpen: false,
        isLoading: false,
        settings: {},
        elements: {}
    };

    /**
     * Initialize the widget
     */
    function init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initWidget);
        } else {
            initWidget();
        }
    }

    /**
     * Initialize widget after DOM is ready
     */
    async function initWidget() {
        try {
            // Load settings from API
            await loadSettings();
            
            // Cache DOM elements
            cacheElements();
            
            // Apply settings
            applySettings();
            
            // Inject reCAPTCHA script
            injectRecaptchaScript();
            
            // Setup event listeners
            setupEventListeners();
            
            // Make widget visible
            widget.elements.bubble.style.display = 'flex';
        } catch (error) {
            console.error('iHumbak Chat: Failed to initialize widget', error);
        }
    }

    /**
     * Load settings from REST API
     */
    async function loadSettings() {
        try {
            const response = await fetch('/wp-json/ihumbak-chat/v1/settings');
            if (!response.ok) {
                throw new Error('Failed to load settings');
            }
            widget.settings = await response.json();
        } catch (error) {
            console.error('iHumbak Chat: Failed to load settings', error);
            // Use defaults if API fails
            widget.settings = {
                recaptcha_site_key: '',
                widget_color: '#007bff',
                widget_position: 'bottom-right',
                widget_title: 'Get in Touch',
                nonce: ''
            };
        }
    }

    /**
     * Cache DOM elements
     */
    function cacheElements() {
        widget.elements = {
            bubble: document.getElementById('ihumbak-chat-bubble'),
            widgetForm: document.getElementById('ihumbak-chat-widget'),
            closeBtn: document.getElementById('ihumbak-close-widget'),
            form: document.getElementById('ihumbak-chat-form'),
            emailInput: document.getElementById('ihumbak-email'),
            messageInput: document.getElementById('ihumbak-message'),
            emailError: document.getElementById('email-error'),
            messageError: document.getElementById('message-error'),
            messageCounter: document.getElementById('message-count'),
            sendBtn: document.getElementById('ihumbak-send-btn'),
            sendBtnText: document.getElementById('send-btn-text'),
            sendBtnSpinner: document.getElementById('send-btn-spinner'),
            successToast: document.getElementById('ihumbak-success-toast'),
            errorToast: document.getElementById('ihumbak-error-toast'),
            successToastMessage: document.getElementById('success-toast-message'),
            errorToastMessage: document.getElementById('error-toast-message'),
            widgetTitle: document.getElementById('widget-title')
        };
    }

    /**
     * Apply settings to widget
     */
    function applySettings() {
        // Apply title
        if (widget.elements.widgetTitle && widget.settings.widget_title) {
            widget.elements.widgetTitle.textContent = widget.settings.widget_title;
        }

        // Apply color
        if (widget.settings.widget_color) {
            widget.elements.bubble.style.backgroundColor = widget.settings.widget_color;
        }

        // Apply position (using Tailwind classes dynamically)
        applyPosition(widget.settings.widget_position);

        // Set data attributes
        if (widget.elements.bubble) {
            widget.elements.bubble.dataset.nonce = widget.settings.nonce || '';
            widget.elements.bubble.dataset.siteKey = widget.settings.recaptcha_site_key || '';
        }
    }

    /**
     * Apply widget position
     */
    function applyPosition(position) {
        const bubble = widget.elements.bubble;
        const form = widget.elements.widgetForm;
        
        // Remove all position classes
        bubble.classList.remove('bottom-6', 'top-6', 'left-6', 'right-6');
        form.classList.remove('bottom-24', 'top-24', 'left-6', 'right-6');
        
        switch (position) {
            case 'bottom-left':
                bubble.classList.add('bottom-6', 'left-6');
                form.classList.add('bottom-24', 'left-6');
                break;
            case 'top-right':
                bubble.classList.add('top-6', 'right-6');
                form.classList.add('top-24', 'right-6');
                break;
            case 'top-left':
                bubble.classList.add('top-6', 'left-6');
                form.classList.add('top-24', 'left-6');
                break;
            case 'bottom-right':
            default:
                bubble.classList.add('bottom-6', 'right-6');
                form.classList.add('bottom-24', 'right-6');
                break;
        }
    }

    /**
     * Inject reCAPTCHA script
     */
    function injectRecaptchaScript() {
        if (!widget.settings.recaptcha_site_key) {
            console.warn('iHumbak Chat: reCAPTCHA site key not configured');
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${widget.settings.recaptcha_site_key}`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    /**
     * Setup event listeners
     */
    function setupEventListeners() {
        // Bubble click
        widget.elements.bubble.addEventListener('click', toggleWidget);
        widget.elements.bubble.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleWidget();
            }
        });

        // Close button
        widget.elements.closeBtn.addEventListener('click', closeWidget);

        // Form submission
        widget.elements.form.addEventListener('submit', handleSubmit);

        // Real-time validation
        widget.elements.emailInput.addEventListener('blur', () => validateEmail());
        widget.elements.emailInput.addEventListener('input', () => {
            if (widget.elements.emailError.textContent) {
                validateEmail();
            }
        });

        widget.elements.messageInput.addEventListener('input', () => {
            updateCharacterCounter();
            if (widget.elements.messageError.textContent) {
                validateMessage();
            }
        });

        widget.elements.messageInput.addEventListener('blur', () => validateMessage());

        // Close widget on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && widget.isOpen) {
                closeWidget();
            }
        });

        // Adjust position on window resize
        window.addEventListener('resize', adjustResponsive);
        adjustResponsive(); // Initial call
    }

    /**
     * Toggle widget open/close
     */
    function toggleWidget() {
        if (widget.isOpen) {
            closeWidget();
        } else {
            openWidget();
        }
    }

    /**
     * Open widget
     */
    function openWidget() {
        widget.isOpen = true;
        widget.elements.widgetForm.classList.remove('hidden');
        widget.elements.widgetForm.setAttribute('aria-hidden', 'false');
        
        // Trigger animation
        setTimeout(() => {
            widget.elements.widgetForm.classList.remove('opacity-0');
            widget.elements.widgetForm.classList.add('animate-slide-up');
        }, 10);

        // Focus on email input
        setTimeout(() => {
            widget.elements.emailInput.focus();
        }, 300);

        // Stop bubble pulse animation
        widget.elements.bubble.classList.remove('animate-pulse-subtle');
    }

    /**
     * Close widget
     */
    function closeWidget() {
        widget.isOpen = false;
        widget.elements.widgetForm.classList.add('opacity-0');
        widget.elements.widgetForm.setAttribute('aria-hidden', 'true');
        
        setTimeout(() => {
            widget.elements.widgetForm.classList.add('hidden');
            widget.elements.widgetForm.classList.remove('animate-slide-up');
        }, 300);

        // Resume bubble pulse animation
        widget.elements.bubble.classList.add('animate-pulse-subtle');
    }

    /**
     * Validate email
     */
    function validateEmail() {
        const email = widget.elements.emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            showFieldError('email', 'Email is required');
            return false;
        }

        if (!emailRegex.test(email)) {
            showFieldError('email', 'Please enter a valid email address');
            return false;
        }

        hideFieldError('email');
        return true;
    }

    /**
     * Validate message
     */
    function validateMessage() {
        const message = widget.elements.messageInput.value.trim();

        if (!message) {
            showFieldError('message', 'Message is required');
            return false;
        }

        if (message.length < 5) {
            showFieldError('message', 'Message must be at least 5 characters long');
            return false;
        }

        if (message.length > 5000) {
            showFieldError('message', 'Message is too long (maximum 5000 characters)');
            return false;
        }

        hideFieldError('message');
        return true;
    }

    /**
     * Show field error
     */
    function showFieldError(field, message) {
        const errorElement = widget.elements[field + 'Error'];
        const inputElement = widget.elements[field + 'Input'];
        
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        inputElement.classList.add('border-red-500');
        inputElement.setAttribute('aria-invalid', 'true');
    }

    /**
     * Hide field error
     */
    function hideFieldError(field) {
        const errorElement = widget.elements[field + 'Error'];
        const inputElement = widget.elements[field + 'Input'];
        
        errorElement.textContent = '';
        errorElement.classList.add('hidden');
        inputElement.classList.remove('border-red-500');
        inputElement.setAttribute('aria-invalid', 'false');
    }

    /**
     * Update character counter
     */
    function updateCharacterCounter() {
        const count = widget.elements.messageInput.value.length;
        widget.elements.messageCounter.textContent = count;

        if (count > 5000) {
            widget.elements.messageCounter.classList.add('text-red-500');
            widget.elements.messageCounter.classList.remove('text-gray-500');
        } else {
            widget.elements.messageCounter.classList.remove('text-red-500');
            widget.elements.messageCounter.classList.add('text-gray-500');
        }
    }

    /**
     * Handle form submission
     */
    async function handleSubmit(e) {
        e.preventDefault();

        // Prevent double submission
        if (widget.isLoading) {
            return;
        }

        // Validate form
        const isEmailValid = validateEmail();
        const isMessageValid = validateMessage();

        if (!isEmailValid || !isMessageValid) {
            return;
        }

        // Get form data
        const email = widget.elements.emailInput.value.trim();
        const message = widget.elements.messageInput.value.trim();

        try {
            // Set loading state
            setLoadingState(true);

            // Get reCAPTCHA token
            const recaptchaToken = await getRecaptchaToken();

            // Send message
            await sendMessage(email, message, recaptchaToken);

            // Show success
            showSuccessToast('Message sent! 🎉 We will get back to you soon.');

            // Reset form
            widget.elements.form.reset();
            updateCharacterCounter();

            // Close widget after delay
            setTimeout(() => {
                closeWidget();
            }, 2000);

        } catch (error) {
            console.error('iHumbak Chat: Failed to send message', error);
            showErrorToast(error.message || 'Failed to send message. Please try again.');
        } finally {
            setLoadingState(false);
        }
    }

    /**
     * Get reCAPTCHA token
     */
    async function getRecaptchaToken() {
        if (!widget.settings.recaptcha_site_key) {
            throw new Error('reCAPTCHA is not configured');
        }

        if (typeof grecaptcha === 'undefined') {
            throw new Error('reCAPTCHA script not loaded');
        }

        try {
            const token = await grecaptcha.execute(widget.settings.recaptcha_site_key, { action: 'submit' });
            return token;
        } catch (error) {
            throw new Error('reCAPTCHA verification failed');
        }
    }

    /**
     * Send message to API
     */
    async function sendMessage(email, message, recaptchaToken) {
        const response = await fetch('/wp-json/ihumbak-chat/v1/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': widget.settings.nonce
            },
            body: JSON.stringify({
                email: email,
                message: message,
                recaptcha_token: recaptchaToken,
                nonce: widget.settings.nonce
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to send message');
        }

        return data;
    }

    /**
     * Set loading state
     */
    function setLoadingState(isLoading) {
        widget.isLoading = isLoading;
        widget.elements.sendBtn.disabled = isLoading;

        if (isLoading) {
            widget.elements.sendBtnText.textContent = 'Sending...';
            widget.elements.sendBtnSpinner.classList.remove('hidden');
        } else {
            widget.elements.sendBtnText.textContent = 'Send Message';
            widget.elements.sendBtnSpinner.classList.add('hidden');
        }
    }

    /**
     * Show success toast
     */
    function showSuccessToast(message) {
        widget.elements.successToastMessage.textContent = message;
        showToast(widget.elements.successToast);
    }

    /**
     * Show error toast
     */
    function showErrorToast(message) {
        widget.elements.errorToastMessage.textContent = message;
        showToast(widget.elements.errorToast);
    }

    /**
     * Show toast notification
     */
    function showToast(toastElement) {
        toastElement.classList.remove('hidden', 'opacity-0', 'translate-y-2');
        toastElement.classList.add('animate-slide-up');

        setTimeout(() => {
            toastElement.classList.remove('opacity-0', 'translate-y-2');
        }, 10);

        // Hide after 5 seconds
        setTimeout(() => {
            hideToast(toastElement);
        }, 5000);
    }

    /**
     * Hide toast notification
     */
    function hideToast(toastElement) {
        toastElement.classList.add('opacity-0', 'translate-y-2');

        setTimeout(() => {
            toastElement.classList.add('hidden');
            toastElement.classList.remove('animate-slide-up');
        }, 300);
    }

    /**
     * Adjust widget for responsive design
     */
    function adjustResponsive() {
        const width = window.innerWidth;
        const form = widget.elements.widgetForm;

        // Mobile adjustments (< 640px)
        if (width < 640) {
            form.classList.remove('w-96');
            form.classList.add('w-full');
            form.style.left = '1rem';
            form.style.right = '1rem';
            form.style.maxWidth = 'calc(100vw - 2rem)';
        }
        // Tablet adjustments (640px - 1024px)
        else if (width < 1024) {
            form.classList.remove('w-full');
            form.classList.add('w-96');
            form.style.left = '';
            form.style.right = '';
            form.style.maxWidth = 'calc(100vw - 3rem)';
        }
        // Desktop (>= 1024px)
        else {
            form.classList.remove('w-full');
            form.classList.add('w-96');
            form.style.left = '';
            form.style.right = '';
            form.style.maxWidth = 'calc(100vw - 3rem)';
        }
    }

    // Initialize on load
    init();

})();
