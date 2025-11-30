$(document).ready(function() {
    if ($('.owl-carousel').length > 0 && typeof $.fn.owlCarousel === 'function') {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplaySpeed: 1000,
            smartSpeed: 1000,
            slideBy: 1,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    }
});