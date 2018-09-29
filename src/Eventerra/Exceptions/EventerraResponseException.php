<?php
/**
 * This is unofficial SDK for working with eventerra.de API
 *
 * @link https://www.eventerra.de/api/help.php
 *
 * Author: Igor Filippov <thisfil@ya.ru>
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace Eventerra\Exceptions;

use Eventerra\EventerraResponse;

/**
 * Class EventerraResponseException
 *
 * @package Eventerra
 */
class EventerraResponseException extends EventerraSDKException {
	 /**
     * @var EventerraResponse The response that threw the exception.
     */
    protected $response;
    /**
     * @var array Decoded response.
     */
    protected $responseData;

    public static function create(EventerraResponse $message)
    {
        $data = $message->getDecodedBody();

        $code = (isset($data['error']) and is_numeric($data['error'])) ? $data['error'] : null;
        $message = isset($data['error_text']) ? $data['error_text'] : 'Unknown error from Eventerra.';


        if ($code == 1) {
            return new static($message, 0, new EventerraAuthorizationException($message, $code));
        }

        // No available tours or requested tour unavailable
	    if ($code == 2) {
        	$message = "No available tours or requested tour unavailable";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

	    // Tour is unavailable or doesn't exist
        if ($code == 3) {
        	$message = "Tour is unavailable or doesn't exist";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

	    // Concert is unavailable or doesn't exist
        if ($code == 4) {
        	$message = "Concert is unavailable or doesn't exist";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

        // Order doesn't exist
        if ($code == 5 or $code == 10) {
        	$message = "Order doesn't exist";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

	    // Place has been reserved already
	    if ($code == 12) {
        	$message = "Place has been reserved already";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

	    // Place hasn't been reserved before
	    if ($code == 14) {
        	$message = "Place hasn't been reserved before";
            return new static($message, 0, new EventerraClientException($message, $code));
	    }

        // All others
        return new static($message, 0, new EventerraOtherException($message, $code));
    }
}