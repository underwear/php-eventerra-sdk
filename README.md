PHP Eventerra SDK
=======================

This is unofficial php SDK for working with eventerra.de API

- supports PSR-3 Loggers (Monolog and etc.)
- supports different http clients (Guzzle, Curl and etc.) // PSR-18

## Prerequisites
- PHP 5.4 or later

## Installation
The PHP Eventerra SDK can be installed using Composer by running the following command:
  

    composer require underwear/php-eventerra-sdk
    

## Basic usage:

**Get tours:**
    
    $eventerra = new \Eventerra\Eventerra([  
	    'aid' => '123',
	    'secret' => 'passw0rd'
    ]);  
    
    $tours = $eventerra->getTours();  
    
    print_r($tours);


**Get free places:**
    
    $eventerra = new \Eventerra\Eventerra([  
	    'aid' => '123',
	    'secret' => 'passw0rd'
    ]);  
    
    $places = $eventerra->getFreePlacesForConcert(77);
    
    print_r($places);


**Create new order:**
    
    <?php

    $eventerra = new \Eventerra\Eventerra([
    	'aid' => '123',
    	'secret' => 'pass!w0rd'
    ]);
    
    $concertId = 99;

    $places = [];
    $places[] = new \Eventerra\Entities\EventerraPlace([
    	'block' => 'Rang Seite Links',
    	'row' => 2,
    	'place' => 4,
    	'price' => 79.00
    ]);
    $places[] = new \Eventerra\Entities\EventerraPlace([
    	'block' => 'Rang Seite Links',
    	'row' => 2,
    	'place' => 5,
    	'price' => 79.00
    ]);
    
    // this returns instance of \Eventerra\Entities\EventerraOrder
    $order = $eventerra->newOrder($concertId, $places); 
    
    // print_r($order);
    // $order->totalSum;
    // $order->status;
    // $order->linkPdfTicket;
    // $order->items; // array with instances of \Eventerra\Entities\EventerraOrderItem
    // $order->items[1]->barcode;


For more code examples you can see [Wiki / Code examples](https://github.com/underwear/php-eventerra-sdk/wiki) page

### Available methods:
- getTours(): EventerraTour[] 
- getTour(int $id): EventerraTour|null
- getConcertsForTour(int $tourId): EventerraConcert[] 
- getFreePlacesForConcert(int $concertId): EventerraPlace[]
- lockPlace(int $concertId, EventerraPlace $place): bool
- unlockPlace(int $concertId, EventerraPlace $place): bool
- newOrder(int $concertId, EventerraPlace[] $places): EventerraOrder 
- getOrder(int $orderId): EventerraOrder|null
- cancelOrder(int $orderId): bool
- getAllOrders(): EventerraOrder[]
## Documentation
- [Making new order](https://github.com/underwear/php-eventerra-sdk/wiki/making-new-order-for-places)
- [Implementing Logger](https://github.com/underwear/php-eventerra-sdk/wiki/adding-logger)
- [Other code examples](https://github.com/underwear/php-eventerra-sdk/wiki)

## Links
- [Official Eventerra.de API documentation](https://eventerra.de/api/help.php)

## License
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER  DEALINGS IN THE SOFTWARE.
