document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && window.location.pathname.includes(href.replace('/', ''))) {
            link.classList.add('active');
        }
    });

    const scrollButtons = document.querySelectorAll('[data-scroll-to]');
    scrollButtons.forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const targetId = button.dataset.scrollTo;
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
