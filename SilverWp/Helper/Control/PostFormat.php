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

namespace SilverWp\Helper\Control;

use SilverWp\Translate;

if ( ! class_exists( 'SilverWp\Helper\Control\PostFormat' ) ) {

	/**
	 *
	 * Post typ format control groups
	 *
	 * @category  WordPress
	 * @package   SilverWp
	 * @subpackage
	 * @author    Michal Kalkowski <michal at silversite.pl>
	 * @copyright SilverSite.pl 2015
	 * @version   Revision:$
	 */
	class PostFormat extends Group {

		/**
		 * Class constructor setup control group
		 *
		 * @param string $name
		 * @access public
		 */
		public function __construct( $name = 'post_format' ) {
			parent::__construct( $name );
			$this->setLabel( Translate::translate( 'Post format' ) );

			$format = new Select( 'format' );
			$format->setLabel( Translate::translate( 'Post format' ) );
			$format->setOptions(
				array(
					array(
						'label' => Translate::translate( 'Standard' ),
						'value' => false,
					),
					array(
						'label' => Translate::translate( 'Video' ),
						'value' => 'video',
					),
					array(
						'label' => Translate::translate( 'Gallery' ),
						'value' => 'gallery',
					),
					array(
						'label' => Translate::translate( 'Audio' ),
						'value' => 'audio',
					),
				)
			);
			$this->addControl( $format );

			$url = new Text( 'video_url' );
			$url->setLabel( Translate::translate( 'YouTube or Vimeo file URL' ) );
			$url->setValidation( 'url' );
			$url->setDependency($format, 'silverwp_post_format_dep_boolean');
			$this->addControl( $url );

			$gallery = new Upload( 'image' );
			$gallery->setLabel( Translate::translate( 'Image' ) );
			$gallery->setDependency($format, 'silverwp_post_format_dep_boolean');
			$this->addControl( $gallery );

			$audio = new Text( 'audio_url' );
			$audio->setLabel( Translate::translate( 'SoundCloud audio URL' ) );
			$audio->setValidation( 'url' );
			$audio->setDependency($format, 'silverwp_post_format_dep_boolean');
			$this->addControl( $audio );

		}
	}
}