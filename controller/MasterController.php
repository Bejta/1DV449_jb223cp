<?php

namespace controller;

require_once("view/LayoutView.php");
require_once("view/HTMLView.php");
require_once("view/FooterView.php");
require_once("view/CrawlerView.php");
require_once("model/Crawler.php");

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

		if($hv->IsPosted()){

            
            $cv->renderMovies($cm->Scrape($hv->getURL()));
      		
	    }
	    else if($cv->checkAvailableTable()){

            if(isset($_SESSION['URL'])){
            	$url=$_SESSION['URL']; 
	    	    $cv->bookTable($cm->getTables($cv->getAvailableTable(),$url)); 
	    	}     
		}
		else{

			$cv->resetMovies();

		}

	    	$lv->render($hv,$cv,$fv);

	    }
	   	
}
