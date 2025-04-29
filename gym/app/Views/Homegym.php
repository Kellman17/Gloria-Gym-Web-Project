<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
  
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/css/Homegym.css">
    <title>GLORIA GYM</title>
  </head>
  <body>
    <nav>
      <div class="nav__bar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#"><img src="assets/Logo1.png" alt="logo" /></a>
          </div>
        </div>
        <ul class="nav__links" id="nav-links">
          <li><a href="#home" style="color: red">HOME</a></li>
          <li><a href="/about">ABOUT</a></li>
          <li><a href="/class">CLASS</a></li>
          <li><a href="/trainer">TRAINER</a></li>
          <li><a href="/instructor">INSTRUCTOR</a></li>
          <li><a href="/membership">MEMBERSHIP</a></li>
          <li><a href="/contact">CONTACT US</a></li>
          <li><a href="/Portal">LOGIN</a></li>
        </ul>
      </div>
    </nav>

    <header class="header" id="header">
      <div class="section__container header__container">
        <div class="header__content" style="padding-bottom: 90px;">
          <h1>HARD WORK</h1>
          <h2>IS FOR EVERY SUCCESS</h2>
          <p>Start by taking inspirations, continue it to give inspirations</p>
          
        </div>
      </div>
    </header>

   
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="Javascript/Script.js"></script>
    <script>
  document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper('.swiper', {
      loop: true, // Membuat slider loop
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      autoplay: {
        delay: 5000, // Waktu antar-slide (ms)
        disableOnInteraction: false, // Tetap autoplay meski user berinteraksi
      },
    });
  });
</script>
  </body>
</html>
