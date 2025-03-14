<?php
/*
 * Template Name: Custom Games Page
 */
get_header(); ?>

<?php
$game_slug = get_query_var('game_slug');

var_dump($game_slug);

// die;
?>

<div class="w-listing-game-title listing-custom-wrapper main-title">
        <h1 class="listing-title">Helldivers 2</h1>
        <div class="listing-custom-header-rating">
        </div>

    </div>

<?php get_footer(); ?>