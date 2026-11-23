<?php
/**
 * RINEWS.ID — Page Template (Redaksi, Privasi Policy, Tentang Kami, Kontak Iklan)
 *
 * @package RINEWS
 */
get_header();
?>

<main class="rn-container rn-section">
    <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'rn-article-sheet' ); ?>>
            <h1 style="margin:0 0 14px; font-size:26px; font-weight:900; color:#0F172A; border-bottom:2px solid #C8102E; padding-bottom:10px;">
                <?php the_title(); ?>
            </h1>
            <div class="rn-article-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
