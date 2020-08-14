<?php

add_action( 'wp_enqueue_scripts', 'liane_theme_enqueue_styles' );

function liane_theme_enqueue_styles() {
    $parenthandle = 'flatbase-style';
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'liane-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}

add_filter( 'pll_get_post_types', 'liane_cpt_to_pll', 10, 2 );

function liane_cpt_to_pll( $post_types, $is_settings ) {
    if ( $is_settings ) {
        // hides from the list of custom post types in Polylang settings
        unset( $post_types['infobox'] );
        unset( $post_types['article'] );
        unset( $post_types['faq'] );
    } else {
        // enables language and translation management
        $post_types['infobox'] = 'infobox';
        $post_types['article'] = 'article';
        $post_types['faq'] = 'faq';
    }
    return $post_types;
}
