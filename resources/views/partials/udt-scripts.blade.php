<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Custom Scripts -->
<script>
    $(document).ready(function() {
        // Countdown Timer
        function updateCountdown() {
            const targetDate = new Date($('.wpkoi-elements-countdown-items').data('date')).getTime();
            const now = new Date().getTime();
            const distance = targetDate - now;

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $('[data-days]').text(days < 10 ? '0' + days : days);
                $('[data-hours]').text(hours < 10 ? '0' + hours : hours);
                $('[data-minutes]').text(minutes < 10 ? '0' + minutes : minutes);
                $('[data-seconds]').text(seconds < 10 ? '0' + seconds : seconds);
            }
        }

        setInterval(updateCountdown, 1000);
        updateCountdown();

        // Copy to Clipboard
        $('.wdp-copy-btn').on('click', function() {
            const text = $(this).data('clipboard-text');
            const message = $(this).data('message');

            navigator.clipboard.writeText(text).then(function() {
                alert(message);
            });
        });

        // RSVP Form Submit
        $('#rsvp-form').on('submit', function(e) {
            e.preventDefault();
            alert('Terima kasih! RSVP Anda telah berhasil dikirim.');
            this.reset();
        });

        // Smooth Scroll
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 800);
            }
        });
    });
</script>

<!-- Audio Control Script -->
<script>
    (function($) {
        var audioEl = document.getElementById("song");
        var hasWelcomeWidget = document.getElementById("wdp-button-wrapper") !== null;
        var isEditor = typeof elementorFrontend !== "undefined" &&
            elementorFrontend.isEditMode();

        if (window.settingAutoplay && !hasWelcomeWidget && !isEditor) {
            audioEl.play();
        }

        $("#wdp-button-wrapper").on("click", "button", function() {
            if (audioEl.paused) {
                audioEl.play();
            }
        });
    })(jQuery);
</script>