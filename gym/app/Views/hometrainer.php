<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class | Gloria Gym</title>
    <link rel="stylesheet" href="/css/Homegym.css">
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="/"><img src="/assets/Logo1.png" alt="logo"></a>
                </div>
            </div>
            <ul class="nav__links">
                <li><a href="/">HOME</a></li>
                <li><a href="/about">ABOUT</a></li>
                <li><a href="/class">CLASS</a></li>
                <li><a href="/trainer" style="color: red">TRAINER</a></li>
                <li><a href="/instructor">INSTRUCTOR</a></li>
                <li><a href="/membership">MEMBERSHIP</a></li>
                <li><a href="/contact">CONTACT US</a></li>
                <li><a href="/Portal">LOGIN</a></li>
            </ul>
        </div>
    </nav>

    <section class="section__container trainer__container" id="trainer">
    <h2 class="section__header" style="margin-bottom: 10px;">MEET OUR TRAINERS</h2>
    <div class="trainer__grid">
        <?php foreach ($trainers as $trainer): ?>
            <div class="trainer__card">
                <img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']) ?>" alt="<?= $trainer['Nama_PT'] ?>" />
                <h4><?= strtoupper($trainer['Nama_PT']) ?></h4>
                <p><?= $trainer['Spesialisasi'] ?> Coach</p>
                <p><?= number_format($trainer['Rating'], 1, ',', '.') ?> 
                  <?php 
                      $fullStars = floor($trainer['Rating']); // Bintang penuh
                      $halfStar = ($trainer['Rating'] - $fullStars >= 0.5) ? true : false; // Bintang setengah

                      // Menampilkan bintang penuh
                      for ($i = 0; $i < $fullStars; $i++) {
                          echo '<i class="fas fa-star" style="color: yellow;"></i>';
                      }

                      // Menampilkan bintang setengah
                      if ($halfStar) {
                          echo '<i class="fas fa-star-half-alt" style="color: yellow;"></i>';
                      }
                  ?>
                </p>
                <div class="trainer__socials">
                    <!-- Placeholder Icon Media Sosial -->
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                    <a href="#"><i class="ri-twitter-fill"></i></a>
                    <a href="#"><i class="ri-youtube-fill"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>
</body>
</html>
