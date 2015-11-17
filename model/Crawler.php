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

	public function getAvailableMovies($URL){
		
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