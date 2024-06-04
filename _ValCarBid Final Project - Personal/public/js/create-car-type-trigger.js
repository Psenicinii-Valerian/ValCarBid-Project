document.addEventListener("DOMContentLoaded", function () {
    const carTypeSelect = document.getElementById("type");
    const cylindersSelect = document.getElementById("cylinders");
    const fuelTypeSelect = document.getElementById("fuel-type");
    const transmissionTypeSelect = document.getElementById("transmission-type");
    const batteryCapacityInput = document.querySelector(
        'input[name="battery-capacity"]'
    );
    const displacementInput = document.querySelector(
        'input[name="displacement"]'
    );

    // Access oldInput data
    const oldFuelType = window.oldInput.fuelType;
    const oldCylinders = window.oldInput.cylinders;
    const oldTransmissionType = window.oldInput.transmissionType;

    const evOptions = {
        cylinders: [{ value: "null", label: "Not Any" }],
        fuelType: [{ value: "electricity", label: "Electricity" }],
        transmissionType: [
            { value: "automatic transmission", label: "Automatic" },
        ],
    };

    const iceOptions = {
        cylinders: [
            { value: "3", label: "3 Cylinders" },
            { value: "4", label: "4 Cylinders" },
            { value: "6", label: "6 Cylinders" },
            { value: "8", label: "8 Cylinders" },
            { value: "12", label: "12 Cylinders" },
        ],
        fuelType: [
            { value: "gasoline", label: "Gasoline" },
            { value: "diesel", label: "Diesel" },
            { value: "ethanol", label: "Ethanol" }, 
            { value: "hybrid", label: "Hybrid" },
        ],
        transmissionType: [
            { value: "manual transmission", label: "Manual" },
            { value: "automatic transmission", label: "Automatic" },
            { value: "cvt", label: "CVT" },
        ],
    };

    carTypeSelect.addEventListener("change", function () {
        const selectedCarType = carTypeSelect.value;

        // Reset all select options and input fields
        cylindersSelect.innerHTML = "";
        fuelTypeSelect.innerHTML = "";
        transmissionTypeSelect.innerHTML = "";

        // Disable both input fields initially
        displacementInput.setAttribute("disabled", "disabled");
        displacementInput.style.display = "none";

        batteryCapacityInput.setAttribute("disabled", "disabled");
        batteryCapacityInput.style.display = "none";

        // Determine which set of options to use based on the selected car type
        const options = selectedCarType === "ev" ? evOptions : iceOptions;

        // Populate the select elements with options
        for (const option of options.cylinders) {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.text = option.label;
            cylindersSelect.appendChild(optionElement);
        }

        for (const option of options.fuelType) {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.text = option.label;
            fuelTypeSelect.appendChild(optionElement);
        }

        for (const option of options.transmissionType) {
            const optionElement = document.createElement("option");
            optionElement.value = option.value;
            optionElement.text = option.label;
            transmissionTypeSelect.appendChild(optionElement);
        }

        // Show/hide input fields based on the selected car type
        if (selectedCarType === "ev") {
            batteryCapacityInput.style.display = "block";
            batteryCapacityInput.removeAttribute("disabled");
        } else if (selectedCarType === "ice") {
            displacementInput.style.display = "block";
            displacementInput.removeAttribute("disabled");
        }
    });

    // Trigger the change event initially to set the defaults based on the selected car type
    carTypeSelect.dispatchEvent(new Event("change"));

    if (oldFuelType) {
        fuelTypeSelect.value = oldFuelType;
    } 
    if (oldCylinders) {
        cylindersSelect.value = oldCylinders;
    } 

    if (oldTransmissionType ) {
        transmissionTypeSelect.value = oldTransmissionType ;
    } 
});
