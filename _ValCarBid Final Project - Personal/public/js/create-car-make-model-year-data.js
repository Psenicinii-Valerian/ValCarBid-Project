$(document).ready(function () {
    // Variables to store selected values
    var selectedMake = '';
    var selectedModel = '';
    var selectedYear = '';

    // Function to fetch and populate the dropdown options
    function fetchDropdownOptions() {
        // Determine which dropdowns have changed
        var makeDropdown = $('#make');
        var modelDropdown = $('#model');
        var yearDropdown = $('#year');

        // Store selected values
        selectedMake = makeDropdown.val();
        selectedModel = modelDropdown.val();
        selectedYear = yearDropdown.val();

        // Fetch and populate the dropdowns based on all selected values
        $.ajax({
            url: '/filter-cars',
            type: 'GET',
            data: {
                make: selectedMake,
                model: selectedModel,
                year: selectedYear,
            },
            success: function (data) {
                // Populate the "Make" dropdown with available makes
                makeDropdown.empty().append('<option value="">Select Make</option>');
                $.each(data.makes, function (index, make) {
                    makeDropdown.append($('<option>', {
                        value: make,
                        text: make
                    }));
                });

                // Populate the "Model" dropdown with available models
                modelDropdown.empty().append('<option value="">Select Model</option>');
                $.each(data.models, function (index, model) {
                    modelDropdown.append($('<option>', {
                        value: model,
                        text: model
                    }));
                });

                // Populate the "Year" dropdown with available years
                yearDropdown.empty().append('<option value="">Select Year</option>');
                $.each(data.years, function (index, year) {
                    yearDropdown.append($('<option>', {
                        value: year,
                        text: year
                    }));
                });

                // Set back the selected values
                makeDropdown.val(selectedMake);
                modelDropdown.val(selectedModel);
                yearDropdown.val(selectedYear);
            },
            error: function () {
                console.error("An error occurred while filtering car makes, models, and years");
            }
        });
    }

    // Event listener for the "Make" dropdown
    $('#make').change(function () {
        // Mark all dropdowns as changed
        $('#make, #model, #year').data('changed', true);
        // Fetch and populate the dropdown options
        fetchDropdownOptions();
    });

    // Event listener for the "Model" dropdown
    $('#model').change(function () {
        // Mark all dropdowns as changed
        $('#make, #model, #year').data('changed', true);
        // Fetch and populate the dropdown options
        fetchDropdownOptions();
    });

    // Event listener for the "Year" dropdown
    $('#year').change(function () {
        // Mark all dropdowns as changed
        $('#make, #model, #year').data('changed', true);
        // Fetch and populate the dropdown options
        fetchDropdownOptions();
    });

    // Call the function once on page load to populate the dropdowns
    // fetchDropdownOptions();
});
