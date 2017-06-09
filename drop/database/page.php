<?php
class pages extends catalyst {
	public $table_name = 'pages';
	public $primary_key = 'page_id';
	
	public function htmlfile(){
		return 'drop/pages/'.$this->get('page_id').$this->get('file').'.html';
	}
	
	public function rawfile(){
		return 'drop/pages/'.$this->get('page_id').$this->get('file').'.md';
	}
}
