const drawerPanel = document.querySelector('[data-drawer-panel]');
const drawerOverlay = document.querySelector('[data-drawer-overlay]');
const drawerToggles = document.querySelectorAll('[data-drawer-toggle]');

if (drawerPanel && drawerToggles.length) {
    const closedClass = drawerPanel.dataset.drawerClosedClass ?? '-translate-x-full';
    const isOpen = () => ! drawerPanel.classList.contains(closedClass);

    const close = () => {
        drawerPanel.classList.add(closedClass);
        drawerOverlay.classList.add('opacity-0', 'pointer-events-none');
        drawerToggles.forEach((toggle) => toggle.setAttribute('aria-expanded', 'false'));
        document.body.classList.remove('overflow-hidden');
    };

    const open = () => {
        drawerPanel.classList.remove(closedClass);
        drawerOverlay.classList.remove('opacity-0', 'pointer-events-none');
        drawerToggles.forEach((toggle) => toggle.setAttribute('aria-expanded', 'true'));
        document.body.classList.add('overflow-hidden');
    };

    drawerToggles.forEach((toggle) => toggle.addEventListener('click', () => (isOpen() ? close() : open())));
    drawerOverlay.addEventListener('click', close);
    drawerPanel.querySelectorAll('a').forEach((link) => link.addEventListener('click', close));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) close();
    });

    window.matchMedia('(min-width: 64rem)').addEventListener('change', (event) => {
        if (event.matches) close();
    });
}

const confirmModal = document.querySelector('[data-confirm-modal]');

if (confirmModal) {
    const form = confirmModal.querySelector('[data-confirm-form]');
    const message = confirmModal.querySelector('[data-confirm-message]');
    const accept = confirmModal.querySelector('[data-confirm-accept]');
    let trigger = null;

    document.addEventListener('click', (event) => {
        const opener = event.target.closest('[data-confirm-url]');

        if (!opener) return;

        event.preventDefault();
        form.action = opener.dataset.confirmUrl;
        message.textContent = opener.dataset.confirmMessage;
        trigger = opener;
        confirmModal.showModal();
        accept.focus();
    });

    confirmModal.querySelector('[data-confirm-cancel]').addEventListener('click', () => confirmModal.close());
    confirmModal.addEventListener('click', (event) => {
        if (event.target === confirmModal) confirmModal.close();
    });
    confirmModal.addEventListener('close', () => {
        if (trigger) trigger.focus();
    });
    accept.addEventListener('click', () => form.submit());
}

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-reveal]');

    if (!toggle) return;

    const field = document.getElementById(toggle.dataset.reveal);

    if (!field) return;

    const hidden = field.type === 'password';

    field.type = hidden ? 'text' : 'password';
    toggle.setAttribute('aria-label', hidden ? 'Sembunyikan password' : 'Lihat password');
    toggle.querySelector('[data-eye]').classList.toggle('hidden', ! hidden);
    toggle.querySelector('[data-eye-slash]').classList.toggle('hidden', hidden);
});

document.querySelectorAll('[data-slug-source]').forEach((slugField) => {
    const nameField = document.getElementById(slugField.dataset.slugSource);

    if (!nameField) return;

    nameField.addEventListener('input', () => {
        slugField.placeholder = nameField.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    });
});

document.querySelectorAll('[data-flash]').forEach((flash) => {
    setTimeout(() => {
        flash.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => flash.remove(), 500);
    }, 5000);
});
