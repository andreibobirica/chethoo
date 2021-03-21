<?php

  // Add base route (startpage)
  $this->pathRoute->add('/',function(){
      echo 'Welcome :-)';
  });

  // Simple test route that simulates static html file
  $this->pathRoute->add('/test.html',function(){
      echo 'Hello from test.html';
  });

  // Get route example
  $this->pathRoute->add('/contact-form',function(){
      echo '<form method="post"><input type="text" name="test" /><input type="submit" value="send" /></form>';
  },'get');

  // Post route example
  $this->pathRoute->add('/contact-form',function(){
      echo 'Hey! The form has been sent:<br/>';
      print_r($_POST);
  },'post');

  // Accept only numbers as parameter. Other characters will result in a 404 error
  $this->pathRoute->add('/foo/([0-9]*)/bar',function($var1){
      echo $var1.' is a great number!';
  });

  // Accept all as parameter. Maybe Other characters will result in a 404 error
  $this->pathRoute->add('/blog/([a-z-0-9-]*)', function($slug) {
      echo $slug;
  });

  // This route is for debugging only
  // It simply prints out some php infos
  // Do not use this route on production systems!
  $this->pathRoute->add('/phpinfo', function() {
      phpinfo();
  });

  // Get and Post route example
  $this->pathRoute->add('/get-post-sample', function() {
      echo 'You can GET this page and also POST this form back to it';
      echo '<form method="post"><input type="text" name="input"><input type="submit" value="send"></form>';
      if (isset($_POST['input'])) {
          echo 'I also received a POST with this data:<br>';
          print_r($_POST);
      }
  }, ['get','post']);

  // Route with regexp parameter
  // Be aware that (.*) will match / (slash) too. For example: /user/foo/bar/edit
  // Also users could inject SQL statements or other untrusted data if you use (.*)
  // You should better use a saver expression like /user/([0-9]*)/edit or /user/([A-Za-z]*)/edit
  $this->pathRoute->add('/user/(.*)/edit', function($id) {
      echo 'Edit user with id '.$id.'<br>';
  });

  // Crazy route with parameters
  $this->pathRoute->add('/(.*)/(.*)/(.*)/(.*)', function($var1,$var2,$var3,$var4) {
      navi();
      echo 'This is the first match: '.$var1.' / '.$var2.' / '.$var3.' / '.$var4.'<br>';
  });

  // Add a 404 not found route
  $this->pathRoute->pathNotFound(function($path) {
      // Do not forget to send a status header back to the client
      // The router will not send any headers by default
      // So you will have the full flexibility to handle this case
      header('HTTP/1.0 404 Not Found');
      navi();
      echo 'Error 404 :-(<br>';
      echo 'The requested path bho"'.$path.'" was not found!';
  });

?>