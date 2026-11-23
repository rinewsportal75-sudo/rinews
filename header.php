<?php
/**
 * RINEWS.ID — Header & Navigasi Kategori + Media Share
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="rn-container rn-masthead-wrap">
    <div class="rn-masthead-card">
        <div class="rn-masthead-top">
            <div style="display:flex; align-items:center; gap:10px;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="font-weight:900;">
                    <?php echo esc_html( get_theme_mod( 'rinews_domain_label', 'RINEWS.ID' ) ); ?>
                </a>
                <span style="color:#CBD5E1;">|</span>
                <span style="color:#475569; font-weight:700;"><?php echo esc_html( function_exists( 'rinews_get_broadcast_clock_label' ) ? rinews_get_broadcast_clock_label() : date_i18n( 'l, j F Y · H:i' ) . ' WIB' ); ?></span>
            </div>
            <div>
                <span style="font-weight:900; color:#0F172A;">PORTAL BERITA INDONESIA</span>
            </div>
        </div>

        <div class="rn-masthead-center">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <div class="rn-logo-lockup">
                        <svg viewBox="0 0 116 86" width="84" height="62" aria-hidden="true">
                            <path d="M8 8 H54 C67 8 74 15 74 28 C74 38 68 45 57 48 L76 78 H56 L39 50 H26 V78 H8 V8 Z M26 22 V36 H49 C54 36 57 33 57 29 C57 24 54 22 49 22 H26 Z" fill="#DC1424" stroke="#FFFFFF" stroke-width="2.5" />
                            <rect x="84" y="14" width="18" height="64" rx="2" fill="#DC1424" stroke="#FFFFFF" stroke-width="2.5" />
                        </svg>
                        <span class="rn-logo-word-news">NEWS</span>
                    </div>
                <?php endif; ?>
                <h1 class="rn-site-title"><?php echo esc_html( get_theme_mod( 'rinews_tagline_id', 'MEDIA BERITA REPUBLIK INDONESIA' ) ); ?></h1>
                <p class="rn-site-subtitle"><?php echo esc_html( get_theme_mod( 'rinews_tagline_en', 'REPUBLIC OF INDONESIA NEWS MEDIA' ) ); ?></p>
            </a>
        </div>
    </div>

    <nav class="rn-navbar" aria-label="Navigasi Kategori Utama">
        <div class="rn-navbar-inner">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'rn-nav-list',
                    'container'      => false,
                    'depth'          => 2,
                ) );
            } else {
                ?>
                <ul class="rn-nav-list">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">BERANDA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/internasional/' ) ); ?>">INTERNASIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/nasional/' ) ); ?>">NASIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/regional/' ) ); ?>">REGIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/buser-berita/' ) ); ?>">BUSER BERITA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/olah-raga/' ) ); ?>">OLAH RAGA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/ilmu-pengetahuan/' ) ); ?>">ILMU PENGETAHUAN</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/budaya/' ) ); ?>">BUDAYA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/hiburan/' ) ); ?>">HIBURAN</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/bisnis/' ) ); ?>">BISNIS</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/redaksi/' ) ); ?>">REDAKSI</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/privasi-policy/' ) ); ?>">PRIVASI POLICY</a></li>
                </ul>
                <?php
            }
            ?>

            <div class="rn-media-share-box">
                <div class="rn-media-share-label">MEDIA SHARE</div>
                <?php rinews_render_media_share_bar( true ); ?>
            </div>
        </div>
    </nav>
</header>
