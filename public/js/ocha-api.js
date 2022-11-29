/**
 * OCHA API custom JS
 */
(function iife() {
  const jumpLinks = document.querySelectorAll('.nav--in-page__link a, a[href="#main-content"]');
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches === false) {
    jumpLinks.forEach(function (link) {
      link.addEventListener('click', function (ev) {
        ev.preventDefault();
        var linkTarget = '#' + link.getAttribute('href').split('#')[1];
        document.querySelector(linkTarget).scrollIntoView({behavior: 'smooth'});
      });
    });
  }
})();
