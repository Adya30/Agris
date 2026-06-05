document.addEventListener('DOMContentLoaded', () => {
    function debounce(func, delay) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    document.querySelectorAll('form[data-ajax-validate]').forEach(form => {
        const validateRoute = form.getAttribute('data-validate-url');

        form.querySelectorAll('input').forEach(input => {
            const validateInput = debounce(async () => {
                const formData = new FormData(form);

                const response = await fetch(validateRoute, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                const parent = input.parentElement;
                const existing = parent.querySelector('.error-msg');

                if (existing) existing.remove();

                if (data.errors && data.errors[input.name]) {
                    const msg = document.createElement('p');
                    msg.className = 'text-red-500 text-xs mt-1 error-msg';
                    msg.innerText = data.errors[input.name][0];
                    parent.appendChild(msg);
                }
            }, 500);

            input.addEventListener('input', validateInput);
        });
    });
});
