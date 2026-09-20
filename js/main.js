// js/main.js
// Ici j'ai trois manipulateurs du DOM


document.addEventListener('DOMContentLoaded', () => {
    // celui-ci gère la recherche
    const searchForms = document.querySelectorAll('form');

    searchForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const input = form.querySelector('input[name="query"]');
            if (input && input.value.trim() === '') {
                e.preventDefault();
                alert('Veuillez saisir au moins un mot-clé (titre ou auteur) pour effectuer la recherche.');
            }
        });
    });
});




document.addEventListener('DOMContentLoaded', function() {
    // Celui-ci gère la Sélection de toutes les alertes du site pour les faire disparaitre après 15 secondes
    const alerts = document.querySelectorAll('.alert');
    
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(function(alert) {
                // Animation de disparition fluide
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                
                // Retrait du DOM après l'animation
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 15000); // 15000 ms = 15 secondes
    }
});



// Celui-ci gère l'Ouverture et la Fermeture du menu mobile
const mobileBtn = document.getElementById('mobileMenuBtn');
const mainNav = document.getElementById('mainNav');

if (mobileBtn && mainNav) {
    mobileBtn.addEventListener('click', function() {
        mainNav.classList.toggle('open');
        const icon = mobileBtn.querySelector('.material-symbols-outlined');
        if (icon) {
            icon.textContent = mainNav.classList.contains('open') ? 'close' : 'menu';
        }
    });
}