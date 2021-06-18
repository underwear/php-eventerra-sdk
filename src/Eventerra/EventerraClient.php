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
use Http\Client\HttpClient as HttpClientInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class EventerraClient
 *
 * @package Eventerra
 */
class EventerraClient {

	/**
	 * @const string Eventerra API URL.
	 */
	const BASE_URL = 'https://www.eventerra-online.de/api/api_cc.php';

	/**
	 * @const int The timeout in seconds for a normal request.
	 */
	const DEFAULT_REQUEST_TIMEOUT = 60;

	/**
	 * @var HttpClientInterface Http client handler
	 */
	protected $httpClientHandler;

	/**
	 * @var LoggerInterface PSR-3 Logger handler
	 */
	protected $loggerHandler;

	/**
	 * Instantiates a new EventerraClient object.
	 *
	 * @param LoggerInterface|null     $loggerHandler
	 * @param HttpClientInterface|null $httpClientHandler
	 *
	 * @throws \Exception
	 */
	public function __construct(LoggerInterface $loggerHandler = null, HttpClientInterface $httpClientHandler = null) {
		$this->loggerHandler = $loggerHandler ?: new NullLogger();
		$this->httpClientHandler = $httpClientHandler ?: HttpClientDiscovery::find();
	}

	/**
	 * Sets the HTTP client handler.
	 *
	 * @param HttpClientInterface $httpClientHandler
	 */
	public function setHttpClientHandler(HttpClientInterface $httpClientHandler) {
		$this->httpClientHandler = $httpClientHandler;
	}

	/**
	 * Returns the HTTP client handler.
	 *
	 * @return HttpClientInterface
	 */
	public function getHttpClientHandler() {
		return $this->httpClientHandler;
	}

	/**
	 * Sets the logger handler.
	 *
	 * @param LoggerInterface $loggerHandler
	 */
	public function setLoggerHandler($loggerHandler) {
		$this->loggerHandler = $loggerHandler;
	}

	/**
	 * Returns the logger handler.
	 *
	 * @return LoggerInterface
	 */
	public function getLoggerHandler() {
		return $this->loggerHandler;
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
	 * Makes the request to API and returns the result.
	 *
	 * @param EventerraRequest $eventerraRequest
	 *
	 * @return EventerraResponse
	 *
	 * @throws EventerraSDKException|\Http\Client\Exception|\Exception
	 */
	public function sendRequest(EventerraRequest $eventerraRequest) {
		//todo needs refactoring

		$messageFactory = MessageFactoryDiscovery::find();

		$psr7Request = $messageFactory->createRequest(
			$eventerraRequest->getMethod(),
			$this->getBaseUrl(),
			$eventerraRequest->getHeaders(),
			$eventerraRequest->getBody()
		);

		$psr7Response = $this->httpClientHandler->sendRequest($psr7Request);

		$eventerraResponse = new EventerraResponse(
			$eventerraRequest,
			$psr7Response->getBody(),
			$psr7Response->getStatusCode(),
			$psr7Response->getHeaders()
		);

		$this->loggerHandler->debug("HTTP request to Eventerra API", [
			'request' => [
				'method' => $psr7Request->getMethod(),
				'uri' => $psr7Request->getUri(),
				'headers' => $psr7Request->getHeaders(),
				'body' => $psr7Request->getBody()
			],
			'response' => [
				'status_code' => $psr7Response->getStatusCode(),
				'headers' => $psr7Response->getHeaders(),
				'body' => $psr7Response->getBody()
			],
		]);

		if ($eventerraResponse->isError()) {
			throw $eventerraResponse->getThrownException();
		}

		return $eventerraResponse;
	}

}
