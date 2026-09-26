const styleguide = document.querySelector('[data-styleguide]');

if (styleguide) {
    const announcement = styleguide.querySelector('[data-announcement]');
    const announce = (message) => {
        announcement.textContent = message;
    };

    styleguide.querySelector('[data-touch-grid]').addEventListener('click', (event) => {
        const isVisible = styleguide.classList.toggle('show-touch-grid');
        event.currentTarget.setAttribute('aria-pressed', String(isVisible));
        announce(`48 pixel touch target grid ${isVisible ? 'shown' : 'hidden'}.`);
    });

    styleguide.querySelector('[data-search]').addEventListener('input', (event) => {
        const query = event.target.value.trim().toLowerCase();
        styleguide.querySelectorAll('[data-styleguide-section]').forEach((section) => {
            section.classList.toggle('hidden', !section.textContent.toLowerCase().includes(query));
        });
        announce(query ? `Filtered components by ${query}.` : 'Showing all components.');
    });

    styleguide.querySelectorAll('[data-copy-value]').forEach((button) => {
        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(button.dataset.copyValue);
                announce(`${button.dataset.copyValue} copied to the clipboard.`);
            } catch {
                announce('Clipboard access is unavailable in this browser.');
            }
        });
    });

    const switchControl = styleguide.querySelector('[data-switch]');
    switchControl.addEventListener('click', () => {
        const isEnabled = switchControl.getAttribute('aria-checked') !== 'true';
        switchControl.setAttribute('aria-checked', String(isEnabled));
        switchControl.querySelector('span').classList.toggle('translate-x-5', isEnabled);
        switchControl.classList.toggle('bg-biolum', isEnabled);
        switchControl.classList.toggle('bg-slate-subtle', !isEnabled);
        announce(`Collection notifications ${isEnabled ? 'enabled' : 'disabled'}.`);
    });

    styleguide.querySelectorAll('[data-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            styleguide.querySelectorAll('[data-tab]').forEach((item) => {
                item.setAttribute('aria-selected', String(item === tab));
                item.classList.toggle('border-chanterelle', item === tab);
                item.classList.toggle('text-chanterelle', item === tab);
            });
            styleguide.querySelectorAll('[data-tab-panel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== tab.dataset.tab);
            });
        });
    });

    const preview = styleguide.querySelector('[data-viewport-preview]');
    styleguide.querySelectorAll('[data-viewport]').forEach((button) => {
        button.addEventListener('click', () => {
            preview.style.maxWidth = button.dataset.viewport;
            announce(`${button.dataset.label} preview selected.`);
        });
    });

}
