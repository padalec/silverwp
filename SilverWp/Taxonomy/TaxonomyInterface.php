<?php
/*
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Taxonomy/TaxonomyInterface.php $
  Last committed: $Revision: 2184 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 13:20:08 +0100 (Śr, 21 sty 2015) $
  ID: $Id: TaxonomyInterface.php 2184 2015-01-21 12:20:08Z padalec $
 */

/**
 * Taxonomy interface
 *
 * @author Michal Kalkowski <michal at silversite.pl>
 * @version $Id: TaxonomyInterface.php 2184 2015-01-21 12:20:08Z padalec $
 * @category WordPress
 * @package SilverWp
 * @subpackage Taxonomy
 * @copyright 2009 - 2014-03-18 SilverSite.pl
 */

namespace SilverWp\Taxonomy;

interface TaxonomyInterface
{
    public function getPostTerms($term_name);
    public function getName($name);
    public function init();
    public function setObjectType(array $post_type);
    public function setPostType($post_type);
}
