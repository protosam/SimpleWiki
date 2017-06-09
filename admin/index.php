<?php
/* Welcome to the acid example file!
 *
 * All we have to do to start using acid is require it.
 */
require('../drop/acid.php');
if($user->get('admin') != "Y")
	error("You don't belong here.");

$tpl = new vision("drop/vision/admin/dashboard.tpl");


$tpl->parse('dashboard');
$tpl->out('dashboard');
