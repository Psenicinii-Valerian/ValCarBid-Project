// Get the container element
const container = document.querySelector('.listings');

// Get the total number of listing items
const listingItems = container.querySelectorAll('.listing').length;

// Check if there is exactly 1 listing items on a row
if (listingItems % 1 === 0) {
    container.classList.add('one-item-on-row');
} else {
    container.classList.remove('one-item-on-row');
}


// Check if there are exactly 3 listing items on a row
if (listingItems % 2 === 0) {
    container.classList.add('two-items-on-row');
} else {
    container.classList.remove('two-items-on-row');
}

// Check if there are exactly 3 listing items on a row
if (listingItems % 3 === 0) {
    container.classList.add('three-items-on-row');
} else {
    container.classList.remove('three-items-on-row');
}

// Check if there are exactly 3 listing items on a row
if (listingItems % 4 === 0) {
    container.classList.add('four-items-on-row');
} else {
    container.classList.remove('four-items-on-row');
}