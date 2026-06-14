<?php
$data = json_decode(file_get_contents('vendor/composer/installed.json'), true);
foreach($data['packages'] as &$pkg) {
    if($pkg['name'] === 'laravel/sentinel') {
        unset($pkg['extra']['laravel']);
    }
}
file_put_contents('vendor/composer/installed.json', json_encode($data));
echo 'Fixed installed.json';
