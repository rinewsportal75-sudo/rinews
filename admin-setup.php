<?php
/**
 * RINEWS.ID — Setup Otomatis Kategori, Submenu, Halaman Redaksi, Gambar, Berita Awal & Widget
 * Membantu pengguna mengatur seluruh struktur website di WordPress hanya dengan 1 klik.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rinews_add_setup_menu() {
    add_theme_page(
        __( 'Setup Portal RINEWS.ID', 'rinews' ),
        __( 'Setup RINEWS.ID', 'rinews' ),
        'manage_options',
        'rinews-setup',
        'rinews_render_setup_page'
    );
}
add_action( 'admin_menu', 'rinews_add_setup_menu' );

function rinews_create_categories_and_menu() {
    $structure = array(
        'INTERNASIONAL'    => array( 'United State', 'Eropa', 'Asia', 'Timur Tengah' ),
        'NASIONAL'         => array( 'Pemerintahan', 'Politik', 'Hukum', 'Istana' ),
        'REGIONAL'         => array( 'Aceh', 'Banten', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Nusa Tenggara', 'Papua' ),
        'BUSER BERITA'     => array( 'TNI/Polri', 'KPK', 'Hukum/Politik' ),
        'OLAH RAGA'        => array(),
        'ILMU PENGETAHUAN' => array(),
        'BUDAYA'           => array(),
        'HIBURAN'          => array(),
        'BISNIS'           => array(),
    );

    $cat_ids = array();

    foreach ( $structure as $parent_name => $children ) {
        $parent_term = term_exists( $parent_name, 'category' );
        if ( ! $parent_term ) {
            $parent_term = wp_insert_term( $parent_name, 'category' );
        }
        if ( is_wp_error( $parent_term ) ) {
            continue;
        }
        $parent_id = (int) $parent_term['term_id'];
        $cat_ids[ $parent_name ] = $parent_id;

        foreach ( $children as $child_name ) {
            $child_term = term_exists( $child_name, 'category', $parent_id );
            if ( ! $child_term ) {
                $child_term = wp_insert_term( $child_name, 'category', array( 'parent' => $parent_id ) );
            }
            if ( ! is_wp_error( $child_term ) ) {
                $cat_ids[ $child_name ] = (int) $child_term['term_id'];
            }
        }
    }

    // Buat Halaman Editorial (REDAKSI, PRIVASI POLICY, dll)
    $pages = array(
        'REDAKSI'             => 'Susunan Redaksi Resmi RINEWS.ID — PT. Virnanda Creator Productions.',
        'PRIVASI POLICY'      => 'Kebijakan Privasi Pengguna Portal Berita RINEWS.ID.',
        'TENTANG KAMI'        => 'RINEWS.ID adalah Media Berita Republik Indonesia.',
        'STRUKTUR REDAKSI'    => 'Struktur Organisasi Redaksi RINEWS.ID.',
        'PEDOMAN MEDIA SIBER' => 'Pedoman Pemberitaan Media Siber Dewan Pers.',
        'KONTAK IKLAN'        => 'Informasi Pemasangan Iklan & Kerja Sama PT. Virnanda Creator Productions.',
    );

    $page_ids = array();
    foreach ( $pages as $title => $content ) {
        $existing = get_page_by_title( $title );
        if ( ! $existing ) {
            $pid = wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
            $page_ids[ $title ] = $pid;
        } else {
            $page_ids[ $title ] = $existing->ID;
        }
    }

    // Buat Navigasi Menu Utama jika belum terpasang
    $menu_name = 'Menu Utama RINEWS.ID';
    $menu_obj  = wp_get_nav_menu_object( $menu_name );
    if ( ! $menu_obj ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'  => 'BERANDA',
            'menu-item-url'    => home_url( '/' ),
            'menu-item-status' => 'publish',
        ) );

        foreach ( $structure as $parent_name => $children ) {
            if ( empty( $cat_ids[ $parent_name ] ) ) continue;
            $parent_menu_id = wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'     => $parent_name,
                'menu-item-object'    => 'category',
                'menu-item-object-id' => $cat_ids[ $parent_name ],
                'menu-item-type'      => 'taxonomy',
                'menu-item-status'    => 'publish',
            ) );

            foreach ( $children as $child_name ) {
                if ( empty( $cat_ids[ $child_name ] ) ) continue;
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => $child_name,
                    'menu-item-object'    => 'category',
                    'menu-item-object-id' => $cat_ids[ $child_name ],
                    'menu-item-type'      => 'taxonomy',
                    'menu-item-parent-id' => $parent_menu_id,
                    'menu-item-status'    => 'publish',
                ) );
            }
        }

        foreach ( array( 'REDAKSI', 'PRIVASI POLICY' ) as $p_title ) {
            if ( ! empty( $page_ids[ $p_title ] ) ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => $p_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page_ids[ $p_title ],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }
        }

        $locations = get_theme_mod( 'nav_menu_locations', array() );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    return $cat_ids;
}

function rinews_seed_default_widgets( $cat_ids ) {
    $sidebars = get_option( 'sidebars_widgets', array() );
    $widget_kat = get_option( 'widget_rinews_widget_kategori', array() );
    $widget_iklan = get_option( 'widget_rinews_widget_iklan', array() );

    // Pre-populate Widget Integrasi Kategori Horizontal jika masih kosong
    if ( empty( $sidebars['widget-kategori-utama'] ) ) {
        $horizontal_list = array( 'INTERNASIONAL', 'NASIONAL', 'REGIONAL', 'BUSER BERITA', 'OLAH RAGA', 'BISNIS' );
        $assigned = array();
        $idx = 10;
        foreach ( $horizontal_list as $cat_name ) {
            $cid = isset( $cat_ids[ $cat_name ] ) ? $cat_ids[ $cat_name ] : 0;
            $widget_kat[ $idx ] = array(
                'title'     => $cat_name,
                'cat_id'    => $cid,
                'count'     => 4,
                'layout'    => 'horizontal',
                'show_subs' => true,
                'header_bg' => '#C8102E',
            );
            $assigned[] = 'rinews_widget_kategori-' . $idx;
            $idx++;
        }
        $sidebars['widget-kategori-utama'] = $assigned;
    }

    // Pre-populate 3 Kolom Vertikal (Ilmu Pengetahuan, Hiburan, Budaya)
    if ( empty( $sidebars['widget-kategori-vertikal'] ) ) {
        $vertical_list = array( 'ILMU PENGETAHUAN', 'HIBURAN', 'BUDAYA' );
        $assigned = array();
        $idx = 30;
        foreach ( $vertical_list as $cat_name ) {
            $cid = isset( $cat_ids[ $cat_name ] ) ? $cat_ids[ $cat_name ] : 0;
            $widget_kat[ $idx ] = array(
                'title'     => $cat_name,
                'cat_id'    => $cid,
                'count'     => 3,
                'layout'    => 'vertical',
                'show_subs' => false,
                'header_bg' => '#C8102E',
            );
            $assigned[] = 'rinews_widget_kategori-' . $idx;
            $idx++;
        }
        $sidebars['widget-kategori-vertikal'] = $assigned;
    }

    // Pre-populate 3 Iklan Gambar di Indeks Pencarian
    if ( empty( $sidebars['widget-indeks-iklan'] ) ) {
        $ads = array(
            array( 'sponsor' => 'IKLAN 1 · BANK NUSANTARA', 'title' => 'Solusi Tabungan Bisnis & QRIS', 'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg' ),
            array( 'sponsor' => 'IKLAN 2 · GARUDA FIBER 5G', 'title' => 'Internet Korporat 38 Provinsi', 'image_url' => RINEWS_URI . '/assets/images/news_teknologi_riset.jpg' ),
            array( 'sponsor' => 'IKLAN 3 · NUSANTARA LOGISTIK', 'title' => 'Kargo Pelabuhan & Udara Cepat', 'image_url' => RINEWS_URI . '/assets/images/news_regional_indonesia.jpg' ),
        );
        $assigned = array();
        $idx = 10;
        foreach ( $ads as $ad ) {
            $widget_iklan[ $idx ] = array(
                'sponsor'   => $ad['sponsor'],
                'title'     => $ad['title'],
                'image_url' => $ad['image_url'],
                'link_url'  => home_url( '/kontak-iklan/' ),
                'mode'      => 'sidebar',
            );
            $assigned[] = 'rinews_widget_iklan-' . $idx;
            $idx++;
        }
        $sidebars['widget-indeks-iklan'] = $assigned;
    }

    update_option( 'widget_rinews_widget_kategori', $widget_kat );
    update_option( 'widget_rinews_widget_iklan', $widget_iklan );
    update_option( 'sidebars_widgets', $sidebars );
}

function rinews_render_setup_page() {
    $done = false;
    if ( isset( $_POST['rinews_run_setup'] ) && check_admin_referer( 'rinews_setup_nonce' ) ) {
        $cat_ids = rinews_create_categories_and_menu();
        rinews_seed_default_widgets( $cat_ids );
        $done = true;
    }
    ?>
    <div class="wrap">
        <h1>Setup Otomatis Portal Berita RINEWS.ID</h1>
        <?php if ( $done ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>Berhasil!</strong> 12 Kategori + 22 Submenu, Menu Navigasi Utama, Halaman Redaksi/Privasi, serta Widget Integrasi Kategori &amp; Iklan telah dipasang. Silakan buka <strong>Tampilan &rarr; Widget</strong> untuk mengedit integrasi tiap kategori.</p>
            </div>
        <?php endif; ?>
        <div class="card" style="max-width:760px; padding:20px;">
            <h2>1-Klik Bangun Kategori, Submenu &amp; Widget Integrasi (Aman untuk Website Lama)</h2>
            <p style="background:#ECFDF5; border-left:4px solid #10B981; padding:10px 12px; color:#065F46;">
                <strong>Aman untuk Website WordPress Lama:</strong> Proses ini hanya menambahkan kategori, halaman, atau widget yang belum ada. Pos berita lama, komentar, pengguna, maupun pengaturan plugin (Yoast/Rank Math, Elementor, WooCommerce, CF7, dll.) <strong>tidak akan dihapus atau ditimpa</strong>.
            </p>
            <p>Klik tombol di bawah untuk otomatis memeriksa dan melengkapi:</p>
            <ul style="list-style:disc; padding-left:20px;">
                <li>Semua 12 menu utama + submenu (Internasional, Nasional, Regional 11 wilayah, Buser Berita).</li>
                <li>Menu Navigasi Merah RINEWS.ID (jika belum ada menu sebelumnya).</li>
                <li>Widget Integrasi Kategori (Horizontal &amp; Vertikal) dan 3 Widget Gambar Iklan di <strong>Tampilan &rarr; Widget</strong> agar langsung siap diedit ulang &amp; diatur jam tayangnya.</li>
            </ul>
            <form method="post">
                <?php wp_nonce_field( 'rinews_setup_nonce' ); ?>
                <p><button type="submit" name="rinews_run_setup" class="button button-primary button-hero">Jalankan Setup Portal RINEWS.ID Sekarang</button></p>
            </form>
            <p style="margin-top:16px; display:flex; flex-wrap:wrap; gap:8px;">
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=rinews-schedule' ) ); ?>" class="button button-primary">Atur Jam Tayang &amp; Shortcode &rarr;</a>
                <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="button">Buka Pengaturan 16 Area Widget &rarr;</a>
                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">Buka Sesuaikan Tema &rarr;</a>
            </p>
        </div>
    </div>
    <?php
}
