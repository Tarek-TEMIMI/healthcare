<?php

namespace Elementor;

$html = '';
if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();
$tabs = $this->get_settings_for_display('tabs');

$args = array();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
global $post;

if ($settings['service_layout_option'] == 'static') {
    $tabs = $this->get_settings_for_display('tabs');
} else {

    $args = array(
        'post_type' => 'service',
        'post_status' => 'publish',
        'paged' => $paged,
        'order' => $settings['order'],
        'posts_per_page' => $settings['dis_number'],
    );
    if (!empty($settings['iqonic_select_services'])) {
        $args['post_name__in'] = $settings['iqonic_select_services'];
    }
}

$hoverclass = '';
if ($settings['iqonic_hover_effect'] == 'yes') {
    $hoverclass = "kivicare-hovereffect";
}

$iconhoverclass = '';
if ($settings['iqonic_icon_hover_effect'] == 'yes') {
    $iconhoverclass = "pulse-shrink-on-hover";
}

$wp_query = new \WP_Query($args);

$blog_layout = '';
if ($settings['service_layout_style'] == 'style1') {
    $blog_layout = "kivicare-service-style1";
} else if ($settings['service_layout_style'] == 'style2') {
    $blog_layout = "kivicare-service-style2";
} else if ($settings['service_layout_style'] == 'style3') {
    $blog_layout = "kivicare-service-style3";
}

?>
<div class="kivicare-service-grid">
    <div class="row">
        <?php if ($settings['service_layout_option'] == 'static') {
            foreach ($tabs as $item) {

                if ($item['tab_link_type'] == 'dynamic') {
                    $url =  get_permalink(get_page_by_path($item['tab_dynamic_link'], OBJECT, 'service'));
                    $this->add_render_attribute('kivicare_class', 'href', esc_url($url));
                } else {
                    if ($item['tab_link']['url']) {
                        $url = $item['tab_link']['url'];
                        $this->add_render_attribute('kivicare_class', 'href', esc_url($url));

                        if ($item['tab_link']['is_external']) {
                            $this->add_render_attribute('kivicare_class', 'target', '_blank');
                        }

                        if ($item['tab_link']['nofollow']) {
                            $this->add_render_attribute('kivicare_class', 'rel', 'nofollow');
                        }
                    }
                }

                $static_link  = $url;
                if ($settings['service_layout_style'] == 'style1') { ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?> <?php echo esc_attr($hoverclass); ?>">
                            <div class="kivicare-box-title">
                                <a href="<?php echo !empty($static_link) ? esc_url($static_link) : '#' ?>">
                                    <<?php echo $settings['title_tag']; ?> class="kivicare-heading-title">
                                        <?php echo sprintf("%s", esc_html($item['tab_title'], 'iqonic')); ?>
                                    </<?php echo $settings['title_tag']; ?>>
                                </a>
                            </div>
                            <div class="kivicare-service-main-detail">
                                <?php if ($settings['iqonic_show_content'] == 'yes') { ?>
                                    <p class="kivicare-description">
                                        <?php echo $item['tab_content']; ?>
                                    </p>
                                <?php
                                } ?>
                            </div>
                            <div class="kivicare-image">
                                <div class="iq-service-image">
                                    <?php
                                    if ($item['media_style'] == 'icon') { ?>
                                        <div class="icon">
                                            <?php \Elementor\Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                        </div>
                                        <!-- if image -->
                                    <?php
                                    } else if ($item['media_style'] == 'image') { ?>
                                        <img src="<?php echo esc_url($item['tab_image']['url']) ?>" alt="service-image" />
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php if ($item['has_icon'] == "yes") { ?>
                                <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                    <?php \Elementor\Icons_Manager::render_icon($item['selected_icon_before'], ['aria-hidden' => 'true']); ?>
                                </div>
                            <?php } ?>


                            <?php if ($settings['has_button'] == "yes") {
                                require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                            } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($settings['service_layout_style'] == 'style2') {
                ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?> <?php echo esc_attr($hoverclass); ?>">
                            <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                <?php
                                if ($item['media_style'] == 'icon') { ?>
                                    <div class="icon">
                                        <?php \Elementor\Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <!-- if image -->
                                <?php
                                } else if ($item['media_style'] == 'image') { ?>
                                    <img src="<?php echo esc_url($item['tab_image']['url']) ?>" alt="service-image" />
                                <?php
                                }
                                ?>
                            </div>
                            <div class="kivicare-service-info">
                                <div class="kivicare-service-main-detail">
                                    <a href="<?php echo !empty($static_link) ? esc_url($static_link) : '#' ?>">
                                        <<?php echo esc_attr($settings['title_tag']);  ?> class="kivicare-service-text kivicare-heading-title">
                                            <?php echo sprintf("%s", esc_html($item['tab_title'], 'iqonic')); ?>
                                        </<?php echo esc_attr($settings['title_tag']); ?>>
                                    </a>

                                    <?php if ($settings['iqonic_show_content'] == 'yes') {  ?>
                                        <p class="kivicare-description"> <?php echo $item['tab_content']; ?></p>
                                    <?php } ?>
                                    <?php
                                    if ($settings['has_button'] == "yes") {
                                        require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
                if ($settings['service_layout_style'] == 'style3') { ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?>">
                            <div class="kivicare-image">
                                <?php
                                if ($item['media_style'] == 'icon') { ?>
                                    <div class="icon">
                                        <?php \Elementor\Icons_Manager::render_icon($item['selected_icon'], ['aria-hidden' => 'true']); ?>
                                    </div>
                                    <!-- if image -->
                                <?php
                                } else if ($item['media_style'] == 'image') { ?>
                                    <img src="<?php echo esc_url($item['tab_image']['url']) ?>" alt="service-image" />
                                <?php
                                }
                                ?>
                            </div>

                            <div class="kivicare-service-inner">
                                <?php if ($item['iqonic_show_category'] == 'yes' && !empty($item['tab_category'])) { ?>
                                    <div class="kivicare_team-category">
                                        <a href="<?php echo esc_url($item['tab_category_link']['url']) ?>" class="kivicare-cat-link">
                                            <?php echo esc_html($item['tab_category']); ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="kivicare-box-title">
                                    <a href=" <?php echo !empty($static_link) ? esc_url($static_link) : '#' ?>">
                                        <<?php echo $settings['title_tag']; ?> class="kivicare-heading-title">
                                            <?php echo sprintf("%s", esc_html($item['tab_title'], 'iqonic')); ?>
                                        </<?php echo $settings['title_tag']; ?>>
                                    </a>
                                </div>
                                <div class="kivicare-service-main-detail">
                                    <?php if ($settings['iqonic_show_content'] == 'yes') { ?>
                                        <p class="kivicare-description">
                                            <?php echo $item['tab_content']; ?>
                                        </p>
                                    <?php
                                    } ?>
                                </div>
                                <?php if ($settings['has_button'] == "yes") {
                                    require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                                } ?>
                                <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                    <?php \Elementor\Icons_Manager::render_icon($item['selected_icon_before'], ['aria-hidden' => 'true']); ?>
                                </div>

                            </div>
                        </div>
                    </div>
        <?php }
            }
        } ?>
        <?php
        if ($wp_query->have_posts()) {
            $count = 0;
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "kivicare-small-thumbnail");
                $terms = wp_get_post_terms($post->ID, 'service_categories'); ?>
                <?php if ($settings['service_layout_style'] == 'style1') { ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?> <?php echo esc_attr($hoverclass); ?>">
                            <div class="kivicare-box-title">
                                <a href="<?php the_permalink(); ?>">
                                    <<?php echo $settings['title_tag']; ?> class="kivicare-heading-title">
                                        <?php echo sprintf("%s", esc_html__(get_the_title($wp_query->ID), 'iqonic')); ?>
                                    </<?php echo $settings['title_tag']; ?>>
                                </a>
                            </div>
                            <div class="kivicare-service-main-detail">
                                <?php if ($settings['iqonic_show_content'] == 'yes') { ?>
                                    <p class="kivicare-description">
                                        <?php echo get_the_excerpt(); ?>
                                    </p>
                                <?php
                                } ?>
                            </div>
                            <div class="kivicare-image">
                                <?php if (!empty($full_image[0])) { ?>
                                    <div class="iq-service-image">
                                        <?php echo get_the_post_thumbnail(); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                <?php
                                if (get_field('field_service_icon_one', get_the_ID())) {
                                    $icon = get_field('field_service_icon_one', get_the_ID());
                                    $svg = $icon['url'];
                                    $svg = file_get_contents($svg);
                                    $filetype = wp_check_filetype($icon['filename']);
                                    if ($filetype['ext'] == 'svg') { ?>
                                        <?php echo $svg; ?>
                                    <?php } else { ?>
                                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr__('image', 'iqonic'); ?>" />
                                <?php }
                                } ?>
                            </div>
                            <?php if ($settings['has_button'] == "yes") {
                                require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                            } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($settings['service_layout_style'] == 'style2') {
                ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?> <?php echo esc_attr($hoverclass); ?>">
                            <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                <?php
                                if (get_field('field_service_icon_one', get_the_ID())) {
                                    $icon = get_field('field_service_icon_one', get_the_ID());
                                    $svg = $icon['url'];
                                    $svg = file_get_contents($svg);
                                    $filetype = wp_check_filetype($icon['filename']);
                                    if ($filetype['ext'] == 'svg') { ?>
                                        <?php echo $svg; ?>
                                    <?php } else { ?>
                                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr__('image', 'iqonic'); ?>" />
                                <?php }
                                } ?>
                            </div>

                            <div class="kivicare-service-info">
                                <div class="kivicare-service-main-detail">
                                    <a href="<?php the_permalink(); ?>">
                                        <<?php echo esc_attr($settings['title_tag']);  ?> class="kivicare-service-text kivicare-heading-title">
                                            <?php echo sprintf("%s", esc_html(get_the_title($post->ID))); ?>
                                        </<?php echo esc_attr($settings['title_tag']); ?>>
                                    </a>

                                    <?php if ($settings['iqonic_show_content'] == 'yes') {  ?>
                                        <p class="kivicare-description"><?php echo sprintf("%s", esc_html(get_the_excerpt($wp_query->ID))); ?></p>
                                    <?php } ?>
                                    <?php

                                    if ($settings['has_button'] == "yes") {
                                        require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
                if ($settings['service_layout_style'] == 'style3') { ?>
                    <div class="<?php echo esc_attr($settings['service_grid_style']); ?>">
                        <div class="kivicare-service-blog <?php echo esc_attr($blog_layout); ?>">
                            <div class="kivicare-image">
                                <?php if (!empty($full_image[0])) { ?>
                                    <div class="iq-service-image">
                                        <?php echo get_the_post_thumbnail(); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="kivicare-service-inner">
                                <div class="kivicare_team-category">
                                    <?php
                                    $total_post = count($terms);
                                    $count = 0;
                                    if ($terms) {
                                        foreach ($terms as $term) {
                                            $count++; ?>
                                            <a href="<?php echo esc_url(get_category_link($term->term_id)) ?>"><?php echo esc_html($term->name); ?></a>
                                    <?php }
                                        if ($count < $total_post) {
                                            echo '&ebsp;';
                                        }
                                    } ?>
                                </div>
                                <div class="kivicare-box-title">
                                    <a href=" <?php the_permalink(); ?>">
                                        <<?php echo $settings['title_tag']; ?> class="kivicare-heading-title">
                                            <?php echo sprintf("%s", esc_html(get_the_title($post->ID), 'iqonic')); ?>
                                        </<?php echo $settings['title_tag']; ?>>
                                    </a>
                                </div>
                                <div class="kivicare-service-main-detail">
                                    <?php if ($settings['iqonic_show_content'] == 'yes') { ?>
                                        <p class="kivicare-description">
                                            <?php echo sprintf("%s", esc_html(get_the_excerpt($wp_query->ID))); ?>
                                        </p>
                                    <?php
                                    } ?>
                                </div>
                                <?php if ($settings['has_button'] == "yes") {
                                    require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                                } ?>
                                <div class="kivicare-service-box-icon <?php echo esc_attr($iconhoverclass); ?>">
                                    <?php
                                    if (get_field('field_service_icon_one', get_the_ID())) {
                                        $icon = get_field('field_service_icon_one', get_the_ID());
                                        $svg = $icon['url'];
                                        $svg = file_get_contents($svg);
                                        $filetype = wp_check_filetype($icon['filename']);
                                        if ($filetype['ext'] == 'svg') { ?>
                                            <?php echo $svg; ?>
                                        <?php } else { ?>
                                            <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr__('image', 'iqonic'); ?>" />
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php }
            }
            wp_reset_postdata();
        } ?>

    </div>
</div>