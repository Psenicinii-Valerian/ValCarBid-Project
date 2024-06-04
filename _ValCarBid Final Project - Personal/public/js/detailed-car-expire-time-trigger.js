function updateCountdownTimer() {
    const countdownElements = document.querySelectorAll('#expires-in');

    countdownElements.forEach((element) => {
        const countdownText = element.textContent.trim().replace(/\s+/g, '');
        const regex = /(\d+)d:(\d+)h:(\d+)m:(\d+)s/g;
        const matches = regex.exec(countdownText);

        if (matches) {
            let [days, hours, minutes, seconds] = matches.slice(1).map(Number);

            if (days === 0 && hours === 0 && minutes === 0 && seconds === 0) {
                // Timer has reached 00d:00h:00m:00s, you can refresh the page here
                window.location.href = '/';
            } else {
                // Decrease the time by 1 second
                let totalSeconds = days * 86400 + hours * 3600 + minutes * 60 + seconds;
                totalSeconds -= 1;

                if (totalSeconds < 0) {
                    totalSeconds = 0;
                }

                // Calculate days, hours, minutes, and seconds
                days = Math.floor(totalSeconds / 86400);
                totalSeconds %= 86400;
                hours = Math.floor(totalSeconds / 3600);
                totalSeconds %= 3600;
                minutes = Math.floor(totalSeconds / 60);
                seconds = totalSeconds % 60;

                // Format days, hours, minutes, and seconds with leading zeros
                const formattedDays = days.toString().padStart(2, '0');
                const formattedHours = hours.toString().padStart(2, '0');
                const formattedMinutes = minutes.toString().padStart(2, '0');
                const formattedSeconds = seconds.toString().padStart(2, '0');

                // Update the countdown timer text
                element.textContent = `${formattedDays}d : ${formattedHours}h : ${formattedMinutes}m : ${formattedSeconds}s`;
            }
        }
    });
}

// Refresh the countdown timer every second
setInterval(updateCountdownTimer, 1000);