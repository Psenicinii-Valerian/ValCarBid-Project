$(document).ready(function () {
    // Cache the city dropdown element
    var $cityDropdown = $("#city");
    var $cityDropdownValue = "{{ $current_city }}"; // Get the initially selected city value

    // Function to fetch and populate cities based on the selected state
    function fetchCities(selectedStateId) {
        if (selectedStateId) {
            // Fetch cities based on the selected state using AJAX
            $.ajax({
                url: "/get-cities", // Use the named route
                method: "GET",
                data: { state_id: selectedStateId }, // Pass the selected state ID
                dataType: "json",
                success: function (data) {
                    // Clear the existing options in the city dropdown
                    $cityDropdown.empty();

                    // Populate the city dropdown with fetched data
                    $.each(data, function (key, city) {
                        var selected = (key === $cityDropdownValue) ? 'selected' : '';
                        $cityDropdown.append(
                            '<option value="' + key + '" ' + selected + '>' + city + '</option>'
                        );
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
            });
        } else {
            // Clear the city dropdown when no state is selected
            $cityDropdown.empty();
        }
    }

    // Listen for changes in the state dropdown
    $("#state").on("change", function () {
        var selectedStateId = $(this).val();
        // Fetch and populate cities when the state changes
        fetchCities(selectedStateId);
    });
});
