document.addEventListener('DOMContentLoaded', function() {
    // Date picker enhancement
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.type = 'date';
        });
    });

    // Time validation for booking form
    const bookingForm = document.querySelector('.booking-form form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const startTime = this.querySelector('input[name="start_time"]').value;
            const endTime = this.querySelector('input[name="end_time"]').value;
            
            if (startTime && endTime) {
                const start = new Date(`2000-01-01T${startTime}`);
                const end = new Date(`2000-01-01T${endTime}`);
                
                if (end <= start) {
                    alert('End time must be after start time');
                    e.preventDefault();
                }
            }
        });
    }

    // Alert timeout
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});