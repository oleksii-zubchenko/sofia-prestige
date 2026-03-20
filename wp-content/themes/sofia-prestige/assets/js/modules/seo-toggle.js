function initAboutCompanyToggle() {
    const blocks = document.querySelectorAll('[data-about-company]');

    if (!blocks.length) return;

    blocks.forEach((block) => {
        const content = block.querySelector('[data-about-company-content]');
        const hiddenPart = block.querySelector('[data-about-company-hidden]');
        const button = block.querySelector('[data-about-company-toggle]');

        if (!content || !hiddenPart || !button) return;

        button.addEventListener('click', () => {
            const isOpen = block.classList.contains('is-open');

            if (isOpen) {
                block.classList.remove('is-open');
                content.classList.add('is-collapsed');
                button.setAttribute('aria-expanded', 'false');
            } else {
                block.classList.add('is-open');
                content.classList.remove('is-collapsed');
                button.setAttribute('aria-expanded', 'true');
            }
        });
    });
}