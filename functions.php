<?php

/*
 * Scripts and styles
 */
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

/*
 * Polylang post types
 */
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

/*
 * Flatbase livesearch filters
 */
add_filter( 'nice_livesearch_label', 'liane_livesearch_label', 100 );

function liane_livesearch_label() {
  return __( 'Have a question? Ask or enter a search term.', 'liane-support' );
}

/*
 * Flatbase custom livesearch
 */
function nice_livesearch_js() {
  // being here we can set admin-ajax.php for WP multisite
  ?>
  <script type="text/javascript">
  //<![CDATA[
    jQuery(document).ready(function() {
      jQuery('#live-search #s').liveSearch({url: '/?ajax=true&livesearch=true&lang=<?php pll_current_language(); ?>&s='});
    });
  //]]>
  </script>
  <?php
}

/*
 * Add lang to ajax requests
 */
add_action( 'pre_get_posts', 'liane_ajax_pll', 20 );

function liane_ajax_pll($query) {
  if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_search ) {
			if ( get_query_var('ajax') == true ) {
        $query->set( 'lang', $_GET['lang'] );
			}
		}
	}
}
