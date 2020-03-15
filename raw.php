<?php
    chdir('files/');
    foreach (glob("*") as $file) {
        echo "http://robertlabs.com/app/bithub/files/" . "$file \n";
    }
?>