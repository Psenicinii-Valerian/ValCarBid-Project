// Get the file input element
const fileInput = document.getElementById("file-images");
const mainImageInput = document.getElementById("file-main-image");

// Get the custom file input container
const imagesContainer = document.querySelector(".images");

// Get the custom file input container
const mainImageContainer = document.querySelector(".main-image");

// Get the label element
const labelElement = document.getElementById("file-label");
const mainImageLabel = document.getElementById("main-image-label");

// Function to update the label text
function updateLabelText() {
    const selectedFilesCount = fileInput.files.length;
    if (selectedFilesCount > 0) {
        labelElement.textContent = `Successfully added ${selectedFilesCount} ${
            selectedFilesCount === 1 ? "image!" : "images!"
        }`;
    } else {
        labelElement.textContent = "Choose your car images";
    }
}

// Function to update the main image label text
function updateMainImageLabelText() {
    const mainImageSelected = mainImageInput.files.length > 0;
    if (mainImageSelected) {
        mainImageLabel.textContent = "Main image selected!";
    } else {
        mainImageLabel.textContent = "Choose your car's main image";
    }
}

// Add a click event listener to the custom file input container
imagesContainer.addEventListener("click", function (event) {
    if (event.target !== fileInput && event.target.tagName !== "LABEL") {
        fileInput.click(); // Trigger the file input when the container is clicked
    }
});

// Add a click event listener to the custom main image input container
mainImageContainer.addEventListener("click", function (event) {
    if (event.target !== mainImageInput && event.target.tagName !== "LABEL") {
        mainImageInput.click(); // Trigger the main image input when the container is clicked
    }
});

// Add a change event listener to update the label text when files are selected
fileInput.addEventListener("change", updateLabelText);

// Add a change event listener to update the main image label text when the file is selected
mainImageInput.addEventListener("change", updateMainImageLabelText);

// Initial label text
updateLabelText();

// Initial main image label text
updateMainImageLabelText();
