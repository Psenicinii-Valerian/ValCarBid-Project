const crashesSelect = document.getElementById("crashes");
const crashDescriptionInput = document.getElementById("crash-description");
const oldCrashDescription = window.oldInput.crashDescription;

// Define a function to handle the toggling of the input field
function toggleCrashDescriptionInput() {
    const crashesSelect = document.getElementById("crashes");
    const crashDescriptionInput = document.getElementById("crash-description");

    if (crashesSelect.value === "1") {
        crashDescriptionInput.style.display = "block";
        crashDescriptionInput.removeAttribute("disabled");
        crashDescriptionInput.value = oldCrashDescription;
    } else {
        crashDescriptionInput.style.display = "none";
        crashDescriptionInput.value = "null";
        crashDescriptionInput.setAttribute("disabled", "disabled");
    }
}

crashesSelect.addEventListener("change", toggleCrashDescriptionInput);

// Call the toggleCrashDescriptionInput function when the page is loaded or reloaded
window.addEventListener("load", toggleCrashDescriptionInput);