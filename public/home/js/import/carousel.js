$(document).ready(function () {
    // relative carousel btn event

    var carouselTranslateX = 0;
    var liWidth = $('.list-products li:first').width() + 7 // 5px (margin-left) + 2px (border)
    var ulWidth = $('ul.list-products').width()
    var countLi = $('.list-products li').length
    var maxTranslateX = liWidth * countLi - 5 - ulWidth;  // -5px margin-left of last li
    $('.relative-carousel-btn-next').click(function () {
        $('.relative-carousel-btn-back').css('display', 'flex')
        carouselTranslateX += liWidth * 4;
        if (carouselTranslateX >= maxTranslateX) {
            carouselTranslateX = maxTranslateX
            $('.relative-carousel-btn-next').css('display', 'none')
        }
        $('.detail-product-pg ul.list-products').css({ transform: 'translateX(-' + carouselTranslateX + 'px) translateZ(0px)', transition: 'all 0.5s' });
    })
    $('.relative-carousel-btn-back').click(function () {
        $('.relative-carousel-btn-next').css('display', 'flex')
        carouselTranslateX -= liWidth * 4;
        if (carouselTranslateX <= 0) {
            carouselTranslateX = 0
            $('.relative-carousel-btn-back').css('display', 'none')

        }
        $('.detail-product-pg ul.list-products').css({ transform: 'translateX(-' + carouselTranslateX + 'px) translateZ(0px)', transition: 'all 0.5s' });
    })

    // $('.relative-carousel-wp').scroll(function () {
    //     var scrollVal = $('.relative-carousel-wp li:first').position().left
    //     // console.log($('.relative-carousel-wp li:first').position().left)
    //     $(this).children('.relative-carousel-btn-back').css('left', -scrollVal)
    //     console.log(scrollVal);
    // })
})
