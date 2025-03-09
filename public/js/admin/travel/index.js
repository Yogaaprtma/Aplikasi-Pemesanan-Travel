document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Animation for table rows
    const tableRows = document.querySelectorAll('.row-item');
    tableRows.forEach((row, index) => {
        row.style.setProperty('--row-index', index);
    });
    
    // Confirmation for delete
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus jadwal ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    
    // Search & Filter functionality
    const searchInput = document.getElementById('searchInput');
    const filterTujuan = document.getElementById('filterTujuan');
    const sortOption = document.getElementById('sortOption');
    const resetBtn = document.getElementById('resetFilter');
    const scheduleTable = document.getElementById('scheduleTable');
    const travelCards = document.querySelectorAll('.travel-card');
    const emptyState = document.getElementById('emptyState');
    
    // Populate destinations dropdown
    const destinations = [...new Set([...document.querySelectorAll('td:nth-child(2) span')].map(el => el.textContent))];
    destinations.forEach(dest => {
        const option = document.createElement('option');
        option.value = dest;
        option.textContent = dest;
        filterTujuan.appendChild(option);
    });
    
    function applyFilters() {
        const searchValue = searchInput.value.toLowerCase();
        const destinationValue = filterTujuan.value.toLowerCase();
        
        let visibleCount = 0;
        
        // Filter table rows
        tableRows.forEach(row => {
            const destination = row.querySelector('td:nth-child(2) span').textContent.toLowerCase();
            
            const matchSearch = destination.includes(searchValue);
            const matchDestination = !destinationValue || destination === destinationValue;
            
            if (matchSearch && matchDestination) {
                row.classList.remove('d-none');
                visibleCount++;
            } else {
                row.classList.add('d-none');
            }
        });
        
        // Filter mobile cards
        travelCards.forEach(card => {
            const destination = card.querySelector('h5').textContent.toLowerCase();
            
            const matchSearch = destination.includes(searchValue);
            const matchDestination = !destinationValue || destination === destinationValue;
            
            if (matchSearch && matchDestination) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.classList.remove('d-none');
        } else {
            emptyState.classList.add('d-none');
        }
    }
    
    searchInput.addEventListener('input', applyFilters);
    filterTujuan.addEventListener('change', applyFilters);
    sortOption.addEventListener('change', applyFilters);
    
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterTujuan.value = '';
        sortOption.value = 'time_asc';
        applyFilters();
    });
});