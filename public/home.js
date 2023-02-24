$(document).ready(()=>{
    $('#hamburger-menu').click(()=>{
        $('#hamburger-menu').toggleClass('active')
        $('#nav-menu').toggleClass('active')
    })
let navText = ["<i class='bx bx-left-arrow-alt'></i>","<i class='bx bx-right-arrow-alt'></i>"]

    $('#hero-carousel').owlCarousel({
        items: 1,
        dots: false,
        loop: true,
        nav:true,
        navText: navText,
        autoplay: true,
        autoplayHoverPause: true
    })

    $('.movies-slide').owlCarousel({
        items: 2,
        dots: false,
        nav:true,
        navText: navText,
        margin: 15,
        responsive: {
            500: {
                items: 2
            },
            800: {
                items: 3
            },
            1150: {
                items: 4
            },
            1280: {
                items: 5
            },
            1600: {
                items: 5
            }
        }
    })
})