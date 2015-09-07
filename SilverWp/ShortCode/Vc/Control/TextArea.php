<?php
/*
 * Copyright (C) 2014 Michal Kalkowski <michal at silversite.pl>
 *
 * SilverWp is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * SilverWp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace SilverWp\ShortCode\Vc\Control;

if ( ! class_exists( '\SilverWp\ShortCode\Vc\Control\TextArea' ) ) {

    /**
     * Visual composer text area control element
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage ShortCode\Vc\Control
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright SilverSite.pl 2015
     * @version 0.2
     */

    class TextArea extends ControlMultiAbstract {
        protected $type = 'textarea';

	    /**
	     * Visual composer have some extra textarea field
	     * Like exploded_textarea so hear we can define
	     * prepend value of type
	     *
	     * @param string $pre_type - exploded
	     *
	     * @return $this
	     * @access public
	     * @since 0.2
	     */
	    public function setPreType( $pre_type ) {
		    $this->setting[ 'type' ] = $pre_type . '_' . $this->type;

		    return $this;
	    }

	    /**
	     * Visual composer have some extra textarea field
	     * Like textarea_raw_html so hear we can define
	     * append value of type
	     *
	     * @param string $pre_type - raw_html or safe
	     *
	     * @return $this
	     * @access public
	     * @since 0.2
	     */
	    public function setPosType( $pos_type ) {
		    $this->setting[ 'type' ] = $this->type . '_' . $pos_type;

		    return $this;
	    }
    }
} 