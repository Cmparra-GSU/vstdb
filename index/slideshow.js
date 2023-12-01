let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("carousel-slide");
    let dots = document.getElementsByClassName("dot");
    if (n > slides.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = slides.length; }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active-dot", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active-dot";
}

// Event delegation for carousel buttons
document.addEventListener('click', function(event) {
    if (event.target.matches('.prev-btn')) {
        plusSlides(-1);
    } else if (event.target.matches('.next-btn')) {
        plusSlides(1);
    }
});

// Add this code to hide the carousel controls if there's only one slide
let slides = document.getElementsByClassName("carousel-slide");
let carouselControls = document.querySelector(".carousel-controls");
if (slides.length <= 1) {
    carouselControls.style.display = "none";
}
