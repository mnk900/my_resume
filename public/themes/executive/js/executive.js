function initExecutiveTheme() {
    // 1. Initialize AOS Scroll Animations if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            once: true,
            duration: 750,
            offset: 40,
            easing: 'ease-out-cubic'
        });
    }

    const sectionPanels = document.querySelectorAll('.exec-section-panel');

    function activateSection(targetId) {
        if (!targetId) return;

        // 1. Hide all section panels
        sectionPanels.forEach(panel => {
            panel.style.display = 'none';
            panel.classList.add('hidden');
            panel.classList.remove('block');
        });

        // 2. Show active target panel
        const targetPanel = document.getElementById(targetId);
        if (targetPanel) {
            targetPanel.style.display = 'block';
            targetPanel.classList.remove('hidden');
            targetPanel.classList.add('block');

            // Refresh AOS animations for newly displayed panel
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }

            // Smooth scroll to top of right content column
            const rightCol = document.getElementById('execRightColumn');
            if (rightCol) {
                const navHeight = 80;
                const elementPosition = rightCol.getBoundingClientRect().top + window.pageYOffset;
                window.scrollTo({
                    top: Math.max(0, elementPosition - navHeight),
                    behavior: 'smooth'
                });
            }
        }

        // 3. Highlight matching tabs & un-highlight non-matching tabs
        const allTabs = document.querySelectorAll('.exec-nav-tab');
        allTabs.forEach(tab => {
            const tabTarget = tab.getAttribute('data-target');
            const indicator = tab.querySelector('.exec-indicator');
            
            if (tabTarget === targetId) {
                tab.classList.add('text-white', 'translate-x-2');
                tab.classList.remove('text-slate-400');
                if (indicator) indicator.classList.remove('opacity-0');
            } else {
                tab.classList.remove('text-white', 'translate-x-2');
                tab.classList.add('text-slate-400');
                if (indicator) indicator.classList.add('opacity-0');
            }
        });
    }

    // Set initial visible state on page load
    if (sectionPanels.length > 0) {
        sectionPanels.forEach(panel => {
            if (panel.id === 'about') {
                panel.style.display = 'block';
                panel.classList.remove('hidden');
                panel.classList.add('block');
            } else {
                panel.style.display = 'none';
                panel.classList.add('hidden');
                panel.classList.remove('block');
            }
        });
        activateSection('about');
    }

    // Global Delegated Click Listener for Navigation Tabs (Guarantees click handling)
    document.addEventListener('click', (e) => {
        const tabLink = e.target.closest('.exec-nav-tab');
        if (tabLink) {
            e.preventDefault();
            const targetId = tabLink.getAttribute('data-target');
            if (targetId) {
                activateSection(targetId);
            }
        }
    });

    // Project Category Filter Engine
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.project-filter-btn');
        if (btn) {
            const filter = btn.getAttribute('data-filter');
            const filterButtons = document.querySelectorAll('.project-filter-btn');
            const projectCards = document.querySelectorAll('.editorial-project-card');

            filterButtons.forEach(b => {
                b.classList.remove('bg-white', 'text-slate-950', 'font-bold');
                b.classList.add('bg-white/5', 'text-slate-300', 'border', 'border-white/10');
            });

            btn.classList.add('bg-white', 'text-slate-950', 'font-bold');
            btn.classList.remove('bg-white/5', 'text-slate-300', 'border', 'border-white/10');

            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
}

// Execute immediately if DOM is ready, or on DOMContentLoaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initExecutiveTheme);
} else {
    initExecutiveTheme();
}
