// Menu toggle logic
const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

// Check if ScrollReveal is defined
if (typeof ScrollReveal !== "undefined") {
  console.log("ScrollReveal loaded successfully!");

  const scrollRevealOption = {
    distance: "50px",
    origin: "bottom",
    duration: 1000,
  };

  ScrollReveal().reveal(".header__content h1", {
    ...scrollRevealOption,
    delay: 500,
  });
  ScrollReveal().reveal(".header__content h2", {
    ...scrollRevealOption,
    delay: 500,
  });
  ScrollReveal().reveal(".header__content p", {
    ...scrollRevealOption,
    delay: 1000,
  });
  ScrollReveal().reveal(".header__content .header__btn", {
    ...scrollRevealOption,
    delay: 1500,
  });

  ScrollReveal().reveal(".about__card", {
    duration: 1000,
    interval: 500,
  });

  ScrollReveal().reveal(".trainer__card", {
    ...scrollRevealOption,
    interval: 500,
  });

  ScrollReveal().reveal(".blog__card", {
    ...scrollRevealOption,
    interval: 500,
  });
} else {
  console.error("ScrollReveal is not defined. Please check the library load order.");
}

// Initialize Swiper
if (typeof Swiper !== "undefined") {
  const swiper = new Swiper(".swiper", {
    loop: true,
    pagination: {
      el: ".swiper-pagination",
    },
  });
} else {
  console.error("Swiper is not defined. Please check the library load order.");
}
