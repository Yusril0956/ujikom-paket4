import './bootstrap';

const body = document.body;
const sidebarPanel = document.querySelector('[data-sidebar-panel]');
const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');
const sidebarClosers = document.querySelectorAll('[data-sidebar-close]');

const setSidebarState = (open) => {
    if (!sidebarPanel || !sidebarBackdrop) return;

    sidebarPanel.classList.toggle('translate-x-0', open);
    sidebarPanel.classList.toggle('-translate-x-full', !open);
    sidebarBackdrop.classList.toggle('hidden', !open);
    body.classList.toggle('overflow-hidden', open);
};

sidebarToggles.forEach((button) => {
    button.addEventListener('click', () => {
        const isOpen = sidebarPanel?.classList.contains('translate-x-0');
        setSidebarState(!isOpen);
    });
});

sidebarClosers.forEach((button) => {
    button.addEventListener('click', () => setSidebarState(false));
});

document.querySelectorAll('[data-sidebar-panel] a').forEach((link) => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 1024) {
            setSidebarState(false);
        }
    });
});

window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        setSidebarState(false);
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        setSidebarState(false);
    }
});
