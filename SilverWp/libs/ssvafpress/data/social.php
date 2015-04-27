<?php
/*
 * function list used to data source for social plugins
 */
/**
 *
 * list of providers and share url's
 *
 * @link http://stackoverflow.com/questions/12448134/social-share-links-with-custom-icons list of all share providers
 * @return array
 */
if ( ! function_exists( 'silverwp_get_social_providers' ) ) {
    function silverwp_get_social_providers() {
        $providers = array(
            array(
                'name'      => 'Facebook',
                'share_url' => 'https://www.facebook.com/sharer/sharer.php?u={post_url}',
                'icon'      => 'icon-facebook',
                'account'   => true,
            ),
            array(
                'name'      => 'Twitter',
                'share_url' => 'http://twitter.com/intent/tweet?url={post_url}&text={post_title}',
                'icon'      => 'icon-twitter',
                'account'   => true,
            ),
            array(
                'name'      => 'LinkedIn',
                'share_url' => 'http://www.linkedin.com/shareArticle?mini=true&url={post_url}&summary={post_short_content}&source={base_url}',
                'icon'      => 'icon-linkedin',
                'account'   => true,
            ),
            array(
                'name'      => 'Google+',
                'share_url' => 'https://plus.google.com/share?url={post_url}',
                'icon'      => 'icon-gplus',
                'account'   => true,
            ),
            array(
                'name'      => 'Pinterest',
                'share_url' => 'https://pinterest.com/pin/create/button/?url={post_url}&media={image_url}&description={post_short_content}',
                'icon'      => 'icon-pinterest-circled',
                'account'   => true,
            ),
            array(
                'name'      => 'Reddit',
                'share_url' => 'http://reddit.com/submit?url={post_url}&title={post_title}',
                'icon'      => 'icon-reddit',
                'account'   => true,
            ),
            array(
                'name'      => 'Delicious',
                'share_url' => 'http://del.icio.us/post?url={post_url}&title={post_title}',
                'icon'      => 'icon-delicious',
                'account'   => true,
            ),
            array(
                'name'      => 'Stumbleupon',
                'share_url' => 'http://www.stumbleupon.com/submit?url={post_url}&title={post_title}',
                'icon'      => 'icon-stumbleupon',
                'account'   => true,
            ),
            array(
                'name'      => 'Yahoo',
                'share_url' => 'http://bookmarks.yahoo.com/toolbar/SaveBM/?u={post_url}&t={post_title}',
                'icon'      => 'icon-yahoo',
                'account'   => true,
            ),
            array(
                'name'      => 'Digg',
                'share_url' => 'http://digg.com/submit?url={post_url}&title={post_title}',
                'icon'      => 'icon-digg',
                'account'   => true,
            ),
            array(
                'name'      => 'Tumblr',
                'share_url' => 'http://www.tumblr.com/share?v=3&u={post_url}&t={post_title}&s=',
                'icon'      => 'icon-tumblr',
                'account'   => true,
            ),
            array(
                'name'      => 'Float it',
                'share_url' => 'http://www.designfloat.com/submit.php?url={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Vkontakte',
                'share_url' => 'http://vkontakte.ru/share.php?&url={post_url}',
                'icon'      => 'icon-vkontakte',
                'account'   => true,
            ),
            array(
                'name'      => 'Email',
                'share_url' => 'mailto:?subject={post_title}&body={post_url}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Google Buzz',
                'share_url' => 'http://www.google.com/reader/link?url={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Slashdot',
                'share_url' => 'http://slashdot.org/bookmark.pl?url={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Technorati',
                'share_url' => 'http://technorati.com/faves?add={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Posterous',
                'share_url' => 'http://posterous.com/share?linkto={post_url}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Google Bookmarks',
                'share_url' => 'http://www.google.com/bookmarks/mark?op=edit&bkmk={post_url}&title={post_title}&annotation={post_short_content}',
                'icon'      => 'icon-bookmarks',
                'account'   => false,
            ),
            array(
                'name'      => 'Newsvine',
                'share_url' => 'http://www.newsvine.com/_tools/seed&save?u={post_url}&h={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Ping.fm',
                'share_url' => 'http://ping.fm/ref/?link={post_url}&title={post_title}&body={post_short_content}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Evernote',
                'share_url' => 'http://www.evernote.com/clip.action?url={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Friendfeed',
                'share_url' => 'http://www.friendfeed.com/share?url={post_url}&title={post_title}',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Behance',
                'share_url' => '',
                'icon'      => 'icon-behance',
                'account'   => true,
            ),
            array(
                'name'      => 'Blogger',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Deviantart',
                'share_url' => '',
                'icon'      => 'icon-deviantart',
                'account'   => true,
            ),
            array(
                'name'      => 'Dribbble',
                'share_url' => '',
                'icon'      => 'icon-dribbble',
                'account'   => true,
            ),
            array(
                'name'      => 'Envato',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Flickr',
                'share_url' => '',
                'icon'      => 'icon-flickr',
                'account'   => true,
            ),
            array(
                'name'      => 'Foursquare',
                'share_url' => '',
                'icon'      => 'icon-foursquare',
                'account'   => true,
            ),
            array(
                'name'      => 'Forrst',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Github',
                'share_url' => '',
                'icon'      => 'icon-github',
                'account'   => true,
            ),
            array(
                'name'      => 'Instagram',
                'share_url' => '',
                'icon'      => 'icon-instagramm',
                'account'   => true,
            ),
            array(
                'name'      => 'Myspace',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Picasa',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'RSS',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Skype',
                'share_url' => '',
                'icon'      => 'icon-skype',
                'account'   => false,
            ),
            array(
                'name'      => 'Spotify',
                'share_url' => '',
                'icon'      => 'icon-spotify',
                'account'   => true,
            ),
            array(
                'name'      => 'SoundCloud',
                'share_url' => '',
                'icon'      => 'icon-soundcloud',
                'account'   => false,
            ),
            array(
                'name'      => 'Vimeo',
                'share_url' => '',
                'icon'      => 'icon-vimeo-squared',
                'account'   => true,
            ),
            array(
                'name'      => 'VK',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
            array(
                'name'      => 'Xing',
                'share_url' => '',
                'icon'      => 'icon-xing',
                'account'   => true,
            ),
            array(
                'name'      => 'YouTube',
                'share_url' => '',
                'icon'      => 'icon-youtube',
                'account'   => true,
            ),
            array(
                'name'      => 'WordPress',
                'share_url' => '',
                'icon'      => 'icon-wordpress',
                'account'   => false,
            ),
            array(
                'name'      => 'Yelp',
                'share_url' => '',
                'icon'      => 'icon-yelp',
                'account'   => true,
            ),
            array(
                'name'      => 'Zerply',
                'share_url' => '',
                'icon'      => '',
                'account'   => false,
            ),
        );
        sort( $providers );

        return $providers;
    }
}
/**
 *
 * social bookmarks lists
 *
 * @return array
 */
if ( ! function_exists( 'silverwp_get_social_accounts' ) ) {
    function silverwp_get_social_accounts() {
        $providers = silverwp_get_social_providers();
        foreach ( $providers as $provider ) {
            if ( $provider[ 'icon' ] != '' && $provider[ 'account' ] ) {
                $accounts[ sanitize_title( $provider[ 'name' ] ) ] = $provider[ 'name' ];
            }
        }

        return $accounts;
    }
}
/**
 * source function for social plugins
 */
if ( ! function_exists( 'silverwp_get_social_plugins' ) ) {
    function silverwp_get_social_plugins() {
        return array(
            'facebook'    => 'Facebook',
            'twitter'     => 'Twitter',
            'google_plus' => 'Google+',
        );
    }
}

/**
 * social ico items
 *
 * @return array
 */
if ( ! function_exists( 'silverwp_get_social_icon' ) ) {
    function silverwp_get_social_icon() {
        $providers = silverwp_get_social_providers();
        $icons     = array();
        foreach ( $providers as $provider ) {
            if ( $provider[ 'icon' ] != '' ) {
                $icons[ ] = array(
                    'value' => $provider[ 'icon' ],
                    'label' => $provider[ 'name' ],
                );
            }
        }

        return $icons;
    }
}
/**
 * social ico items
 *
 * @return array
 */
if ( ! function_exists( 'silverwp_get_name_icon' ) ) {
    function silverwp_get_name_icon() {
        $providers = silverwp_get_social_providers();
        $icons     = array();
        foreach ( $providers as $provider ) {
            if ( $provider[ 'icon' ] != '' && $provider[ 'account' ] == true ) {
                $icons[ ] = array(
                    'icon' => $provider[ 'icon' ],
                    'name' => $provider[ 'name' ],
                    'slug' => sanitize_title( $provider[ 'name' ] ),
                );
            }
        }

        return $icons;
    }
}
