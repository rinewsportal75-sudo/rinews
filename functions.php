<?php
/**
 * RINEWS.ID — Portal Berita Republik Indonesia
 * Functions & Definitions
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'RINEWS_VERSION', '2.1.0' );
define( 'RINEWS_DIR', get_template_directory() );
define( 'RINEWS_URI', get_template_directory_uri() );

/**
 * Setup dasar tema RINEWS.ID (Kompatibel WordPress Lama & Semua Plugin)
 */
function rinews_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'custom-logo', array(
        'height'      => 120,
        'width'       => 380,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );
    add_theme_support( 'customize-selective-refresh-widgets' );

    register_nav_menus( array(
        'primary' => __( 'Menu Utama Kategori RINEWS (Header Merah)', 'rinews' ),
        'footer'  => __( 'Menu Footer RINEWS', 'rinews' ),
    ) );

    add_image_size( 'rinews-slide', 960, 540, true );
    add_image_size( 'rinews-card', 480, 320, true );
    add_image_size( 'rinews-thumb', 240, 180, true );
}
add_action( 'after_setup_theme', 'rinews_theme_setup' );

/**
 * Muat Stylesheet & Script Frontend + Admin Widget Uploader
 */
function rinews_enqueue_assets() {
    wp_enqueue_style(
        'rinews-google-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap',
        array(),
        null
    );
    wp_enqueue_style( 'rinews-style', get_stylesheet_uri(), array( 'rinews-google-fonts' ), RINEWS_VERSION );

    wp_enqueue_script(
        'rinews-portal-js',
        RINEWS_URI . '/assets/js/rinews-portal.js',
        array(),
        RINEWS_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'rinews_enqueue_assets' );

function rinews_admin_widget_scripts( $hook ) {
    if ( 'widgets.php' === $hook || 'customize.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script(
            'rinews-admin-widgets',
            RINEWS_URI . '/assets/js/rinews-admin-widgets.js',
            array( 'jquery' ),
            RINEWS_VERSION,
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'rinews_admin_widget_scripts' );

/**
 * Daftarkan Semua Area Widget WordPress (Appearance -> Widgets)
 * Memudahkan pengeditan tiap bagian portal langsung dari Dashboard WordPress.
 */
function rinews_register_widget_areas() {
    $areas = array(
        'widget-indeks-iklan' => array(
            'name'        => __( '1. Indeks Pencarian — 3 Space Gambar Iklan', 'rinews' ),
            'description' => __( 'Tambahkan hingga 3 Widget Gambar Iklan di bawah kolom pencarian.', 'rinews' ),
        ),
        'widget-berita-viral' => array(
            'name'        => __( '2. Kolom Berita Viral (Pembaca Terbanyak)', 'rinews' ),
            'description' => __( 'Gunakan Widget RINEWS: Berita Viral untuk mengatur jumlah/judul.', 'rinews' ),
        ),
        'widget-slide-berita' => array(
            'name'        => __( '3. Kolom Slide Berita Baru & 3 Kategori Teratas', 'rinews' ),
            'description' => __( 'Gunakan Widget RINEWS: Slide Berita Baru untuk mengedit sumber kategori slide & 3 kolom bawahnya.', 'rinews' ),
        ),
        'widget-iklan-panjang-1' => array(
            'name'        => __( '4. Iklan Panjang 1 (Leaderboard Atas)', 'rinews' ),
            'description' => __( 'Pasang banner iklan horizontal pertama di bawah hero.', 'rinews' ),
        ),
        'widget-pos-baru-kiri' => array(
            'name'        => __( '5. Pos Baru — Kolom Kiri', 'rinews' ),
            'description' => __( 'Edit integrasi kategori/jumlah berita untuk kolom Pos Baru Kiri.', 'rinews' ),
        ),
        'widget-pos-baru-kanan' => array(
            'name'        => __( '6. Pos Baru — Kolom Kanan', 'rinews' ),
            'description' => __( 'Edit integrasi kategori/jumlah berita untuk kolom Pos Baru Kanan.', 'rinews' ),
        ),
        'widget-iklan-panjang-2' => array(
            'name'        => __( '7. Iklan Panjang 2 (Leaderboard Tengah)', 'rinews' ),
            'description' => __( 'Pasang banner iklan horizontal kedua di atas blok kategori.', 'rinews' ),
        ),
        'widget-kategori-utama' => array(
            'name'        => __( '8. Integrasi Kategori Horizontal (Internasional, Nasional, Regional, Buser, Olah Raga, Bisnis)', 'rinews' ),
            'description' => __( 'Tambahkan & edit Widget RINEWS: Integrasi Kategori Berita untuk setiap kategori horizontal.', 'rinews' ),
        ),
        'widget-kategori-vertikal' => array(
            'name'        => __( '9. Integrasi 3 Kolom Vertikal (Ilmu Pengetahuan, Hiburan, Budaya)', 'rinews' ),
            'description' => __( 'Tambahkan 3 Widget RINEWS: Integrasi Kategori Berita (mode Vertikal) di sini.', 'rinews' ),
        ),
        'widget-kolom-hitam' => array(
            'name'        => __( '10. Kolom Hitam Bawah (Komentar, Arsip 12 Bulan, Mungkin Terlewatkan H-1)', 'rinews' ),
            'description' => __( 'Atur urutan atau judul kolom Komentar, Arsip 12 Bulan, dan Mungkin Terlewatkan.', 'rinews' ),
        ),
        'widget-tiktok-video' => array(
            'name'        => __( '11. Integrasi TikTok Video', 'rinews' ),
            'description' => __( 'Pasang Widget RINEWS: Integrasi Video TikTok Otomatis.', 'rinews' ),
        ),
        'widget-youtube-video' => array(
            'name'        => __( '12. Integrasi Video Berita YouTube', 'rinews' ),
            'description' => __( 'Pasang Widget RINEWS: Integrasi Video YouTube Otomatis.', 'rinews' ),
        ),
        'widget-iklan-footer-3' => array(
            'name'        => __( '13. 3 Gambar Iklan Bawah', 'rinews' ),
            'description' => __( 'Tambahkan 3 Widget Gambar Iklan di atas footer.', 'rinews' ),
        ),
        'widget-iklan-panjang-bawah' => array(
            'name'        => __( '14. Iklan Panjang Paling Bawah', 'rinews' ),
            'description' => __( 'Pasang banner iklan panjang paling bawah sebelum copyright.', 'rinews' ),
        ),
        'widget-sidebar-artikel' => array(
            'name'        => __( '15. Sidebar Halaman Baca Artikel', 'rinews' ),
            'description' => __( 'Widget yang tampil di samping artikel tunggal.', 'rinews' ),
        ),
        'widget-live-streaming' => array(
            'name'        => __( '16. LIVE Streaming Portrait & TikTok Live (Tengah Atas Copyright)', 'rinews' ),
            'description' => __( 'Widget tersendiri untuk mengatur siaran langsung Portrait 9:16, izin penyiar umum, akun TikTok Live personal, dan peraturan di bawah layar.', 'rinews' ),
        ),
    );

    foreach ( $areas as $id => $args ) {
        register_sidebar( array(
            'name'          => $args['name'],
            'id'            => $id,
            'description'   => $args['description'],
            'before_widget' => '<div id="%1$s" class="rn-widget-block %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="rn-widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'rinews_register_widget_areas' );

/**
 * Helper Gambar Fallback agar tidak pernah broken image
 */
function rinews_get_post_image_url( $post_id = null, $size = 'rinews-card' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    if ( has_post_thumbnail( $post_id ) ) {
        $src = get_the_post_thumbnail_url( $post_id, $size );
        if ( $src ) {
            return $src;
        }
    }
    // Fallback ke gambar default yang dibundel di dalam tema
    return RINEWS_URI . '/assets/images/news_istana_jakarta.jpg';
}

/**
 * Muat Modul Tema RINEWS
 */
require_once RINEWS_DIR . '/inc/seo-and-views.php';
require_once RINEWS_DIR . '/inc/media-share.php';
require_once RINEWS_DIR . '/inc/customizer.php';
require_once RINEWS_DIR . '/inc/schedule-and-shortcodes.php';
require_once RINEWS_DIR . '/inc/widgets.php';
require_once RINEWS_DIR . '/inc/admin-setup.php';
