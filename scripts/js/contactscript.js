document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kontaktForm');
    const loading = document.getElementById('loading');
    const response = document.getElementById('response');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        loading.style.display = 'block';
        response.style.display = 'none';

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        })
        .then(res => res.json())
        .then(data => {
            loading.style.display = 'none';

            response.textContent = data.message;
            response.style.display = 'block';

            if (data.status === 'success') {
                response.style.color = 'green';
                form.reset(); 
            } else {
                response.style.color = 'red';
            }
        })
        .catch(error => {
            loading.style.display = 'none';

            response.textContent = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.';
            response.style.display = 'block';
            response.style.color = 'red';
        });
    });
});
