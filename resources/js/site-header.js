const header = document.querySelector('[data-site-header]');

if (header) {
    const themeToggle = header.querySelector('[data-theme-toggle]');
    const menuButton = header.querySelector('[data-menu-button]');
    const menu = header.querySelector('[data-mobile-menu]');
    const closeMenuButton = header.querySelector('[data-menu-close]');

    const applyTheme = (isDark) => {
        document.documentElement.classList.toggle('dark', isDark);
        themeToggle.setAttribute('aria-pressed', String(isDark));
        themeToggle.textContent = isDark ? 'Light mode' : 'Dark mode';
    };

    const closeMenu = () => {
        menu.classList.add('hidden');
        menuButton.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
    };

    const openMenu = () => {
        menu.classList.remove('hidden');
        menuButton.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');
        closeMenuButton.focus();
    };

    applyTheme(localStorage.getItem('site-theme') !== 'light');

    themeToggle.addEventListener('click', () => {
        const isDark = !document.documentElement.classList.contains('dark');
        localStorage.setItem('site-theme', isDark ? 'dark' : 'light');
        applyTheme(isDark);
    });

    menuButton.addEventListener('click', openMenu);
    closeMenuButton.addEventListener('click', closeMenu);
    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
            closeMenu();
            menuButton.focus();
        }
    });
}
