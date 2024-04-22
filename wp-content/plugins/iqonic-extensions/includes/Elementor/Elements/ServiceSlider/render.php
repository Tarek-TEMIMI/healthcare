<?php

namespace Elementor;

$html = '';
if (!defined('ABSPATH')) exit;

$settings = $this->get_settings();

$args = array();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
global $post;



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

$wp_query = new \WP_Query($args);

$id = iqonic_random_strings();

if ($wp_query->have_posts()) { ?>

    <?php if ($settings['service_style'] == '1') {
        
        $this->add_render_attribute('slider', 'data-items', $settings['sw_slide']);
        $this->add_render_attribute('slider', 'data-items-laptop', $settings['sw_laptop_no']);
        $this->add_render_attribute('slider', 'data-items-tab', $settings['sw_tab_no']);
        $this->add_render_attribute('slider', 'data-items-mobile', $settings['sw_mob_no']);
        $this->add_render_attribute('slider', 'data-items-mobile-sm', $settings['sw_mob_no']);
        $this->add_render_attribute('slider', 'data-loop', $settings['sw_loop']);
        $this->add_render_attribute('slider', 'data-centered_slides', $settings['centered_slides']);
        $this->add_render_attribute('slider', 'data-enable_autoplay', $settings['sw_enable_autoplay']);
        $this->add_render_attribute('slider', 'data-autoplay', $settings['sw_autoplay']);
        $this->add_render_attribute('slider', 'data-spacebtslide', $settings['sw_space_slide']); ?>

        <div class="swiper swiper-container kivicare-service-slider kivicare-service-slider-1 <?php echo esc_attr($id); ?>" data-id="<?php echo esc_attr($id); ?>" <?php echo $this->get_render_attribute_string('slider'); ?>>
            <div class="swiper-wrapper">
                <?php 
                while ($wp_query->have_posts()) {
                    $wp_query->the_post();
                    $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($wp_query->ID), "full"); ?>
                    <div class="swiper-slide iq-service-slide">

                        <div class="service-content "> <?php
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
                            <<?php echo esc_attr($settings['title_tag']);  ?> class="iq-title iq-heading-title">
                                <?php echo sprintf("%s", esc_html(get_the_title($post->ID))); ?>
                            </<?php echo esc_attr($settings['title_tag']); ?>>

                            <?php if ($settings['iqonic_show_content'] == 'yes') {  ?>
                                <p class="iq-service-desc m-0"><?php echo sprintf("%s", esc_html(get_the_excerpt($wp_query->ID))); ?></p>
                            <?php }

                            if ($settings['has_button'] == "yes") {
                                require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                            }
                            ?>
                        </div>

                    </div> <?php
                }
                wp_reset_postdata(); ?>
            </div>
            <?php if ($settings['want_pagination'] == "true") { ?>
                <div class="swiper-pagination"></div> <?php
            } ?>

            <?php if ($settings['want_nav'] == "true") { ?>
                <div class="iq-swiper-arrow">
                  <div class="swiper-button-prev"></div>
                  <div class="swiper-button-next"></div>
                </div> <?php
        } ?>
        </div>

    <?php } ?>

    <?php if ($settings['service_style'] == '2') { ?>

        <div class="swiper kivicare-service-slider kivicare-service-slider-2 <?php echo esc_attr($id); ?>" data-id="<?php echo esc_attr($id); ?>" >
            <div class="swiper-wrapper">
                <?php 
                while ($wp_query->have_posts()) {
                    $wp_query->the_post();
                    $full_image = wp_get_attachment_image_src(get_post_thumbnail_id($wp_query->ID), "full"); ?>
                    <div class="swiper-slide iq-service-slide">
                        <div class="service-content">
                            <div class="service-content-image">
                                <?php echo sprintf('<img src="%1$s" alt="iqonic-service"/>', esc_url($full_image[0], 'iqonic')); ?>
                            </div>
                            <div class="service-content-inner">
                                <div class="service-content-wrapper">
                                    <<?php echo esc_attr($settings['title_tag']);  ?> class="iq-title iq-heading-title">
                                        <?php echo sprintf("%s", esc_html(get_the_title($post->ID))); ?>
                                    </<?php echo esc_attr($settings['title_tag']); ?>>

                                    <?php if ($settings['iqonic_show_content'] == 'yes') {  ?>
                                        <p class="iq-service-desc"><?php echo sprintf("%s", esc_html(get_the_excerpt($wp_query->ID))); ?></p>
                                    <?php }

                                    if ($settings['has_button'] == "yes") {
                                        require  IQONIC_EXTENSION_PLUGIN_PATH . 'includes/Elementor/Controls/iq_blog_button.php';
                                    }
                                    ?>
                                </div> 
                            </div>
                        </div>
                    </div> <?php
                }
                wp_reset_postdata(); ?>
            </div>

        </div>

    <?php } ?>

<?php } ?>