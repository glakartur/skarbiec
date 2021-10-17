<?php
    header("Content-type: application/json");

    $personsJson = file_get_contents("../" . $cfg->personsFileName);
    if ($personsJson == null)
    {
        echo "[]";
    }
    
    echo $personsJson

?>