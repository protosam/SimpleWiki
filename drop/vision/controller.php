<?php
// this array controls which template file is used based on the URL path
// first match will be used.
$TEMPLATES = array(
	'/login.php' => '',
	'/api' => '',
	'/admin' => 'drop/vision/admin/overall.tpl',
	'/' => 'drop/vision/overall.tpl',
);


$user = new users();


$action = strtolower((isset($_REQUEST['action']) && $_REQUEST['action'] != "")? $_REQUEST['action'] : "view");
$page = strtolower((isset($_REQUEST['page']) && $_REQUEST['page'] != "")? $_REQUEST['page'] : "homepage");


function vision_no_template()
{	extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
	$user->logged_in();	
}

function vision_userpanels($blockname, $tpl)
{	extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
	if($user->logged_in()){
		if($tpl->check($blockname.".admin_panel") && $user->get("admin") == "Y"){
			$tpl->parse($blockname.".admin_panel");
		}

		if($tpl->check($blockname.".logged_in")){
			$tpl->assign('username', $user->get("username"));
			$tpl->parse($blockname.".logged_in");
		}

	}else if ($tpl->check($blockname.".logged_out")){
		$tpl->parse($blockname.".logged_out");
	}

	if(isset($inc_editor) && $inc_editor == true){
		$tpl->parse($blockname.".inc_editor");
	}

}

// this is logic neccessary for our headers
function vision_header()
{	extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
	$tpl = new vision($tpl_file);
	vision_userpanels("header", $tpl);

	if(isset($_SESSION['info'])){
		$tpl->assign("message", $_SESSION['info']);
		unset($_SESSION['info']);
		$tpl->parse('header.info');
	}
	if(isset($_SESSION['error'])){
		$tpl->assign("message", $_SESSION['error']);
		unset($_SESSION['error']);
		$tpl->parse('header.error');
	}

	$tpl->assign('wikiname', $CONF['wiki']['name']);
	$tpl->parse('header');
	$tpl->out('header');
}

// this is logic neccessary for our footers
function vision_footer()
{	extract($GLOBALS, EXTR_REFS | EXTR_SKIP);
	$tpl = new vision($tpl_file);
	vision_userpanels("footer", $tpl);
	if(isset($_REQUEST['page']) && !isset($_REQUEST['action'])){
		$tpl->assign('page', $page);
		
		// conf check for page edits.
		if($CONF['wiki']['page_edit_require_login'] == false || $CONF['wiki']['page_edit_require_login'] && $user->logged_in()){
			$tpl->parse('footer.edit_page');
		}
	}
	$tpl->parse('footer');
	$tpl->out('footer');
}


function redirect($url){
	header("Location: $url");
	$tpl = new vision("drop/vision/redirect.tpl");
	$tpl->assign("url", $url);
	$tpl->parse("redirect");
	die;
}
function error($msg){
	$tpl = new vision("drop/vision/error.tpl");
	$tpl->assign("message", $msg);
	$tpl->parse("error");
	$tpl->out("error");
	die;
}
