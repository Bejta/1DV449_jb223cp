<?php

namespace view;

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

class LayoutView {

  public function render($hv,$cv,$fv){
  		echo '<!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8">
            <title>Laboration 1</title>
            <link href="style/main.css" rel="stylesheet" type="text/css" />
            </head>
            <body>
              <div>
              ' . $hv->response() . '
              </div>
              <br />
              <div>
                  ' . $cv->response() . '
              </div>
              
              ' . $fv->response() . '
             
             </body>
          </html>
      ';
  	}
}