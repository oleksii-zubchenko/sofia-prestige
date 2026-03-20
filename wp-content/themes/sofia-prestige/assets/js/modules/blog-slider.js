function initBlogSlider() {
    const sliders = document.querySelectorAll('[data-blog-slider]');

    if (!sliders.length) return;

    sliders.forEach((slider) => {
        const track = slider.querySelector('[data-blog-track]');
        const pagination = slider.querySelector('[data-blog-pagination]');
        const slides = slider.querySelectorAll('.blog-slide');

        if (!track || !pagination || !slides.length) return;

        let currentPage = 0;
        let slidesPerPage = getSlidesPerPage();
        let totalPages = 1;

        let isDragging = false;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationId = 0;

        function getSlidesPerPage() {
            if (window.innerWidth <= 768) return 1;
            if (window.innerWidth <= 1200) return 2;
            return 3;
        }

        function getGap() {
            const trackStyles = window.getComputedStyle(track);
            return parseFloat(trackStyles.columnGap || trackStyles.gap || 0);
        }

        function getStepWidth() {
            if (!slides.length) return 0;
            return (slides[0].offsetWidth + getGap()) * slidesPerPage;
        }

        function getMaxPage() {
            return Math.max(0, Math.ceil(slides.length / slidesPerPage) - 1);
        }

        function buildPagination() {
            pagination.innerHTML = '';
            totalPages = Math.max(1, Math.ceil(slides.length / slidesPerPage));

            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'blog-dot' + (i === currentPage ? ' active' : '');

                dot.addEventListener('click', () => {
                    currentPage = i;
                    updateSlider(true);
                });

                pagination.appendChild(dot);
            }
        }

        function updateDots() {
            const dots = pagination.querySelectorAll('.blog-dot');

            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentPage);
            });
        }

        function updateSlider(withTransition = true) {
            slidesPerPage = getSlidesPerPage();
            totalPages = Math.max(1, Math.ceil(slides.length / slidesPerPage));

            const maxPage = getMaxPage();

            if (currentPage > maxPage) currentPage = maxPage;
            if (currentPage < 0) currentPage = 0;

            const offset = currentPage * getStepWidth();

            track.style.transition = withTransition ? 'transform 0.35s ease' : 'none';
            track.style.transform = `translateX(-${offset}px)`;

            prevTranslate = -offset;
            currentTranslate = -offset;

            if (pagination.querySelectorAll('.blog-dot').length !== totalPages) {
                buildPagination();
            } else {
                updateDots();
            }
        }

        function setSliderPosition() {
            track.style.transform = `translateX(${currentTranslate}px)`;
        }

        function animation() {
            setSliderPosition();

            if (isDragging) {
                animationId = requestAnimationFrame(animation);
            }
        }

        function getPositionX(event) {
            return event.type.includes('mouse')
                ? event.pageX
                : event.touches[0].clientX;
        }

        function dragStart(event) {
            if (event.type === 'mousedown' && event.button !== 0) return;

            isDragging = true;
            startX = getPositionX(event);
            track.style.transition = 'none';
            slider.classList.add('is-dragging');

            animationId = requestAnimationFrame(animation);
        }

        function dragMove(event) {
            if (!isDragging) return;

            const currentPosition = getPositionX(event);
            const movedBy = currentPosition - startX;

            currentTranslate = prevTranslate + movedBy;
        }

        function dragEnd() {
            if (!isDragging) return;

            isDragging = false;
            cancelAnimationFrame(animationId);
            slider.classList.remove('is-dragging');

            const movedBy = currentTranslate - prevTranslate;
            const threshold = Math.max(50, slides[0].offsetWidth * 0.15);

            if (movedBy < -threshold && currentPage < getMaxPage()) {
                currentPage += 1;
            } else if (movedBy > threshold && currentPage > 0) {
                currentPage -= 1;
            }

            updateSlider(true);
        }

        buildPagination();
        updateSlider(false);

        track.addEventListener('dragstart', (event) => event.preventDefault());

        slider.addEventListener('mousedown', dragStart);
        slider.addEventListener('mousemove', dragMove);
        slider.addEventListener('mouseup', dragEnd);
        slider.addEventListener('mouseleave', dragEnd);

        slider.addEventListener('touchstart', dragStart, { passive: true });
        slider.addEventListener('touchmove', dragMove, { passive: true });
        slider.addEventListener('touchend', dragEnd);
        slider.addEventListener('touchcancel', dragEnd);

        let resizeTimer = null;

        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);

            resizeTimer = setTimeout(() => {
                updateSlider(false);
            }, 120);
        });
    });
}