/*
 * Cursor companion: hummingbird (darts, points its beak at the cursor).
 * One rAF loop drives it; it sleeps when off, hidden, or after a long idle.
 */

const STORAGE_KEY = 'equinox-companion';
const KINDS = ['hummingbird', 'off'];
const IDLE_SLEEP_MS = 45000;

const clamp = (v, lo, hi) => Math.min(hi, Math.max(lo, v));

export function initCompanion() {
    const layer = document.querySelector('[data-companion]');
    const buttons = [...document.querySelectorAll('[data-companion-set]')];
    const canRun = matchMedia('(hover: hover) and (pointer: fine)').matches
        && !matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!layer || !canRun) return;

    const els = Object.fromEntries([...layer.querySelectorAll('[data-bird]')].map((el) => [el.dataset.bird, el]));
    const sparks = [...layer.querySelectorAll('.companion__spark')];

    const mouse = { x: innerWidth * 0.6, y: innerHeight * 0.5, seen: false, lastMove: 0, overLink: false };
    const pos = { x: innerWidth + 60, y: innerHeight * 0.3 };
    const vel = { x: 0, y: 0 };
    const s = { facing: 1, scaleX: 1, tilt: 0, wander: { x: 0, y: 0, until: 0 }, sparkAt: 0, sparkIdx: 0 };
    let kind = 'hummingbird';
    let running = false;
    let last = performance.now();

    try {
        const q = new URLSearchParams(location.search).get('bird');
        const stored = localStorage.getItem(STORAGE_KEY);
        kind = KINDS.includes(q) ? q : KINDS.includes(stored) ? stored : kind;
    } catch {}

    const spring = (tx, ty, k, damping) => {
        vel.x = (vel.x + (tx - pos.x) * k) * damping;
        vel.y = (vel.y + (ty - pos.y) * k) * damping;
        pos.x += vel.x;
        pos.y += vel.y;
        return Math.hypot(vel.x, vel.y);
    };

    const place = (el, x, y, ax, ay, extra = '') => {
        el.style.transform = `translate3d(${x - ax}px, ${y - ay}px, 0) ${extra}`;
    };

    /* ---------- Per-companion behaviour ---------- */
    const behaviours = {
        hummingbird(now) {
            const idleFor = now - mouse.lastMove;
            const idle = idleFor > 2200 && idleFor < 20000;
            if (idle && now > s.wander.until) {
                const a = Math.random() * Math.PI * 2;
                const d = 50 + Math.random() * 70;
                s.wander = { x: Math.cos(a) * d, y: Math.sin(a) * d * 0.6 - 16, until: now + 900 + Math.random() * 1400 };
            }
            const w = idle ? s.wander : { x: 0, y: 0 };
            const gap = (mouse.overLink ? -3 : -9) * s.facing;
            const speed = spring(mouse.x + gap + w.x, mouse.y - 3 + w.y, 0.085, 0.74);

            if (Math.abs(vel.x) > 4) s.facing = Math.sign(vel.x);
            else if (mouse.x > pos.x + 30) s.facing = 1;
            else if (mouse.x < pos.x - 30) s.facing = -1;
            s.scaleX += (s.facing - s.scaleX) * 0.28;
            s.tilt += (clamp(vel.y * 2.4, -30, 30) * s.facing - s.tilt) * 0.2;
            const bob = Math.sin(now * 0.006) * 2;
            place(els.hummingbird, pos.x, pos.y + bob, 67, 15, `rotate(${s.tilt}deg) scaleX(${s.scaleX})`);

            if (speed > 7 && now - s.sparkAt > 45) {
                s.sparkAt = now;
                const sp = sparks[s.sparkIdx++ % sparks.length];
                sp.classList.remove('is-live');
                sp.style.setProperty('--sx', `${pos.x - 28 * s.facing + (Math.random() - 0.5) * 8}px`);
                sp.style.setProperty('--sy', `${pos.y + 14 + (Math.random() - 0.5) * 8}px`);
                void sp.offsetWidth; // restart the animation
                sp.classList.add('is-live');
            }
            return speed;
        },
    };

    function tick(now) {
        if (kind === 'off' || document.hidden) {
            running = false;
            return;
        }
        const dt = Math.min(64, now - last);
        last = now;
        const speed = behaviours[kind](now, dt);
        if (speed < 0.05 && now - mouse.lastMove > IDLE_SLEEP_MS) {
            running = false;
            return;
        }
        requestAnimationFrame(tick);
    }

    function wake() {
        if (!running && kind !== 'off' && mouse.seen && !document.hidden) {
            running = true;
            last = performance.now();
            requestAnimationFrame(tick);
        }
    }

    function show(visible) {
        Object.entries(els).forEach(([name, el]) => {
            el.classList.toggle('is-active', visible && name === kind);
        });
    }

    function setKind(next) {
        kind = KINDS.includes(next) ? next : 'hummingbird';
        buttons.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.companionSet === kind)));
        // Start the newcomer where the cursor is, so it doesn't fly in from across the screen.
        pos.x = mouse.x;
        pos.y = mouse.y;
        show(mouse.seen);
        try {
            localStorage.setItem(STORAGE_KEY, kind);
        } catch {}
        wake();
    }

    document.addEventListener('pointermove', (e) => {
        if (e.pointerType !== 'mouse') return;
        mouse.x = e.clientX;
        mouse.y = e.clientY;
        mouse.lastMove = performance.now();
        mouse.overLink = !!e.target.closest?.('a, button, input, select, textarea, label');
        if (!mouse.seen) {
            mouse.seen = true;
            pos.x = mouse.x + 120;
            pos.y = mouse.y - 60;
            show(true);
        }
        wake();
    }, { passive: true });

    document.documentElement.addEventListener('mouseleave', () => show(false));
    document.documentElement.addEventListener('mouseenter', () => mouse.seen && show(true));
    document.addEventListener('visibilitychange', wake);

    buttons.forEach((b) => b.addEventListener('click', () => setKind(b.dataset.companionSet)));
    setKind(kind);
}
