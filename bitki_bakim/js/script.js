document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                alert('Lütfen tüm zorunlu alanları doldurun.');
            }
            form.classList.add('was-validated');
        }, false);
    });
});