<?php

namespace view;

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');*/

error_reporting(0);

class CrawlerView {

	private $message='';
	private static $RestaurantURL = 't';

	/*
     * Resets message!
     */
    public function resetMovies()
    {
       $this->message = '';
    }

	public function response(){

		return $this->getMessage();
	}

	public function getMessage(){
		return $this->message;
	}
	public function getAvailableTable(){
		if(isset($_GET[self::$RestaurantURL])){

	      return $_GET[self::$RestaurantURL];
	    }
	    else {
	      return null;
	    }

	}

	//If there are available tables at the restaurant
	public function checkAvailableTable()
    {
    	return (isset($_GET[self::$RestaurantURL]));
	    
    }
    public function bookTable($tables){



    	$this->message='<ul>';

    	for ($i=0; $i<count($tables);$i++){

    		$dinner=$tables[$i];
    		$dinnertime=substr($dinner,-4);
    		$dinnerstart=substr($dinnertime,0,2);

    		$this->message .='<li> Det finns ledigt bord klockan ' .$dinnerstart ;
    		
    	}

		$this->message .= '</ul>';
       

    }

	public function renderMovies($movies){

		if($movies!=null){

			$this->message = '<ul>';
			foreach($movies as $movie){
                
                $this->message .='<li> Filmen '.$movie['title'] .' visas kl '.$movie['time'] . ' på '.$movie['day'] .' <a href="?'.self::$RestaurantURL.'='.$movie['title'].$movie['time'].$movie['day'].'">Titta tillgängliga bord</a>';

			}
			$this->message .= '</ul>';

		}
		else{
           $this->message="Det finns inga filmer denna dagen!!!";
		}

	}

}