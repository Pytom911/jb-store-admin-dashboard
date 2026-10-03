const drawerRoot = document.querySelector('[data-drawer-root]');

if (drawerRoot) {
    const drawerPanel = drawerRoot.querySelector('[data-drawer-panel]');
    const drawerOverlay = drawerRoot.querySelector('[data-drawer-overlay]');
    const drawerToggles = document.querySelectorAll('[data-drawer-toggle]');

    if (drawerPanel && drawerOverlay && drawerToggles.length) {
        const closedClass = drawerPanel.dataset.drawerClosedClass ?? '-translate-x-full';
        const isOpen = () => drawerRoot.dataset.open === 'true';
        let trigger = null;

        // The root, not just the panel, carries the open state. The root covers the
        // whole viewport, so while it is closed it has to stop hit testing entirely
        // (and leave the tab order), otherwise it swallows every tap on the page
        // underneath. `inert` does both; the pointer-events swap is the fallback.
        const close = () => {
            drawerPanel.classList.add(closedClass);
            drawerOverlay.classList.add('opacity-0');
            drawerRoot.removeAttribute('data-open');
            drawerRoot.setAttribute('inert', '');
            drawerToggles.forEach((toggle) => toggle.setAttribute('aria-expanded', 'false'));
            document.body.classList.remove('overflow-hidden');
            trigger?.focus();
            trigger = null;
        };

        const open = (toggle) => {
            trigger = toggle;
            drawerPanel.classList.remove(closedClass);
            drawerOverlay.classList.remove('opacity-0');
            drawerRoot.removeAttribute('inert');
            drawerRoot.dataset.open = 'true';
            drawerToggles.forEach((element) =>
                element.setAttribute('aria-expanded', element === toggle ? 'true' : 'false'),
            );
            document.body.classList.add('overflow-hidden');
            drawerPanel.focus();
        };

        drawerToggles.forEach((toggle) =>
            toggle.addEventListener('click', () => (isOpen() ? close() : open(toggle))),
        );
        drawerOverlay.addEventListener('click', close);
        drawerPanel.querySelectorAll('a, button').forEach((element) =>
            element.addEventListener('click', close),
        );

        document.addEventListener('keydown', (event) => {
            if (! isOpen()) return;

            if (event.key === 'Escape') {
                close();

                return;
            }

            if (event.key !== 'Tab') return;

            // The drawer covers the viewport, so Tab has to cycle inside the panel.
            // Without this the next stop is a link buried under the overlay.
            const focusable = Array.from(
                drawerPanel.querySelectorAll(
                    'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
                ),
            ).filter((element) => element.offsetParent !== null);

            if (! focusable.length) return;

            const first = focusable[0];
            const last = focusable.at(-1);
            const active = document.activeElement;

            if (event.shiftKey && (active === first || active === drawerPanel)) {
                event.preventDefault();
                last.focus();
            } else if (! event.shiftKey && (active === last || active === drawerPanel)) {
                event.preventDefault();
                first.focus();
            }
        });

        window.matchMedia('(min-width: 64rem)').addEventListener('change', (event) => {
            if (event.matches && isOpen()) close();
        });

        if (isOpen()) close();
    }
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

document.addEventListener('click', (event) => {
    const dismiss = event.target.closest('[data-alert-dismiss]');

    if (!dismiss) return;

    // Focus has to land somewhere deliberate, otherwise removing the notice
    // drops a keyboard user back at the top of the document.
    const notice = dismiss.closest('[role="status"], [role="alert"]');
    notice?.remove();

    document.querySelector('main')?.focus();
});

const MARQUEE_PIXELS_PER_SECOND = 42;
const MARQUEE_MAX_SETS = 24;

document.querySelectorAll('[data-game-marquee]').forEach((frame) => {
    const track = frame.querySelector('[data-game-marquee-track]');
    const set = frame.querySelector('[data-game-marquee-set]');

    if (!track || !set) return;

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const clones = [];
    let focusInside = false;
    let onScreen = true;

    const syncPaused = () => {
        const paused = focusInside || ! onScreen;

        if (paused) {
            frame.dataset.paused = 'true';
        } else {
            delete frame.dataset.paused;
        }
    };

    const measure = () => {
        // The set carries its own trailing gap as padding, so this width already
        // includes the space that follows the last card. Scrolling by exactly
        // this much lands the next set where this one began, which is why the
        // wrap leaves neither a seam nor a gap.
        const distance = set.getBoundingClientRect().width;

        if (! distance) return;

        frame.style.setProperty('--game-marquee-shift', `${distance}px`);
        frame.style.setProperty('--game-marquee-duration', `${distance / MARQUEE_PIXELS_PER_SECOND}s`);
    };

    const fill = () => {
        clones.splice(0).forEach((clone) => clone.remove());

        if (reducedMotion.matches) {
            frame.dataset.static = 'true';

            return;
        }

        delete frame.dataset.static;

        // A storefront can hold a single game, and one set is narrower than the
        // hero column on most screens. Repeat until a second set spans the
        // frame, so the track can always translate a full set width without
        // uncovering empty paper.
        const setWidth = set.getBoundingClientRect().width;
        let sets = 1;

        while (setWidth && sets < MARQUEE_MAX_SETS && track.scrollWidth < frame.clientWidth + setWidth) {
            const clone = set.cloneNode(true);

            // Assistive tech and the tab order only ever see the original set.
            clone.setAttribute('aria-hidden', 'true');
            clone.querySelectorAll('a, button').forEach((element) => element.setAttribute('tabindex', '-1'));

            track.append(clone);
            clones.push(clone);
            sets += 1;
        }

        measure();
    };

    frame.addEventListener('focusin', () => {
        focusInside = true;
        syncPaused();
    });

    frame.addEventListener('focusout', (event) => {
        if (frame.contains(event.relatedTarget)) return;

        focusInside = false;
        syncPaused();
    });

    // Nothing to animate while the hero is scrolled out of view. Scrolling back
    // in must not undo a pause the shopper asked for, so this only feeds the
    // same OR rather than driving it.
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(
            ([entry]) => {
                onScreen = entry.isIntersecting;
                syncPaused();
            },
            { threshold: 0 },
        ).observe(frame);
    }

    reducedMotion.addEventListener('change', fill);

    let resizeFrame = 0;

    window.addEventListener('resize', () => {
        cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(fill);
    });

    fill();

    // Card width is fixed in CSS, but web fonts can still shift the caption and
    // change the measured set width after first paint.
    document.fonts?.ready.then(measure);
});
