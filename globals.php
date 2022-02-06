<?php

function ed(string $m)
{
    echo $m;
    die();
}

function dd($var)
{
    var_dump($var);
    die();
}

function notifySend(string $m)
{
    shell_exec("notify-send \"$m\"");
}

function notifySendAndEnd(string $m)
{
    notifySend($m);
    die();
}

function setClipboard(string $new): bool
{
    $clip = popen('xclip -selection clipboard', 'wb');
    $written = fwrite($clip, $new);
    return (pclose($clip) === 0 && strlen($new) === $written);
}