<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Supprime les attributs du contenu
$post_ids = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT ID FROM %i WHERE post_content LIKE '%data-gaa-animation=%' OR post_content LIKE '%\"gaaAnimation\":\"%'",
        $wpdb->posts
    )
);

foreach ( $post_ids as $pid ) {
    $content = get_post_field( 'post_content', $pid );
    $content = preg_replace(
        [
            '/\sdata-gaa-animation="[^"]*"/i',
            '/"gaaAnimation":"[^"]*",?/'
        ],
        '',
        $content
    );
    wp_update_post( [
        'ID'           => $pid,
        'post_content' => $content,
    ] );
}

// Supprime la meta
$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM %i WHERE meta_key = %s",
        array(
            $wpdb->postmeta,
            '_gaa_has_animation'
        )
    )
);
