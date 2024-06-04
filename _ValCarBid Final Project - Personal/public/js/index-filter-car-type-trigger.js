// Get references to the select dropdowns and the filter options div
const typeSelect = document.getElementById("type-filter");
const fuelTypeSelect = document.getElementById("fuel-type-filter");
const displacementFilter = document.querySelector(".displacement-filter");
const transmissionTypeSelect = document.getElementById("transmission-type-filter"); 
const batteryCapacityFilter = document.querySelector(".battery-capacity-filter");

const transmissionCurrentValue = transmissionTypeSelect.value;
const fuelCurrentValue = fuelTypeSelect.value;

// Define the options for each type
const typeOptions = {
    ev: {
        transmission: [
            { value: "any", label: "Any" },
            { value: "automatic transmission", label: "Automatic" },
        ],
        fuel: [
            { value: "any", label: "Any" },
            { value: "electricity", label: "Electricity" },
        ],
        hideDisplacement: true, // Hide displacement filter for EV
        hideBatteryCapacity: false, // Show battery capacity filter for EV
    },
    ice: {
        transmission: [
            { value: "any", label: "Any" },
            { value: "automatic transmission", label: "Automatic" },
            { value: "manual transmission", label: "Manual" },
            { value: "cvt", label: "CVT" },
        ],
        fuel: [
            { value: "any", label: "Any" },
            { value: "gasoline", label: "Gasoline" },
            { value: "diesel", label: "Diesel" },
            { value: "ethanol", label: "Ethanol" },
            { value: "hybrid", label: "Hybrid" },
        ],
        hideDisplacement: false, // Show displacement filter for ICE
        hideBatteryCapacity: true, // Hide battery capacity filter for ICE
    },
    any: {
        transmission: [
            { value: "any", label: "Any" },
            { value: "automatic transmission", label: "Automatic" },
            { value: "manual transmission", label: "Manual" },
            { value: "cvt", label: "CVT" },
        ],
        fuel: [
            { value: "any", label: "Any" },
            { value: "gasoline", label: "Gasoline" },
            { value: "diesel", label: "Diesel" },
            { value: "ethanol", label: "Ethanol" },
            { value: "hybrid", label: "Hybrid" },
            { value: "electricity", label: "Electricity" },
        ],
        hideDisplacement: false, // Show displacement filter for "any"
        hideBatteryCapacity: false, // Show battery capacity filter for "any"
    },
};

// Function to update the options of a select element
function updateSelectOptions(selectElement, options) {
    // Clear existing options
    selectElement.innerHTML = '';

    // Add new options
    for (const option of options) {
        const optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.text = option.label;
        selectElement.appendChild(optionElement);
    }
}

// Function to update the options of the transmission type select
function updateTransmissionOptions(selectedType) {
    const transmissionOptions = typeOptions[selectedType].transmission;
    updateSelectOptions(transmissionTypeSelect, transmissionOptions);
}

// Function to update the options of the fuel type select
function updateFuelOptions(selectedType) {
    const fuelOptions = typeOptions[selectedType].fuel;
    updateSelectOptions(fuelTypeSelect, fuelOptions);
}

// Function to hide or show a filter element based on the "hidden" parameter
function toggleFilterVisibility(filterElement, hidden) {
    if (hidden) {
        filterElement.style.display = "none";
    } else {
        filterElement.style.display = "flex";
    }
}

// Add event listener for changes in the car type select element
typeSelect.addEventListener("change", function () {
    const selectedType = typeSelect.value;
    if (selectedType in typeOptions) {
        // Update fuel and transmission options
        updateFuelOptions(selectedType);
        updateTransmissionOptions(selectedType);
        // Toggle filter visibility
        toggleFilterVisibility(displacementFilter, typeOptions[selectedType].hideDisplacement);
        toggleFilterVisibility(batteryCapacityFilter, typeOptions[selectedType].hideBatteryCapacity);
    }
});

// Initial update based on the default selected type
const initialSelectedType = typeSelect.value;
if (initialSelectedType in typeOptions) {
    updateFuelOptions(initialSelectedType);
    updateTransmissionOptions(initialSelectedType);
    toggleFilterVisibility(displacementFilter, typeOptions[initialSelectedType].hideDisplacement);
    toggleFilterVisibility(batteryCapacityFilter, typeOptions[initialSelectedType].hideBatteryCapacity);
}

// Function to set the selected option for a select element based on its value
function setSelectedOption(selectElement, value) {
    for (const option of selectElement.options) {
        if (option.value === value) {
            option.selected = true;
            break;
        }
    }
}

// Update the selected options for fuelTypeSelect and transmissionTypeSelect
setSelectedOption(fuelTypeSelect, fuelCurrentValue);
setSelectedOption(transmissionTypeSelect, transmissionCurrentValue);