<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Gloria Gym</title>
    <link rel="stylesheet" href="/css/Homegym.css">
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="/"><img src="/assets/Logo1.png" alt="logo"></a>
                </div>
            </div>
            <ul class="nav__links">
                <li><a href="/">HOME</a></li>
                <li><a href="/about" style="color: red">ABOUT</a></li>
                <li><a href="/class">CLASS</a></li>
                <li><a href="/trainer">TRAINER</a></li>
                <li><a href="/instructor">INSTRUCTOR</a></li>
                <li><a href="/membership">MEMBERSHIP</a></li>
                <li><a href="/contact">CONTACT US</a></li>
                <li><a href="/Portal">LOGIN</a></li>
            </ul>
        </div>
    </nav>


    <!-- About Section -->
    <section class="section__container about__container">
        <div class="about__header">
            <h2 class="section__header">ABOUT US</h2>
            <p class="section__description">
                Our mission is to inspire and support individuals in achieving their
                health and wellness goals, regardless of their fitness level or background.
            </p>
        </div>
        <div class="about__grid">
            <div class="about__card">
                <h4>WINNER COACHES</h4>
                <p>
                    We pride ourselves on having a team of dedicated and experienced
                    coaches who are committed to helping you succeed.
                </p>
            </div>
            <div class="about__card">
                <h4>AFFORDABLE PRICE</h4>
                <p>
                    We believe that everyone should have access to high-quality fitness
                    facilities without breaking the bank.
                </p>
            </div>
            <div class="about__card">
                <h4>MODERN EQUIPMENTS</h4>
                <p>
                    Stay ahead of the curve with our state-of-the-art equipment designed
                    to elevate your workout experience.
                </p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section__container client__container" id="client">
      <h2 class="section__header">OUR TESTIMONIALS</h2>
      <!-- Slider main container -->
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <div class="swiper-slide">
            <div class="client__card">
              <img src="assets/client-1.jpg" alt="client" />
              <div><i class="ri-double-quotes-r"></i></div>
              <p>
                I've been a member at FitPhysique for over a year now, and I
                couldn't be happier with my experience. The range of classes
                offered here is impressive - from high-energy cardio sessions to
                relaxing yoga classes, there's something for everyone.
              </p>
              <h4>Sarah Johnson</h4>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <img src="assets/client-2.jpg" alt="client" />
              <div><i class="ri-double-quotes-r"></i></div>
              <p>
                The classes are always well-planned and engaging, and the
                instructors do an excellent job of keeping us motivated
                throughout. I'm so grateful to have found such a supportive and
                inclusive gym.
              </p>
              <h4>Michael Wong</h4>
            </div>
          </div>
          <div class="swiper-slide">
            <div class="client__card">
              <img src="assets/client-3.jpg" alt="client" />
              <div><i class="ri-double-quotes-r"></i></div>
              <p>
                I've tried many gyms in the past, but none of them compare to
                FitPhysique. From the moment I walked through the doors, I felt
                welcomed and supported by the staff and fellow members alike.
              </p>
              <h4>Emily Davis</h4>
            </div>
          </div>
        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
      </div>
    </section>

    <!-- Blogs Section -->
    <section class="blog" id="blog">
      <div class="section__container blog__container">
        <h2 class="section__header">BLOGS</h2>
        <div class="blog__grid">
          <div class="blog__card">
            <img src="assets/blog-1.jpg" alt="blog" />
            <h4>Fueling Your Body for Optimal Performance</h4>
          </div>
          <div class="blog__card">
            <img src="assets/blog-2.jpg" alt="blog" />
            <h4>A Guide to Setting and Achieving Fitness Goals</h4>
          </div>
          <div class="blog__card">
            <img src="assets/blog-3.jpg" alt="blog" />
            <h4>Tips and Techniques for Efficient Exercise</h4>
          </div>
          <div class="blog__card">
            <img src="assets/blog-4.jpg" alt="blog" />
            <h4>A Beginner's Guide to Starting Your Running Journey</h4>
          </div>
        </div>
      </div>
    </section>

    <!-- Scripts -->
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
