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
 /*
  Repository path: $HeadURL: $
  Last committed: $Revision: $
  Last changed by: $Author: $
  Last changed date: $Date: $
  ID: $Id: $
 */
namespace SilverWp\PostRelationship;

if ( ! class_exists( 'SilverWp\PostRelationship\Labels' ) ) {
    /**
     *
     * @category WordPress
     * @package SilverWp
     * @subpackage SilverWp\PostRelationship
     * @author Michal Kalkowski <michal at silversite.pl>
     * @copyright Dynamite-Studio.pl & silversite.pl 2015
     * @version $Revision:$
     */
    class Labels {
        const OP_FROM   = 'from_labels';
        const OP_TO     = 'to_labels';

        public $from;
        public $to;

        private $labels;
        private $predicates = array();

        public function setSingularName( $label ) {
            $this->labels['singular_name'] = $label;
            return $this;
        }

        public function setSearchItems( $label ) {
            $this->labels['search_items'] = $label;
            return $this;
        }

        public function setNotFound( $label ) {
            $this->labels['not_found'] = $label;
            return $this;
        }

        public function setCreate( $label ) {
            $this->labels['create'] = $label;
            return $this;
        }
        public function getLabels() {
            return $this->labels;
        }

        public function __get($name) {
            switch (strtolower($name)) {
                case 'from':
                    //$this->fromPredicate();
                    break;
                case 'to':
                    //$this->toPredicate();
                    break;
            }
            return $this;
        }
        public function fromPredicate($predicate) {
            $this->predicates[] = array(self::OP_FROM, $predicate);
            return $this;
        }

        public function toPredicate($predicate) {
            $this->predicates[] = array(self::OP_TO, $predicate);
            return $this;
        }
    }
}