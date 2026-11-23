<?php
/**
 * RINEWS.ID — Koleksi Widget WordPress Lengkap & Mudah Diedit
 * Termasuk Widget Integrasi Masing-Masing Kategori, Berita Viral, Slide Berita Baru,
 * Iklan Gambar (Media Uploader), YouTube Feed Otomatis, TikTok Feed, Arsip 12 Bulan, & Mungkin Terlewatkan H-1.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. WIDGET INTEGRASI MASING-MASING KATEGORI
 * Dapat dipasang di Blok Kategori Horizontal, Blok 3 Kolom Vertikal, atau Pos Baru.
 */
class RINEWS_Widget_Kategori_Berita extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_kategori',
            __( 'RINEWS: Integrasi Kategori Berita', 'rinews' ),
            array( 'description' => __( 'Menampilkan dan mengedit integrasi berita per kategori (Internasional, Nasional, Regional, Buser, Olah Raga, Iptek, Budaya, Hiburan, Bisnis).', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : 'KATEGORI BERITA';
        $cat_id     = ! empty( $instance['cat_id'] ) ? (int) $instance['cat_id'] : 0;
        $count      = ! empty( $instance['count'] ) ? (int) $instance['count'] : 4;
        $layout     = ! empty( $instance['layout'] ) ? $instance['layout'] : 'horizontal';
        $show_subs  = ! empty( $instance['show_subs'] );
        $header_bg  = ! empty( $instance['header_bg'] ) ? $instance['header_bg'] : '#C8102E';

        $query_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => $count,
            'ignore_sticky_posts' => true,
        );
        if ( $cat_id > 0 ) {
            $query_args['cat'] = $cat_id;
        }
        $q = new WP_Query( $query_args );

        $cat_link = $cat_id > 0 ? get_category_link( $cat_id ) : home_url( '/' );
        $subcats  = $cat_id > 0 ? get_categories( array( 'parent' => $cat_id, 'hide_empty' => false ) ) : array();

        echo $args['before_widget'];
        ?>
        <div class="rn-red-panel" style="background-color: <?php echo esc_attr( $header_bg ); ?>;">
            <div class="rn-panel-header">
                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:10px;">
                    <h2 class="rn-panel-title">
                        <a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $title ); ?></a>
                    </h2>
                    <?php if ( $show_subs && ! empty( $subcats ) ) : ?>
                        <div style="display:flex; flex-wrap:wrap; gap:4px;">
                            <?php foreach ( $subcats as $sub ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $sub->term_id ) ); ?>" style="background:rgba(0,0,0,0.22); color:#FFF; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700;">
                                    <?php echo esc_html( $sub->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <a class="rn-panel-more" href="<?php echo esc_url( $cat_link ); ?>">Lihat Semua &rarr;</a>
            </div>

            <?php if ( 'list_kiri_kanan' === $layout ) : ?>
                <div class="rn-posbaru-list">
                    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="rn-posbaru-item">
                            <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-thumb' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <div>
                                <span style="font-size:10px; font-weight:800; color:#B91C1C; text-transform:uppercase;">
                                    <?php $c = get_the_category(); echo ! empty( $c ) ? esc_html( $c[0]->name ) : 'BERITA'; ?>
                                </span>
                                <h3><?php the_title(); ?></h3>
                                <div class="rn-meta-line"><?php echo esc_html( get_the_date( 'd M Y · H:i' ) ); ?> WIB · <?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> dibaca</div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php elseif ( 'vertical' === $layout ) : ?>
                <div class="rn-posbaru-list">
                    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="rn-news-card">
                            <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <div class="rn-news-card-body">
                                <h3><?php the_title(); ?></h3>
                                <div class="rn-meta-line"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?> · <?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> dibaca</div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="rn-4col-grid">
                    <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="rn-news-card">
                            <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <div class="rn-news-card-body">
                                <h3><?php the_title(); ?></h3>
                                <div class="rn-meta-line"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?> · <?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> dibaca</div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? $instance['title'] : 'INTERNASIONAL';
        $cat_id    = isset( $instance['cat_id'] ) ? (int) $instance['cat_id'] : 0;
        $count     = isset( $instance['count'] ) ? (int) $instance['count'] : 4;
        $layout    = isset( $instance['layout'] ) ? $instance['layout'] : 'horizontal';
        $show_subs = isset( $instance['show_subs'] ) ? (bool) $instance['show_subs'] : true;
        $header_bg = isset( $instance['header_bg'] ) ? $instance['header_bg'] : '#C8102E';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><strong>Judul Blok Kategori:</strong></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'cat_id' ) ); ?>"><strong>Pilih Kategori / Subkategori WordPress:</strong></label>
            <?php
            wp_dropdown_categories( array(
                'show_option_all' => __( '— Semua Kategori (Pos Terbaru) —', 'rinews' ),
                'name'            => $this->get_field_name( 'cat_id' ),
                'id'              => $this->get_field_id( 'cat_id' ),
                'selected'        => $cat_id,
                'hierarchical'    => true,
                'hide_empty'      => false,
                'class'           => 'widefat',
            ) );
            ?>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><strong>Jumlah Berita:</strong></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="12" value="<?php echo esc_attr( $count ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><strong>Mode Tata Letak:</strong></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
                <option value="horizontal" <?php selected( $layout, 'horizontal' ); ?>>Horizontal 4 Kolom (Internasional, Nasional, Regional, Buser, Olah Raga)</option>
                <option value="vertical" <?php selected( $layout, 'vertical' ); ?>>Vertikal 3 Berita (Ilmu Pengetahuan, Hiburan, Budaya)</option>
                <option value="list_kiri_kanan" <?php selected( $layout, 'list_kiri_kanan' ); ?>>Daftar Thumbnail Samping (Pos Baru Kiri/Kanan)</option>
            </select>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_subs' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_subs' ) ); ?>" <?php checked( $show_subs ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_subs' ) ); ?>">Tampilkan tombol Submenu di header kategori</label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'header_bg' ) ); ?>"><strong>Warna Bingkai Panel (Hex):</strong></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'header_bg' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'header_bg' ) ); ?>" type="text" value="<?php echo esc_attr( $header_bg ); ?>" />
        </p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'title'     => sanitize_text_field( $new_instance['title'] ),
            'cat_id'    => (int) $new_instance['cat_id'],
            'count'     => max( 1, min( 12, (int) $new_instance['count'] ) ),
            'layout'    => sanitize_key( $new_instance['layout'] ),
            'show_subs' => ! empty( $new_instance['show_subs'] ),
            'header_bg' => sanitize_hex_color( $new_instance['header_bg'] ) ? $new_instance['header_bg'] : '#C8102E',
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 2. WIDGET BERITA VIRAL (Pembaca Terbanyak)
 */
class RINEWS_Widget_Berita_Viral extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_viral',
            __( 'RINEWS: Berita Viral (Pembaca Terbanyak)', 'rinews' ),
            array( 'description' => __( 'Menampilkan 3 berita dengan jumlah pembaca tertinggi secara otomatis.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'BERITA VIRAL';
        $count = ! empty( $instance['count'] ) ? (int) $instance['count'] : 3;

        $q = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $count,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ) );

        if ( ! $q->have_posts() ) {
            $q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $count ) );
        }

        echo $args['before_widget'];
        ?>
        <div class="rn-red-panel" style="height:100%; display:flex; flex-direction:column;">
            <div class="rn-panel-header">
                <h2 class="rn-panel-title">&#128293; <?php echo esc_html( $title ); ?></h2>
                <span style="font-size:10px; color:#FEE2E2;">Pembaca Terbanyak</span>
            </div>
            <div class="rn-viral-stack" style="flex:1;">
                <?php $rank = 1; while ( $q->have_posts() ) : $q->the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="rn-viral-card">
                        <div class="rn-viral-thumb">
                            <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <span class="rn-viral-badge">#<?php echo esc_html( $rank ); ?> VIRAL</span>
                            <span class="rn-viral-views"><?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?></span>
                        </div>
                        <div class="rn-viral-body">
                            <h3><?php the_title(); ?></h3>
                        </div>
                    </a>
                <?php $rank++; endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : 'BERITA VIRAL';
        $count = isset( $instance['count'] ) ? (int) $instance['count'] : 3;
        ?>
        <p>
            <label><strong>Judul Kolom:</strong></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label><strong>Jumlah Berita:</strong></label>
            <input class="tiny-text" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" min="1" max="6" value="<?php echo esc_attr( $count ); ?>" />
        </p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'title' => sanitize_text_field( $new_instance['title'] ),
            'count' => max( 1, min( 6, (int) $new_instance['count'] ) ),
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 3. WIDGET RUANG GAMBAR IKLAN (Bisa upload / pilih gambar dari Media Library WordPress)
 */
class RINEWS_Widget_Iklan_Gambar extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_iklan',
            __( 'RINEWS: Ruang Gambar Iklan', 'rinews' ),
            array( 'description' => __( 'Pasang gambar iklan pada kolom Indeks Pencarian (3 Space), Iklan Panjang, atau Iklan Bawah.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $sponsor   = ! empty( $instance['sponsor'] ) ? $instance['sponsor'] : 'IKLAN RINEWS.ID';
        $title     = ! empty( $instance['title'] ) ? $instance['title'] : 'Pasang Iklan & Advertorial Nasional';
        $image_url = ! empty( $instance['image_url'] ) ? $instance['image_url'] : RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg';
        $link_url  = ! empty( $instance['link_url'] ) ? $instance['link_url'] : home_url( '/kontak-iklan/' );
        $mode      = ! empty( $instance['mode'] ) ? $instance['mode'] : 'sidebar';

        echo $args['before_widget'];
        if ( 'leaderboard' === $mode ) :
            ?>
            <a href="<?php echo esc_url( $link_url ); ?>" class="rn-leaderboard-ad" target="_blank" rel="noopener">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                <div class="rn-leaderboard-overlay"></div>
                <div class="rn-leaderboard-content">
                    <div style="font-size:10px; font-weight:800; color:#FFFFFF; text-transform:uppercase;"><?php echo esc_html( $sponsor ); ?></div>
                    <div style="font-size:15px; font-weight:800; color:#FFFFFF; margin-top:2px;"><?php echo esc_html( $title ); ?></div>
                </div>
                <span class="rn-leaderboard-cta">Info Iklan</span>
            </a>
            <?php
        else :
            ?>
            <a href="<?php echo esc_url( $link_url ); ?>" class="rn-ad-card" target="_blank" rel="noopener">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
                <div class="rn-ad-overlay">
                    <div class="rn-ad-tag"><?php echo esc_html( $sponsor ); ?></div>
                    <h4 class="rn-ad-title"><?php echo esc_html( $title ); ?></h4>
                </div>
            </a>
            <?php
        endif;
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $sponsor   = isset( $instance['sponsor'] ) ? $instance['sponsor'] : 'SPONSOR RINEWS';
        $title     = isset( $instance['title'] ) ? $instance['title'] : 'Ruang Iklan Portal RINEWS.ID';
        $image_url = isset( $instance['image_url'] ) ? $instance['image_url'] : RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg';
        $link_url  = isset( $instance['link_url'] ) ? $instance['link_url'] : home_url( '/kontak-iklan/' );
        $mode      = isset( $instance['mode'] ) ? $instance['mode'] : 'sidebar';
        $img_id    = $this->get_field_id( 'image_url' );
        ?>
        <p>
            <label><strong>Label Sponsor:</strong></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'sponsor' ) ); ?>" type="text" value="<?php echo esc_attr( $sponsor ); ?>" />
        </p>
        <p>
            <label><strong>Judul Iklan:</strong></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label><strong>URL Gambar Iklan:</strong></label>
            <input class="widefat js-rn-img-input" id="<?php echo esc_attr( $img_id ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_url' ) ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>" />
            <button type="button" class="button js-rn-upload-btn" data-target="#<?php echo esc_attr( $img_id ); ?>" style="margin-top:5px;">Pilih / Upload Gambar</button>
        </p>
        <p>
            <label><strong>Link Tujuan Iklan:</strong></label>
            <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'link_url' ) ); ?>" type="url" value="<?php echo esc_attr( $link_url ); ?>" />
        </p>
        <p>
            <label><strong>Jenis Banner:</strong></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'mode' ) ); ?>">
                <option value="sidebar" <?php selected( $mode, 'sidebar' ); ?>>Kartu Gambar Iklan (Indeks / Bawah)</option>
                <option value="leaderboard" <?php selected( $mode, 'leaderboard' ); ?>>Iklan Panjang (Leaderboard)</option>
            </select>
        </p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'sponsor'   => sanitize_text_field( $new_instance['sponsor'] ),
            'title'     => sanitize_text_field( $new_instance['title'] ),
            'image_url' => esc_url_raw( $new_instance['image_url'] ),
            'link_url'  => esc_url_raw( $new_instance['link_url'] ),
            'mode'      => sanitize_key( $new_instance['mode'] ),
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 4. WIDGET INTEGRASI YOUTUBE OTOMATIS (RSS Channel)
 */
class RINEWS_Widget_YouTube_Feed extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_youtube',
            __( 'RINEWS: Integrasi Video YouTube Otomatis', 'rinews' ),
            array( 'description' => __( 'Menarik tayangan terbaru dari Channel ID YouTube secara otomatis.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : 'VIDEO BERITA (YOUTUBE)';
        $channel_id = ! empty( $instance['channel_id'] ) ? trim( $instance['channel_id'] ) : get_theme_mod( 'rinews_youtube_cid', '' );
        $channel_url = ! empty( $instance['channel_url'] ) ? $instance['channel_url'] : get_theme_mod( 'rinews_youtube_url', 'https://www.youtube.com/@rinewsid' );

        $videos = array();
        if ( ! empty( $channel_id ) ) {
            $cache_key = 'rinews_yt_rss_' . md5( $channel_id );
            $videos    = get_transient( $cache_key );
            if ( false === $videos ) {
                $videos = array();
                $resp   = wp_remote_get( 'https://www.youtube.com/feeds/videos.xml?channel_id=' . rawurlencode( $channel_id ), array( 'timeout' => 8 ) );
                if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
                    $xml = @simplexml_load_string( wp_remote_retrieve_body( $resp ) );
                    if ( $xml && isset( $xml->entry ) ) {
                        $i = 0;
                        foreach ( $xml->entry as $entry ) {
                            if ( $i >= 4 ) break;
                            $ns_yt   = $entry->children( 'http://www.youtube.com/xml/schemas/2015' );
                            $vid     = (string) $ns_yt->videoId;
                            $videos[] = array(
                                'videoId' => $vid,
                                'title'   => (string) $entry->title,
                                'thumb'   => 'https://i.ytimg.com/vi/' . $vid . '/hqdefault.jpg',
                            );
                            $i++;
                        }
                    }
                }
                set_transient( $cache_key, $videos, 15 * MINUTE_IN_SECONDS );
            }
        }

        echo $args['before_widget'];
        ?>
        <div class="rn-video-blue-panel">
            <div class="rn-video-head">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="width:32px; height:32px; border-radius:8px; background:#FF0000; color:#FFF; display:inline-flex; align-items:center; justify-content:center;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M21.58 7.19a2.75 2.75 0 0 0-1.94-1.95C17.93 4.75 12 4.75 12 4.75s-5.93 0-7.64.49A2.75 2.75 0 0 0 2.42 7.2 28.8 28.8 0 0 0 2 12a28.8 28.8 0 0 0 .42 4.81 2.75 2.75 0 0 0 1.94 1.95c1.71.49 7.64.49 7.64.49s5.93 0 7.64-.49a2.75 2.75 0 0 0 1.94-1.95A28.8 28.8 0 0 0 22 12a28.8 28.8 0 0 0-.42-4.81ZM10 15.2V8.8l5.5 3.2-5.5 3.2Z"/></svg>
                    </span>
                    <div>
                        <h2 style="margin:0; font-size:14px; font-weight:900; color:#FFFFFF;"><?php echo esc_html( $title ); ?></h2>
                        <a href="<?php echo esc_url( $channel_url ); ?>" target="_blank" rel="noopener" style="font-size:11px; color:#FFFFFF; text-decoration:underline;"><?php echo esc_html( $channel_url ); ?></a>
                    </div>
                </div>
            </div>
            <div class="rn-4col-grid">
                <?php if ( ! empty( $videos ) ) : ?>
                    <?php foreach ( $videos as $v ) : ?>
                        <button type="button" class="rn-yt-card js-rn-play-yt" data-yt-id="<?php echo esc_attr( $v['videoId'] ); ?>" data-yt-title="<?php echo esc_attr( $v['title'] ); ?>">
                            <div class="rn-yt-thumb">
                                <img src="<?php echo esc_url( $v['thumb'] ); ?>" alt="<?php echo esc_attr( $v['title'] ); ?>" />
                            </div>
                            <div style="padding:10px; text-align:left;">
                                <strong style="font-size:12.5px; line-height:1.35; display:block;"><?php echo esc_html( $v['title'] ); ?></strong>
                            </div>
                        </button>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php
                    $fallback_imgs = array( 'news_istana_jakarta.jpg', 'news_global_summit.jpg', 'news_regional_indonesia.jpg', 'news_bisnis_scbd.jpg' );
                    foreach ( $fallback_imgs as $idx => $img ) :
                        ?>
                        <a href="<?php echo esc_url( $channel_url ); ?>" target="_blank" rel="noopener" class="rn-yt-card">
                            <div class="rn-yt-thumb">
                                <img src="<?php echo esc_url( RINEWS_URI . '/assets/images/' . $img ); ?>" alt="Video Berita RINEWS" />
                            </div>
                            <div style="padding:10px;">
                                <strong style="font-size:12.5px; line-height:1.35;">Laporan Video Berita RINEWS.ID #<?php echo esc_html( $idx + 1 ); ?></strong>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title       = isset( $instance['title'] ) ? $instance['title'] : 'VIDEO BERITA (YOUTUBE)';
        $channel_id  = isset( $instance['channel_id'] ) ? $instance['channel_id'] : '';
        $channel_url = isset( $instance['channel_url'] ) ? $instance['channel_url'] : 'https://www.youtube.com/@rinewsid';
        ?>
        <p><label><strong>Judul:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p><label><strong>Channel ID YouTube (diawali UC...):</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'channel_id' ) ); ?>" type="text" value="<?php echo esc_attr( $channel_id ); ?>" placeholder="UCxxxxxxxxxxxxxxxxxxxxxx" /></p>
        <p><label><strong>URL Akun YouTube:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'channel_url' ) ); ?>" type="url" value="<?php echo esc_attr( $channel_url ); ?>" /></p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'title'       => sanitize_text_field( $new_instance['title'] ),
            'channel_id'  => sanitize_text_field( $new_instance['channel_id'] ),
            'channel_url' => esc_url_raw( $new_instance['channel_url'] ),
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 5. WIDGET INTEGRASI TIKTOK VIDEO OTOMATIS (oEmbed)
 */
class RINEWS_Widget_TikTok_Feed extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_tiktok',
            __( 'RINEWS: Integrasi Video TikTok Otomatis', 'rinews' ),
            array( 'description' => __( 'Menampilkan 4 tayangan TikTok vertikal dari akun atau tautan video TikTok.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title      = ! empty( $instance['title'] ) ? $instance['title'] : 'TIKTOK VIDEO — 4 TAYANGAN TERATAS';
        $handle     = ! empty( $instance['handle'] ) ? $instance['handle'] : get_theme_mod( 'rinews_tiktok_handle', '@ri.news.portal' );
        $account    = ! empty( $instance['account_url'] ) ? $instance['account_url'] : get_theme_mod( 'rinews_tiktok_url', 'https://www.tiktok.com/@ri.news.portal?lang=id-ID' );
        $urls_raw   = ! empty( $instance['video_urls'] ) ? $instance['video_urls'] : "https://www.tiktok.com/@ri.news.portal/video/7708887155644452110";
        $urls       = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $urls_raw ) ) );

        $items = array();
        foreach ( array_slice( $urls, 0, 4 ) as $u ) {
            $cache_key = 'rinews_tt_' . md5( $u );
            $data      = get_transient( $cache_key );
            if ( false === $data ) {
                $resp = wp_remote_get( 'https://www.tiktok.com/oembed?url=' . rawurlencode( $u ), array( 'timeout' => 7 ) );
                if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
                    $json = json_decode( wp_remote_retrieve_body( $resp ), true );
                    if ( ! empty( $json ) ) {
                        $data = array(
                            'title' => isset( $json['title'] ) ? $json['title'] : 'Tayangan TikTok ' . $handle,
                            'thumb' => isset( $json['thumbnail_url'] ) ? $json['thumbnail_url'] : RINEWS_URI . '/assets/images/news_istana_jakarta.jpg',
                            'url'   => $u,
                        );
                    }
                }
                if ( ! is_array( $data ) ) {
                    $data = array( 'title' => 'Tayangan TikTok ' . $handle, 'thumb' => RINEWS_URI . '/assets/images/news_istana_jakarta.jpg', 'url' => $u );
                }
                set_transient( $cache_key, $data, 30 * MINUTE_IN_SECONDS );
            }
            $items[] = $data;
        }

        // Lengkapi otomatis hingga 4 Tayangan Teratas dari berita terbaru & terpopuler portal
        if ( count( $items ) < 4 ) {
            $fallback_q = new WP_Query( array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'meta_key'       => 'rinews_post_views',
                'orderby'        => 'meta_value_num date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            ) );
            if ( $fallback_q->have_posts() ) {
                while ( $fallback_q->have_posts() && count( $items ) < 4 ) {
                    $fallback_q->the_post();
                    $thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    $items[] = array(
                        'title' => get_the_title(),
                        'thumb' => $thumb ? $thumb : RINEWS_URI . '/assets/images/news_regional_indonesia.jpg',
                        'url'   => $account,
                    );
                }
                wp_reset_postdata();
            }
        }

        if ( count( $items ) < 4 ) {
            $defaults = array(
                array( 'title' => 'Laporan Langsung Istana Negara & Kebijakan Nasional', 'thumb' => RINEWS_URI . '/assets/images/news_istana_jakarta.jpg', 'url' => $account ),
                array( 'title' => 'Buser Berita: Operasi Satgas Penegakan Hukum', 'thumb' => RINEWS_URI . '/assets/images/news_hukum_kpk.jpg', 'url' => $account ),
                array( 'title' => 'Sorotan Suporter Timnas Indonesia di GBK', 'thumb' => RINEWS_URI . '/assets/images/news_stadion_olahraga.jpg', 'url' => $account ),
                array( 'title' => 'Festival Batik Tulis & Budaya Nusantara', 'thumb' => RINEWS_URI . '/assets/images/news_budaya_nusantara.jpg', 'url' => $account ),
            );
            foreach ( $defaults as $def ) {
                if ( count( $items ) >= 4 ) {
                    break;
                }
                $items[] = $def;
            }
        }

        echo $args['before_widget'];
        ?>
        <div class="rn-video-blue-panel">
            <div class="rn-video-head">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="width:32px; height:32px; border-radius:8px; background:#000; border:1px solid rgba(255,255,255,0.25); color:#FFF; display:inline-flex; align-items:center; justify-content:center;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 0 0-.79-.05A6.34 6.34 0 0 0 3.15 15a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.87a8.16 8.16 0 0 0 4.76 1.52v-3.4a4.85 4.85 0 0 1-1-.3Z"/></svg>
                    </span>
                    <div>
                        <h2 style="margin:0; font-size:14px; font-weight:900; color:#FFFFFF;"><?php echo esc_html( $title ); ?></h2>
                        <a href="<?php echo esc_url( $account ); ?>" target="_blank" rel="noopener" style="font-size:11px; color:#FFFFFF; text-decoration:underline;"><?php echo esc_html( $handle ); ?></a>
                    </div>
                </div>
            </div>
            <div class="rn-tiktok-grid">
                <?php
                $rank = 1;
                foreach ( array_slice( $items, 0, 4 ) as $it ) :
                    ?>
                    <a href="<?php echo esc_url( $it['url'] ); ?>" target="_blank" rel="noopener" class="rn-tiktok-card">
                        <img src="<?php echo esc_url( $it['thumb'] ); ?>" alt="<?php echo esc_attr( $it['title'] ); ?>" />
                        <span style="position:absolute; top:10px; left:10px; z-index:2; background:#C8102E; color:#FFF; font-size:10px; font-weight:800; padding:2px 7px; border-radius:4px;">#<?php echo (int) $rank; ?> TERATAS</span>
                        <div class="rn-tiktok-card-body">
                            <span style="font-size:10px; font-weight:800; color:#FFFFFF;"><?php echo esc_html( $handle ); ?></span>
                            <div style="font-size:12.5px; font-weight:800; line-height:1.35; margin-top:2px; color:#FFFFFF;"><?php echo esc_html( $it['title'] ); ?></div>
                        </div>
                    </a>
                <?php $rank++; endforeach; ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title       = isset( $instance['title'] ) ? $instance['title'] : 'TIKTOK VIDEO — 4 TAYANGAN TERATAS';
        $handle      = isset( $instance['handle'] ) ? $instance['handle'] : '@ri.news.portal';
        $account_url = isset( $instance['account_url'] ) ? $instance['account_url'] : 'https://www.tiktok.com/@ri.news.portal?lang=id-ID';
        $video_urls  = isset( $instance['video_urls'] ) ? $instance['video_urls'] : 'https://www.tiktok.com/@ri.news.portal/video/7708887155644452110';
        ?>
        <p><label><strong>Judul:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p><label><strong>Username TikTok (@...):</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'handle' ) ); ?>" type="text" value="<?php echo esc_attr( $handle ); ?>" /></p>
        <p><label><strong>URL Akun TikTok:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'account_url' ) ); ?>" type="url" value="<?php echo esc_attr( $account_url ); ?>" /></p>
        <p><label><strong>URL Video TikTok (1 URL per baris):</strong></label><textarea class="widefat" rows="4" name="<?php echo esc_attr( $this->get_field_name( 'video_urls' ) ); ?>"><?php echo esc_textarea( $video_urls ); ?></textarea></p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'title'       => sanitize_text_field( $new_instance['title'] ),
            'handle'      => sanitize_text_field( $new_instance['handle'] ),
            'account_url' => esc_url_raw( $new_instance['account_url'] ),
            'video_urls'  => sanitize_textarea_field( $new_instance['video_urls'] ),
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 6. WIDGET ARSIP 12 BULAN (Tulisan Bulan & Tahun Saja, Font Putih di Kotak Hitam)
 */
class RINEWS_Widget_Arsip_12_Bulan extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_arsip_12',
            __( 'RINEWS: Arsip 12 Bulan (Tulisan Saja)', 'rinews' ),
            array( 'description' => __( 'Menampilkan 12 arsip catatan jurnal bulanan (Bulan dan Tahun saja) dengan font putih.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'ARSIP';
        echo $args['before_widget'];
        ?>
        <div class="rn-black-box" style="height:100%;">
            <h2 class="rn-black-heading">&#128197; <?php echo esc_html( $title ); ?></h2>
            <ul class="rn-archive-12-list">
                <?php
                for ( $i = 0; $i < 12; $i++ ) {
                    $ts    = strtotime( "-{$i} month", current_time( 'timestamp' ) );
                    $year  = date_i18n( 'Y', $ts );
                    $month = date_i18n( 'm', $ts );
                    $label = date_i18n( 'F Y', $ts );
                    $link  = get_month_link( (int) $year, (int) $month );
                    echo '<li><a href="' . esc_url( $link ) . '">' . esc_html( $label ) . '</a></li>';
                }
                ?>
            </ul>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : 'ARSIP';
        echo '<p><label><strong>Judul:</strong></label><input class="widefat" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array( 'title' => sanitize_text_field( $new_instance['title'] ) );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 7. WIDGET MUNGKIN TERLEWATKAN (Berita 1 Hari Sebelum Hari Ini, Judul Saja)
 */
class RINEWS_Widget_Kemarin_Terlewatkan extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_kemarin',
            __( 'RINEWS: Mungkin Terlewatkan (Berita Kemarin H-1)', 'rinews' ),
            array( 'description' => __( 'Menampilkan judul berita saja dari 1 hari sebelum hari ini dengan font putih.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'MUNGKIN TERLEWATKAN';
        $yesterday = strtotime( '-1 day', current_time( 'timestamp' ) );

        $q = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => 6,
            'date_query'     => array(
                array(
                    'year'  => (int) date( 'Y', $yesterday ),
                    'month' => (int) date( 'm', $yesterday ),
                    'day'   => (int) date( 'd', $yesterday ),
                ),
            ),
        ) );

        if ( ! $q->have_posts() ) {
            $q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 5, 'offset' => 1 ) );
        }

        echo $args['before_widget'];
        ?>
        <div class="rn-black-box" style="height:100%;">
            <h2 class="rn-black-heading">&#10024; <?php echo esc_html( $title ); ?></h2>
            <ul class="rn-yesterday-list">
                <?php while ( $q->have_posts() ) : $q->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
        <?php
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : 'MUNGKIN TERLEWATKAN';
        echo '<p><label><strong>Judul:</strong></label><input class="widefat" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array( 'title' => sanitize_text_field( $new_instance['title'] ) );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 8. WIDGET SLIDE BERITA BARU (6 Pos Terbaru + 3 Kategori Teratas Bawahnya)
 */
class RINEWS_Widget_Slide_Berita_Baru extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_slide',
            __( 'RINEWS: Slide Berita Baru (6 Slide + 3 Teratas)', 'rinews' ),
            array( 'description' => __( 'Slide 6 berita terbaru teratas dan 3 kotak kategori (Internasional, Nasional, Regional) di bawahnya.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $slide_count = ! empty( $instance['slide_count'] ) ? (int) $instance['slide_count'] : 6;
        $cat_1       = ! empty( $instance['cat_1'] ) ? (int) $instance['cat_1'] : 0;
        $cat_2       = ! empty( $instance['cat_2'] ) ? (int) $instance['cat_2'] : 0;
        $cat_3       = ! empty( $instance['cat_3'] ) ? (int) $instance['cat_3'] : 0;

        $q = new WP_Query( array(
            'post_type'           => 'post',
            'posts_per_page'      => $slide_count,
            'ignore_sticky_posts' => true,
        ) );

        $slides = array();
        while ( $q->have_posts() ) {
            $q->the_post();
            $cats = get_the_category();
            $slides[] = array(
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'img'   => rinews_get_post_image_url( get_the_ID(), 'rinews-slide' ),
                'cat'   => ! empty( $cats ) ? $cats[0]->name : 'TERBARU',
                'date'  => get_the_date( 'd M Y · H:i' ) . ' WIB',
            );
        }
        wp_reset_postdata();

        echo $args['before_widget'];
        ?>
        <div class="rn-slide-wrapper">
            <div class="rn-slider-card js-rn-slider">
                <div class="rn-slider-header">
                    <strong style="font-size:12px; letter-spacing:0.06em;">SLIDE BERITA BARU (6 POS TERATAS)</strong>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <button type="button" class="js-rn-prev" style="background:rgba(0,0,0,0.25); color:#FFF; border:none; border-radius:4px; padding:2px 8px; cursor:pointer; font-weight:800;">&lsaquo;</button>
                        <button type="button" class="js-rn-next" style="background:rgba(0,0,0,0.25); color:#FFF; border:none; border-radius:4px; padding:2px 8px; cursor:pointer; font-weight:800;">&rsaquo;</button>
                    </div>
                </div>
                <div class="rn-slider-stage">
                    <?php foreach ( $slides as $idx => $s ) : ?>
                        <a href="<?php echo esc_url( $s['link'] ); ?>" class="rn-slide-item <?php echo 0 === $idx ? 'is-active' : ''; ?>">
                            <img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" />
                            <div class="rn-slide-caption">
                                <h3><?php echo esc_html( $s['title'] ); ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="rn-slide-thumbs">
                    <?php foreach ( $slides as $idx => $s ) : ?>
                        <button type="button" class="rn-slide-thumb-btn <?php echo 0 === $idx ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $idx ); ?>" aria-label="<?php echo esc_attr( $s['title'] ); ?>">
                            <img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>" />
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 3 Kolom di Bawah Slide: Internasional, Nasional, Regional Teratas -->
            <div class="rn-top3-grid">
                <?php
                $slots = array(
                    array( 'label' => 'INTERNASIONAL TERATAS', 'slug' => 'internasional', 'cid' => $cat_1 ),
                    array( 'label' => 'NASIONAL TERATAS', 'slug' => 'nasional', 'cid' => $cat_2 ),
                    array( 'label' => 'REGIONAL TERATAS', 'slug' => 'regional', 'cid' => $cat_3 ),
                );
                foreach ( $slots as $slot ) {
                    $q_top = null;
                    if ( $slot['cid'] > 0 ) {
                        $q_top = new WP_Query( array( 'cat' => $slot['cid'], 'posts_per_page' => 1 ) );
                    } else {
                        $q_top = new WP_Query( array( 'category_name' => $slot['slug'], 'posts_per_page' => 1 ) );
                    }
                    if ( ! $q_top->have_posts() ) {
                        $q_top = new WP_Query( array( 'posts_per_page' => 1 ) );
                    }
                    while ( $q_top->have_posts() ) : $q_top->the_post();
                        ?>
                        <a href="<?php the_permalink(); ?>" class="rn-top3-card">
                            <div class="rn-top3-head"><?php echo esc_html( $slot['label'] ); ?></div>
                            <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                            <h4><?php the_title(); ?></h4>
                        </a>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                }
                ?>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $slide_count = isset( $instance['slide_count'] ) ? (int) $instance['slide_count'] : 6;
        $cat_1       = isset( $instance['cat_1'] ) ? (int) $instance['cat_1'] : 0;
        $cat_2       = isset( $instance['cat_2'] ) ? (int) $instance['cat_2'] : 0;
        $cat_3       = isset( $instance['cat_3'] ) ? (int) $instance['cat_3'] : 0;
        ?>
        <p><label><strong>Jumlah Slide Teratas:</strong></label><input class="tiny-text" name="<?php echo esc_attr( $this->get_field_name( 'slide_count' ) ); ?>" type="number" min="3" max="8" value="<?php echo esc_attr( $slide_count ); ?>" /></p>
        <p><label><strong>Kategori Kotak 1 (Default: Internasional):</strong></label><?php wp_dropdown_categories( array( 'show_option_all' => 'Otomatis (Internasional)', 'name' => $this->get_field_name( 'cat_1' ), 'selected' => $cat_1, 'hide_empty' => false, 'class' => 'widefat' ) ); ?></p>
        <p><label><strong>Kategori Kotak 2 (Default: Nasional):</strong></label><?php wp_dropdown_categories( array( 'show_option_all' => 'Otomatis (Nasional)', 'name' => $this->get_field_name( 'cat_2' ), 'selected' => $cat_2, 'hide_empty' => false, 'class' => 'widefat' ) ); ?></p>
        <p><label><strong>Kategori Kotak 3 (Default: Regional):</strong></label><?php wp_dropdown_categories( array( 'show_option_all' => 'Otomatis (Regional)', 'name' => $this->get_field_name( 'cat_3' ), 'selected' => $cat_3, 'hide_empty' => false, 'class' => 'widefat' ) ); ?></p>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'slide_count' => max( 3, min( 8, (int) $new_instance['slide_count'] ) ),
            'cat_1'       => (int) $new_instance['cat_1'],
            'cat_2'       => (int) $new_instance['cat_2'],
            'cat_3'       => (int) $new_instance['cat_3'],
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

/**
 * 9. WIDGET KHUSUS LIVE STREAMING PORTRAIT (TERINTEGRASI TIKTOK LIVE & IZIN ADMIN)
 */
function rinews_handle_public_live_request() {
    if ( ! isset( $_POST['rinews_live_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rinews_live_nonce'] ) ), 'rinews_live_request_action' ) ) {
        wp_safe_redirect( wp_get_referer() ? wp_get_referer() : home_url( '/' ) );
        exit;
    }

    $name  = isset( $_POST['rn_live_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_live_name'] ) ) : '';
    $tiktok = isset( $_POST['rn_live_handle'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_live_handle'] ) ) : '';
    $url   = isset( $_POST['rn_live_url'] ) ? esc_url_raw( wp_unslash( $_POST['rn_live_url'] ) ) : '';
    $topic = isset( $_POST['rn_live_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['rn_live_topic'] ) ) : '';

    if ( $name && $tiktok && $topic ) {
        $handle_clean = '@' . ltrim( $tiktok, '@' );
        if ( empty( $url ) ) {
            $url = 'https://www.tiktok.com/' . $handle_clean . '/live';
        }
        $requests = get_option( 'rinews_live_stream_requests', array() );
        if ( ! is_array( $requests ) ) {
            $requests = array();
        }
        array_unshift(
            $requests,
            array(
                'id'         => uniqid( 'live_', false ),
                'name'       => $name,
                'handle'     => $handle_clean,
                'url'        => $url,
                'topic'      => $topic,
                'status'     => 'pending',
                'created_at' => current_time( 'mysql' ),
            )
        );
        update_option( 'rinews_live_stream_requests', array_slice( $requests, 0, 30 ) );
    }

    $redirect = wp_get_referer() ? add_query_arg( 'rinews_live_sent', '1', wp_get_referer() ) : home_url( '/?rinews_live_sent=1#rinews-live-streaming' );
    wp_safe_redirect( $redirect . '#rinews-live-streaming' );
    exit;
}
add_action( 'admin_post_rinews_live_request', 'rinews_handle_public_live_request' );
add_action( 'admin_post_nopriv_rinews_live_request', 'rinews_handle_public_live_request' );

class RINEWS_Widget_Live_Streaming extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'rinews_widget_live_streaming',
            __( 'RINEWS: LIVE Streaming Portrait & TikTok Live (Izin Admin)', 'rinews' ),
            array( 'description' => __( 'Pemutar Video Streaming Portrait 9:16 di tengah atas copyright dengan pengaturan layar, integrasi akun TikTok Live personal yang diizinkan, serta peraturan singkat di bawah layar.', 'rinews' ) )
        );
    }

    public function widget( $args, $instance ) {
        if ( function_exists( 'rinews_is_widget_scheduled' ) && ! rinews_is_widget_scheduled( $instance ) ) {
            return;
        }
        $title         = ! empty( $instance['title'] ) ? $instance['title'] : 'LIVE Streaming';
        $is_live       = isset( $instance['is_live_active'] ) ? (bool) $instance['is_live_active'] : true;
        $broadcaster   = ! empty( $instance['broadcaster_name'] ) ? $instance['broadcaster_name'] : 'RI News Portal (Siaran Resmi)';
        $tiktok_handle = ! empty( $instance['tiktok_handle'] ) ? $instance['tiktok_handle'] : '@ri.news.portal';
        $tiktok_live   = ! empty( $instance['tiktok_live_url'] ) ? $instance['tiktok_live_url'] : 'https://www.tiktok.com/' . $tiktok_handle . '/live';
        $stream_url    = ! empty( $instance['stream_video_url'] ) ? $instance['stream_video_url'] : 'https://www.rinews.id/wp-content/uploads/2025/07/Video-Spot-RINEWS.mp4';
        $topic         = ! empty( $instance['stream_topic'] ) ? $instance['stream_topic'] : 'Siaran Langsung Portal Berita RINEWS.ID & Penyiar Terverifikasi';
        $screen_size   = ! empty( $instance['screen_size'] ) ? $instance['screen_size'] : 'standard';
        $screen_fit    = ! empty( $instance['screen_fit'] ) ? $instance['screen_fit'] : 'cover';
        $rules         = ! empty( $instance['rules_summary'] ) ? $instance['rules_summary'] : '1. Siaran terbuka untuk umum dengan izin & persetujuan resmi Admin RINEWS.ID. 2. Wajib menautkan akun TikTok Live aktif serta mematuhi Kode Etik Jurnalistik, UU ITE, dan bebas SARA/hoaks. 3. Admin berhak menghentikan siaran sewaktu-waktu bila terjadi pelanggaran.';

        $widget_uid    = esc_attr( $this->id ? $this->id : 'rn_live_default' );
        $width_px      = 'compact' === $screen_size ? '275px' : ( 'wide' === $screen_size ? '395px' : '335px' );
        $clean_handle  = ltrim( $tiktok_handle, '@' );
        $sent_notice   = isset( $_GET['rinews_live_sent'] ) && '1' === $_GET['rinews_live_sent'];

        echo $args['before_widget'];
        ?>
        <div class="rn-live-card" data-live-widget="<?php echo $widget_uid; ?>">
            <div class="rn-live-badge">
                <span class="rn-live-dot <?php echo $is_live ? 'is-on' : ''; ?>"></span>
                <span><?php echo $is_live ? 'ON AIR · TIKTOK LIVE PORTRAIT 9:16' : 'STANDBY · MENUNGGU SIARAN'; ?></span>
            </div>

            <h2 class="rn-live-heading"><?php echo esc_html( $title ); ?></h2>
            <p class="rn-live-topic"><?php echo esc_html( $topic ); ?></p>
            <p class="rn-live-credit">
                Penyiar Diizinkan: <strong><?php echo esc_html( $broadcaster ); ?></strong> &bull;
                <a href="<?php echo esc_url( $tiktok_live ); ?>" target="_blank" rel="noopener">TikTok Live @<?php echo esc_html( $clean_handle ); ?></a>
            </p>

            <!-- Layar Portrait 9:16 Kompatibel TikTok Live -->
            <div class="rn-live-portrait-frame" id="frame-<?php echo $widget_uid; ?>" style="max-width: <?php echo esc_attr( $width_px ); ?>;">
                <div class="rn-live-top-bar">
                    <span class="rn-live-pill"><?php echo $is_live ? 'LIVE' : 'STANDBY'; ?></span>
                    <span>@<?php echo esc_html( $clean_handle ); ?> &middot; 9:16</span>
                </div>

                <video
                    id="video-<?php echo $widget_uid; ?>"
                    src="<?php echo esc_url( $stream_url ); ?>"
                    autoplay
                    muted
                    loop
                    playsinline
                    style="object-fit: <?php echo esc_attr( $screen_fit ); ?>;"
                ></video>

                <div class="rn-live-bottom-bar">
                    <div style="font-size:11px; font-weight:800; color:#FFFFFF;"><?php echo esc_html( $broadcaster ); ?></div>
                    <div style="font-size:12px; font-weight:700; color:#FFFFFF; margin-bottom:6px;"><?php echo esc_html( $topic ); ?></div>
                    <a href="<?php echo esc_url( $tiktok_live ); ?>" target="_blank" rel="noopener" class="rn-live-tiktok-cta">
                        Tonton di TikTok Live (@<?php echo esc_html( $clean_handle ); ?>) &rarr;
                    </a>
                </div>
            </div>

            <!-- Pengaturan Layar -->
            <div class="rn-live-controls">
                <button type="button" class="rn-live-btn" onclick="var v=document.getElementById('video-<?php echo $widget_uid; ?>'); if(v.paused){v.play();this.textContent='Jeda';}else{v.pause();this.textContent='Putar';}">Jeda</button>
                <button type="button" class="rn-live-btn" onclick="var v=document.getElementById('video-<?php echo $widget_uid; ?>'); v.muted=!v.muted; this.textContent=v.muted?'Suara Mati':'Suara Aktif';">Suara Mati</button>
                <button type="button" class="rn-live-btn" onclick="document.getElementById('frame-<?php echo $widget_uid; ?>').style.maxWidth='275px';">Layar Kecil</button>
                <button type="button" class="rn-live-btn" onclick="document.getElementById('frame-<?php echo $widget_uid; ?>').style.maxWidth='335px';">Standar</button>
                <button type="button" class="rn-live-btn" onclick="document.getElementById('frame-<?php echo $widget_uid; ?>').style.maxWidth='395px';">Lebar</button>
                <button type="button" class="rn-live-btn" onclick="var v=document.getElementById('video-<?php echo $widget_uid; ?>'); v.style.objectFit=(v.style.objectFit==='contain'?'cover':'contain'); this.textContent='Mode: '+(v.style.objectFit==='contain'?'Utuh':'Penuh');">Mode: <?php echo 'contain' === $screen_fit ? 'Utuh' : 'Penuh'; ?></button>
                <button type="button" class="rn-live-btn" onclick="var f=document.getElementById('frame-<?php echo $widget_uid; ?>'); if(f.requestFullscreen){f.requestFullscreen();}">Layar Penuh</button>
                <button type="button" class="rn-live-btn is-primary" onclick="var box=document.getElementById('reqbox-<?php echo $widget_uid; ?>'); box.style.display=(box.style.display==='none'?'block':'none');">Ajukan Izin Siaran (Umum)</button>
            </div>

            <?php if ( $sent_notice ) : ?>
                <div style="margin:10px auto; max-width:560px; padding:10px 14px; border-radius:8px; background:#064E3B; border:1px solid #10B981; color:#ECFDF5; font-size:12px; font-weight:700;">
                    Permohonan izin siaran Anda telah terkirim! Admin akan memproses integrasi akun TikTok Live Anda melalui Widget Pengaturan LIVE Streaming.
                </div>
            <?php endif; ?>

            <!-- Formulir Permohonan Siaran Umum (Dengan Izin Admin) -->
            <div id="reqbox-<?php echo $widget_uid; ?>" style="display:none; margin:12px auto 0; max-width:560px; text-align:left; background:#0F172A; border:1px solid #334155; border-radius:12px; padding:14px;">
                <h4 style="margin:0 0 8px; font-size:13px; font-weight:900; color:#FFFFFF;">Ajukan Izin Siaran &amp; Integrasi TikTok Live</h4>
                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:grid; gap:8px;">
                    <input type="hidden" name="action" value="rinews_live_request" />
                    <?php wp_nonce_field( 'rinews_live_request_action', 'rinews_live_nonce' ); ?>
                    <input type="text" name="rn_live_name" placeholder="Nama Penyiar / Media *" required style="padding:8px 10px; border-radius:6px; border:1px solid #475569; background:#020617; color:#FFF; font-size:12px;" />
                    <input type="text" name="rn_live_handle" placeholder="Username TikTok (@akun) *" required style="padding:8px 10px; border-radius:6px; border:1px solid #475569; background:#020617; color:#FFF; font-size:12px;" />
                    <input type="url" name="rn_live_url" placeholder="URL TikTok Live (https://www.tiktok.com/@akun/live)" style="padding:8px 10px; border-radius:6px; border:1px solid #475569; background:#020617; color:#FFF; font-size:12px;" />
                    <input type="text" name="rn_live_topic" placeholder="Judul / Topik Liputan Siaran *" required style="padding:8px 10px; border-radius:6px; border:1px solid #475569; background:#020617; color:#FFF; font-size:12px;" />
                    <button type="submit" style="padding:9px 14px; border:none; border-radius:8px; background:#C8102E; color:#FFF; font-size:12px; font-weight:800; cursor:pointer;">Kirim Permohonan Izin ke Admin</button>
                </form>
            </div>

            <!-- Penjelasan Peraturan Singkat di Bawah Layar -->
            <div class="rn-live-rules">
                <strong>PERATURAN SINGKAT PENGGUNAAN LIVE STREAMING:</strong>
                <p><?php echo esc_html( $rules ); ?></p>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title            = isset( $instance['title'] ) ? $instance['title'] : 'LIVE Streaming';
        $is_live_active   = isset( $instance['is_live_active'] ) ? (bool) $instance['is_live_active'] : true;
        $broadcaster_name = isset( $instance['broadcaster_name'] ) ? $instance['broadcaster_name'] : 'RI News Portal (Siaran Resmi)';
        $tiktok_handle    = isset( $instance['tiktok_handle'] ) ? $instance['tiktok_handle'] : '@ri.news.portal';
        $tiktok_live_url  = isset( $instance['tiktok_live_url'] ) ? $instance['tiktok_live_url'] : 'https://www.tiktok.com/@ri.news.portal/live';
        $stream_video_url = isset( $instance['stream_video_url'] ) ? $instance['stream_video_url'] : 'https://www.rinews.id/wp-content/uploads/2025/07/Video-Spot-RINEWS.mp4';
        $stream_topic     = isset( $instance['stream_topic'] ) ? $instance['stream_topic'] : 'Siaran Langsung Portal Berita RINEWS.ID & Kontributor Terverifikasi';
        $screen_size      = isset( $instance['screen_size'] ) ? $instance['screen_size'] : 'standard';
        $screen_fit       = isset( $instance['screen_fit'] ) ? $instance['screen_fit'] : 'cover';
        $rules_summary    = isset( $instance['rules_summary'] ) ? $instance['rules_summary'] : '1. Siaran terbuka untuk umum dengan izin & persetujuan resmi Admin RINEWS.ID. 2. Wajib menautkan akun TikTok Live aktif serta mematuhi Kode Etik Jurnalistik & UU ITE. 3. Admin berhak menghentikan siaran sewaktu-waktu bila terjadi pelanggaran.';

        $requests = get_option( 'rinews_live_stream_requests', array() );
        ?>
        <p><label><input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'is_live_active' ) ); ?>" value="1" <?php checked( $is_live_active ); ?> /> <strong>Status Siaran ON AIR (Aktif)</strong></label></p>
        <p><label><strong>Judul Seksi:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p><label><strong>Nama Penyiar yang Diizinkan Admin:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'broadcaster_name' ) ); ?>" type="text" value="<?php echo esc_attr( $broadcaster_name ); ?>" /></p>
        <p><label><strong>Username TikTok Live Personal (@akun):</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'tiktok_handle' ) ); ?>" type="text" value="<?php echo esc_attr( $tiktok_handle ); ?>" /></p>
        <p><label><strong>URL TikTok Live Personal:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'tiktok_live_url' ) ); ?>" type="url" value="<?php echo esc_attr( $tiktok_live_url ); ?>" /></p>
        <p><label><strong>URL Video Stream / MP4 Portrait:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'stream_video_url' ) ); ?>" type="url" value="<?php echo esc_attr( $stream_video_url ); ?>" /></p>
        <p><label><strong>Topik / Keterangan Siaran:</strong></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'stream_topic' ) ); ?>" type="text" value="<?php echo esc_attr( $stream_topic ); ?>" /></p>
        <p>
            <label><strong>Ukuran Default Layar Portrait:</strong></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'screen_size' ) ); ?>">
                <option value="compact" <?php selected( $screen_size, 'compact' ); ?>>Kecil (275px)</option>
                <option value="standard" <?php selected( $screen_size, 'standard' ); ?>>Standar (335px)</option>
                <option value="wide" <?php selected( $screen_size, 'wide' ); ?>>Lebar (395px)</option>
            </select>
        </p>
        <p>
            <label><strong>Mode Rasio Layar Portrait:</strong></label>
            <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'screen_fit' ) ); ?>">
                <option value="cover" <?php selected( $screen_fit, 'cover' ); ?>>Penuh (Cover)</option>
                <option value="contain" <?php selected( $screen_fit, 'contain' ); ?>>Utuh (Contain)</option>
            </select>
        </p>
        <p><label><strong>Peraturan Singkat di Bawah Layar:</strong></label><textarea class="widefat" rows="3" name="<?php echo esc_attr( $this->get_field_name( 'rules_summary' ) ); ?>"><?php echo esc_textarea( $rules_summary ); ?></textarea></p>
        <?php if ( is_array( $requests ) && ! empty( $requests ) ) : ?>
            <div style="background:#F8FAFC; border:1px solid #CBD5E1; border-radius:6px; padding:8px; margin-top:8px;">
                <strong>Permohonan Izin Siaran Terbaru:</strong>
                <ul style="margin:6px 0 0 16px; font-size:12px;">
                    <?php foreach ( array_slice( $requests, 0, 5 ) as $rq ) : ?>
                        <li style="margin-bottom:4px;">
                            <strong><?php echo esc_html( $rq['name'] ); ?></strong> (<?php echo esc_html( $rq['handle'] ); ?>) — <?php echo esc_html( $rq['topic'] ); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php
        if ( function_exists( 'rinews_render_widget_schedule_fields' ) ) {
            rinews_render_widget_schedule_fields( $this, $instance );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $base = array(
            'title'            => sanitize_text_field( $new_instance['title'] ),
            'is_live_active'   => ! empty( $new_instance['is_live_active'] ) ? 1 : 0,
            'broadcaster_name' => sanitize_text_field( $new_instance['broadcaster_name'] ),
            'tiktok_handle'    => sanitize_text_field( $new_instance['tiktok_handle'] ),
            'tiktok_live_url'  => esc_url_raw( $new_instance['tiktok_live_url'] ),
            'stream_video_url' => esc_url_raw( $new_instance['stream_video_url'] ),
            'stream_topic'     => sanitize_text_field( $new_instance['stream_topic'] ),
            'screen_size'      => in_array( $new_instance['screen_size'], array( 'compact', 'standard', 'wide' ), true ) ? $new_instance['screen_size'] : 'standard',
            'screen_fit'       => in_array( $new_instance['screen_fit'], array( 'cover', 'contain' ), true ) ? $new_instance['screen_fit'] : 'cover',
            'rules_summary'    => sanitize_textarea_field( $new_instance['rules_summary'] ),
        );
        return function_exists( 'rinews_sanitize_widget_schedule_fields' )
            ? array_merge( $base, rinews_sanitize_widget_schedule_fields( $new_instance ) )
            : $base;
    }
}

function rinews_register_custom_widgets() {
    register_widget( 'RINEWS_Widget_Kategori_Berita' );
    register_widget( 'RINEWS_Widget_Berita_Viral' );
    register_widget( 'RINEWS_Widget_Slide_Berita_Baru' );
    register_widget( 'RINEWS_Widget_Iklan_Gambar' );
    register_widget( 'RINEWS_Widget_YouTube_Feed' );
    register_widget( 'RINEWS_Widget_TikTok_Feed' );
    register_widget( 'RINEWS_Widget_Arsip_12_Bulan' );
    register_widget( 'RINEWS_Widget_Kemarin_Terlewatkan' );
    register_widget( 'RINEWS_Widget_Live_Streaming' );
}
add_action( 'widgets_init', 'rinews_register_custom_widgets' );

