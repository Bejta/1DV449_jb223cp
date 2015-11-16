<?php 

namespace view;

class HTMLView{

	private static $URL = 'HTMLView::URL';
	private static $sendURL = 'HTMLView::sendURL';

	public function response() {

		$response = $this->generateURLFormHTML();
		
		return $response;
	}

	public function generateURLFormHTML() {
		return '<form method="post"> 

		<fieldset>
					<legend>Ange URL</legend>
					<br /><br />
					<div>
					
					<input type="text" class="input" id="' . self::$URL . '" name="' . self::$URL . '" value="' . $this->getNumberOfWins() . '" />
                    <input type='submit' name=' . self::$postURL . ' value='Submit' /> 
					</div>
                    <br />		
		</fieldset>		
		</form>';
	}

	// If submit button is pressed
	public function IsPosted() {
		return isset($_POST[self::$postURL]);
	}
    
    // Getter for URL
	public function getURL()
	{
	    if($this->IsPosted()){

	      return $_POST[self::$URL];

	    }
	    else{

	      return null;
	    }
	}
}