<!-- FOOTER  -->
<div id="footer">
  <div class="container">
    <h1 class="main-title">
      COPYRIGHT © 2021 CRYSTAL AUDIO VISUAL SERVICES, INC. - ALL RIGHTS
      RESERVED
    </h1>
  </div>
</div>
<script src="public/js/jquery.js"></script>
<script src="public/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
    loop: true,
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".mySwiper2", {
    loop: true,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    thumbs: {
      swiper: swiper,
    },
  });
</script>
</body>

</html>