const sideMenu = document.querySelector("aside");
const menuBtn = document.querySelector("#menu-btn");
const closeBtn = document.querySelector("#close-btn");
const themeToggler = document.querySelector(".theme-toggler");

// Show sidebar
menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
    sideMenu.style.animation = 'showMenu 0.4s ease forwards';
})

// Close sidebar
closeBtn.addEventListener('click', () => {
    sideMenu.style.animation = 'hideMenu 0.4s ease forwards';
    setTimeout(() => {
        sideMenu.style.display = 'none';
    }, 400);
})

// Change theme
themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
    
    // Save theme preference
    const isDark = document.body.classList.contains('dark-theme-variables');
    localStorage.setItem('darkTheme', isDark);
})

// Load saved theme
document.addEventListener('DOMContentLoaded', function() {
    // Load theme
    const darkTheme = localStorage.getItem('darkTheme') === 'true';
    if (darkTheme) {
        document.body.classList.add('dark-theme-variables');
        themeToggler.querySelector('span:nth-child(1)').classList.remove('active');
        themeToggler.querySelector('span:nth-child(2)').classList.add('active');
    }

    // Delete confirmation for all forms
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--color-danger)';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Print functionality
function printInvoice(invoiceId) {
    const invoice = document.getElementById(invoiceId);
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Invoice Print</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .invoice-container { border: 1px solid #ddd; padding: 20px; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                ${invoice.innerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Search functionality with debounce
let searchTimeout;
function debounceSearch(input, callback, delay = 500) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        callback(input.value);
    }, delay);
}