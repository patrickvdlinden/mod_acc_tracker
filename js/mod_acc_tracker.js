(function() {
    const refreshRateInSeconds = 60 * 5; // 5 min
    let countdown;
    let refreshTimer;
    function startRefreshTimer() {
        countdown = refreshRateInSeconds;
        refreshTimer = setInterval(updateRefreshTimer, 1000);
    }

    function formatTime(seconds) {
        var minutes = Math.floor(seconds / 60);
        seconds = seconds % 60;

        return `${(minutes < 10 ? '0' + minutes : minutes)}:${(seconds < 10 ? '0' + seconds : seconds)}`;
    }

    function updateRefreshTimer() {
        countdown = Math.max(countdown - 1, 0);

        const modAccTrackerEl = document.getElementById('mod_acc_tracker');
        const resultsTable = modAccTrackerEl.getElementsByTagName('table')[0];
        if (!resultsTable) {
            return;
        }

        let refreshCaption = resultsTable.getElementById('refresh_caption');
        if (!refreshCaption) {
            refreshCaption = document.createElement('caption');
            refreshCaption.id = 'refresh_caption';
            resultsTable.appendChild(caption);
        }

        refreshCaption.innerText = `Data of completed sessions, will be updated in ${formatTime(countdown)}`;

        if (countdown === 0) {
            refreshCaption.innerText = 'New results are being fetched...';
            clearInterval(refreshTimer);
            window.location.reload();
        }
    }

    window.addEventListener('load', function () {
        startRefreshTimer();
    });
})();