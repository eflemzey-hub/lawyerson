<?php
require_once __DIR__ . '/bootstrap.php';

api_require_method('POST');

$input = api_input();
$target = strtolower(trim((string) ($input['target'] ?? '')));
$texts = $input['texts'] ?? [];

$allowedLanguages = ['es', 'fr', 'de', 'it', 'pt', 'ru', 'zh-cn', 'ja', 'ko', 'ar', 'hi'];
if (!in_array($target, $allowedLanguages, true)) {
    api_error('Unsupported translation language.', 400);
}

if (!is_array($texts)) {
    api_error('Texts must be an array.', 400);
}

$texts = array_values(array_filter(array_map(static function ($text) {
    $text = trim((string) $text);
    if ($text === '' || strlen($text) > 1200) {
        return '';
    }
    return $text;
}, $texts), static function ($text) {
    return $text !== '';
}));

if (count($texts) > 250) {
    api_error('Too many text items requested.', 413);
}

if (empty($texts)) {
    api_success(['translations' => []], 'Nothing to translate.');
}

function translate_fetch_url($url) {
    $context = stream_context_create([
        'http' => [
            'timeout' => 12,
            'header' => "User-Agent: LawyersonTranslator/1.0\r\n",
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    if ($body !== false) {
        return $body;
    }

    if (stripos(PHP_OS_FAMILY, 'Windows') === false || !function_exists('shell_exec')) {
        return false;
    }

    $script = "\$ProgressPreference='SilentlyContinue'; " .
        "[Net.ServicePointManager]::SecurityProtocol=[Net.SecurityProtocolType]::Tls12; " .
        "(Invoke-WebRequest -Uri " . var_export($url, true) . " -UseBasicParsing).Content";
    $encodedScript = function_exists('mb_convert_encoding')
        ? mb_convert_encoding($script, 'UTF-16LE', 'UTF-8')
        : iconv('UTF-8', 'UTF-16LE', $script);
    $encoded = base64_encode($encodedScript);
    return shell_exec('powershell -NoProfile -ExecutionPolicy Bypass -EncodedCommand ' . escapeshellarg($encoded));
}

function translate_chunk($texts, $target) {
    $separator = '[[[LAWYERSON_TRANSLATE_SPLIT_' . bin2hex(random_bytes(4)) . ']]]';
    $joined = implode("\n" . $separator . "\n", $texts);
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=' .
        rawurlencode($target) . '&dt=t&format=text&q=' . rawurlencode($joined);

    $body = translate_fetch_url($url);
    if ($body === false || trim((string) $body) === '') {
        return false;
    }

    $decoded = json_decode($body, true);
    if (!is_array($decoded) || !isset($decoded[0]) || !is_array($decoded[0])) {
        return false;
    }

    $translated = '';
    foreach ($decoded[0] as $segment) {
        if (isset($segment[0])) {
            $translated .= $segment[0];
        }
    }

    $parts = preg_split('/\s*' . preg_quote($separator, '/') . '\s*/', trim($translated));
    if (!is_array($parts) || count($parts) !== count($texts)) {
        return false;
    }

    return array_map('trim', $parts);
}

$translations = [];
$chunk = [];
$chunkLength = 0;

foreach ($texts as $text) {
    $length = strlen($text);
    if (!empty($chunk) && (count($chunk) >= 35 || $chunkLength + $length > 3500)) {
        $translatedChunk = translate_chunk($chunk, $target);
        if ($translatedChunk === false) {
            api_error('Translation service is temporarily unavailable.', 502);
        }
        $translations = array_merge($translations, $translatedChunk);
        $chunk = [];
        $chunkLength = 0;
    }
    $chunk[] = $text;
    $chunkLength += $length;
}

if (!empty($chunk)) {
    $translatedChunk = translate_chunk($chunk, $target);
    if ($translatedChunk === false) {
        api_error('Translation service is temporarily unavailable.', 502);
    }
    $translations = array_merge($translations, $translatedChunk);
}

api_success(['translations' => $translations], 'Translated.');
