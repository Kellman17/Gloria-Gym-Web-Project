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
                <li><a href="/trainer">TRAINER</a></li>
                <li><a href="/instructor" style="color: red">INSTRUCTOR</a></li>
                <li><a href="/membership">MEMBERSHIP</a></li>
                <li><a href="/contact">CONTACT US</a></li>
                <li><a href="/Portal">LOGIN</a></li>
            </ul>
        </div>
    </nav>

    <section class="section__container trainer__container" id="instruktur">
    <h2 class="section__header" style="margin-bottom: 10px;">MEET OUR INSTRUCTORS</h2>
    <div class="trainer__grid">
        <?php foreach ($instrukturs as $instruktur): ?>
            <div class="trainer__card">
                <img src="<?= base_url('uploads/instruktur_photos/' . $instruktur['Foto']) ?>" />
                <h4><?= strtoupper($instruktur['Nama_Instruktur']) ?></h4>
                <p><?= $instruktur['Spesialisasi'] ?> Coach</p>
    
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
