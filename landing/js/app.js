function windowScroll() {
    const navbar = document.getElementById("navbar");
    if (
        document.body.scrollTop >= 50 ||
        document.documentElement.scrollTop >= 50
    ) {
        navbar.classList.add("nav-sticky");
    } else {
        navbar.classList.remove("nav-sticky");
    }
}

window.addEventListener('scroll', (ev) => {
    ev.preventDefault();
    windowScroll();
});

// var preloader = document.getElementById("preloader");
// window.addEventListener("load", function () {
//     preloader.style.opacity = "0";
//     preloader.style.visibility = "hidden";
// });

const swiper = new Swiper('.client-swiper', {
    loop: true,
    spaceBetween: 30,
    centeredSlides: true,
    grabCursor: true,

    slidesPerView: 3.2,

    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        576: {
            slidesPerView: 1.6,
        },
        768: {
            slidesPerView: 2.3,
        },
        992: {
            slidesPerView: 3.3,
        }
    }
});

new WOW({
    offset: 80,
    mobile: true
}).init();


