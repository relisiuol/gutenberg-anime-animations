<?php
/**
 * Plugin Name: Gutenberg Anime.js Animations
 * Plugin URI:  https://wordpress.org/plugins/gutenberg-anime-animations
 * Description: Ajoute des animations Anime.js à vos blocs Gutenberg existants. Anime.js n’est chargé que lorsque c’est nécessaire.
 * Version:     1.0.0
 * Author:      ChatGPT
 * Author URI:  https://openai.com
 * License:     GPLv2 or later
 * Text Domain: gutenberg-anime-animations
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

const GAA_VER = '1.0.0';

/**
 * Charge le text‑domain.
 */
// TODO: generate translation files
// add_action( 'plugins_loaded', function () {
    // load_plugin_textdomain( 'gutenberg-anime-animations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
// } );

/**
 * Enqueue assets éditeur (panneau + attribut).
 */
add_action( 'enqueue_block_editor_assets', function () {
    wp_enqueue_script(
        'animejs',
        'https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js',
        [],
        '3.2.2',
        true
    );
    $asset_editor = include __DIR__ . '/build/editor.asset.php';
    wp_enqueue_script(
        'gaa-editor',
        plugin_dir_url( __FILE__ ) . 'build/editor.js',
        $asset_editor['dependencies'],
        $asset_editor['version'],
        true
    );
    wp_enqueue_style(
        'gaa-editor',
        plugin_dir_url( __FILE__ ) . 'build/editor.css',
        [],
        $asset_editor['version']
    );
} );

/**
 * Scan du contenu lors de l’enregistrement pour marquer la présence d’animations.
 */
add_action( 'save_post', function ( $post_id, $post ) {
    if ( wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
        return;
    }

    $has = false;
    foreach ( parse_blocks( $post->post_content ) as $block ) {
        if ( ! empty( $block['attrs']['gaaAnimation'] ) ) {
            $has = true;
            break;
        }
    }
    $meta = '_gaa_has_animation';
    if( $has ) {
        update_post_meta( $post_id, $meta, 1 );
    } else {
        delete_post_meta( $post_id, $meta );
    }
}, 10, 2 );

/**
 * Enqueue conditionnel côté public.
 */
add_action( 'wp_enqueue_scripts', function () {
    if ( is_singular() ) {
        $post = get_post();
        if ( $post && get_post_meta( $post->ID, '_gaa_has_animation', true ) ) {
            wp_enqueue_script(
                'animejs',
                'https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js',
                [],
                '3.2.2',
                true
            );
            $asset_editor = include __DIR__ . '/build/frontend.asset.php';
            wp_enqueue_script(
                'gaa-frontend',
                plugin_dir_url( __FILE__ ) . 'build/frontend.js',
                [ 'animejs' ],
                $asset_editor['version'],
                true
            );
        }
    }
} );
