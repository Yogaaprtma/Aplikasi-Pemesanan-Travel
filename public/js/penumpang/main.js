// Initialize AOS
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out'
    });
    
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
        document.querySelector('.overlay').classList.toggle('show');
    });
    
    // Close sidebar when clicking on overlay
    document.querySelector('.overlay').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.remove('show');
        document.querySelector('.overlay').classList.remove('show');
    });
    
    // Current date
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    
    if (document.getElementById('currentDate')) {
        document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
    }
    
    // Add animate.css classes on scroll using AOS
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.setAttribute('data-aos', 'fade-up');
        card.setAttribute('data-aos-delay', (index * 100).toString());
    });
});