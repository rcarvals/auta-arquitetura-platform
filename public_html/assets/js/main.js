import { initProjectFilter } from './filter.js';

document.addEventListener('DOMContentLoaded', () => {
    initProjectFilter();

    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const responseBox = document.getElementById('form-response');
            const submitBtn = contactForm.querySelector('.submit-btn');
            const formData = new FormData(contactForm);

            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            try {
                const res = await fetch(contactForm.action, {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                responseBox.className = 'form-response ' + data.status;
                responseBox.textContent = data.message;

                if (data.status === 'success') {
                    contactForm.reset();
                }
            } catch (error) {
                responseBox.className = 'form-response error';
                responseBox.textContent = 'Erro ao processar o envio. Tente novamente mais tarde.';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Enviar Mensagem';
            }
        });
    }
});