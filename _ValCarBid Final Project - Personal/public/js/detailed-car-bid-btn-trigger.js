const bidInput = document.getElementById('bid-input');
const bidButton = document.getElementById('bid-button');

bidInput.addEventListener('input', function () {
    if (bidInput.value.trim() !== '') {
        bidButton.removeAttribute('hidden');
    } else {
        bidButton.setAttribute('hidden', 'true');
    }
});