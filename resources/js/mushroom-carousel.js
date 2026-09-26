document.querySelectorAll('[data-mushroom-carousel]').forEach((carousel) => {
    const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
    const previousButton = carousel.querySelector('[data-carousel-previous]');
    const nextButton = carousel.querySelector('[data-carousel-next]');
    const status = carousel.querySelector('[data-carousel-status]');
    let activeIndex = 0;

    const showSlide = (index) => {
        activeIndex = Math.max(0, Math.min(index, slides.length - 1));

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === activeIndex;
            slide.classList.toggle('hidden', !isActive);
            slide.setAttribute('aria-hidden', String(!isActive));
        });

        previousButton.disabled = activeIndex === 0;
        nextButton.disabled = activeIndex === slides.length - 1;
        status.textContent = `Slide ${activeIndex + 1} of ${slides.length}: ${slides[activeIndex].dataset.carouselName}`;
    };

    previousButton.addEventListener('click', () => showSlide(activeIndex - 1));
    nextButton.addEventListener('click', () => showSlide(activeIndex + 1));

    carousel.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            showSlide(activeIndex - 1);
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            showSlide(activeIndex + 1);
        }
    });

    showSlide(activeIndex);
});
