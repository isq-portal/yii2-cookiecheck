<?php

// use Yii;

?>
<link href="<?= $this->assetBasePath ?>/css/isqCookie.css" rel="stylesheet" type="text/css">
<script>
    var ISQCookieCheckAssetPath = '<?= $this->assetBasePath ?>';
</script>
<div class="scw-cookie<?= $this->decisionMade ? ' scw-cookie-out' : ''; ?>">
    <div class="scw-cookie-panel-toggle scw-cookie-panel-toggle-<?= $this->config['panelTogglePosition']; ?>"
        onclick="scwCookiePanelToggle()"
    >
        <span class="scwc-icon scwc-icon-cookie"></span>
    </div>
    <div class="scw-cookie-content">
        <div class="scw-cookie-message">
            Diese Webseite verwendet Cookies. Notwendige Cookies sind für den Betrieb der Website und die einwandfreie Funktion erforderlich. Cookies für die Webanalyse setzen wir ein, um die Nutzung unserer Website statistisch auszuwerten. Nähere Informationen finden Sie in unseren Datenschutzhinweisen. Dort können Sie auch Ihre Cookie-Einstellungen jederzeit ändern.
        </div>
        <div class="scw-cookie-decision">
            <div class="scw-cookie-btn" onclick="scwCookieHide()">OK</div>
            <div class="scw-cookie-settings scw-cookie-tooltip-trigger"
                onclick="scwCookieDetails()"
                data-label="Einstellungen"
            >
                <span class="scwc-icon scwc-icon-settings"></span>
            </div>
            <div class="scw-cookie-policy scw-cookie-tooltip-trigger" data-label="Datenschutzerklärung">
                <a href="<?= $this->config['cookiePolicyURL']; ?>">
                    <span class="scwc-icon scwc-icon-policy"></span>
                </a>
            </div>
        </div>
        <div class="scw-cookie-details">
            <div class="scw-cookie-details-title">Verwaltung der Cookies</div>
            <div class="scw-cookie-toggle">
                <div class="scw-cookie-name">Notwendige Cookies</div>
                <label class="scw-cookie-switch checked disabled">
                    <input type="checkbox" name="essential" checked="checked" disabled="disabled">
                    <div></div>
                </label>
            </div>
            <?php foreach ($this->enabledCookies() as $name => $label) { ?>
                <div class="scw-cookie-toggle">
                    <div class="scw-cookie-name" onclick="scwCookieToggle(this)"><?= $label; ?></div>
                    <label class="scw-cookie-switch<?= $this->isAllowed($name) ? ' checked' : ''; ?>">
                        <input type="checkbox"
                        name="<?= $name; ?>"
                        <?= $this->isAllowed($name) ? 'checked="checked"' : ''; ?>
                        >
                        <div></div>
                    </label>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <script src="<?= $this->assetBasePath ?>/js/js-cookie.js" type="text/javascript"></script>
    <script src="<?= $this->assetBasePath ?>/js/isqCookie.js" type="text/javascript"></script>
