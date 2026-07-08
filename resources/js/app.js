document.documentElement.dataset.js = 'enabled';

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const setupSidebar = () => {
    const root = document.documentElement;
    const openButtons = document.querySelectorAll('[data-sidebar-toggle]');
    const closeButtons = document.querySelectorAll('[data-sidebar-close]');

    const closeSidebar = () => {
        root.dataset.sidebar = 'closed';
    };

    const openSidebar = () => {
        root.dataset.sidebar = 'open';
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', openSidebar);
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', closeSidebar);
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    const mediaQuery = window.matchMedia('(min-width: 768px)');
    const syncSidebar = () => {
        if (mediaQuery.matches) {
            closeSidebar();
        }
    };

    syncSidebar();
    mediaQuery.addEventListener('change', syncSidebar);
};

const setupPasswordToggles = () => {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const targetId = button.dataset.passwordToggle;
        const input = document.getElementById(targetId);

        if (!input) {
            return;
        }

        button.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? 'Masquer' : 'Afficher';
        });
    });
};

const setupFlashDismiss = () => {
    document.querySelectorAll('[data-flash]').forEach((flash) => {
        const closeButton = flash.querySelector('[data-flash-close]');

        if (!closeButton) {
            return;
        }

        closeButton.addEventListener('click', () => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-8px)';
            flash.style.transition = 'opacity 180ms ease, transform 180ms ease';

            window.setTimeout(() => {
                flash.remove();
            }, 200);
        });
    });
};

const setupRevealAnimations = () => {
    if (prefersReducedMotion) {
        document.querySelectorAll('[data-reveal]').forEach((element) => {
            element.classList.add('is-visible');
        });

        return;
    }

    const elements = Array.from(document.querySelectorAll('[data-reveal]'));

    elements.forEach((element, index) => {
        element.style.transitionDelay = `${Math.min(index * 45, 220)}ms`;
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.18,
        }
    );

    elements.forEach((element) => observer.observe(element));
};

const animateCounter = (element) => {
    const target = Number(element.dataset.counter ?? '0');

    if (!Number.isFinite(target)) {
        return;
    }

    if (prefersReducedMotion) {
        element.textContent = new Intl.NumberFormat('fr-FR').format(target);

        return;
    }

    const duration = 900;
    const start = performance.now();

    const step = (timestamp) => {
        const progress = Math.min((timestamp - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(target * eased);

        element.textContent = new Intl.NumberFormat('fr-FR').format(value);

        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };

    window.requestAnimationFrame(step);
};

const setupCounters = () => {
    const counters = document.querySelectorAll('[data-counter]');

    if (!counters.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting || entry.target.dataset.counted === 'true') {
                    return;
                }

                entry.target.dataset.counted = 'true';
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        },
        {
            threshold: 0.4,
        }
    );

    counters.forEach((counter) => observer.observe(counter));
};

const setupTableSearch = () => {
    document.querySelectorAll('[data-table-search]').forEach((input) => {
        const scope = input.closest('[data-filter-scope]');

        if (!scope) {
            return;
        }

        const rows = Array.from(scope.querySelectorAll('[data-filterable-row]'));
        const emptyState = scope.querySelector('[data-filter-empty]');

        const applyFilter = () => {
            const query = input.value.trim().toLowerCase();
            let visibleCount = 0;

            rows.forEach((row) => {
                const text = (row.dataset.search ?? row.textContent ?? '').toLowerCase();
                const matches = query === '' || text.includes(query);

                row.hidden = !matches;

                if (matches) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.hidden = visibleCount > 0;
            }
        };

        input.addEventListener('input', applyFilter);
        applyFilter();
    });
};

const setupSplashScreen = () => {
    const splash = document.getElementById('app-splash');

    if (!splash) {
        return;
    }

    const storageKey = 'schoolgood_splash_seen';
    const seenThisSession = sessionStorage.getItem(storageKey) === '1';

    if (seenThisSession) {
        splash.remove();

        return;
    }

    sessionStorage.setItem(storageKey, '1');

    const finish = () => {
        splash.classList.add('is-done');
        splash.setAttribute('aria-hidden', 'true');

        window.setTimeout(() => {
            splash.remove();
        }, 600);
    };

    const minDisplay = prefersReducedMotion ? 400 : 2200;

    window.setTimeout(finish, minDisplay);
};

const setupThemeToggle = () => {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    const root = document.documentElement;

    const applyTheme = (dark) => {
        if (dark) {
            root.dataset.theme = 'dark';
            localStorage.setItem('sg-theme', 'dark');
        } else {
            delete root.dataset.theme;
            localStorage.removeItem('sg-theme');
        }
        btn.querySelector('[data-sun]').style.display  = dark ? '' : 'none';
        btn.querySelector('[data-moon]').style.display = dark ? 'none' : '';
        btn.setAttribute('aria-label', dark ? 'Passer en mode clair' : 'Passer en mode sombre');
    };

    const isDark = root.dataset.theme === 'dark';
    applyTheme(isDark);

    btn.addEventListener('click', () => applyTheme(root.dataset.theme !== 'dark'));
};

setupSplashScreen();
setupSidebar();
setupPasswordToggles();
setupFlashDismiss();
setupRevealAnimations();
setupCounters();
setupTableSearch();
setupThemeToggle();

// Import API modules for integration-demo
import('./api/elevePhoto.js').then(module => {
    window.setupPhotoPreview = module.setupPhotoPreview;
    window.submitPhotoForm = module.submitPhotoForm;
});

import('./api/parentLink.js').then(module => {
    window.setupParentAutocomplete = module.setupParentAutocomplete;
    window.setupEleveAutocomplete = module.setupEleveAutocomplete;
    window.setupLinkParentEleve = module.setupLinkParentEleve;
});

import('./api/printLists.js').then(module => {
    window.setupPrintControls = module.setupPrintControls;
});
