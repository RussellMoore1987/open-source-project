<?php
for ($i=0; $i < 25; $i++) {
        if (!($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 5)) {
            if (!($i % 2)) {
                echo $i ." parent \n";
            } else {
                echo $i ." sub \n";
            }
        } else {
            echo $i ." parent \n";
        }
    }