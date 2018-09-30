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
    
You can use any PSR-18 compatible http-client, but if you don't have one, let's require guzzle
    
    composer require guzzlehttp/guzzle "^6.0"
    
That is it.

## Simple usage:
  

    $eventerra = new \Eventerra\Eventerra([  
	    'aid' => '123',
	    'secret' => 'passw0rd'
    ]);  
    
    $tours = $eventerra->getTours();  
    
    print_r($tours);

### Available methods:
- getTours(): EventerraTour[] 
- getTour(int $id): EventerraTour|null
- getConcertsForTour(int $tourId): EventerraConcert[] 
- getFreePlacesForConcert(int $concertId): EventerraPlace[]
- newOrder(int $concertId, EventerraPlace[] $places): EventerraOrder
- cancelOrder(int $orderId): bool
- getOrder(int $orderId): EventerraOrder|null
- getAllOrders(): EventerraOrder[]

## Links
[Official Eventerra.de API documentation](https://eventerra.de/api/help.php)

## License
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER  DEALINGS IN THE SOFTWARE.
