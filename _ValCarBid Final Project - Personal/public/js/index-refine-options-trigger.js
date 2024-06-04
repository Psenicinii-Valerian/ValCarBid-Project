document.addEventListener("DOMContentLoaded", function () {
    const refineOptions = document.querySelector(".refine-options");
    const filterSort = document.querySelector(".filter-search-sort");

    let isHidden = true;

    refineOptions.addEventListener("click", function () {
        if (isHidden) {
            filterSort.style.display = "block";
            refineOptions.innerHTML = 'Refine Options <i class="fa fa-chevron-up"></i>';
        } else {
            filterSort.style.display = "none";
            refineOptions.innerHTML = 'Refine Options <i class="fa fa-chevron-down"></i>';
        }

        isHidden = !isHidden;
    });
});
