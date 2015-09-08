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

use SilverWp\Debug;
use SilverWp\Helper\Control\ControlInterface;
use SilverWp\Helper\MetaBox;
use SilverWp\Helper\Option;
use SilverWp\Helper\RecursiveArray;
use SilverWp\Helper\UtlArray;
use SilverWp\Interfaces\Core;
use SilverWp\Interfaces\PostType;
use SilverWp\Oembed;
use SilverWp\PostInterface;
use SilverWp\PostType\PostTypeInterface;
use SilverWp\SingletonAbstract;
use SilverWp\Taxonomy\TaxonomyInterface;
use SilverWp\Translate;
use SilverWp\Video;
use VP_Metabox;

if ( ! class_exists( 'SilverWp\MetaBox\MetaBoxAbstract' ) ) {

	/**
	 * Abstract Meta Boxes based on meta-box plugin
	 *
	 * @property string types
	 *
	 * @author        Michal Kalkowski <michal at silversite.pl>
	 * @version       0.5
	 * @category      WordPress
	 * @package       SilverWp
	 * @subpackage    MetaBox
	 * @link          http://www.deluxeblogtips.com/meta-box/
	 * @copyright     2015 (c) SilverSite.pl
	 * @TODO          implement methods setColumns and columnDisplay
	 *       http://wpengineer.com/display-post-thumbnail-post-page-overview
	 *       http://wptheming.com/2010/07/column-edit-pages/
	 */
	abstract class MetaBoxAbstract extends SingletonAbstract
		implements MetaBoxInterface, Core {

        /**
         *
         * Whether the development mode is active or not. You should activate this
         * when you are still working and testing on the builder.
         * This mode will prevent the framework to save your meatbox into WordPress
         * Database and take the default value set in your meta_boxes instead.
         * Default to FALSE.
         *
         * @const boolean
         */
        const DEV_MODE = SILVERWP_META_BOX_DEV;

		/**
		 * Meta Key prefix
		 * @const
		 */
		const PREFIX = THEME_OPTION_PREFIX;

        /**
         *
         * Unique ID for the meta box, required.
         * this is the same lik in post meta $this->name
         *
         * @var string
         */
        protected $id;

		/**
		 * Attributes array
		 *
		 * @var array
		 * @access private
		 */
		private $attributes = array();

        /**
         *
         * Handle all our meta box form controls
         *
         * @var array
         * @access protected
         */
        protected $controls = array();

		/**
		 *
		 * Handle meta boxes controls then we can filtered in WP_Query
		 *
		 * @var array
		 * @access protected
		 */
		protected $filter_controls = array();

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
        private $enter_title_hear_label;

	    /**
	     * Set Post Title as required
	     *
	     * @var bool
	     * @access protected
	     */
	    protected $title_required = true;

	    /**
	     * Display settings array
	     *
	     * @var bool
	     * @access protected
	     * @since 0.2
	     */
	    protected $debug = false;

		/**
		 * Post types names wen meta boxes will be registered
		 *
		 * @var string
		 * @access protected
		 */
		protected $post_types = array();

		/**
         *
         * Class constructor
         *
         * @access protected
         */
        protected function __construct() {
            //only in admin area register meta boxes
            if ( \is_admin() ) {
                // the safest hook to use, since Vafpress Framework may exists in Theme or Plugin
                add_action( 'after_setup_theme', array( $this, 'init' ), 20 );

                add_filter( 'enter_title_here', array( $this, 'changeEnterTitleHearLabel' ) );

	            if ( $this->title_required ) {
	                add_action( 'admin_footer', array( $this, 'forceTitle' ) );
	            }

	            $parent_class = get_called_class();
                if (
                    $this->isImplemented( $parent_class, 'SilverWp\MetaBox\RemoveInterface' )
                ) {
                    add_action( 'admin_menu', array( $this, 'removeMetaBoxes' ) );
                }
	        }
        }

		/**
		 * Add attribute to attributes array
		 *
		 * @param string $name
		 * @param mixed $value
		 *
		 * @access public
		 * @since 0.5
		 */
		public function __set( $name, $value ) {
			$this->attributes[ $name ] = $value;
		}

		/**
		 *
		 * Set post types
		 *
		 * @param array $post_types
		 *
		 * @return $this
		 * @access public
		 */
		public function setPostTypes( array $post_types ) {
			$this->post_types = $post_types;

			return $this;
		}

		/**
		 * Add new post type to post types array
		 *
		 * @param string $post_type
		 *
		 * @return $this
		 * @access public
		 */
		public function addPostType( $post_type ) {
			$this->post_types[] = $post_type;

			return $this;
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
		 * @since 0.4
		 */
		public function setEnterTitleHearLabel( $title ) {
			$this->enter_title_hear_label = $title;

			return $this;
		}

		/**
		 *
		 * Set unique id
		 *
		 * @param string $id
		 *
		 * @access public
		 * @return $this
		 */
		public function setId( $id ) {
			$this->id = self::PREFIX . '_' . $id;

			return $this;
		}

		/**
		 *
		 * Add new meta box controls
		 *
		 * @param \SilverWp\Helper\Control\ControlInterface $control
		 *
		 * @return $this
		 * @access public
		 * @since 0.5
		 */
		public function addControl( ControlInterface $control ) {
			$this->controls[ ] = $control;

			return $this;
		}

		/**
		 *
		 * Add new meta box control then we can
		 * filtered by this in meta_query in WP_Query
		 *
		 * @param \SilverWp\Helper\Control\ControlInterface $control
		 *
		 * @return $this
		 * @access public
		 */
		public function addFilterControl( ControlInterface $control ) {
			$this->controls[ ] = $control;
			$this->filter_controls[] = $control;

			return $this;
		}

		/**
		 * Get all registered settings
		 *
		 * @return array
		 * @access public
		 */
		public function getAttributes() {
			return $this->attributes;
		}

		/**
	     * Get unique meta box ID
	     *
	     * @return string
	     * @access public
	     */
	    public function getId() {
		    return $this->id;
	    }

		/**
		 *
		 * Get the registered meta boxes
		 *
		 * @param bool $to_array if true all controls will be
		 *                       flat to ich settings array
		 *
		 * @return array|\SilverWp\Helper\Control\ControlInterface[]
		 * @access public
		 */
        public function getControls( $to_array = false ) {
            $controls = array();
	        if ( $to_array ) {
		        foreach ( $this->controls as $control ) {
			        $controls[] = $control->getSettings();
		        }

		        return $controls;
	        }

	        return $this->controls;
        }

		/**
		 * Get single meta box by name
		 *
		 * @param int    $post_id
		 * @param string $control_name valid meta box name
		 * @param bool   $remove_first remove first element
		 *
		 * @return array|boolean
		 *
		 * @access   public
		 */
		public function get( $post_id, $control_name, $remove_first = true ) {
			$post_meta = get_post_meta( $post_id, $this->id, true );

			if ( $post_meta && RecursiveArray::searchKey( $control_name, $post_meta ) ) {

				$meta_boxes = RecursiveArray::searchRecursive( $post_meta, $control_name );

				if ( \count( $meta_boxes ) == 1 && \is_array( $meta_boxes ) && $remove_first ) {
					return $meta_boxes[ 0 ];
				}

				if ( is_array( $meta_boxes ) ) {
					$meta_boxes = RecursiveArray::removeEmpty( $meta_boxes );
				}

				return $meta_boxes;

			} else {
				return false;
			}
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
					$child_class = get_called_class();
					throw new Exception(
						Translate::translate(
							'Class property %s is required and can\'t be empty.'
							, $child_class . '::id'
						)
					);
				}

				$this->setUp();

				if ( count( $this->filter_controls ) ) {
					add_action( 'save_post', array( $this, 'saveFilterMeta' ), 10, 1 );
				}

				$default_attributes = array(
					'id'          => $this->id,
					'is_dev_mode' => self::DEV_MODE,
					'template'    => $this->getControls( true ),
					'priority'    => 'high',
				);

				$this->types = $this->post_types;

				$this->attributes = wp_parse_args( $this->attributes, $default_attributes );

				if ( $this->debug ) {
					Debug::dumpPrint( $this->attributes );
					Debug::dumpPrint( $this->filter_controls );
				}

				new VP_Metabox( $this->attributes );

				$this->manageColumns();

			} catch ( Exception $ex ) {
				$ex->displayAdminNotice();
			}
		}

        /**
         *
         * In this method we set up our meta boxes and all other settings
         *
         * @access protected
         * @abstract
         */
        abstract protected function setUp();

		/**
		 *
		 * All meta boxes registered by VP are serialized so we can't
		 * filter this method register meta boxes for filter by meta
		 * box key => value in WP_Query
		 *
		 * @param int $post_id
		 *
		 * @access public
		 * @todo   add some $_POST filters
		 * @return int
		 */
		public function saveFilterMeta( $post_id ) {
			//check is not autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			//verify nonce
			$nonce_name = $this->getId() . '_nonce';
			if ( ! isset( $_POST[ $nonce_name ] )
			     || ! wp_verify_nonce( $_POST[ $nonce_name ], $this->getId() )
			) {
				return $post_id;
			}
			//check current post type
			if ( ! in_array( $_POST['post_type'], $this->post_types ) ) {
				return $post_id;
			}
			// check user permissions
			if ( $_POST['post_type'] == 'page' ) {
				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return $post_id;
				}
			}

			foreach ( $this->filter_controls as $control ) {
				// Sanitize the user input.
				$post_value = $_POST[ $this->getId() ][ $control->getName() ];
				//todo change this is not universal
				//todo maybe get value from control class?
				if ( is_array( $post_value ) ) {
					$post_value = $post_value[ 0 ];
				}
				$value = sanitize_text_field( $post_value );
				// Update the meta field.
				update_post_meta( $post_id, $control->getName(), $value );
			}

			return $post_id;
		}

        /**
         * Change default label in meta box enter title hear
         *
         * @param string $title
         *
         * @return string
         * @access public
         */
        public function changeEnterTitleHearLabel( $title ) {
            if ( isset( $this->enter_title_hear_label ) ) {
                $screen = get_current_screen();
	            if ( in_array( $screen->post_type, $this->post_types ) ) {
                    $title = $this->enter_title_hear_label;
                }
            }
            return $title;
        }

        /**
         * Remove meta boxes from admin page
         *
         * @access public
         */
		public function removeMetaBoxes() {
			foreach ( $this->remove() as $value ) {
				remove_meta_box( $value['id'], $value['page'], $value['context'] );
			}
		}

		/**
		 * Set post title as required
		 *
		 * @access public
		 */
		public function forceTitle() {
			global $typenow;

			if ( in_array( $typenow, $this->post_types ) ) {
				if ( isset( $this->enter_title_hear_label ) ) {
					$error_message = Translate::translate(
						'Field %s is required and can not be empty.',
						$this->enter_title_hear_label
					);
				} else {
					$error_message = Translate::translate(
						'Field %s is required and can not be empty.',
						'Post Title'
					);
				}
				echo "<script type='text/javascript'>\n";
				echo "
			        jQuery('#publish').click(function(){
						var testervar = jQuery('[id^=\"title\"]')
						.find('#title');
						if (testervar.val().length < 1)
						{
							jQuery('[id^=\"title\"]').css('background', 'red');
							setTimeout(function () {jQuery('#ajax-loading').css('visibility', 'hidden');}, 100);
							alert('" . $error_message . "');
							setTimeout(\"jQuery('#publish').removeClass('button-primary-disabled');\", 100);
							return false;
						}
			        });
			    ";
				echo "</script>\n";
			}
		}

		/**
		 * Manage custom columns in edit screen
		 *
		 * @access private
		 * @todo move to CustomColumn class
		 */
		private function manageColumns() {
			if ( is_admin() ) {
				// Adds columns in the admin view for thumbnail and taxonomies
				foreach ( $this->post_types as $post_type ) {
					add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'setColumnsLabels' ), 10, 1 );
					add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'customColumns' ), 10, 2 );
				}
			}
		}

		/**
		 * Add columns labels to edit screen
		 *
		 * @link   http://wptheming.com/2010/07/column-edit-pages/
		 * @access public
		 *
		 * @param array $columns
		 *
		 * @return array
		 * @todo move to CustomColumn class
		 */
		public function setColumnsLabels( $columns ) {
			$unique_cols   = array( 'category', 'tag' );
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
		 * Add custom columns in edit screen
		 *
		 * @param string $column column name
		 * @param int    $post_id
		 *
		 * @access public
		 * @since 0.5
		 * @todo move to CustomColumn class
		 */
		public function customColumns( $column, $post_id ) {
			try {
//				todo move to meta box
				if ( $column == 'thumbnail' ) {
					// Display the featured image in the column view if possible
					if ( \has_post_thumbnail( $post_id ) ) {
						\the_post_thumbnail( $this->column_image_size );
					} else {
						echo Translate::translate( 'None' );
					}
				}

			} catch ( Exception $ex ) {
				echo $ex->displayAdminNotice();
			}
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
		 * @todo move to CustomColumn class
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
			);

			$columns = UtlArray::array_remove_part( $columns_default, $this->exclude_columns );

			return $columns;
		}
    }
}