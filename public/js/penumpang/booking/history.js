document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchBooking');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let searchText = this.value.toLowerCase();
            
            let rows = document.querySelectorAll('#bookingTable tbody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
            
            let cards = document.querySelectorAll('.booking-mobile-card');
            cards.forEach(card => {
                let text = card.textContent.toLowerCase();
                let searchContent = card.getAttribute('data-search-content').toLowerCase();
                card.style.display = (text.includes(searchText) || searchContent.includes(searchText)) ? '' : 'none';
            });
        });
    }
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});