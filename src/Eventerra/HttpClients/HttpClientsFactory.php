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

use GuzzleHttp\Client;
use InvalidArgumentException;
use Exception;

/**
 * Class HttpClientsFactory
 *
 * @package Eventerra
 */
class HttpClientsFactory {
	private function __construct() {
		// a factory constructor should never be invoked
	}

	/**
	 * HTTP client generation.
	 *
	 * @param EventerraHttpClientInterface|Client|string|null $handler
	 *
	 * @throws Exception                If the cURL extension or the Guzzle client aren't available (if required).
	 * @throws InvalidArgumentException If the http client handler isn't "curl", "guzzle", or an instance of
	 *                                  Eventerra\HttpClients\EventerraHttpClientInterface.
	 *
	 * @return EventerraHttpClientInterface
	 */
	public static function createHttpClient($handler = null) {
		if (!$handler) {
			return self::detectDefaultClient();
		}
		if ($handler instanceof EventerraHttpClientInterface) {
			return $handler;
		}

		if ('curl' === $handler) {
			if (!extension_loaded('curl')) {
				throw new Exception('The cURL extension must be loaded in order to use the "curl" handler.');
			}
			return new EventerraCurlHttpClient();
		}
		if ('guzzle' === $handler && !class_exists('GuzzleHttp\Client')) {
			throw new Exception('The Guzzle HTTP client must be included in order to use the "guzzle" handler.');
		}
		if ($handler instanceof Client) {
			return new EventerraGuzzleHttpClient($handler);
		}
		if ('guzzle' === $handler) {
			return new EventerraGuzzleHttpClient();
		}
		throw new InvalidArgumentException('The http client handler must be set to "curl", "stream", "guzzle", be an instance of GuzzleHttp\Client or an instance of Eventerra\HttpClients\EventerraHttpClientInterface');
	}

	/**
	 * Detect default HTTP client.
	 *
	 * @return EventerraHttpClientInterface
	 * @throws Exception
	 */
	private static function detectDefaultClient() {
		if (class_exists('GuzzleHttp\Client')) {
			return new EventerraGuzzleHttpClient();
		}
		if (extension_loaded('curl')) {
			return new EventerraCurlHttpClient();
		}

		throw new \Exception("Eventerra SDK requires curl-php-extension or guzzle");
	}
}