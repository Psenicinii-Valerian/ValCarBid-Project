$(document).ready(function () {
    var $cityDropdown = $("#city");
    var $cityDiv = $("#cityDiv");

    $("#state").on("change", function () {
        var selectedStateId = $(this).val();

        $cityDropdown.empty();
        $cityDiv.addClass("hidden pointer-events-none cursor-not-allowed");

        if (selectedStateId) {
            $cityDiv.removeClass("hidden pointer-events-none cursor-not-allowed");
            
            // Populate the city dropdown when a state is selected.
            populateCityDropdown(selectedStateId);
        }
    });

    function populateCityDropdown(selectedStateId, selectedCityId) {
        $.ajax({
            url: "/get-cities", // Use the named route
            method: "GET",
            data: { state_id: selectedStateId },
            dataType: "json",
            success: function (data) {
                $cityDropdown.empty();
                $.each(data, function (key, city) {
                    var selected = (key == selectedCityId) ? 'selected' : '';
                    $cityDropdown.append(
                        '<option value="' + key + '" ' + selected + '>' + city + "</option>"
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }
    
    // Check if a state is pre-selected and populate the city dropdown accordingly.
    var selectedStateId = $("#state").val();
    var selectedCityId = $("#city").data("selected-city");
    
    if (selectedStateId) {
        populateCityDropdown(selectedStateId, selectedCityId);
    }
});