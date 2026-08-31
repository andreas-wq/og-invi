<div class="modalx"
    data-sampul="https://undangandigit.id/wp-content/uploads/2025/08/Putih-Biru-Minimalis-Undangan-Pernikahanmmmmmmmmmm-e1696480239700.webp"
    style="
        background-image: url(https://undangandigit.id/wp-content/uploads/2025/08/Putih-Biru-Minimalis-Undangan-Pernikahanmmmmmmmmmm-e1696480239700.webp) !important;
    ">
    <div class="overlayy"></div>
    <div class="content-modalx">
        <div class="info_modalx">
            <div class="elementor-image img"></div>

            <div
                class="elementor-heading-title elementor-size-default wdp-text">
                Our Wedding Invitation
            </div>

            <div class="elementor-heading-title elementor-size-default wdp-dear">
                Kepada Yth.
            </div>

            <div class="elementor-heading-title elementor-size-default wdp-name">
                Bapak/Ibu/Saudara/i
            </div>

            <div class="wdp-button-wrapper" id="wdp-button-wrapper">
                <button class="elementor-button">
                    <span class="elementor-button-icon">
                        <svg aria-hidden="true" class="e-font-icon-svg e-far-envelope-open" viewBox="0 0 512 512"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M494.586 164.516c-4.697-3.883-111.723-89.95-135.251-108.657C337.231 38.191 299.437 0 256 0c-43.205 0-80.636 37.717-103.335 55.859-24.463 19.45-131.07 105.195-135.15 108.549A48.004 48.004 0 0 0 0 201.485V464c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V201.509a48 0 0 0-17.414-36.993zM464 458a6 6 0 0 1-6 6H54a6 6 0 0 1-6-6V204.347c0-1.813.816-3.526 2.226-4.665 15.87-12.814 108.793-87.554 132.364-106.293C200.755 78.88 232.398 48 256 48c23.693 0 55.857 31.369 73.41 45.389 23.573 18.741 116.503 93.493 132.366 106.316a5.99 5.99 0 0 1 2.224 4.663V458z">
                            </path>
                        </svg>
                    </span>
                    Buka Undangan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var sampulbg = jQuery(".modalx").data("sampul");
    jQuery(".modalx").attr(
        "style",
        "background-image: url(" + sampulbg + ") !important;",
    );
    jQuery("body").css("overflow", "hidden");

    jQuery(".wdp-button-wrapper button").on("click", function () {
        jQuery(".modalx").addClass("removeModals");
        jQuery("body").css("overflow", "auto");
    });
</script>

<style type="text/css">
    .wdp-button-wrapper button.elementor-button,
    .wdp-button-qr button.elementor-button-qr {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .wdp-button-wrapper button.elementor-button .elementor-button-icon {
        margin-right: 8px;
    }

    .wdp-button-wrapper button.elementor-button svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }
</style>

<!-- Audio -->
<audio id="song" loop="">
    <source
        src="https://undangandigit.id/wp-content/uploads/2025/11/MARRY-YOUR-DAUGHTER-BRIAN-MCKNIGHT-VIOLIN-COVER.mp3"
        type="audio/mpeg">
</audio>