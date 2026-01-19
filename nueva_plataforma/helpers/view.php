<?php

function component(string $name, array $data = [])
{
    extract($data);
    ob_start();
    require __DIR__ . "/../view/components/{$name}.php";
    return ob_get_clean();
}