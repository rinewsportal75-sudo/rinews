<?php
/**
 * RINEWS.ID — Template Baca Berita Tunggal (Single Post)
 * Lengkap dengan SEO Schema NewsArticle, Media Share 7 Platform, View Counter, & Komentar.
 *
 * @package RINEWS
 */
get_header();
?>

<main class="rn-container rn-section">
    <?php while ( have_posts() ) : the_post(); ?>
        <div style="display:grid; grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr); gap:18px; align-items:start;">
            <article <?php post_class( 'rn-article-sheet' ); ?>>
                <div style="font-size:11px; font-weight:800; text-transform:uppercase; color:#B91C1C; margin-bottom:8px;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">BERANDA</a> /
                    <?php the_category( ' / ' ); ?>
                </div>

                <h1 style="margin:0 0 12px; font-family:'Fraunces', Georgia, serif; font-size:clamp(1.6rem, 3vw, 2.3rem); line-height:1.2; color:#020617;">
                    <?php the_title(); ?>
                </h1>

                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; gap:10px; padding:10px 0; border-top:1px solid #E2E8F0; border-bottom:1px solid #E2E8F0; font-size:12px; color:#475569; margin-bottom:16px;">
                    <span>Oleh <strong><?php the_author(); ?></strong> · <?php echo esc_html( get_the_date( 'l, j F Y · H:i' ) ); ?> WIB</span>
                    <span style="color:#B91C1C; font-weight:800;"><?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> pembaca</span>
                </div>

                <figure style="margin:0 0 16px;">
                    <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'full' ) ); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; border-radius:10px; max-height:460px; object-fit:cover;" />
                    <?php if ( get_the_post_thumbnail_caption() ) : ?>
                        <figcaption style="font-size:12px; font-style:italic; color:#64748B; margin-top:6px;"><?php the_post_thumbnail_caption(); ?></figcaption>
                    <?php endif; ?>
                </figure>

                <!-- Media Share 7 Platform -->
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:12px 14px; margin-bottom:20px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:10px;">
                    <strong style="font-size:12px; text-transform:uppercase; color:#0F172A;">Bagikan Berita:</strong>
                    <?php rinews_render_media_share_bar( false, get_permalink(), get_the_title() ); ?>
                </div>

                <div class="rn-article-content">
                    <?php the_content(); ?>
                </div>

                <div style="margin-top:28px; padding-top:20px; border-top:1px solid #E2E8F0;">
                    <?php
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                    ?>
                </div>
            </article>

            <aside style="display:flex; flex-direction:column; gap:14px;">
                <?php
                if ( is_active_sidebar( 'widget-sidebar-artikel' ) ) {
                    dynamic_sidebar( 'widget-sidebar-artikel' );
                } else {
                    the_widget( 'RINEWS_Widget_Berita_Viral', array( 'title' => 'BERITA VIRAL', 'count' => 3 ) );
                    the_widget( 'RINEWS_Widget_Arsip_12_Bulan', array( 'title' => 'ARSIP' ) );
                }
                ?>
            </aside>
        </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
