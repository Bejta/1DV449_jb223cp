<?php 

namespace view;

class HTMLView{

	private static $URL = 'HTMLView::URL';
	private static $postURL = 'HTMLView::sendURL';

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
					
					<input type="text" class="input" id="' . self::$URL . '" name="' . self::$URL . '" value="' . '' . '" />
                    <input type="submit" name=' . self::$postURL . ' value="submit" /> 
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

          $_SESSION['URL'] = trim($_POST[self::$URL]);
	      return $_POST[self::$URL];

	    }
	    else{

	      return null;
	    }
	}
}