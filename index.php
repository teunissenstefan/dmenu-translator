<?php

require_once "globals.php";
require_once "env.php";

array_shift($argv);

if (count($argv) < 3)
    ed("Not enough arguments supplied");

$fromArg = $argv[2];
switch ($fromArg) {
    case "clipboard":
        $in = shell_exec('xclip -out -selection clipboard');
        break;
    case "input":
    default:
        $in = trim(shell_exec("echo '' | dmenu"));
        break;
}

$in = rawurlencode($in);
$fromLang = $argv[0];
$toLang = $argv[1];

array_splice($argv, 0, 3);
$options = [];
foreach ($argv as $arg) {
    switch ($arg) {
        case "--display-result":
        case "-d":
            $options["display-result"] = true;
            break;
        case "--copy-result":
        case "-c":
            $options["copy-result"] = true;
            break;
        default:
            ed("Invalid argument: " . $arg);
    }
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $env["requestUrl"]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "q=$in&source=$fromLang&target=$toLang&format=text&api_key=" . $env["apiKey"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$parsed = json_decode($server_output);

if ($httpcode != 200 || $parsed == null || !isset($parsed->translatedText))
    notifySendAndEnd("Translation failed");

$translation = $parsed->translatedText;
if (array_key_exists("copy-result", $options)) {
    if (!setClipboard($translation))
        notifySend("Could not copy translation to clipboard!");
    else
        notifySend("Translation copied to clipboard!");
}

if (array_key_exists("display-result", $options))
    notifySend("Translation: " . $translation);