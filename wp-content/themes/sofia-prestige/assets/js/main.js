document.addEventListener('DOMContentLoaded', () => {
  if (typeof initHeroSlider === 'function') initHeroSlider();
  if (typeof initMobileMenu === 'function') initMobileMenu();
  if (typeof initFaqAccordion === 'function') initFaqAccordion();
  if (typeof initBlogSlider === 'function') initBlogSlider();
  if (typeof initPartnersSlider === 'function') initPartnersSlider();
  if (typeof initAboutCompanyToggle === 'function') initAboutCompanyToggle();
  if (typeof initScrollTopButton === 'function') initScrollTopButton();
});

  document.addEventListener('click', async function (event) {
      const button = event.target.closest('.js-archive-load-more');
      if (!button) return;

      event.preventDefault();

      const nextUrl = button.getAttribute('data-next-url');
      if (!nextUrl) return;

      const root = button.closest('[data-archive-root]');
      if (!root) return;

      const grid = root.querySelector('[data-archive-grid]');
      const pagination = root.querySelector('[data-archive-pagination]');
      const loadMoreWrap = root.querySelector('[data-load-more-wrap]');

      if (!grid) return;

      button.disabled = true;
      button.style.opacity = '0.6';

      try {
      const response = await fetch(nextUrl, { credentials: 'same-origin' });
      const html = await response.text();
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');

      const newRoot = doc.querySelector('[data-archive-root]');
      if (!newRoot) {
      button.disabled = false;
      button.style.opacity = '1';
      return;
  }

      const newGrid = newRoot.querySelector('[data-archive-grid]');
      const newPagination = newRoot.querySelector('[data-archive-pagination]');
      const newLoadMoreWrap = newRoot.querySelector('[data-load-more-wrap]');

      if (newGrid) {
      grid.insertAdjacentHTML('beforeend', newGrid.innerHTML);
  }

      if (pagination) {
      if (newPagination) {
      pagination.innerHTML = newPagination.innerHTML;
  } else {
      pagination.remove();
  }
  }

      if (loadMoreWrap) {
      if (newLoadMoreWrap) {
      loadMoreWrap.innerHTML = newLoadMoreWrap.innerHTML;
  } else {
      loadMoreWrap.remove();
  }
  }
  } catch (error) {
      button.disabled = false;
      button.style.opacity = '1';
  }
});
