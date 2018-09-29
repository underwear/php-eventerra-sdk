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

namespace Eventerra;

use Eventerra\Exceptions\EventerraSDKException;
use Eventerra\Exceptions\EventerraResponseException;

/**
 * Class EventerraResponse
 *
 * @package Eventerra
 */
class EventerraResponse {
	/**
	 * @var int The HTTP status code response from Graph.
	 */
	protected $httpStatusCode;
	/**
	 * @var array The headers returned from Graph.
	 */
	protected $headers;
	/**
	 * @var string The raw body of the response from Graph.
	 */
	protected $body;
	/**
	 * @var array The decoded body of the Graph response.
	 */
	protected $decodedBody = [];
	/**
	 * @var EventerraRequest The original request that returned this response.
	 */
	protected $request;
	/**
	 * @var EventerraSDKException The exception thrown by this request.
	 */
	protected $thrownException;

	/**
	 * Creates a new Response entity.
	 *
	 * @param EventerraRequest $request
	 * @param string|null      $body
	 * @param int|null         $httpStatusCode
	 * @param array|null       $headers
	 */
	public function __construct(EventerraRequest $request, $body = null, $httpStatusCode = null, array $headers = []) {
		$this->request = $request;
		$this->body = $body;
		$this->httpStatusCode = $httpStatusCode;
		$this->headers = $headers;
		$this->decodeBody();
	}

	/**
	 * Return the original request that returned this response.
	 *
	 * @return EventerraRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Return the EventerraApp entity used for this response.
	 *
	 * @return EventerraApp
	 */
	public function getApp() {
		return $this->request->getApp();
	}

	/**
	 * Return the HTTP status code for this response.
	 *
	 * @return int
	 */
	public function getHttpStatusCode() {
		return $this->httpStatusCode;
	}

	/**
	 * Return the HTTP headers for this response.
	 *
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * Return the raw body response.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Return the decoded body response.
	 *
	 * @return array
	 */
	public function getDecodedBody() {
		return $this->decodedBody;
	}

	/**
	 * Returns true if Graph returned an error message.
	 *
	 * @return boolean
	 */
	public function isError() {
		return isset($this->decodedBody['error']);
	}

	/**
	 * Throws the exception.
	 *
	 * @throws EventerraSDKException
	 */
	public function throwException() {
		throw $this->thrownException;
	}

	/**
	 * Instantiates an exception to be thrown later.
	 */
	public function makeException() {
		$this->thrownException = EventerraResponseException::create($this);
	}

	/**
	 * Returns the exception that was thrown for this request.
	 *
	 * @return EventerraResponseException|EventerraSDKException|null
	 */
	public function getThrownException() {
		return $this->thrownException;
	}

	/**
	 * Convert the raw response into an array if possible.
	 *
	 */
	public function decodeBody() {
		$this->decodedBody = json_decode($this->body, true);

		if ($this->isError()) {
			$this->makeException();
		}
	}

}