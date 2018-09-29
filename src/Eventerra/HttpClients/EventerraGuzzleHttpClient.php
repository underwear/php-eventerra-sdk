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

namespace Eventerra\HttpClients;

use Eventerra\Http\RawResponse;
use Eventerra\Exceptions\EventerraSDKException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class EventerraGuzzleHttpClient implements EventerraHttpClientInterface {
	/**
	 * @var \GuzzleHttp\Client The Guzzle client.
	 */
	protected $guzzleClient;

	/**
	 * @param \GuzzleHttp\Client|null The Guzzle client.
	 */
	public function __construct(Client $guzzleClient = null) {
		$this->guzzleClient = $guzzleClient ?: new Client();
	}

	/**
	 * @inheritdoc
	 */
	public function send($url, $method, $body, array $headers, $timeOut) {
		$options = [
			'headers' => $headers,
			'body' => $body,
			'timeout' => $timeOut,
			'connect_timeout' => 10,
		];
		$request = $this->guzzleClient->request($method, $url, $options);
		try {
			$rawResponse = $this->guzzleClient->send($request);
		} catch (RequestException $e) {
			throw new EventerraSDKException($e->getMessage(), $e->getCode());
		}
		$rawHeaders = $this->getHeadersAsString($rawResponse);
		$rawBody = $rawResponse->getBody();
		$httpStatusCode = $rawResponse->getStatusCode();
		return new RawResponse($rawHeaders, $rawBody, $httpStatusCode);
	}

	/**
	 * Returns the Guzzle array of headers as a string.
	 *
	 * @param ResponseInterface $response The Guzzle response.
	 *
	 * @return string
	 */
	public function getHeadersAsString(ResponseInterface $response) {
		$headers = $response->getHeaders();
		$rawHeaders = [];
		foreach ($headers as $name => $values) {
			$rawHeaders[] = $name . ": " . implode(", ", $values);
		}
		return implode("\r\n", $rawHeaders);
	}
}