function initHeroSlider() {
    const hero = document.querySelector('.hero');

    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('.backgroundContainer .slide'));
    const indicatorWrap = hero.querySelector('.sliderIndicator');
    const content = hero.querySelector('.content');
    const titleH2 = content?.querySelector('h2');
    const titleH1 = content?.querySelector('h1');
    const titleH3 = content?.querySelector('h3');
    const total = slides.length;

    if (!indicatorWrap || !content || !titleH1 || !titleH2 || !titleH3 || total === 0) {
        return;
    }

    indicatorWrap.innerHTML = '';

    const indicators = slides.map((_, index) => {
        const button = document.createElement('button');
        button.className = 'sliderButton' + (index === 0 ? ' active' : '');
        button.type = 'button';
        button.setAttribute('aria-label', `Переключить слайд ${index + 1}`);
        button.setAttribute('aria-pressed', index === 0 ? 'true' : 'false');
        indicatorWrap.appendChild(button);
        return button;
    });

    const baseText = {
        h1: titleH1.textContent?.trim() || '',
        h2: titleH2.textContent?.trim() || '',
        h3: titleH3.textContent?.trim() || '',
    };

    const texts = slides.map((slide) => {
        const { h1, h2, h3 } = slide.dataset;

        return {
            h1: h1 || baseText.h1,
            h2: h2 || baseText.h2,
            h3: h3 || baseText.h3,
        };
    });

    const autoplayDelay = 10000;
    let currentIndex = 0;
    let timerId = null;

    function updateText(index) {
        const next = texts[index] || baseText;

        content.classList.add('is-fading');
        content.style.opacity = '0';

        setTimeout(() => {
            titleH1.textContent = next.h1;
            titleH2.textContent = next.h2;
            titleH3.textContent = next.h3;
            content.style.opacity = '1';
            content.classList.remove('is-fading');
        }, 180);
    }

    function updateSlides(index) {
        currentIndex = ((index % total) + total) % total;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === currentIndex;

            slide.style.opacity = isActive ? '1' : '0';
            slide.style.transform = isActive ? 'translateX(0)' : 'translateX(2%)';
            slide.style.zIndex = isActive ? '1' : '0';
            slide.style.transition = 'opacity 600ms ease, transform 600ms ease';
            slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
        });

        indicators.forEach((button, buttonIndex) => {
            const isActive = buttonIndex === currentIndex;
            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        updateText(currentIndex);
    }

    function stopAutoplay() {
        if (!timerId) return;

        clearTimeout(timerId);
        timerId = null;
    }

    function startAutoplay() {
        stopAutoplay();

        timerId = setTimeout(function autoplayTick() {
            updateSlides(currentIndex + 1);
            timerId = setTimeout(autoplayTick, autoplayDelay);
        }, autoplayDelay);
    }

    indicators.forEach((button, index) => {
        button.addEventListener('click', () => {
            updateSlides(index);
            startAutoplay();
        });
    });

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            startAutoplay();
        } else {
            stopAutoplay();
        }
    });

    updateSlides(0);
    startAutoplay();
}