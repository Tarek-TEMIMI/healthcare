<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;
$settings = $this->get_settings_for_display();

$text_array = [
    'facebook'     		=> [__('Facebook', 'iqonic'), __('fb', 'iqonic')],
    'twitter'      		=> [__('Twitter', 'iqonic'), __('tw', 'iqonic')],
    'google-plus'  		=> [__('Google Plus', 'iqonic'), __('g+', 'iqonic')],
    'github'      	 	=> [__('Github', 'iqonic'), __('gh', 'iqonic')],
    'instagram'      	=> [__('Instagram', 'iqonic'), __('in', 'iqonic')],
    'linkedin'       	=> [__('LinkedIn', 'iqonic'), __('ln', 'iqonic')],
    'tumblr'         	=> [__('Tumblr', 'iqonic'), __('tl', 'iqonic')],
    'pinterest'      	=> [__('Pinterest', 'iqonic'), __('pt', 'iqonic')],
    'dribbble'       	=> [__('Dribbble', 'iqonic'), __('db', 'iqonic')],
    'reddit'         	=> [__('Reddit', 'iqonic'), __('rd', 'iqonic')],
    'flickr'         	=> [__('Flicker', 'iqonic'), __('fc', 'iqonic')],
    'skype'          	=> [__('skype', 'iqonic'), __('sp', 'iqonic')],
    'youtube-play'   	=> [__('Youtube Play', 'iqonic'), __('yt', 'iqonic')],
    'vimeo'          	=> [__('Vimeo', 'iqonic'), __('vm', 'iqonic')],
    'soundcloud'     	=> [__('Soundcloud', 'iqonic'), __('sc', 'iqonic')],
    'wechat'         	=> [__('Wechat', 'iqonic'), __('wc', 'iqonic')],
    'renren'         	=> [__('Renren', 'iqonic'), __('rr', 'iqonic')],
    'weibo'          	=> [__('Weibo', 'iqonic'), __('wb', 'iqonic')],
    'xing'           	=> [__('Xing', 'iqonic'), __('xi', 'iqonic')],
    'qq'             	=> [__('QQ', 'iqonic'), __('qq', 'iqonic')],
    'rss'            	=> [__('Rss', 'iqonic'), __('rs', 'iqonic')],
    'vk'             	=> [__('VK', 'iqonic'), __('vk', 'iqonic')],
    'behance'        	=> [__('Behance', 'iqonic'), __('bh', 'iqonic')],
    'snapchat'       	=> [__('Snapchat', 'iqonic'), __('sp', 'iqonic')],
];

global $kivicare_options;

if (isset($kivicare_options['social-media-iq'])) {
    $top_social = $kivicare_options['social-media-iq']; ?>
    <div class="kivicare-social">
        <ul class="m-0">
            <?php
            foreach ($top_social as $key => $value) {
                if ($value) {
                   
                    echo '<li class="list-inline-item"><a target="_blank" href="' . $value . '"><svg class="base-circle animated" width="38" height="38" viewBox="0 0 50 50">
                        <circle class="c1" cx="25" cy="25" r="23" stroke="#6e7990" stroke-width="1" fill="none"></circle>
                    </svg><i class="fab fa-' . $key . '"></i></a></li>';
                    
                }
            }
            ?>
        </ul>
    </div>
     <?php
}