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

use Eventerra\HttpClients\EventerraHttpClientInterface;
use Eventerra\Exceptions\EventerraSDKException;
use Eventerra\HttpClients\HttpClientsFactory;

class EventerraClient {

	/**
	 * @const string Eventerra API URL.
	 */
	const BASE_URL = 'https://www.eventerra.de/api/api_cc.php';

	/**
	 * @const int The timeout in seconds for a normal request.
	 */
	const DEFAULT_REQUEST_TIMEOUT = 60;

	protected $httpClientHandler;

	/**
	 * Instantiates a new EventerraClient object.
	 *
	 * @param EventerraHttpClientInterface|null $httpClientHandler
	 *
	 * @throws \Exception
	 */
	public function __construct(EventerraHttpClientInterface $httpClientHandler = null) {
		$this->httpClientHandler = $httpClientHandler ?: HttpClientsFactory::createHttpClient();
	}

	/**
	 * Sets the HTTP client handler.
	 *
	 * @param EventerraHttpClientInterface $httpClientHandler
	 */
	public function setHttpClientHandler(EventerraHttpClientInterface $httpClientHandler) {
		$this->httpClientHandler = $httpClientHandler;
	}

	/**
	 * Returns the HTTP client handler.
	 *
	 * @return EventerraHttpClientInterface
	 */
	public function getHttpClientHandler() {
		return $this->httpClientHandler;
	}

	/**
	 * Returns the base Eventerra API URL.
	 *
	 * @return string
	 */
	public function getBaseUrl() {
		return self::BASE_URL;
	}

	/**
	 * Prepares the request for sending to the client handler.
	 *
	 * @param EventerraRequest $request
	 *
	 * @return array
	 */
	public function prepareRequestMessage(EventerraRequest $request) {
		$url = $this->getBaseUrl();

		$request->setHeaders([
			'Content-Type' => 'application/x-www-form-urlencoded',
		]);

		return [
			$url,
			$request->getMethod(),
			$request->getHeaders(),
			$request->getUrlEncodedBody()->getBody()
		];
	}

	/**
	 * Makes the request to API and returns the result.
	 *
	 * @param EventerraRequest $request
	 *
	 * @return EventerraResponse
	 *
	 * @throws EventerraSDKException
	 */
	public function sendRequest(EventerraRequest $request) {

		list($url, $method, $headers, $body) = $this->prepareRequestMessage($request);


		$timeOut = static::DEFAULT_REQUEST_TIMEOUT;

		// Don't catch to allow it to bubble up.
		$rawResponse = $this->httpClientHandler->send($url, $method, $body, $headers, $timeOut);

		$returnResponse = new EventerraResponse(
			$request,
			$rawResponse->getBody(),
			$rawResponse->getHttpResponseCode(),
			$rawResponse->getHeaders()
		);

		if ($returnResponse->isError()) {
			throw $returnResponse->getThrownException();
		}

		return $returnResponse;
	}
}