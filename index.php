<?php
require('drop/acid.php');


$pagedb = new pages();
$pagedb->findby('page', $page);


if($CONF['wiki']['page_edit_require_login'] && !$user->logged_in() && in_array($action, array('edit', 'delete', 'save') ) ){
	$action = "view";
	$_SESSION["error"] = "You must be logged in to make changes.";
	redirect("/".$page);
}

switch($action){
	case "delete":
		if($pagedb->count() != 0){
			unlink($pagedb->htmlfile());
			unlink($pagedb->rawfile());
			$pagedb->delete();
		}
		$_SESSION['info'] = "Page ".$page." deleted successfully!";
		redirect('/'.$page);
		break;
	case "save":
		require('drop/helpers/Parsedown.php');
		$Parsedown = new Parsedown();
		$Parsedown->setMarkupEscaped(true);
		
		$content_html = $Parsedown->text($_REQUEST['editor']);
		$content_raw = htmlentities($_REQUEST['editor']);
		$file = md5($_REQUEST['page_slug']);
		
		$pagedb->set('page', $_REQUEST['page_slug']);
		$pagedb->set('file', $file);
		$pagedb->save();
				
		file_put_contents($pagedb->htmlfile(), $content_html);
		file_put_contents($pagedb->rawfile(), $content_raw);
		
		$_SESSION['info'] = "Updated page: ".$page;
		redirect("/".$page);
		
		break;
	case "edit":
		if($pagedb->count() == 0){
			$content = "# ".str_replace("/", " ", $page)."\nSome content here. \n## Smaller Header \nSome more content here.";
		}else{
			$content = file_get_contents($pagedb->rawfile());
		}

		$tpl = new vision("drop/vision/editpage.tpl");

		$tpl->assign('page', $page);
		$tpl->assign('content', $content);
		$tpl->parse('editor');
		$tpl->out('editor');
		break;

	case "view":
	default:
		if($pagedb->count() == 0){
			header("HTTP/1.0 404 Not Found");
			$content = '<h2>Page Not Found</h2> Page currently does not exists. If you would like to create it, click <a href="/edit/'.$page.'">here</a> to do so.';
		}else{
			$content = file_get_contents($pagedb->htmlfile());
		}


		echo $content;
		break;
}
