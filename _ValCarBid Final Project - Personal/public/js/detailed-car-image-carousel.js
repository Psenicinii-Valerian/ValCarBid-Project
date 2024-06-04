// Function to initialize the image carousel
function initializeImageCarousel() {
    const imgElement = document.querySelector(".car-image img");
    const leftArrow = document.querySelector(".left-arrow");
    const rightArrow = document.querySelector(".right-arrow");
    const imagesData = imgElement.getAttribute("data-images");

    // Log the JSON data to the console for debugging
    console.log("JSON Data:", imagesData);

    const images = JSON.parse(imgElement.getAttribute("data-images"));
    let currentImageIndex = 0;

    function updateImage() {
        imgElement.src = images[currentImageIndex];
    }

    leftArrow.addEventListener("click", () => {
        currentImageIndex =
            (currentImageIndex - 1 + images.length) % images.length;
        updateImage();
    });

    rightArrow.addEventListener("click", () => {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        updateImage();
    });

    // Initial image display
    updateImage();
}

// Call the initialization function when the DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    initializeImageCarousel();
});
