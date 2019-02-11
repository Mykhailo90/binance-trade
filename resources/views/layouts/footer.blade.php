<footer>
    <p class="footer_text">@msarapii</p>
</footer>
<script>
    function soundWarning() {
        var audio = new Audio();
        audio.src = 'http://binance-trade.local/beep-01a.mp3';
        audio.autoplay = true;
        audio.loop = true;
    }

    $(document).ready(function() {

        var newAlarms = <?php echo $newAlarms->count(); ?>;

        if (newAlarms)
            soundWarning();

        if (newAlarms) {
            $("#alarmsPage").css("color", "red");
        }
    });
</script>