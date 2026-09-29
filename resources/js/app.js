import { initCompanion } from './companion';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
const root = document.documentElement;

/* ---------- Hero entrance: start once fonts are ready (or after a short cap) ---------- */
let startSunrise = () => {};
const start = () => requestAnimationFrame(() => {
    root.classList.add('is-loaded');
    startSunrise();
});
Promise.race([document.fonts?.ready ?? Promise.resolve(), new Promise((r) => setTimeout(r, 450))]).then(start);

/* ---------- Style switcher ---------- */
const themeButtons = [...document.querySelectorAll('[data-theme-set]')];
const themeMeta = document.querySelector('meta[name="theme-color"]');
const themeColors = { black: '#08080A', violet: '#140A2C' };
const themeName = document.querySelector('[data-theme-name]');

function applyTheme(theme) {
    if (!(theme in themeColors)) theme = 'black';
    root.dataset.theme = theme;
    themeButtons.forEach((b) => {
        const active = b.dataset.themeSet === theme;
        b.setAttribute('aria-pressed', String(active));
        if (active && themeName) themeName.textContent = b.dataset.themeLabel;
    });
    themeMeta?.setAttribute('content', themeColors[theme]);
    try {
        localStorage.setItem('equinox-theme', theme);
    } catch {}
}
applyTheme(root.dataset.theme || 'black');

themeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const theme = button.dataset.themeSet;
        if ((root.dataset.theme || 'black') === theme) return;
        if (!document.startViewTransition || reduceMotion) {
            applyTheme(theme);
            return;
        }
        // The new style grows in a circle from the button that was clicked.
        const r = button.getBoundingClientRect();
        const x = r.left + r.width / 2;
        const y = r.top + r.height / 2;
        const radius = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));
        document.startViewTransition(() => applyTheme(theme)).ready.then(() => {
            root.animate(
                { clipPath: [`circle(0 at ${x}px ${y}px)`, `circle(${radius}px at ${x}px ${y}px)`] },
                { duration: 700, easing: 'cubic-bezier(.65, 0, .35, 1)', pseudoElement: '::view-transition-new(root)' },
            );
        });
    });
});

/* ---------- Header: background, hide on scroll down, mobile menu ---------- */
const header = document.querySelector('[data-header]');
const toggle = document.querySelector('[data-nav-toggle]');

function setMenu(open) {
    header?.classList.toggle('is-open', open);
    toggle?.setAttribute('aria-expanded', String(open));
    toggle?.querySelector('.sr-only')?.replaceChildren(open ? 'Close menu' : 'Open menu');
}
toggle?.addEventListener('click', () => setMenu(!header.classList.contains('is-open')));
document.querySelectorAll('.site-nav a').forEach((a) => a.addEventListener('click', () => setMenu(false)));
document.addEventListener('keydown', (e) => e.key === 'Escape' && setMenu(false));

/* ---------- Scroll-driven effects (one rAF per frame) ---------- */
const hero = document.querySelector('[data-hero]');
const starLayers = [...document.querySelectorAll('.starfield__layer')].map((el) => ({ el, speed: Number(el.dataset.speed) }));
const planets = [...document.querySelectorAll('[data-depth]')].map((el) => ({ el, depth: Number(el.dataset.depth), section: el.parentElement }));
let lastY = window.scrollY;
let ticking = false;

function onScrollFrame() {
    ticking = false;
    const y = window.scrollY;
    const max = root.scrollHeight - window.innerHeight;

    header?.classList.toggle('is-scrolled', y > 24);
    // Hide the header when scrolling down past the hero, show it again on scroll up.
    if (Math.abs(y - lastY) > 6) {
        header?.classList.toggle('is-hidden', y > lastY && y > window.innerHeight * 0.8);
        lastY = y;
    }
    root.style.setProperty('--progress', max > 0 ? (y / max).toFixed(4) : 0);

    if (reduceMotion) return;

    // Starfield layers tile every 1000px, so the modulo keeps them seamless on long pages.
    starLayers.forEach(({ el, speed }) => {
        el.style.transform = `translate3d(0, ${-((y * speed) % 1000)}px, 0)`;
    });

    if (hero && y < window.innerHeight * 1.2) {
        hero.style.setProperty('--hero-sy', `${y * 0.25}px`);
        hero.style.setProperty('--hero-cy', `${y * 0.18}px`);
        hero.style.setProperty('--hero-o', Math.max(0, 1 - y / (window.innerHeight * 0.85)).toFixed(3));
        hero.style.setProperty('--hero-hy', `${-y * 0.12}px`); // the planet rises as you scroll
    }

    // Planets drift at their own depth relative to their section's position in the viewport.
    planets.forEach(({ el, depth, section }) => {
        const r = section.getBoundingClientRect();
        if (r.bottom < -200 || r.top > window.innerHeight + 200) return;
        const offset = r.top + r.height / 2 - window.innerHeight / 2;
        el.style.setProperty('--py', `${(-offset * depth).toFixed(1)}px`);
    });
}
const requestScrollFrame = () => {
    if (!ticking) {
        ticking = true;
        requestAnimationFrame(onScrollFrame);
    }
};
window.addEventListener('scroll', requestScrollFrame, { passive: true });
window.addEventListener('resize', requestScrollFrame);
onScrollFrame();

/* ---------- Sunrise over the hero horizon: on load, and again whenever you come back to the top ---------- */
if (hero) {
    let heroInView = true;
    startSunrise = () => {
        if (heroInView && root.classList.contains('is-loaded')) hero.classList.add('is-sunrise');
    };
    new IntersectionObserver(([entry]) => {
        heroInView = entry.intersectionRatio >= 0.5;
        if (heroInView) startSunrise();
        else if (!entry.isIntersecting) hero.classList.remove('is-sunrise'); // back to night, ready to replay
    }, { threshold: [0, 0.5] }).observe(hero);
}

/* ---------- The orbital "atom" (The Idea): draws itself in view, drifts with the pointer ---------- */
const orbitBox = document.querySelector('[data-orbits]');
if (orbitBox) {
    // Replays every time you scroll down to it: draws once its center is on screen, resets once fully out of view.
    new IntersectionObserver(([entry]) => {
        if (entry.intersectionRatio >= 0.55) orbitBox.classList.add('is-drawn');
        else if (!entry.isIntersecting) orbitBox.classList.remove('is-drawn');
    }, { threshold: [0, 0.55] }).observe(orbitBox);

    const area = orbitBox.closest('section');
    if (area && finePointer && !reduceMotion) {
        area.addEventListener('pointermove', (e) => {
            const r = area.getBoundingClientRect();
            orbitBox.style.setProperty('--mx', ((e.clientX - r.left) / r.width - 0.5).toFixed(3));
            orbitBox.style.setProperty('--my', ((e.clientY - r.top) / r.height - 0.5).toFixed(3));
        });
        area.addEventListener('pointerleave', () => {
            orbitBox.style.setProperty('--mx', 0);
            orbitBox.style.setProperty('--my', 0);
        });
    }
}

/* ---------- Active nav link ---------- */
const navLinks = [...document.querySelectorAll('.site-nav ul a')];
const sections = navLinks.map((a) => document.querySelector(a.getAttribute('href'))).filter(Boolean);
if (sections.length) {
    const spy = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            navLinks.forEach((a) => a.classList.toggle('is-active', a.getAttribute('href') === `#${entry.target.id}`));
        });
    }, { rootMargin: '-45% 0px -50% 0px' });
    sections.forEach((s) => spy.observe(s));
}

/* ---------- Split display headings into words that slide up ---------- */
function splitWords(el) {
    let index = 0;
    const walk = (node) => {
        [...node.childNodes].forEach((child) => {
            if (child.nodeType === Node.TEXT_NODE) {
                const frag = document.createDocumentFragment();
                child.textContent.split(/(\s+)/).forEach((part) => {
                    if (!part) return;
                    if (/^\s+$/.test(part)) {
                        frag.append(document.createTextNode(' '));
                        return;
                    }
                    const outer = document.createElement('span');
                    const inner = document.createElement('span');
                    outer.className = 'w';
                    inner.textContent = part;
                    inner.style.setProperty('--wi', index++);
                    outer.append(inner);
                    frag.append(outer);
                });
                child.replaceWith(frag);
            } else if (child.nodeType === Node.ELEMENT_NODE) {
                walk(child);
            }
        });
    };
    walk(el);
    // Screen readers still get the heading as one sentence.
    el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
    [...el.children].forEach((c) => c.setAttribute('aria-hidden', 'true'));
}

const splitHeadings = reduceMotion ? [] : [...document.querySelectorAll('.display')];
splitHeadings.forEach((h) => {
    h.classList.remove('reveal');
    h.classList.add('split');
    splitWords(h);
});

/* ---------- Reveal on scroll, staggered among siblings ---------- */
const revealEls = [...document.querySelectorAll('.reveal')];
revealEls.forEach((el) => {
    const siblings = [...el.parentElement.children].filter((c) => c.classList.contains('reveal'));
    const i = siblings.indexOf(el);
    if (i > 0) el.style.setProperty('--stagger', `${Math.min(i, 6) * 90}ms`);
});

const revealer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        el.classList.add('is-visible');
        el.querySelectorAll('[data-count]').forEach(countUp);
        revealer.unobserve(el);
        // Once revealed, drop the reveal classes so hover transforms/transitions behave normally.
        if (el.classList.contains('reveal')) {
            const delay = parseFloat(getComputedStyle(el).getPropertyValue('--stagger')) || 0;
            setTimeout(() => {
                el.classList.remove('reveal', 'is-visible');
                el.style.removeProperty('--stagger');
            }, delay + 1400);
        }
    });
}, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
[...revealEls, ...splitHeadings].forEach((el) => revealer.observe(el));

function countUp(el) {
    const target = Number(el.dataset.count);
    if (reduceMotion || !target) return;
    const duration = 1600;
    const t0 = performance.now();
    const tick = (now) => {
        const t = Math.min(1, (now - t0) / duration);
        el.textContent = Math.round(target * (1 - Math.pow(1 - t, 4)));
        if (t < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

/* ---------- Pause decorative animations while their section is off screen ---------- */
const animatedSections = document.querySelectorAll('[data-hero], .marquee, .idea, .equator, .glance, .experience, .themes, .audience, .join, .closing');
const pauser = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting }) => {
        target.classList.toggle('is-offscreen', !isIntersecting);
        // SMIL animations (satellites, globe meridians) ignore CSS, so pause the SVGs directly.
        target.querySelectorAll('svg').forEach((svg) => {
            if (isIntersecting) svg.unpauseAnimations?.();
            else svg.pauseAnimations?.();
        });
    });
}, { rootMargin: '100px 0px' });
animatedSections.forEach((s) => pauser.observe(s));

/* ---------- Review dock: collapsible (remembered; ?dock=hidden starts it collapsed) ---------- */
const dock = document.querySelector('[data-dock]');
const dockToggle = dock?.querySelector('[data-dock-toggle]');
function setDock(collapsed, remember = true) {
    if (!dock) return;
    dock.classList.toggle('is-collapsed', collapsed);
    dockToggle.setAttribute('aria-expanded', String(!collapsed));
    dockToggle.setAttribute('aria-label', collapsed ? 'Show style options' : 'Hide style options');
    if (remember) {
        try {
            localStorage.setItem('equinox-dock', collapsed ? 'hidden' : 'shown');
        } catch {}
    }
}
if (dock) {
    let startCollapsed = false;
    try {
        const q = new URLSearchParams(location.search).get('dock');
        startCollapsed = q ? q === 'hidden' : localStorage.getItem('equinox-dock') === 'hidden';
    } catch {}
    setDock(startCollapsed, false);
    dockToggle.addEventListener('click', () => setDock(!dock.classList.contains('is-collapsed')));
}

/* ---------- Keep the style switcher out of the way while typing ---------- */
document.addEventListener('focusin', (e) => {
    if (e.target.matches('input, select, textarea')) document.body.classList.add('is-typing');
});
document.addEventListener('focusout', (e) => {
    if (e.target.matches('input, select, textarea')) document.body.classList.remove('is-typing');
});

/* ---------- Pointer spotlight on cards ---------- */
if (finePointer) {
    const spots = document.querySelectorAll('.card, .exp, .stat, .chip');
    spots.forEach((el) => {
        el.classList.add('spot');
        if (!el.classList.contains('stat')) el.classList.add('spot--border');
        // Cards tilt toward the pointer (chips are too small to benefit).
        const tilts = !el.classList.contains('chip') && !el.classList.contains('exp') && !reduceMotion;
        if (tilts) el.classList.add('tilt');
        const max = el.classList.contains('exp') ? 4 : 7;
        el.addEventListener('pointermove', (e) => {
            const r = el.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            el.style.setProperty('--x', `${e.clientX - r.left}px`);
            el.style.setProperty('--y', `${e.clientY - r.top}px`);
            if (tilts) {
                el.style.setProperty('--ry', `${((px - 0.5) * 2 * max).toFixed(2)}deg`);
                el.style.setProperty('--rx', `${((0.5 - py) * 2 * max).toFixed(2)}deg`);
            }
        });
        el.addEventListener('pointerleave', () => {
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        });
    });
}

initCompanion();

/* ---------- Modals ---------- */
const modals = {
    join: document.getElementById('modal-join'),
    sponsor: document.getElementById('modal-sponsor'),
    map: document.getElementById('modal-map'),
};

function openModal(name) {
    const dialog = modals[name];
    if (!dialog?.showModal) return false;
    setMenu(false);
    dialog.showModal();
    document.body.classList.add('modal-open');
    (dialog.querySelector('input:not([type=hidden]):not([tabindex="-1"])') ?? dialog.querySelector('[data-close]'))?.focus();
    return true;
}

/* ---------- Globe → map of Ecuador: the globe zooms into Quito, then the map draws itself ---------- */
const globeBox = document.querySelector('[data-globe]');
const mapFrame = document.querySelector('[data-map]');
document.querySelectorAll('[data-open-map]').forEach((pin) => {
    pin.addEventListener('click', () => {
        const open = () => {
            if (!openModal('map')) return;
            // On phones the map scrolls sideways: start with Quito about a third of the way in.
            const venue = mapFrame?.querySelector('.map__venue--conference');
            if (mapFrame && venue && mapFrame.scrollWidth > mapFrame.clientWidth) {
                const x = venue.getBoundingClientRect().left - mapFrame.getBoundingClientRect().left + mapFrame.scrollLeft;
                mapFrame.scrollLeft = x - mapFrame.clientWidth * 0.3;
            }
            requestAnimationFrame(() => mapFrame?.classList.add('is-open'));
        };
        if (reduceMotion || !globeBox) return open();
        globeBox.classList.add('is-zooming');
        setTimeout(open, 520);
    });
});
modals.map?.addEventListener('close', () => {
    mapFrame?.classList.remove('is-open');
    globeBox?.classList.remove('is-zooming');
});

// The first time the globe is seen, show the "Explore the map" hint for a moment so people know it's clickable.
const globePin = document.querySelector('.globe-pin');
if (globeBox && globePin) {
    new IntersectionObserver((entries, obs) => {
        if (!entries[0].isIntersecting) return;
        obs.disconnect();
        setTimeout(() => {
            globePin.classList.add('is-hinting');
            setTimeout(() => globePin.classList.remove('is-hinting'), 2800);
        }, 1600);
    }, { threshold: 0.6 }).observe(globeBox);
}

document.querySelectorAll('[data-open]').forEach((trigger) => {
    trigger.addEventListener('click', (e) => {
        if (openModal(trigger.dataset.open)) e.preventDefault();
    });
});

Object.values(modals).forEach((dialog) => {
    if (!dialog) return;
    dialog.addEventListener('close', () => document.body.classList.remove('modal-open'));
    dialog.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', () => dialog.close()));
    // Click on the backdrop closes the dialog.
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) dialog.close();
    });
});

/* ---------- AJAX forms ---------- */
document.querySelectorAll('[data-ajax-form]').forEach((form) => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors(form);

        const button = form.querySelector('button[type=submit]');
        const status = form.querySelector('.form__status');
        const label = button.querySelector('.btn__label');
        const original = label.textContent;

        button.disabled = true;
        label.textContent = 'Sending…';
        status.textContent = '';
        status.classList.remove('is-error');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
            });
            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                showSuccess(form, data.message);
                return;
            }
            if (response.status === 422 && data.errors) {
                showErrors(form, data.errors);
                status.textContent = 'Please review the highlighted fields.';
            } else if (response.status === 429) {
                status.textContent = 'Too many attempts. Please wait a minute and try again.';
            } else if (response.status === 419) {
                status.textContent = 'Your session expired. Please reload the page and try again.';
            } else {
                status.textContent = 'Something went wrong. Please try again.';
            }
            status.classList.add('is-error');
        } catch {
            status.textContent = 'Network error. Please check your connection and try again.';
            status.classList.add('is-error');
        } finally {
            button.disabled = false;
            label.textContent = original;
        }
    });
});

let errorSeq = 0;

function clearErrors(form) {
    form.querySelectorAll('.has-error').forEach((el) => el.classList.remove('has-error'));
    form.querySelectorAll('.field__error').forEach((el) => el.remove());
    form.querySelectorAll('[aria-invalid]').forEach((el) => el.removeAttribute('aria-invalid'));
}

function showErrors(form, errors) {
    let first = null;
    Object.entries(errors).forEach(([name, messages]) => {
        const input = form.elements[name];
        if (!input) return;
        const wrapper = input.closest('.field, .check');
        wrapper?.classList.add('has-error');
        input.setAttribute('aria-invalid', 'true');
        if (wrapper) {
            // Every error gets visible text (not just a color change): inside fields, after checkboxes.
            const msg = document.createElement('span');
            msg.className = 'field__error';
            msg.id = `${input.id || name}-error-${++errorSeq}`;
            msg.textContent = messages[0];
            input.setAttribute('aria-describedby', msg.id);
            if (wrapper.classList.contains('field')) wrapper.append(msg);
            else wrapper.after(msg);
        }
        first ??= input;
    });
    first?.focus();
}

function showSuccess(form, message) {
    const box = document.createElement('div');
    box.className = 'form-success';
    box.setAttribute('role', 'status');
    box.innerHTML = '<span class="icon-ring icon-ring--lg"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></span><p></p>';
    box.querySelector('p').textContent = message || 'Thank you!';
    form.replaceWith(box);
}
