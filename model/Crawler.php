<?php

namespace model;

use DOMDocument;
use DOMXPath;

class Crawler{

	 //private $dom;


	public function Scrape($URL){

		if ($URL!=null){

          $dom = new DOMDocument();

          $start = $this->curl_get_request($URL);
          
          $result = array();
          $movies = array();
          $tables = array();

          
          if($start!=null){
            
            if ($dom->loadHTML($start)){
                  
                  $links = $this->getLinks($URL.'calendar/');
                  

      	        //$properDays=$this->getAvailableDays($URL,$linksToSubpages[0]->getAttribute("href"));
                $properDays=$this->getAvailableDays($links,$URL);
                
      	        $movies=$this->getAvailableMovies($URL,$properDays);
                //var_dump($movies);
      	        $tables=$this->getTables($movies,$URL);
                  //var_dump($tables);

                 
                  array_push($result, array('tables'=>$tables, 'movies'=>$movies));
            }
          }
		}
		else{
			die("Site is not available");
		}

		return $result;

	}

	/*
	 *
	 * This function analyse movies and days and compares it with available tables at the restaurant
	 *
	 */
	public function getTables($movie,$URL){

		$tables=array();
		$data=$this->curl_get_request($URL);

		$dom = new DOMDocument($data);

		if ($dom->loadHTML($data)) {
                $xpath = new DOMXPath($dom);
                $tables = $xpath->query("//input[@type='radio']");

                foreach($tables as $table){
                    
                    // http://stackoverflow.com/questions/4366730/check-if-string-contains-specific-words
                	$comparison = $table->getAttribute("value");

                	$time=intval($movie['time']);

                	if( $movie['day'] === "Friday" && (strpos($comparison,'fre') !== false)){

                        if($time<intval(substr($comparison, 3, 2))){
                        	$tables[]=$comparison;
                        }
                		

                	}
                	else if( $movie['day'] === "Saturday" && (strpos($comparison,'lor') !== false)){

                		if($time<intval(substr($comparison, 3, 2))){
                        	$tables[]=$comparison;
                        }

                	}
                	else if( $movie['day'] === "Sunday" && (strpos($comparison,'son') !== false)){

                		if($time<intval(substr($comparison, 3, 2))){
                        	$tables[]=$comparison;
                        }

                	}
                }
         }
         return $tables;       
	}
    

    //Curl request, example from demo video
	public function curl_get_request($URL){

		$ch=curl_init();

		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data=curl_exec($ch);
		curl_close($ch);
    
		return $data;
	}
    
    // Get links to all pages from given URL
    // It can be calendar URL so it gives URL to all people
	public function getLinks($calendarURL){
        
		    $links=array();
       
        $data = $this->curl_get_request($calendarURL);
        
        $dom = new DOMDocument($this->curl_get_request($calendarURL));
       
        if($dom->loadHTML($data)){

        	//Writes all a-elements in array $href
        	$href=$dom->getElementsByTagName("a");
            
            // Loop trought array of hrefs and writes it's href attribute in array
            // http://php.net/manual/en/domelement.getattribute.php

            foreach($href as $link){
                $links[]=$link->getAttribute("href");

            }
        }
    
		return $links;

	}
    
    /*
     *
     * This function will analyze friends available days, and return one array with available days
     * 
     *
     */
	public function getAvailableDays($links,$url){

        $url.='calendar/';
		    $availableDays = array();
        
        for ($i=0; $i<count($links); $i++ ){
           
           $availableDays[$i]=$this->getPersonsAvailableDays($url . $links[$i]);

        }
        
        // Solution to intersect arrays as a members of one array
        //http://stackoverflow.com/questions/5389437/intersect-unknown-number-of-arrays-in-php
        $result = call_user_func_array('array_intersect',$availableDays);
        
        //http://php.net/manual/en/function.array-values.php
        $emptyRemoved = array_values($result);
       
		return $emptyRemoved;

	}
    
    /*
     *
     * This function returns a times for movie that has avilable places on given day
     *
     */
	/*public function getPossibleMovies($movieValue,$day,$movieURL){
        
        // Builds a query for a specific day and specific movie that comes in parameters
		$query = "/check?day=0".$day."&movie=".$movieValue;

		// Builds the whole url
		$moviequery=$this->curl_get_request($movieURL.$query);

		$moviesResult=array();
        $dom = new DOMDocument();

        if($dom->loadHTML($moviequery)){

            // Decodes json object and from the array takes only those movies that has available places (status==1)
            // Writes the start time of the available movie
        	foreach(json_decode($moviequery) as $movie){
        		 if($movie->status == 1){
        		 	//array_push($moviesResult, $movie->time);
        		 	//$moviesResult[]=array($movie->getAttribute("value"),$possibleMovies,$days[i]);
        		 	array_push($movieResult, array('time'=>$movie['time'],'title'=>$movie->nodeValue));
        		 }
        	}
        }
        else{
        	die("Fel vid inläsning av HTML");
        }

        return $moviesResult;

	}*/

	public function getAvailableMovies($URL,$days){

		$moviesResult = array();
    $URL.='cinema';
		$data= $this->curl_get_request($URL);
    var_dump($days);   

        $dom = new DOMDocument();

        for($i=0; $i<count($days); $i++){

        	if($days[$i]=='Friday'){

        		$days[$i]='Fredag';
        	}
        	else if($days[$i]=='Saturday'){

        		$days[$i]='Lördag';
        	}
        	else if($days[$i]=='Sunday'){

        		$days[$i]='Söndag';
        	}
        }


        if($dom->loadHTML($data)){

        	$xpath= new DOMXPath($dom);

        	$movies = $xpath->query('//select[@name = "movie"]/option[@value]');
        	$moviedays = $xpath->query("//select[@id='day']/option[not(@disabled)]");
          

        	foreach($moviedays as $day){
                  
                  if (in_array($day->nodeValue, $days)){
                      var_dump('hej');

	                  	foreach($movies as $movie){

	                  		$JSONMovies = $this->curl_get_request($URL . "/check?day=" . $day->getAttribute('value') . "&movie=" . $movie->getAttribute('value'));
                        
                        $DeceodedMovies=json_decode($JSONMovies,true);
	                  		foreach($DeceodedMovies as $JSONmovie){

	                  			if($JSONmovie['status'] == 1){

	                  				array_push($moviesResult, array('time'=>$JSONmovie['time'], 'day'=>$day->nodeValue, 'title'=>$movie->nodeValue));
	                  			}
	                  		}
	                  	}
                  }
        	}

        	
            /*
            for($i=0;$i<$days->length;i++){
            	foreach($movies as $movie){
            		$possibleMovies=$this->getPossibleMovies($movie->getAttribute("value"),$days[i],$movie)
                    $moviesResult[]=array($movie->getAttribute("value"),$possibleMovies,$days[i]);
            	}
            }*/


        }
        else{
        	die("Fel vid inläsning av HTML");
        }

        return $moviesResult;

		
	}
    

    /*
     *
     * This function returns available days for only one person
     *
     */
	public function getPersonsAvailableDays($URL){

		$okayDays=array();
    //var_dump($URL);
		$data=$this->curl_get_request($URL);
    //var_dump($data);
		$dom = new DOMDocument();

		//$dom->loadHTML($data);

		if($dom->loadHTML($data)){
            
            // Table header has information about specific day
            // Table cells "td" has information about availability
			$ok=$dom->getElementsByTagName("td");
			$day=$dom->getElementsByTagName("th");

            
            //Goes trought all days in $day array, and write those with "ok" (or "Ok" and "OK" which is corrected by strtoupper function) in okaydays array
			for( $i=0; $i<$day->length;$i++){

				if(strtoupper($ok->item($i)->nodeValue) == "OK"){
					$okayDays[] = $day->item($i)->nodeValue;
				}
			}
		}
		else{
			echo "Sorry, It's not you it's me";
		}

		return $okayDays;
		
	}
}