// ----- MENU BURGER -----
(function() {
  const navToggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navLinks.classList.toggle('show-nav');
    });
  }
})();

// ----- SLIDER (only if slider exists on the page) -----
(function() {
  const slider = document.querySelector('.slider');
  if (!slider) return; // no slider on this page

  let slideIndex = 0;
  const slidesWrapper = slider.querySelector(".slides");
  let slides = slider.querySelectorAll(".slides img");
  let dots = slider.querySelectorAll(".dot");
  const prevBtn = slider.querySelector(".prev");
  const nextBtn = slider.querySelector(".next");

  function render() {
    // Ensure proper transform for the visible slide
    slidesWrapper.style.transform = `translateX(${-slideIndex * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle("active", i === slideIndex));
  }

  function nextSlide() { slideIndex = (slideIndex + 1) % slides.length; render(); }
  function prevSlide() { slideIndex = (slideIndex - 1 + slides.length) % slides.length; render(); }

  if (nextBtn) nextBtn.addEventListener("click", nextSlide);
  if (prevBtn) prevBtn.addEventListener("click", prevSlide);
  dots.forEach((dot, i) => dot.addEventListener("click", () => { slideIndex = i; render(); }));

  // Auto play
  setInterval(nextSlide, 5000);

  // Initial
  render();
})();
