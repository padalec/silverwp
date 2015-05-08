<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace SilverWp\MetaBox;

use SilverWp\Helper\Control\ControlInterface;
use SilverWp\Helper\Form\Group;
use SilverWp\Helper\Message;
use SilverWp\Helper\MetaBox;
use SilverWp\Helper\Option;
use SilverWp\Helper\RecursiveArray;
use SilverWp\Helper\Thumbnail;
use SilverWp\Helper\UtlArray;
use SilverWp\Oembed;
use SilverWp\PostInterface;
use SilverWp\PostType\PostTypeInterface;
use SilverWp\SingletonAbstract;
use SilverWp\Translate;
use SilverWp\Video;
use VP_Metabox;

if (! class_exists('SilverWp\MetaBox\MetaBoxAbstract')) {
    /**
     * Abstract Meta Boxes based on meta-box plugin
     *
     * @author Michal Kalkowski <michal at silversite.pl>
     * @version $Id: MetaBoxAbstract.php 2559 2015-03-12 13:01:04Z padalec $
     * @category WordPress
     * @package SilverWp
     * @subpackage MetaBox
     * @link http://www.deluxeblogtips.com/meta-box/
     * @copyright (c) 2014, Michal Kalkowski
     */
    abstract class MetaBoxAbstract extends SingletonAbstract implements MetaBoxInterface, PostInterface {

        /**
         *
         * Whether the development mode is active or not. You should activate this
         * when you are still working and testing on the builder.
         * This mode will prevent the framework to save your meatbox into WordPress
         * Database and take the default value set in your meta_boxes instead.
         * Default to FALSE.
         *
         * @global boolean
         */
        const DEV_MODE = SILVERWP_META_BOX_DEV;

        /**
         *
         * Unique ID for the metabox, required.
         * this is the same lik in post meta $this->name
         *
         * @var string
         */
        protected $id;

        /**
         *
         * Display title in the metabox header, required.
         *
         * @var string
         */
        protected $title;

        /**
         *
         * meta box priority: low, medium, high
         *
         * @var string
         */
        protected $priority = 'high';

        /**
         *
         * Array meta_boxes or path to array meta_boxes file, required.
         *
         * @var mixed
         */
        protected $meta_box = null;

        /**
         *
         * Where this meta box have to be displayed
         *
         * @var array
         */
        protected $post_type = array();

        /**
         * Meta box show on right
         * sidebar in admin panel
         *
         * @var string side
         */
        protected $context = null;

        /**
         * Post id
         *
         * @var integer
         * @access private
         */
        private $post_id = null;

        /**
         * Post type class handler
         *
         * @var object
         * @access private
         */
        private $post_type_class = null;

        /**
         * List of columns that should be
         * exclude from edit table
         *
         * @var array
         * @access protected
         * @since 1.8
         */
        protected $exclude_columns = array();

        /**
         * Thumbnail image size
         *
         * @var array
         * @access protected
         */
        protected $column_image_size = array( 50, 50 );

        /**
         *
         * @link http://wordpress.stackexchange.com/questions/6818/change-enter-title-here-help-text-on-a-custom-post-type
         * @var string
         * @access private
         */
        private $enter_title_hear;
        /**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            //set up labels
            $this->setTitle();
            //only in admin area register meta boxes
            if ( \is_admin() ) {
                // the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
                add_action( 'after_setup_theme', array( $this, 'init' ), 20 );
                add_filter( 'enter_title_here', array( $this, 'changeDefaultTitleLabel' ) );
            }
        }

        /**
         *
         * Set unique id
         *
         * @param string $id
         *
         * @access public
         * @return object
         */
        public function setId( $id ) {
            $this->id = $id;

            return $this;
        }

        /**
         * Set the post id
         *
         * @param integer $post_id post id
         *
         * @return MetaBoxAbstract
         * @access public
         */
        public function setPostId( $post_id ) {
            $this->post_id = (int) $post_id;

            return $this;
        }

        /**
         * Get post id
         *
         * @return integer
         * @access public
         */
        public function getPostId() {
            return $this->post_id;
        }

        /**
         *
         * Set post type when meta box have to be displayed
         *
         * @param array $post_type list of post types
         *
         * @return $this
         */
        public function setPostType( array $post_type ) {
            $this->post_type = \array_unique( \array_merge( $this->post_type, $post_type ) );

            return $this;
        }

        /**
         * Set post type class
         *
         * @param PostTypeInterface $post_type_class
         *
         * @return $this
         * @access public
         */
        public function setPostTypeClass( PostTypeInterface $post_type_class ) {
            $this->post_type_class = $post_type_class;

            return $this;
        }

        /**
         * Get post type main class
         *
         * @return object
         * @access public
         */
        public function getPostTypeClass() {
            return $this->post_type_class;
        }

        /**
         *
         * Register meta box to post type
         *
         * @access public
         * @return void
         * @throws Exception
         */
        public function init() {
            try {
                if ( \is_null( $this->id ) ) {
                    throw new Exception( Translate::translate( '$id variable is required and can\'t be emapty.' ) );
                }

                if ( \is_null( $this->title ) ) {
                    throw new Exception( Translate::translate( '$title variable is required and can\'t be emapty.' ) );
                }

                $meta_box = $this->getMetaBox();
                //silverwp_debug_array($meta_box);
                $data = array(
                    'id'          => MetaBox::getKeyName( $this->id ),
                    'types'       => $this->post_type,
                    'title'       => Translate::translate( $this->title ),
                    'priority'    => $this->priority,
                    'is_dev_mode' => self::DEV_MODE,
                    'template'    => $meta_box,
                );
                if ( isset( $this->context ) && ! is_null( $this->context ) ) {
                    $data['context'] = $this->context;
                }
                new VP_Metabox( $data );
                $this->manageColumns();
            } catch ( Exception $ex ) {
                $ex->displayAdminNotice();
            }
        }

        /**
         *
         * Get the registered meta boxes
         *
         * @return array
         * @throws Exception
         * @access public
         */
        public function getMetaBox() {
            $this->createMetaBox();
            return $this->meta_box;
        }

        /**
         *
         * Add new meta box controls
         *
         * @param \SilverWp\Helper\Control\ControlInterface $meta_box
         *
         * @return $this
         * @access public
         */
        public function addMetaBox( ControlInterface $meta_box ) {
            $this->meta_box[ ] = $meta_box->getSettings();

            return $this;
        }

        /**
         * Get single meta box by name
         *
         * @param string $name meta box name
         *
         * @return mixed
         * @access public
         */
        public function getSingle( $name, $remove_first = true ) {
            $this->isSetPostId();
            $post_id  = $this->post_id;
            $meta_box = MetaBox::getPostMeta( $this->id, $name, $post_id, $remove_first );

            if ( is_array( $meta_box ) ) {
                $meta_box = RecursiveArray::removeEmpty( $meta_box );
            }

            return $meta_box;
        }

        /**
         * Get all meta boxes for current post_id
         *
         * @return array
         * @access public
         */
        public function getAll() {
            $this->isSetPostId();
            $post_id    = $this->post_id;
            $meta_boxes = \get_post_meta( $post_id, MetaBox::getKeyName( $this->id ), true );
            if ( isset( $meta_boxes[0] ) && \count( $meta_boxes ) == 1 ) {
                return $meta_boxes[0];
            }

            return $meta_boxes;
        }

        /**
         *
         * Create new meta box too post tye
         *
         * @access protected
         * @abstract
         */
        abstract protected function createMetaBox();

        /**
         * This method is used to set title ($this->title)
         * variable and should by registered in all post types
         *
         * @access protected
         */
        protected function setTitle() {
            $this->title = Translate::translate( 'Settings' );
        }

        /**
         *
         * Get features list
         *
         * @return array
         * @access public
         */
        public function getFeatures() {
            $this->isSetPostId();
            $post_id      = $this->post_id;
            $return_array = array();
            $features     = $this->getSingle( 'features', $post_id, true );
            if ( $features ) {
                foreach ( $features['feature'] as $key => $value ) {
                    if ( $value['name'] != '' ) {
                        $return_array[ $key ] = $value;
                    }
                }
            }

            return $return_array;
        }

        /**
         *
         * Gallery list
         *
         * @param string|array $size thumbnail image size
         *
         * @return array
         */
        public function getGallery( $size = 'thumbnail' ) {
            $images  = array();

            $gallery = $this->getSingle( 'gallery_section', false );
            if ( $gallery && count( $gallery ) ) {
                foreach ( $gallery as $key => $gallery_item ) {
                    if ( ! is_null( $gallery_item['image'] ) && $gallery_item['image'] != '' ) {

                        $gallery_item['attachment_id'] = Thumbnail::getAttachmentIdFromUrl( $gallery_item['image'] );
                        $image_html         = \wp_get_attachment_image( $gallery_item['attachment_id'], $size );

                        $images[ $key ]  = array(
                            'attachment_id' => $gallery_item['attachment_id'],
                            'image_url'     => $gallery_item['image'],
                            'image_html'    => $image_html,
                        );
                    }
                }
            }
            return $images;
        }

        /**
         * Get video data
         *
         * @param string $key_name field key name
         *
         * @return array
         */
        public function getMedia( $key_name = 'video' ) {
            $file_data = array();

            $meta_box = $this->getSingle( $key_name );

            $video_url = false;
            if ( isset( $meta_box['video_url'] ) && $meta_box['video_url'] ) {
                $video_url = $meta_box['video_url'];
            }

            if ( $video_url ) {
                try {
                    $oEmbed = new Oembed( $video_url );

                    $file_data['provider_name'] = $oEmbed->provider_name;
                    $file_data['file_url']      = $video_url;
                    $file_data['thumbnail_url'] = $oEmbed->getThumbnailUrl();

                } catch ( \SilverWp\Exception $ex ) {
                    echo Message::alert( $ex->getMessage(), 'alert-danger' );
                    if ( WP_DEBUG ) {
                        silverwp_debug_array($ex->getTraceAsString(), 'Stack trace:');
                        silverwp_debug_array($ex->getTrace(), 'Full stack:');
                    }
                }
            }

            return $file_data;
        }

        /**
         *
         * Get post format
         *
         * @return string link | quote | video | gallery | default
         * @access public
         */
        public function getPostFormat() {

            $link = $this->getSingle( 'link' );
            if ( isset( $link['post_format_link'] ) && $link['post_format_link'] ) {
                return 'link';
            }

            $quote = $this->getSingle( 'quote' );
            if ( isset( $quote['post_format_quote_author'] ) && $quote['post_format_quote_author'] ) {
                return 'quote';
            }

            $video = $this->getSingle( 'video' );
            if ( isset( $video['video_url'] ) && $video['video_url'] ) {
                return 'video';
            }

            $gallery = $this->getSingle( 'gallery_section' );

            if ( $gallery && $this->isGallery( $gallery ) ) {
                return 'gallery';
            }

            $audio_mp3 = $this->getSingle( 'audio_mp3' );
            $audio_ogg = $this->getSingle( 'audio_ogg' );
            $audio_wav = $this->getSingle( 'audio_wav' );
            if ( $audio_mp3 || $audio_ogg || $audio_wav ) {
                return 'audio';
            }

            return 'default';
        }

        /**
         *
         * Check is images in array
         *
         * @param array $array_in
         *
         * @return boolean
         */
        private function isGallery( array $array_in ) {
            $images     = UtlArray::array_remove_empty(
                RecursiveArray::searchRecursive( $array_in, 'image' )
            );
            $is_gallery = ( count( $images ) > 0 ) ? true : false;

            return $is_gallery;
        }

        /**
         * Get sidebar position
         *
         * @return string
         * @access public
         */
        public function getSidebarPosition() {
            //Fix for tag page and all post type where don't have config from meta box
            if ( \is_home() || \is_tag() || \is_date() || \is_archive() ) {
                $this->post_id = Option::get_option( 'page_for_posts' );
            }

            $sidebar_code = $this->getSingle( 'sidebar' );

            switch ( $sidebar_code ) {
                case '1':
                    $sidebar_position = 'left';
                    break;
                case '2':
                    $sidebar_position = 'right';
                    break;
                default:
                    $sidebar_position = 'right'; // default position
            }

            return $sidebar_position;
        }

        /**
         *
         * Check the variable post_id is set and is not null
         * throw exception if not
         *
         * @throws \SilverWp\MetaBox\Exception
         * @access private
         */
        private function isSetPostId() {
            if ( isset( $this->post_id ) && \is_null( $this->post_id ) ) {
                $child_class = \get_called_class();
                throw new Exception( Translate::param( 'Variable %s::post_id is not sets.', $child_class ) );
            }
        }

        /**
         *
         * Display data in columns in edit Screen
         * this was moved from PostTypeAbstract class
         *
         * @param int $columns
         *
         * @link http://wpengineer.com/display-post-thumbnail-post-page-overview
         * @access public
         */
        public function columnDisplay( $columns, $post_id ) {
            try {
                switch ( $columns ) {
                    case $this->id . '_thumbnail':
                        // Display the featured image in the column view if possible
                        if ( \has_post_thumbnail( $post_id ) ) {
                            \the_post_thumbnail( $this->column_image_size );
                        } else {
                            echo Translate::translate( 'None' );
                        }
                        break;
                    // Display categories in the column view
                    case $this->id . '_category':
                        if ( $this->getPostTypeClass()->isTaxonomyRegistered() && $this->getPostTypeClass()->getTaxonomy()->isRegistered( 'category' ) ) {
                            $category_list = \get_the_term_list( $post_id, $this->id . '_category', '', ', ', '' );

                            if ( \is_wp_error( $category_list ) ) {
                                throw new Exception(
                                    $category_list->get_error_message() . ': ' . $this->id . '_category'
                                );
                            }
                            if ( $category_list ) {
                                echo $category_list;
                            } else {
                                echo Translate::translate( 'None' );
                            }
                        }
                        break;
                    // Display the tags in the column view
                    case $this->id . '_tag':
                        if ( $this->getPostTypeClass()->isTaxonomyRegistered() && $this->getPostTypeClass()->getTaxonomy()->isRegistered( 'tag' ) ) {
                            $tag_list = \get_the_term_list( $post_id, $this->id . '_tag', '', ', ', '' );

                            if ( \is_wp_error( $tag_list ) ) {
                                throw new Exception(
                                    $tag_list->get_error_message() . ': ' . $this->id . '_tag'
                                );
                            }

                            if ( $tag_list ) {
                                echo $tag_list;
                            } else {
                                echo Translate::translate( 'None' );
                            }
                        }
                        break;
                    /* Just break out of the switch statement for everything else. */
                    default:
                        break;
                }
            } catch ( Exception $ex ) {
                echo $ex->displayAdminNotice();
            }
        }

        /**
         * Add columns to edit screen
         *
         * @link http://wptheming.com/2010/07/column-edit-pages/
         * @access public
         * @return array
         */
        public function setColumns( $columns ) {
            $unique_cols   = array( 'category', 'thumbnail', 'tag' );
            $columns_list = $this->getEditColumns();
            foreach ( $columns_list as $key => $value ) {

                if ( \in_array( $key, $unique_cols ) ) {
                    $key = $this->id . '_' . $key;
                }

                if ( isset( $value['label'] ) ) {
                    $columns[ $key ] = $value['label'];
                } elseif ( isset( $value['html'] ) ) {
                    $columns[ $key ] = $value['html'];
                }
            }

            return $columns;
        }

        /**
         *
         * get list of edit columns displayed in lists of Post Type
         *
         *
         * list of columns displayed in dashboard list. Example
         * array(
         *       'cb' => array(
         *           'html' => '<input type="checkbox" />',
         *       ),
         *       'title' => array(
         *           'label' => 'Title',
         *       ),
         *       'category' => array(
         *            'label' => 'Categories',
         *       ),
         *       'thumbnail' => array(
         *           'label' => 'Thumbnail',
         *       ),
         *       'tag' => array(
         *           'label' => 'Tags',
         *      ),
         *      'date' => array(
         *          'label' => 'Date',
         *      ),
         *      'author' => array(
         *          'label' => 'Author',
         *      ),
         *  );
         *
         * @access protected
         * @return array
         */
        protected function getEditColumns() {
            $columns_default = array(
                'cb'                     => array(
                    'html' => '<input type="checkbox" />',
                ),
                'title'                  => array(
                    'label' => Translate::translate( 'Title' ),
                ),
                'thumbnail'              => array(
                    'label' => Translate::translate( 'Thumbnail' ),
                ),
                'author'                 => array(
                    'label' => Translate::translate( 'Author' ),
                ),
                'date'                   => array(
                    'label' => Translate::translate( 'Date' ),
                ),
                'category'               => array(
                    'label' => Translate::translate( 'Categories' ),
                ),
                'tag'                    => array(
                    'label' => Translate::translate( 'Tags' ),
                ),
                'silverwp_custom_column' => array(
                    'label' => '',
                )
            );
            $columns         = UtlArray::array_remove_part( $columns_default, $this->exclude_columns );

            return $columns;
        }

        /**
         * add hook for table displayed in "show all" post type
         *
         * @access private
         * @return void
         */
        private function manageColumns() {
            // Adds columns in the admin view for thumbnail and taxonomies
            \add_filter( 'manage_' . $this->id . '_posts_columns', array( $this, 'setColumns' ), 10, 1 );
            \add_action( 'manage_posts_custom_column', array( $this, 'columnDisplay' ), 10, 2 );
        }

        /**
         *
         * If need change default label of meta
         * box enter title hear just put new label to this method
         *
         * @param string $title
         *
         * @return $this
         * @access public
         */
        public function setEnterTitleHearLabel( $title ) {
            $this->enter_title_hear = $title;

            return $this;
        }

        /**
         * Change default label in meta box enter title hear
         *
         * @param string $title
         *
         * @return string
         * @access public
         */
        public function changeDefaultTitleLabel( $title ) {
            if ( isset( $this->enter_title_hear ) ) {
                $screen = get_current_screen();
                if ( $this->getPostTypeClass()->getName() === $screen->post_type ) {
                    $title = $this->enter_title_hear;
                }
            }
            return $title;
        }
    }
}