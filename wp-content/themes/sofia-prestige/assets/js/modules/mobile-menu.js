function initMobileMenu() {
    const mobileMenu = document.querySelector('.mobileMenuContainer');
    const burger = document.querySelector('.burger');

    if (!mobileMenu || !burger) return;

    burger.addEventListener('click', () => {
        burger.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    mobileMenu.addEventListener('click', (event) => {
        const toggleLink = event.target.closest('.menu-item-has-children > a');

        if (!toggleLink || !mobileMenu.contains(toggleLink)) return;

        event.preventDefault();

        const menuItem = toggleLink.closest('.menu-item-has-children');
        const submenu = menuItem?.querySelector('.sub-menu');

        if (!menuItem || !submenu) return;

        submenu.classList.toggle('active');
        menuItem.classList.toggle('open');
    });
}