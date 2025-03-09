document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    const decreaseButtons = document.querySelectorAll('.decrease-seats');
    const increaseButtons = document.querySelectorAll('.increase-seats');
    
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            if (currentValue > parseInt(input.min)) {
                input.value = currentValue - 1;
            }
        });
    });
    
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input[type="number"]');
            const currentValue = parseInt(input.value);
            if (currentValue < parseInt(input.max)) {
                input.value = currentValue + 1;
            }
        });
    });
    
    const lazyImages = document.querySelectorAll(".lazy-load");
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                console.log('Loading image:', img.dataset.src); // Debugging
                img.src = img.dataset.src;
                img.onload = () => img.classList.add('loaded');
                img.onerror = () => {
                    img.src = img.dataset.errorSrc;
                    img.classList.add('loaded');
                };
                observer.unobserve(img);
            }
        });
    }, {
        rootMargin: "100px",
        threshold: 0.1
    });
    
    lazyImages.forEach(img => {
        imageObserver.observe(img);
    });
    
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const allCards = document.querySelectorAll('.travel-card-wrapper');
    const allTabCards = document.querySelectorAll('#allTravelsContainer .travel-card-wrapper');
    const availableTabCards = document.querySelectorAll('#availableTravelsContainer .travel-card-wrapper');
    const noResultsAll = document.getElementById('noResultsAll');
    const noResultsAvailable = document.getElementById('noResultsAvailable');
    
    function highlightText(element, text) {
        if (!text) return;
        
        const regex = new RegExp(text, 'gi');
        const originalText = element.innerText;
        
        if (regex.test(originalText)) {
            element.innerHTML = originalText.replace(regex, match => `<span class="highlight">${match}</span>`);
        }
    }
    
    function clearHighlights() {
        document.querySelectorAll('.destination-text, .destination-title, .departure-text').forEach(el => {
            el.innerHTML = el.innerText;
        });
    }
    
    let searchTimeout;
    function performSearch() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            const query = searchInput.value.trim().toLowerCase();
            let allResultsCount = 0;
            let availableResultsCount = 0;
            
            clearHighlights();
            
            if (query) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
            
            allTabCards.forEach(card => {
                const destination = card.getAttribute('data-destination').toLowerCase();
                const departure = card.getAttribute('data-departure');
                const departureDate = new Date(departure).toLocaleDateString('id-ID');
                
                if (query === '' || 
                    destination.includes(query) || 
                    departure.includes(query) || 
                    departureDate.includes(query)) {
                    
                    card.style.display = 'block';
                    allResultsCount++;
                    
                    if (query) {
                        const destinationText = card.querySelector('.destination-text');
                        const destinationTitle = card.querySelector('.destination-title');
                        const departureText = card.querySelector('.departure-text');
                        
                        highlightText(destinationText, query);
                        highlightText(destinationTitle, query);
                        highlightText(departureText, query);
                    }
                } else {
                    card.style.display = 'none';
                }
            });
            
            availableTabCards.forEach(card => {
                const destination = card.getAttribute('data-destination').toLowerCase();
                const departure = card.getAttribute('data-departure');
                const departureDate = new Date(departure).toLocaleDateString('id-ID');
                
                if (query === '' || 
                    destination.includes(query) || 
                    departure.includes(query) || 
                    departureDate.includes(query)) {
                    
                    card.style.display = 'block';
                    availableResultsCount++;
                    
                    if (query) {
                        const destinationText = card.querySelector('.destination-text');
                        const destinationTitle = card.querySelector('.destination-title');
                        const departureText = card.querySelector('.departure-text');
                        
                        highlightText(destinationText, query);
                        highlightText(destinationTitle, query);
                        highlightText(departureText, query);
                    }
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (allResultsCount === 0) {
                noResultsAll.style.display = 'block';
            } else {
                noResultsAll.style.display = 'none';
            }
            
            if (availableResultsCount === 0) {
                noResultsAvailable.style.display = 'block';
            } else {
                noResultsAvailable.style.display = 'none';
            }
        }, 300);
    }
    
    function clearSearch() {
        searchInput.value = '';
        clearSearchBtn.style.display = 'none';
        clearHighlights();
        
        allCards.forEach(card => {
            card.style.display = 'block';
        });

        noResultsAll.style.display = 'none';
        noResultsAvailable.style.display = 'none';
    }
    
    searchInput.addEventListener('input', performSearch);
    clearSearchBtn.addEventListener('click', clearSearch);
    
    const destinationFilter = document.getElementById('destination');
    const departureDateFilter = document.getElementById('departure_date');
    const applyFilterBtn = document.getElementById('applyFilter');
    const resetFilterBtn = document.getElementById('resetFilter');
    
    applyFilterBtn.addEventListener('click', function() {
        const selectedDestination = destinationFilter.value.toLowerCase();
        const selectedDate = departureDateFilter.value;
        
        allCards.forEach(card => {
            const cardDestination = card.getAttribute('data-destination').toLowerCase();
            const cardDeparture = card.getAttribute('data-departure');
            
            let showCard = true;
            
            if (selectedDestination && cardDestination !== selectedDestination) {
                showCard = false;
            }
            
            if (selectedDate && cardDeparture !== selectedDate) {
                showCard = false;
            }
            
            card.style.display = showCard ? 'block' : 'none';
        });
        
        let allResultsVisible = false;
        let availableResultsVisible = false;
        
        allTabCards.forEach(card => {
            if (card.style.display !== 'none') {
                allResultsVisible = true;
            }
        });
        
        availableTabCards.forEach(card => {
            if (card.style.display !== 'none') {
                availableResultsVisible = true;
            }
        });
        
        noResultsAll.style.display = allResultsVisible ? 'none' : 'block';
        noResultsAvailable.style.display = availableResultsVisible ? 'none' : 'block';
    });
    
    resetFilterBtn.addEventListener('click', function() {
        destinationFilter.value = '';
        departureDateFilter.value = '';
        
        allCards.forEach(card => {
            card.style.display = 'block';
        });
        
        noResultsAll.style.display = 'none';
        noResultsAvailable.style.display = 'none';
        
        clearSearch();
    });
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

function showNoHistoryAlert() {
    Swal.fire({
        icon: 'warning',
        title: 'Oops!',
        text: 'Belum ada jadwal tiket atau riwayat pemesanan!',
        confirmButtonText: 'OK'
    });
}