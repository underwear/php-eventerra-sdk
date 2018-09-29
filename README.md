# php-eventerra-sdk  
  
**Simple usage:**  
  

    $eventerra = new \Eventerra\Eventerra([  
	    'aid' => '123',
	    'secret' => 'passw0rd']
    );  
    
    $tours = $eventerra->getTours();  
    
    print_r($tours);
