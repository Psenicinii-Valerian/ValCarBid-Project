function updateCountdownTimer() {
    const countdownElements = document.querySelectorAll('#expires-in');

    countdownElements.forEach((element) => {
        const countdownText = element.textContent.trim().replace(/\s+/g, '');
        const regex = /(\d+):(\d+):(\d+)/g;
        const matches = countdownText.match(regex);

        if (matches) {
            const [hours, minutes, seconds] = matches[0].split(':').map(Number);

            if (hours === 0 && minutes === 0 && seconds === 0) {
                // Timer has reached 00:00:00, you can refresh the page here
                location.reload();
            } else {
                // Decrease the time by 1 second
                let totalSeconds = hours * 3600 + minutes * 60 + seconds;
                totalSeconds -= 1;

                if (totalSeconds < 0) {
                    totalSeconds = 0;
                }

                const updatedHours = Math.floor(totalSeconds / 3600);
                const updatedMinutes = Math.floor((totalSeconds % 3600) / 60);
                const updatedSeconds = totalSeconds % 60;

                // Format hours, minutes, and seconds with leading zeros
                const formattedHours = updatedHours.toString().padStart(2, '0');
                const formattedMinutes = updatedMinutes.toString().padStart(2, '0');
                const formattedSeconds = updatedSeconds.toString().padStart(2, '0');

                // Update the countdown timer text
                element.textContent = `${formattedHours} : ${formattedMinutes} : ${formattedSeconds}`;
            }
        }
    });
}

// Refresh the countdown timer every second
setInterval(updateCountdownTimer, 1000);
