document.addEventListener("DOMContentLoaded", function() {
    var shuffleBtn = document.getElementById('shuffle-btn');

    if (shuffleBtn) {
        shuffleBtn.addEventListener('click', function() {
            // Reload the page
            location.reload();
        });
    }
});
