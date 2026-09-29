/*
 * Cursor companions: hummingbird (darts, points its beak at the cursor), mini moon and satellite (orbit the cursor),
 * comet (glowing head + particle tail) and a Galápagos blue-footed booby (glides, slow wing beats).
 * One rAF loop drives whichever is active; it sleeps when off, hidden, or after a long idle.
 */

const STORAGE_KEY = 'equinox-companion';
const KINDS = ['hummingbird', 'moon', 'satellite', 'comet', 'booby', 'off'];
const IDLE_SLEEP_MS = 45000;

const clamp = (v, lo, hi) => Math.min(hi, Math.max(lo, v));

export function initCompanion() {
    const layer = document.querySelector('[data-companion]');
    const buttons = [...document.querySelectorAll('[data-companion-set]')];
    const canRun = matchMedia('(hover: hover) and (pointer: fine)').matches
        && !matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!layer || !canRun) return;

    const els = Object.fromEntries([...layer.querySelectorAll('[data-bird]')].map((el) => [el.dataset.bird, el]));
    const orbitRing = layer.querySelector('[data-sat-orbit]');
    const sparks = [...layer.querySelectorAll('.companion__spark')];
    const cometDots = [...layer.querySelectorAll('.comet__dot')];

    const mouse = { x: innerWidth * 0.6, y: innerHeight * 0.5, seen: false, lastMove: 0, overLink: false };
    const pos = { x: innerWidth + 60, y: innerHeight * 0.3 };
    const vel = { x: 0, y: 0 };
    const s = { facing: 1, scaleX: 1, tilt: 0, theta: 0, wander: { x: 0, y: 0, until: 0 }, sparkAt: 0, sparkIdx: 0, vx: 0, vy: 0, flipAt: 0, wingRate: 1 };
    const trail = cometDots.map(() => ({ x: pos.x, y: pos.y }));
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

        satellite(now, dt) {
            // The orbit's center follows the cursor; the satellite circles it on a tilted ellipse.
            const speed = spring(mouse.x, mouse.y, 0.14, 0.68);
            s.theta += dt * 0.0022;
            const rx = 48;
            const ry = 18;
            const x = pos.x + Math.cos(s.theta) * rx;
            const y = pos.y + Math.sin(s.theta) * ry;
            const front = Math.sin(s.theta); // +1 nearest the viewer
            const heading = Math.atan2(Math.cos(s.theta) * ry, -Math.sin(s.theta) * rx) * 57.3;
            place(els.satellite, x, y, 31, 15.5, `rotate(${heading * 0.25}deg) scale(${0.82 + front * 0.18})`);
            els.satellite.style.zIndex = front > 0 ? 2 : 0;
            orbitRing.style.transform = `translate3d(${pos.x - rx}px, ${pos.y - ry}px, 0)`;
            return speed;
        },

        moon(now, dt) {
            // A little moon on a slightly tilted orbit; smaller and dimmer on the far side.
            const speed = spring(mouse.x, mouse.y, 0.16, 0.66);
            s.theta += dt * 0.0017;
            const front = Math.sin(s.theta);
            const x = pos.x + Math.cos(s.theta) * 34;
            const y = pos.y + Math.sin(s.theta) * 12 - Math.cos(s.theta) * 6;
            place(els.moon, x, y, 7, 7, `scale(${0.78 + front * 0.22})`);
            els.moon.style.opacity = els.moon.classList.contains('is-active') ? (0.72 + front * 0.28).toFixed(2) : '';
            return speed;
        },

        comet() {
            const speed = spring(mouse.x, mouse.y, 0.22, 0.6);
            // Each particle chases the one ahead of it, so the tail stretches with speed and folds when still.
            let lead = pos;
            trail.forEach((p, i) => {
                p.x += (lead.x - p.x) * 0.62;
                p.y += (lead.y - p.y) * 0.62;
                cometDots[i].style.transform = `translate3d(${p.x}px, ${p.y}px, 0)`;
                lead = p;
            });
            return speed;
        },

        booby(now) {
            // A seabird: trails just above the cursor; when the cursor rests it soars in slow, wide circles.
            // The target never depends on which way the bird faces, so turning can't feed back into motion.
            const idleFor = now - mouse.lastMove;
            const circling = idleFor > 1500 && idleFor < 30000;
            const t = now * 0.0008;
            const speed = spring(
                mouse.x + (circling ? Math.cos(t) * 70 : 0),
                mouse.y - 44 + (circling ? Math.sin(t) * 18 : 0),
                0.03, 0.8,
            );

            // Smoothed velocity: turn around only on sustained movement, at most once a second.
            s.vx = s.vx * 0.9 + vel.x * 0.1;
            s.vy = s.vy * 0.9 + vel.y * 0.1;
            if (now > s.flipAt && Math.abs(s.vx) > 1.1 && Math.sign(s.vx) !== s.facing) {
                s.facing = Math.sign(s.vx);
                s.flipAt = now + 1000;
            }
            s.scaleX += (s.facing - s.scaleX) * 0.22; // quick turn: the edge-on moment lasts only a few frames
            s.tilt += (clamp(s.vy * 4, -18, 18) * s.facing - s.tilt) * 0.08;

            // Wing beats speed up with effort and relax into a glide, continuously (no jumps).
            s.wingRate += (clamp(0.35 + speed * 0.12, 0.35, 1.1) - s.wingRate) * 0.05;
            els.booby.getAnimations({ subtree: true }).forEach((a) => { a.playbackRate = s.wingRate; });

            place(els.booby, pos.x, pos.y, 29, 19, `rotate(${s.tilt}deg) scaleX(${s.scaleX})`);
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
            const on = visible && name === kind;
            el.classList.toggle('is-active', on);
            if (!on) el.style.opacity = ''; // the moon dims itself inline; hand control back to CSS
        });
        orbitRing?.classList.toggle('is-active', visible && kind === 'satellite');
    }

    function setKind(next) {
        kind = KINDS.includes(next) ? next : 'hummingbird';
        buttons.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.companionSet === kind)));
        // Start the newcomer where the cursor is, so it doesn't fly in from across the screen.
        pos.x = mouse.x;
        pos.y = mouse.y;
        trail.forEach((p) => Object.assign(p, { x: mouse.x, y: mouse.y }));
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
