// Hero slider
(() => {
  const hero = document.querySelector('.hero');
  if (!hero) return;

  const slides = Array.from(hero.querySelectorAll('.backgroundContainer .slide'));
  const indicatorWrap = hero.querySelector('.sliderIndicator');
  const content = hero.querySelector('.content');
  const titleH2 = content?.querySelector('h2');
  const titleH1 = content?.querySelector('h1');
  const titleH3 = content?.querySelector('h3');
  const total = slides.length;

  if (!indicatorWrap || !content || !titleH1 || !titleH2 || !titleH3 || total === 0) return;

  // Generate indicators to match slide count (hardcoded markup stays as fallback)
  indicatorWrap.innerHTML = '';
  const indicators = slides.map((_, idx) => {
    const btn = document.createElement('button');
    btn.className = 'sliderButton' + (idx === 0 ? ' active' : '');
    btn.type = 'button';
    btn.setAttribute('aria-label', `Переключить слайд ${idx + 1}`);
    indicatorWrap.appendChild(btn);
    return btn;
  });

  // Text payloads; currently reuse existing copy per slide, but slot per slide for future content.
  const baseText = {
    h1: titleH1.textContent?.trim() || '',
    h2: titleH2.textContent?.trim() || '',
    h3: titleH3.textContent?.trim() || '',
  };
  const texts = slides.map((slide, idx) => {
    const { h1, h2, h3 } = slide.dataset;
    return {
      h1: h1 || baseText.h1,
      h2: h2 || baseText.h2,
      h3: h3 || baseText.h3,
    };
  });

  const AUTOPLAY_MS = 10_000;
  let currentIndex = 0;
  let timerId = null;

  const updateText = (nextIndex) => {
    const next = texts[nextIndex] || baseText;
    content.classList.add('is-fading');
    requestAnimationFrame(() => {
      content.style.opacity = '0';
      setTimeout(() => {
        titleH1.textContent = next.h1;
        titleH2.textContent = next.h2;
        titleH3.textContent = next.h3;
        content.style.opacity = '1';
        content.classList.remove('is-fading');
      }, 180);
    });
  };

  const updateSlides = (nextIndex) => {
    currentIndex = ((nextIndex % total) + total) % total;

    slides.forEach((slide, idx) => {
      const isActive = idx === currentIndex;
      slide.style.opacity = isActive ? '1' : '0';
      slide.style.transform = isActive ? 'translateX(0)' : 'translateX(2%)';
      slide.style.zIndex = isActive ? '1' : '0';
      slide.style.transition = 'opacity 600ms ease, transform 600ms ease';
      slide.setAttribute('aria-hidden', String(!isActive));
    });

    indicators.forEach((btn, idx) => {
      const active = idx === currentIndex;
      btn.classList.toggle('active', active);
      btn.setAttribute('aria-pressed', String(active));
    });

    updateText(currentIndex);
  };

  const stopAutoplay = () => {
    if (timerId) {
      clearTimeout(timerId);
      timerId = null;
    }
  };

  const restartAutoplay = () => {
    stopAutoplay();
    timerId = setTimeout(function tick() {
      updateSlides(currentIndex + 1);
      timerId = setTimeout(tick, AUTOPLAY_MS);
    }, AUTOPLAY_MS);
  };

  indicators.forEach((btn, idx) => {
    btn.addEventListener('click', () => {
      updateSlides(idx);
      restartAutoplay();
    });
  });

  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
      restartAutoplay();
    } else {
      stopAutoplay();
    }
  });

  // Initial paint + autoplay start
  updateSlides(0);
  restartAutoplay();
})();

// Header mobile
(() => {
  const mobileMenu = document.querySelector('.mobileMenuContainer');
  if (!mobileMenu) return;

  const burger = document.querySelector('.burger');
  if (burger) {
    burger.addEventListener('click', () => {
      burger.classList.toggle('active');
      mobileMenu.classList.toggle('active');
    });
  }

  mobileMenu.addEventListener('click', (e) => {
    // Toggle submenu by clicking the parent link (no markup changes available)
    const toggle = e.target.closest('.menu-item-has-children > a');
    if (!toggle || !mobileMenu.contains(toggle)) return;

    e.preventDefault();

    const menuItem = toggle.closest('.menu-item-has-children');
    const submenu = menuItem?.querySelector('.sub-menu');

    if (!submenu) return;

    submenu.classList.toggle('active');
    menuItem.classList.toggle('open');
  });
})();
