// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Date picker initialization for forms
    const today = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="date"]').forEach(input => {
        if (!input.min) {
            input.min = today;
        }
        
        // Set check-out min date based on check-in
        if (input.id === 'check-out') {
            const checkIn = document.getElementById('check-in');
            if (checkIn) {
                checkIn.addEventListener('change', function() {
                    input.min = this.value;
                    
                    // If check-out is before new min date, reset it
                    if (input.value && input.value < this.value) {
                        input.value = '';
                    }
                });
            }
        }
    });
    
    // Initialize tooltips
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
    });
});

function showTooltip(e) {
    const tooltipText = this.getAttribute('data-tooltip');
    const tooltip = document.createElement('div');
    tooltip.className = 'absolute z-50 bg-black text-white text-xs rounded py-1 px-2 whitespace-nowrap';
    tooltip.textContent = tooltipText;
    
    // Position the tooltip
    const rect = this.getBoundingClientRect();
    tooltip.style.top = (rect.top - 30) + 'px';
    tooltip.style.left = (rect.left + rect.width / 2) + 'px';
    tooltip.style.transform = 'translateX(-50%)';
    
    // Add to DOM
    tooltip.id = 'tooltip';
    document.body.appendChild(tooltip);
}

function hideTooltip() {
    const tooltip = document.getElementById('tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}

// Format phone number input
document.addEventListener('input', function(e) {
    if (e.target.id === 'phone' || e.target.id === 'phone-number') {
        let phone = e.target.value.replace(/\D/g, '');
        
        // Format Kenyan phone numbers
        if (phone.length > 3 && phone.length <= 6) {
            phone = phone.replace(/(\d{3})(\d{1,3})/, '$1 $2');
        } else if (phone.length > 6) {
            phone = phone.replace(/(\d{3})(\d{3})(\d{1,4})/, '$1 $2 $3');
        }
        
        e.target.value = phone;
    }
    
    // Format credit card number
    if (e.target.id === 'card-number') {
        let cardNumber = e.target.value.replace(/\D/g, '');
        
        if (cardNumber.length > 4 && cardNumber.length <= 8) {
            cardNumber = cardNumber.replace(/(\d{4})(\d{1,4})/, '$1 $2');
        } else if (cardNumber.length > 8 && cardNumber.length <= 12) {
            cardNumber = cardNumber.replace(/(\d{4})(\d{4})(\d{1,4})/, '$1 $2 $3');
        } else if (cardNumber.length > 12) {
            cardNumber = cardNumber.replace(/(\d{4})(\d{4})(\d{4})(\d{1,4})/, '$1 $2 $3 $4');
        }
        
        e.target.value = cardNumber;
    }
    
    // Format expiry date
    if (e.target.id === 'expiry-date') {
        let expiry = e.target.value.replace(/\D/g, '');
        
        if (expiry.length > 2) {
            expiry = expiry.replace(/(\d{2})(\d{1,2})/, '$1/$2');
        }
        
        e.target.value = expiry;
    }
});

// Show password toggle
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toggle-password')) {
        const input = e.target.previousElementSibling;
        const icon = e.target.querySelector('svg');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        }
    }
});