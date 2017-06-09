<?php
require('drop/acid.php');

$tpl = new vision("drop/vision/search_results.tpl");

if(isset($_REQUEST['goto'])){
	$pagedb = new pages();
	$pagedb->findby('file', $_REQUEST['goto']);
	if($pagedb->count() == 0){
		$_SESSION['error'] = "Failed to go to requested file.";
		redirect('/');
	}else{
		redirect('/'.$pagedb->get('page'));
	}
}else{

	$terms = (isset($_REQUEST['query']))? $_REQUEST['query'] : "";

	$terms = preg_replace("/[^A-Za-z0-9 ]/", '', $terms);
	$first = $terms;
	$terms = trim(preg_replace('!\s+!', ' ', $terms));
	$terms = str_replace(' ', '|', $terms);
	$terms = explode('|', $terms);
	foreach($terms as $k => $v){
		if(strlen($v) <= 3){
			unset($terms[$k]);
		}
	}
	$terms = implode("|", $terms);
	$terms = $terms."|".$first;
	
	$tpl->assign('terms', $terms);
	
	$grepcmd = 'grep -riHE "'.$terms.'" drop/pages/';

	exec($grepcmd, $outvar);

	$res = array();

	foreach($outvar as $result){
		$sep = split(':', $result, 2);
		$filetypeck = substr($sep[0], -2);
		if($filetypeck != "md"){ continue; }
		$md5 = substr($sep[0], -35, 32);
		$match = $sep[1];
	
		if(!isset($res[$md5])){
			$res[$md5] = array();
		}
	
		$res[$md5][] = $match;
	}

	foreach($res as $md5 => $matches){
		$tpl->assign('md5', $md5);
		$tpl->assign('count', count($matches));
		$tpl->assign('matches', implode("<br>", $matches));
		$tpl->parse("search_results.result");
	}
	
	if(count($res) == 0){
		$tpl->parse('search_results.no_results');
	}
	$tpl->parse('search_results');
	$tpl->out('search_results');

}
