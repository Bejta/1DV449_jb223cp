<?php

namespace controller;

require_once("view/LayoutView.php");

class MasterController{

	public function __construct()
	{

	}

	public function Run(){

		//CREATE OBJECTS OF THE VIEWS
		$lv = new \view\LayoutView();
		$hv = new \view\HTMLView();
		$fv = new \view\FooterView();
		$cv = new \view\CrawlerView();

		//CREATE OBJECT OF THE MODEL
		$cm = new \model\Crawler();

		if($cv->urlPressed()){

			$cm->crawl($hv->getURL());
      		$lv->render($hv,$cv,$fv);
	    }
	    $lv->render($hv,$cv,$fv);
      		
}
