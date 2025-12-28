// DOM Elements
const header = document.querySelector('.header');

// Scroll Event for Sticky Header
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Mobile Menu Toggle
const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
const nav = document.querySelector('.nav');

mobileMenuBtn.addEventListener('click', () => {
    nav.classList.toggle('active');
    // Simple toggle styling (needs CSS support if not 'display:none' based)
    if (nav.classList.contains('active')) {
        nav.style.display = 'block';
        nav.style.position = 'absolute';
        nav.style.top = '80px';
        nav.style.left = '0';
        nav.style.width = '100%';
        nav.style.background = 'white';
        nav.style.padding = '20px';
        nav.style.boxShadow = '0 5px 10px rgba(0,0,0,0.1)';
        nav.style.textAlign = 'center';
    } else {
        nav.style.display = ''; // Revert to css default
    }
});

// Slider Logic
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
let slideInterval;

function initSlider() {
    // Show first slide
    if (slides.length > 0) slides[0].classList.add('active');
    // Auto-advance
    startSlideTimer();
}

function moveSlide(direction) {
    // Clean up current
    slides[currentSlide].classList.remove('active');

    // Calculate next
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;

    // Show next
    slides[currentSlide].classList.add('active');

    // Reset timer on manual interaction
    resetSlideTimer();
}

function startSlideTimer() {
    slideInterval = setInterval(() => {
        moveSlide(1);
    }, 5000); // 5 seconds
}

function resetSlideTimer() {
    clearInterval(slideInterval);
    startSlideTimer();
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    initSlider();
});
