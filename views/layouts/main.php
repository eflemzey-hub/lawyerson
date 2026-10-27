<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Law Firm Template') ?></title>
    <?php if (!empty($siteFaviconUrl)): ?>
    <link rel="icon" href="<?= htmlspecialchars($siteFaviconUrl) ?>">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($siteFaviconUrl) ?>">
    <?php endif; ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- FontAwesome (Optional but good for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Flag Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css">
    
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* Hide Google Translate toolbar only — keep the combo functional */
        body { top: 0 !important; }
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        #goog-gt-tt,
        .goog-te-balloon-frame,
        iframe.goog-te-banner-frame {
            display: none !important;
        }
        .goog-logo-link,
        .goog-te-gadget > span {
            display: none !important;
        }
        #google_translate_element,
        #google_translate_element .goog-te-gadget,
        .goog-te-gadget {
            position: absolute !important;
            left: -9999px !important;
            top: auto !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            z-index: -1 !important;
        }
        select.goog-te-combo {
            visibility: hidden !important;
            position: absolute !important;
            left: -9999px !important;
        }

        /* Custom Translate Widget */
        .custom-translate-widget {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
        }
        .custom-translate-widget .dropdown-toggle {
            border-radius: 4px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            color: #333;
            font-weight: 600;
            padding: 8px 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .custom-translate-widget .dropdown-menu {
            min-width: 200px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php require_once 'views/partials/navbar.php'; ?>

    <!-- Main Content Injection -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <?php require_once 'views/partials/footer.php'; ?>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS (if any) -->
    <script src="assets/js/main.js"></script>
    
    <!-- Leaflet JS for Maps -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Custom Translate Widget -->
    <div class="custom-translate-widget">
        <button class="btn btn-light dropdown-toggle shadow-sm d-flex align-items-center" type="button" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="fi fi-us me-2" id="active-lang-flag"></span> 
            <span id="active-lang-text">EN</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-start shadow border-0" aria-labelledby="langDropdown" style="max-height: 350px; overflow-y: auto;">
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('en', 'us', 'EN'); return false;"><span class="fi fi-us me-3"></span> English</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('es', 'es', 'ES'); return false;"><span class="fi fi-es me-3"></span> Spanish</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('fr', 'fr', 'FR'); return false;"><span class="fi fi-fr me-3"></span> French</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('de', 'de', 'DE'); return false;"><span class="fi fi-de me-3"></span> German</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('it', 'it', 'IT'); return false;"><span class="fi fi-it me-3"></span> Italian</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('pt', 'pt', 'PT'); return false;"><span class="fi fi-pt me-3"></span> Portuguese</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('ru', 'ru', 'RU'); return false;"><span class="fi fi-ru me-3"></span> Russian</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('zh-CN', 'cn', 'ZH'); return false;"><span class="fi fi-cn me-3"></span> Chinese</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('ja', 'jp', 'JA'); return false;"><span class="fi fi-jp me-3"></span> Japanese</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('ko', 'kr', 'KO'); return false;"><span class="fi fi-kr me-3"></span> Korean</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('ar', 'sa', 'AR'); return false;"><span class="fi fi-sa me-3"></span> Arabic</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="changeLanguage('hi', 'in', 'HI'); return false;"><span class="fi fi-in me-3"></span> Hindi</a></li>
        </ul>
    </div>

    <!-- Page Translator -->
    <script type="text/javascript">
        var translateLangMap = {
            'en': { flag: 'us', label: 'EN' },
            'es': { flag: 'es', label: 'ES' },
            'fr': { flag: 'fr', label: 'FR' },
            'de': { flag: 'de', label: 'DE' },
            'it': { flag: 'it', label: 'IT' },
            'pt': { flag: 'pt', label: 'PT' },
            'ru': { flag: 'ru', label: 'RU' },
            'zh-CN': { flag: 'cn', label: 'ZH' },
            'ja': { flag: 'jp', label: 'JA' },
            'ko': { flag: 'kr', label: 'KO' },
            'ar': { flag: 'sa', label: 'AR' },
            'hi': { flag: 'in', label: 'HI' }
        };

        var currentTranslationRecords = [];
        var currentAttributeRecords = [];
        var languageStorageKey = 'lawyerson_language';

        function updateLanguageUi(flagCode, label) {
            var flagEl = document.getElementById('active-lang-flag');
            var textEl = document.getElementById('active-lang-text');
            if (flagEl) flagEl.className = 'fi fi-' + flagCode + ' me-2';
            if (textEl) textEl.innerText = label;
        }

        function setTranslatorBusy(isBusy) {
            var button = document.getElementById('langDropdown');
            if (button) {
                button.disabled = isBusy;
            }
        }

        function shouldSkipTranslationNode(node) {
            var parent = node.parentElement;
            if (!parent) return true;
            if (parent.closest('.custom-translate-widget, script, style, noscript, svg, canvas, iframe, code, pre, textarea, select, option, .notranslate')) {
                return true;
            }
            var text = node.nodeValue.replace(/\s+/g, ' ').trim();
            return text.length < 2 || !/[A-Za-z]/.test(text);
        }

        function collectTranslationRecords() {
            if (currentTranslationRecords.length > 0 || currentAttributeRecords.length > 0) {
                return;
            }

            var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode: function (node) {
                    return shouldSkipTranslationNode(node) ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT;
                }
            });
            var node;
            while ((node = walker.nextNode())) {
                currentTranslationRecords.push({
                    node: node,
                    original: node.nodeValue
                });
            }

            document.querySelectorAll('input, textarea, button, img, [title]').forEach(function (el) {
                if (el.closest('.custom-translate-widget, .notranslate')) return;
                ['placeholder', 'value', 'alt', 'title'].forEach(function (attr) {
                    var value = el.getAttribute(attr);
                    if (value && /[A-Za-z]/.test(value)) {
                        currentAttributeRecords.push({
                            element: el,
                            attr: attr,
                            original: value
                        });
                    }
                });
            });
        }

        function restoreEnglish() {
            currentTranslationRecords.forEach(function (record) {
                record.node.nodeValue = record.original;
            });
            currentAttributeRecords.forEach(function (record) {
                record.element.setAttribute(record.attr, record.original);
            });
            document.documentElement.lang = 'en';
            document.documentElement.dir = 'ltr';
        }

        function uniqueTextsFromRecords() {
            var seen = {};
            var texts = [];
            currentTranslationRecords.concat(currentAttributeRecords).forEach(function (record) {
                var text = record.original.replace(/\s+/g, ' ').trim();
                if (text && !seen[text]) {
                    seen[text] = true;
                    texts.push(text);
                }
            });
            return texts;
        }

        function requestTranslations(langCode, texts) {
            return fetch('api/translate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    target: langCode.toLowerCase(),
                    texts: texts
                })
            }).then(function (response) {
                return response.json();
            }).then(function (payload) {
                if (!payload.success || !payload.data || !Array.isArray(payload.data.translations)) {
                    throw new Error(payload.message || 'Translation failed.');
                }
                return payload.data.translations;
            });
        }

        function applyTranslatedText(langCode, translatedMap) {
            currentTranslationRecords.forEach(function (record) {
                var key = record.original.replace(/\s+/g, ' ').trim();
                if (translatedMap[key]) {
                    record.node.nodeValue = record.original.replace(key, translatedMap[key]);
                }
            });
            currentAttributeRecords.forEach(function (record) {
                var key = record.original.replace(/\s+/g, ' ').trim();
                if (translatedMap[key]) {
                    record.element.setAttribute(record.attr, translatedMap[key]);
                }
            });
            document.documentElement.lang = langCode;
            document.documentElement.dir = langCode === 'ar' ? 'rtl' : 'ltr';
        }

        function changeLanguage(langCode, flagCode, label) {
            collectTranslationRecords();
            updateLanguageUi(flagCode, label);
            localStorage.setItem(languageStorageKey, langCode);

            if (langCode === 'en') {
                restoreEnglish();
                return;
            }

            setTranslatorBusy(true);
            restoreEnglish();
            var texts = uniqueTextsFromRecords();
            requestTranslations(langCode, texts).then(function (translations) {
                var translatedMap = {};
                texts.forEach(function (text, index) {
                    translatedMap[text] = translations[index] || text;
                });
                applyTranslatedText(langCode, translatedMap);
            }).catch(function (error) {
                console.error(error);
                restoreEnglish();
                updateLanguageUi('us', 'EN');
                localStorage.removeItem(languageStorageKey);
            }).finally(function () {
                setTranslatorBusy(false);
            });
        }

        function syncLanguageUiFromStorage() {
            var lang = localStorage.getItem(languageStorageKey) || 'en';
            if (translateLangMap[lang]) {
                updateLanguageUi(translateLangMap[lang].flag, translateLangMap[lang].label);
                if (lang !== 'en') {
                    changeLanguage(lang, translateLangMap[lang].flag, translateLangMap[lang].label);
                }
            }
        }

        window.addEventListener('load', function () {
            syncLanguageUiFromStorage();
        });
    </script>

    <!-- Tawk.to Live Chat Integration -->
    <?php 
    $tawkId = isset($settingModel) ? $settingModel->get('tawk_id', '') : '';
    if (!empty($tawkId)): 
    ?>
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/<?= htmlspecialchars(urlencode(trim($tawkId))) ?>/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <?php endif; ?>
    <!-- End Tawk.to -->
</body>
</html>
