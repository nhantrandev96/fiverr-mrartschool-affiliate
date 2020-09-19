<?php

$path = __DIR__.'/../elements';

$elements = array();

$files = new DirectoryIterator($path);

foreach ($files as $file) {
    if ( ! $file->isDot()) {
        $contents = file_get_contents($path.'/'.$file->getFilename());

        preg_match('/<script>(.+?)<\/script>/s', $contents, $config);
        preg_match('/<style.*?>(.+?)<\/style>/s', $contents, $css);
        preg_match('/<\/style.*?>(.+?)<script>/s', $contents, $html);

        if (!isset($config[1]) || !isset($html[1])) {
            continue;
        }

        $elements[] = array(
            'css' => isset($css[1]) ? trim($css[1]) : '',
            'html' => trim($html[1]),
            'config' => trim($config[1])
        );
    }
}

echo json_encode($elements);