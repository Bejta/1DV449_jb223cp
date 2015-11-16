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

	public function getAvailableDays($URL){

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

		$dom->loadHTML($data);

		if($dom != null){
            
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

		return $okayDays;
		
	}
}