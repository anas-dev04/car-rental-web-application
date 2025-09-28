document.addEventListener("DOMContentLoaded", function() {
    const passwordForm = document.querySelector('.form_password');
    const addAdminForm = document.querySelector('.add_admin_form');
    const orderHistory = document.querySelector('.history_reservation');

    function hideAll() {
        passwordForm.style.display = 'none';
        addAdminForm.style.display = 'none';
        orderHistory.style.display = 'none';
    }

    document.querySelector('.show-password-form').addEventListener('click', function(e) {
        e.preventDefault();
        hideAll();
        passwordForm.style.display = 'block';
    });

    document.querySelector('.show-add-admin').addEventListener('click', function(e) {
        e.preventDefault();
        hideAll();
        addAdminForm.style.display = 'block';
    });

    document.querySelector('.show-reservation-history').addEventListener('click', function(e) {
        e.preventDefault();
        hideAll();
        orderHistory.style.display = 'block';
    });
});