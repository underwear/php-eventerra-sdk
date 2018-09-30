PHP Eventerra SDK
=======================

This is unofficial php SDK for working with eventerra.de API

- supports PSR-3 Logger (Monolog and etc.)
- supports different http clients (Guzzle, Curl and etc.)

## Prerequisites
- PHP 5.4 or later

## Installation
The PHP Eventerra SDK can be installed using Composer by running the following command:
  

    composer require underwear/php-eventerra-sdk
    
PHP Eventerra SDK can ask you to require some more packages (if you don't use them or analogues in your project)
    
    composer require php-http/message
    composer require php-http/guzzle6-adapter
    composer require guzzlehttp/guzzle
    
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

## Links
[Official Eventerra.de API documentation](https://eventerra.de/api/help.php)

## License
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER  DEALINGS IN THE SOFTWARE.
