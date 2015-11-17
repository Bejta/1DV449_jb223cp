<?php

namespace model;


class Crawler{

	 private $dom;


	public function Scrape($URL){

		if ($URL!=null){

          $this->dom = new DOMDocument();

          $start = curl_get_request($URL);
          
          if($start!=null){

            $this->dom->loadHTML($start);

          }


		}
		else{
			return "<p> Site is not available</p>"
		}

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
	public function getLinks($URL){

		$links=array();
        $data=curl_get_request($URL);
        $dom = new DOMDocument();

        if($dom->loadHTML($data)){

        	//Writes all a-elements in array $href
        	$href=$dom->getElementsByTagName("a");
            
            // Loop trought array of hrefs and writes it's href attribute in array
            // http://php.net/manual/en/domelement.getattribute.php
            foreach($href as $link){
                $links[]=$link->getAttribute("href");

            }
            
            // For some reason, this doesn't works
        	/*for( $i=0; $i<$href->length;$i++){ 
                $links[]=$href->item(i)->getAttribute("href");
        	}*/

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

		$availableDays = array();
        
        
        for ($i=0; $i<$links->length; i++){
           
           $link = $url . $link[i];

           //have to check if link is ok
           var_dump($link);
           $availableDays[]=getPersonsAvailableDays($link);

        }

        // Solution to intersect arrays as a members of one array
        //http://stackoverflow.com/questions/5389437/intersect-unknown-number-of-arrays-in-php

        $result = call_user_func_array('array_intersect',$availableDays);


		return $result;

	}
    
    /*
     *
     * This function returns a times for movie that has avilable places on given day
     *
     */
	public function getPossibleMovies($movieURL,$day,$movie){
        
        // Builds a query for a specific day and specific movie that comes in parameters
		$query = "/check?day=0".$day."&movie=".$movie;

		// Builds the whole url
		$moviequery=$this->curl_get_request($movieURL.$query);

		$moviesResult=array();
        $dom = new DOMDocument();

        if($dom->loadHTML($moviequery)){

            // Decodes json object and from the array takes only those movies that has available places (status==1)
            // Writes the start time of the available movie, and at the end returns an array with the times of the movie
        	foreach(json_decode($moviequery) as $movie){
        		 if($movie->status == 1){
        		 	array_push($moviesResult, $movie->time);
        		 }
        	}
        }
        else{
        	die("Fel vid inläsning av HTML");
        }

        return $moviesResult;

	}

	public function getAvailableMovies($URL,$days){

		$moviesResult = array();
		$data= $this->curl_get_request($URL);
        $possibleMovies = array();

        $dom = new DOMDocument();

        
        for($i=0;$i<$days->length;i++){

        	if($days[i]=='Friday'){

        		$days[i]='Fredag';
        	}
        	else if($days[i]=='Saturday'){

        		$days[i]='Lördag';
        	}
        	else if($days[i]=='Sunday'){

        		$days[i]='Söndag';
        	}
        }

        if($dom->loadHTML($data)){

        	$xpath= new DOMXPath($dom);

        	$movies = $xpath->query('//select[@name = "movie"]/option[@value]');
            
            for($i=0;$i<$days->length;i++){
            	foreach($movies as $movie){
            		$possibleMovies=$this->getPossibleMovies($movie->getAttribute("value"),$days[i],$movie)
                    $moviesResult[]=array($movie->getAttribute("value"),$possibleMovies,$days[i]);
            	}
            }


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
		$data=$this->curl_get_request($URL);

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
					$okayDays[] = $day->item(i)->nodeValue;
				}
			}
		}
		else{
			echo "Sorry, It's not you it's me";
		}

		return $okayDays;
		
	}
}