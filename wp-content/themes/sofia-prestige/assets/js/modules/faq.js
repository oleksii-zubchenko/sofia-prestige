function initFaqAccordion() {
    const faqItems = document.querySelectorAll('.faq-item');

    if (!faqItems.length) return;

    function updateItemHeight(item) {
        const content = item.querySelector('.faq-content');

        if (!content) return;

        if (item.classList.contains('active')) {
            content.style.maxHeight = content.scrollHeight + 'px';
        } else {
            content.style.maxHeight = '0px';
        }
    }

    faqItems.forEach((item) => {
        const button = item.querySelector('.faq-button');
        const content = item.querySelector('.faq-content');

        if (!button || !content) return;

        content.style.maxHeight = '0px';

        button.addEventListener('click', () => {
            const isOpen = item.classList.contains('active');

            item.classList.toggle('active', !isOpen);
            button.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            updateItemHeight(item);
        });
    });

    let resizeTimer = null;

    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);

        resizeTimer = setTimeout(() => {
            faqItems.forEach((item) => {
                if (item.classList.contains('active')) {
                    updateItemHeight(item);
                }
            });
        }, 150);
    });
}