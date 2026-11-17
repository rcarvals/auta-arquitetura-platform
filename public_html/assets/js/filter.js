export function initProjectFilter() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');

    if (!filterButtons.length || !projectCards.length) return;

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.getAttribute('data-filter');

            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            projectCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');

                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.classList.remove('hide');
                } else {
                    card.classList.add('hide');
                }
            });
        });
    });
}