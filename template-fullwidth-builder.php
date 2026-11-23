<?php
/**
 * Template Name: RINEWS Full-Width (Elementor / Page Builder / Toko)
 * Template Post Type: page, post
 *
 * Kompatibel penuh dengan Elementor, WPBakery, Gutenberg Block Editor,
 * WooCommerce, Contact Form 7, WPForms, dan halaman dari website lama.
 *
 * @package RINEWS
 */
get_header();
?>

<main class="rn-container rn-section">
    <?php while ( have_posts() ) : the_post(); ?>
        <div <?php post_class( 'rn-builder-wrapper' ); ?> style="background:#FFFFFF; border-radius:16px; padding:24px; border:2px solid #C8102E;">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
