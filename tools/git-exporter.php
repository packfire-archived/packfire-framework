<?php
chdir(dirname(dirname(__FILE__)));

$destination = $argv[1];

if ($destination) {

    if (!is_dir($destination)) {
        mkdir($destination, 0777, true);
    }

    function glob_recursive($pattern) {
        $files = glob($pattern, GLOB_NOSORT);

        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern)));
        }
        return $files;
    }
    
    function doCopy($files, $dest){
        foreach ($files as $file) {
            if(in_array(basename($file), array('readme.md', '.git'))){
                continue;
            }
            if (is_file($file) && !is_file($dest . '/' . $file)) {
                copy($file, $dest . '/' . $file);
            } elseif (is_dir($file) && !is_dir($dest . '/' . $file)) {
                mkdir($dest . '/' . $file, 0777, true);
            }
        }
    }

    chdir('packfire');
    $frameworkFiles = glob_recursive('*');
    $dest = $destination . '/packfire';
    doCopy($frameworkFiles, $dest);
    chdir('../public');
    $appFiles = glob_recursive('*');
    $dest = $destination . '/public';
    doCopy($appFiles, $dest);
    chdir('../');
}