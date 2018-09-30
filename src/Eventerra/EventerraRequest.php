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
use Eventerra\Http\RequestBodyUrlEncoded;

/**
 * Class EventerraRequest
 *
 * @package Eventerra
 */
class EventerraRequest {
	/**
	 * @var EventerraApp The Eventerra app entity.
	 */
	protected $app;

	/**
	 * @var string The HTTP method for this request.
	 */
	protected $method;

	/**
	 * @var array The headers to send with this request.
	 */
	protected $headers = [];

	/**
	 * @var array The parameters to send with this request.
	 */
	protected $params = [];

	/**
	 * @var string Part of URL for request to API
	 */
	protected $action;

	/**
	 * Creates a new Request entity.
	 *
	 * @param EventerraApp|null $app
	 * @param string|null       $method
	 * @param string|null       $action
	 * @param array|null        $params
	 *
	 * @throws EventerraSDKException
	 */
	public function __construct(EventerraApp $app = null, $method = null, $action = null, array $params = []) {
		$this->setApp($app);
		$this->setMethod($method);
		$this->setAction($action);
		$this->setParams($params);
	}

	/**
	 * Return the EventerraApp entity used for this request.
	 *
	 * @return EventerraApp
	 */
	public function getApp() {
		return $this->app;
	}

	/**
	 * Set the EventerraApp entity used for this request.
	 *
	 * @param EventerraApp $app
	 */
	public function setApp($app) {
		$this->app = $app;
	}

	/**
	 * Set the HTTP method for this request.
	 *
	 * @param string
	 */
	public function setMethod($method) {
		$this->method = strtoupper($method);
	}

	/**
	 * Return the HTTP method for this request.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Validate that the HTTP method is set.
	 *
	 * @throws EventerraSDKException
	 */
	public function validateMethod() {
		if (!$this->method) {
			throw new EventerraSDKException('HTTP method not specified.');
		}
		if (!in_array($this->method, ['POST'])) {
			throw new EventerraSDKException('Invalid HTTP method specified.');
		}
	}

	/**
	 * Generate and return the headers for this request.
	 *
	 * @return array
	 */
	public function getHeaders() {
		$headers = static::getDefaultHeaders();

		return array_merge($this->headers, $headers);
	}

	/**
	 * Set the headers for this request.
	 *
	 * @param array $headers
	 */
	public function setHeaders(array $headers) {
		$this->headers = array_merge($this->headers, $headers);
	}

	/**
	 * Set the params for this request.
	 *
	 * @param array $params
	 *
	 * @throws EventerraSDKException
	 */
	public function setParams(array $params = []) {
		if (isset($params['hash'])) {
			throw new EventerraSDKException('Parameter "hash" is using for signing the request and will be added automatically');
		}

		if (isset($params['action'])) {
			throw new EventerraSDKException('Parameter "action" must be defined using method setAction()');
		}

		$this->params = array_merge($this->params, $params);
	}

	/**
	 * Generate and return the params for this request.
	 *
	 * @return array
	 */
	public function getParams() {
		$params = $this->params;
		$params['action'] = $this->getAction();
		$params['aid'] = $this->getApp()->getId();
		ksort($params);

		$params['hash'] = $this->getHash($params); // signing the request

		return $params;
	}

	/**
	 * Only return params on POST requests.
	 *
	 * @return array
	 */
	public function getPostParams() {
		if ($this->getMethod() === 'POST') {
			return $this->getParams();
		}
		return [];
	}

	public function getBody() {
		return $this->getUrlEncodedBody();
	}

	/**
	 * Returns the body of the request as URL-encoded.
	 *
	 * @return string
	 */
	public function getUrlEncodedBody() {
		$params = $this->getPostParams();
		return http_build_query($params, null, '&');
	}

	/**
	 * Return secure-sign for the request
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function getHash($params = []) {
		$string = '';
		foreach ($params as $param_value) {
			$string .= $param_value;
		}

		$hash = md5($string . $this->getApp()->getSecret());
		return $hash;
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * Return the default headers that every request should use.
	 *
	 * @return array
	 */
	public static function getDefaultHeaders() {
		return [
			'Content-Type' => 'application/x-www-form-urlencoded',
			'User-Agent' => 'php-underwear-eventerra-sdk',
			'Accept-Encoding' => '*',
		];
	}
}