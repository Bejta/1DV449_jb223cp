<?php

namespace view;

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