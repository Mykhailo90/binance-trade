<header>
    <div class="title">
        Binance-trade
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" id="mainPage" href="/">Главная</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" id="CurrencyPage" href="/currency-pairs">Валютные пары<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="settingsPage" href="/global-settings">Настройки мониторинга</a>
                </li><li class="nav-item">
                    <a class="nav-link" id="castPage" href="/cast">Слепки состояний</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="alarmsPage" href="/alarms">Уведомления</a>
                </li>
            </ul>
        </div>
    </nav>
    <script>
        $('#mainPage').on('click', function(evt) {
            evt.preventDefault();
            window.open(evt.target.href, '_blank');
        });

        $('#CurrencyPage').on('click', function(evt) {
            evt.preventDefault();
            window.open(evt.target.href, '_blank');
        });
        $('#settingsPagePage').on('click', function(evt) {
            evt.preventDefault();
            window.open(evt.target.href, '_blank');
        });

        $('#alarmsPage').on('click', function(evt) {
            evt.preventDefault();
            window.open(evt.target.href, '_blank');
        });

        $('#castPage').on('click', function(evt) {
            evt.preventDefault();
            window.open(evt.target.href, '_blank');
        });
    </script>
</header>