// BodyLuxa Modern JavaScript
class BodyLuxaApp {
    constructor() {
        this.init();
    }

    init() {
        this.initNavbar();
        this.initScrollEffects();
        this.initPriceCalculator();
        this.initContactForms();
        this.initAnimations();
        this.initUtilities();
        this.initServiceFilters();
        this.initTestimonialSlider();
    }

    // Navbar functionality
    initNavbar() {
        const navbar = document.querySelector('.navbar');
        const backToTop = document.getElementById('backToTop');

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar?.classList.add('scrolled');
            } else {
                navbar?.classList.remove('scrolled');
            }

            // Back to top button
            if (backToTop) {
                if (window.scrollY > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            }
        });

        // Back to top functionality
        backToTop?.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Mobile menu auto-close
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        const navbarCollapse = document.querySelector('.navbar-collapse');
        
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }
            });
        });
    }

    // Scroll effects and animations
    initScrollEffects() {
        // Parallax effect for hero section
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = heroSection.querySelector('.hero-content');
                if (parallax) {
                    parallax.style.transform = `translateY(${scrolled * 0.1}px)`;
                }
            });
        }

        // Reveal elements on scroll
        const revealElements = document.querySelectorAll('[data-reveal]');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        revealElements.forEach(el => revealObserver.observe(el));
    }

    // Price calculator functionality
    initPriceCalculator() {
        const calculators = document.querySelectorAll('[data-price-calculator]');
        
        calculators.forEach(calculator => {
            const serviceSelect = calculator.querySelector('[data-service-select]');
            const packageSelect = calculator.querySelector('[data-package-select]');
            const priceResult = calculator.querySelector('[data-price-result]');
            const finalPrice = calculator.querySelector('[data-final-price]');

            if (!serviceSelect || !packageSelect) return;

            const updatePrice = () => {
                const service = serviceSelect.options[serviceSelect.selectedIndex];
                const packageOption = packageSelect.options[packageSelect.selectedIndex];

                if (service.value && packageOption.value && service.dataset.price && packageOption.dataset.multiplier) {
                    const basePrice = parseInt(service.dataset.price);
                    const multiplier = parseFloat(packageOption.dataset.multiplier);
                    const total = Math.round(basePrice * multiplier);

                    if (finalPrice) {
                        finalPrice.textContent = total.toLocaleString();
                    }
                    
                    if (priceResult) {
                        priceResult.style.display = 'block';
                        priceResult.classList.add('animate__animated', 'animate__fadeIn');
                    }
                } else {
                    if (priceResult) {
                        priceResult.style.display = 'none';
                    }
                }
            };

            serviceSelect.addEventListener('change', updatePrice);
            packageSelect.addEventListener('change', updatePrice);
        });
    }

    // Contact forms with API integration
    initContactForms() {
        const forms = document.querySelectorAll('[data-api-form]');
        
        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formType = form.dataset.apiForm;
                const submitBtn = form.querySelector('[type="submit"]');
                const alertContainer = form.querySelector('[data-form-alert]');
                
                if (!submitBtn) return;

                // Get form data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Update button state
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = this.getLoadingButton(formType);

                try {
                    const response = await this.submitForm(formType, data);
                    
                    if (response.success) {
                        this.showAlert(alertContainer, 'success', response.message);
                        form.reset();
                        
                        // Hide price results if they exist
                        const priceResults = form.querySelectorAll('[data-price-result]');
                        priceResults.forEach(result => result.style.display = 'none');
                    } else {
                        this.showAlert(alertContainer, 'danger', response.message);
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    this.showAlert(alertContainer, 'danger', 'An error occurred. Please try again.');
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });
    }

    // Form submission helper
    async submitForm(type, data) {
        const endpoints = {
            'contact': '/api/contact',
            'quote': '/api/quote',
            'newsletter': '/api/newsletter'
        };

        const endpoint = endpoints[type];
        if (!endpoint) {
            throw new Error(`Unknown form type: ${type}`);
        }

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    // Loading button states
    getLoadingButton(type) {
        const loadingTexts = {
            'contact': '<i class="fas fa-spinner fa-spin me-2"></i>Sending...',
            'quote': '<i class="fas fa-spinner fa-spin me-2"></i>Processing...',
            'newsletter': '<i class="fas fa-spinner fa-spin me-2"></i>Subscribing...'
        };

        return loadingTexts[type] || '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    }

    // Alert helper
    showAlert(container, type, message) {
        if (!container) return;

        container.className = `alert alert-${type} alert-dismissible fade show`;
        container.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        container.style.display = 'block';

        // Auto hide after 5 seconds
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(container);
            alert?.close();
        }, 5000);
    }

    // Initialize animations
    initAnimations() {
        // Counter animation
        const counters = document.querySelectorAll('[data-counter]');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        });

        counters.forEach(counter => counterObserver.observe(counter));

        // Typing effect
        const typingElements = document.querySelectorAll('[data-typing]');
        typingElements.forEach(element => {
            this.initTypingEffect(element);
        });
    }

    // Counter animation
    animateCounter(element) {
        const target = parseInt(element.dataset.counter);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 16);
    }

    // Typing effect
    initTypingEffect(element) {
        const text = element.dataset.typing;
        const speed = parseInt(element.dataset.typingSpeed) || 100;
        let i = 0;

        element.textContent = '';
        
        const typeWriter = () => {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            }
        };

        // Start typing when element is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    typeWriter();
                    observer.unobserve(entry.target);
                }
            });
        });

        observer.observe(element);
    }

    // Service filters
    initServiceFilters() {
        const filterButtons = document.querySelectorAll('[data-filter]');
        const serviceCards = document.querySelectorAll('[data-category]');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                // Filter cards
                serviceCards.forEach(card => {
                    const category = card.dataset.category;
                    if (filter === 'all' || category === filter) {
                        card.style.display = 'block';
                        card.classList.add('animate__animated', 'animate__fadeIn');
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // Testimonial slider
    initTestimonialSlider() {
        const slider = document.querySelector('[data-testimonial-slider]');
        if (!slider) return;

        const slides = slider.querySelectorAll('.testimonial-slide');
        const prevBtn = slider.querySelector('[data-prev]');
        const nextBtn = slider.querySelector('[data-next]');
        const indicators = slider.querySelectorAll('[data-slide]');
        
        let currentSlide = 0;
        const totalSlides = slides.length;

        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });
        };

        const nextSlide = () => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        };

        const prevSlide = () => {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        };

        // Event listeners
        nextBtn?.addEventListener('click', nextSlide);
        prevBtn?.addEventListener('click', prevSlide);
        
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });

        // Auto-play
        setInterval(nextSlide, 5000);
    }

    // Utility functions
    initUtilities() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Account for fixed navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Lazy loading for images
        const lazyImages = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));

        // Newsletter subscription
        const newsletterForms = document.querySelectorAll('[data-newsletter]');
        newsletterForms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = form.querySelector('input[type="email"]').value;
                
                try {
                    const response = await this.submitForm('newsletter', { email });
                    if (response.success) {
                        this.showToast('success', response.message);
                        form.reset();
                    }
                } catch (error) {
                    this.showToast('error', 'Subscription failed. Please try again.');
                }
            });
        });
    }

    // Toast notifications
    showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        // Add to toast container or create one
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
}

// Initialize the app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new BodyLuxaApp();
});

// Export for potential module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BodyLuxaApp;
}