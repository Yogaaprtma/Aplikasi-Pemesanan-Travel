document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scheduleForm');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            const dateInput = document.getElementById('departure_date');
            const timeInput = document.getElementById('departure_time');
            const combinedInput = document.getElementById('combined_datetime');
            
            if (dateInput.value && timeInput.value) {
                combinedInput.value = dateInput.value + 'T' + timeInput.value;
            }
        }
        
        form.classList.add('was-validated');
    });
    
    const quotaInput = document.getElementById('quota');
    const quotaRange = document.getElementById('quotaRange');
    const quotaValue = document.getElementById('quotaValue');
    
    quotaRange.addEventListener('input', function() {
        quotaInput.value = this.value;
        quotaValue.textContent = this.value;
    });
    
    quotaInput.addEventListener('input', function() {
        if (this.value < 1) this.value = 1;
        quotaRange.value = this.value;
        quotaValue.textContent = this.value;
    });
    
    quotaValue.textContent = quotaInput.value || "0";
    quotaRange.value = quotaInput.value || 0;
    
    const priceInput = document.getElementById('price');
    const priceFormatted = document.getElementById('priceFormatted');
    
    priceInput.addEventListener('input', function() {
        const val = this.value;
        priceFormatted.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
    });
    
    priceFormatted.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(priceInput.value);

    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach((el, index) => {
        el.style.setProperty('--delay', `${(index + 1) * 0.1}s`);
    });
});