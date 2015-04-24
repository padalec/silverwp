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
  Repository path: $HeadURL: https://svn.nq.pl/wordpress/branches/dynamite/igniter/wp-content/themes/igniter/lib/SilverWp/Views/Helper/auto-update.php $
  Last committed: $Revision: 2185 $
  Last changed by: $Author: padalec $
  Last changed date: $Date: 2015-01-21 14:08:54 +0100 (Śr, 21 sty 2015) $
  ID: $Id: auto-update.php 2185 2015-01-21 13:08:54Z padalec $
 */
?>
<div class="updated">
<h3><?= \SilverWp\Translate::translate('Update Available!');?></h3>
A new Version (<?= $data['new_version']?>) of your "<?= $data['parent'] ;?> " is available! You are using Version " <?= $data['version'] ?> ". <br/>Do you want to update?<br/><br/>
<a href="/update-core.php" class="">Update Now!</a>
</div>