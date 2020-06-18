<?php

chdir("..");
$cd = getcwd();

if (!file_exists($cd . "/files_doc")){
    mkdir($cd . "/files_doc", "0777",true);
    chmod($cd . "/files_doc", 0777);
}
$file_doc_dir = $cd . "/files_doc";

echo $file_doc_dir;