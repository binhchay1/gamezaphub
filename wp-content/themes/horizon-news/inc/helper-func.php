<?php

function is_true_homepage()
{
    if (is_front_page()) {
        $current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        return $current_path === '';
    }
    
    return false;
}
