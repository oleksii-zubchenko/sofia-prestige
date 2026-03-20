function initScrollTopButton() {
    const scrollButton = document.querySelector('[data-scroll-top]');

    if (!scrollButton) return;

    scrollButton.addEventListener('click', () => {
        const start = window.pageYOffset;
        const duration = 900;
        const startTime = performance.now();

        function easeInOutCubic(time) {
            return time < 0.5
                ? 4 * time * time * time
                : 1 - Math.pow(-2 * time + 2, 3) / 2;
        }

        function animateScroll(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easedProgress = easeInOutCubic(progress);
            const nextPosition = start * (1 - easedProgress);

            window.scrollTo(0, nextPosition);

            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            }
        }

        requestAnimationFrame(animateScroll);
    });
}