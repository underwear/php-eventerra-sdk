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

namespace Eventerra\Http;

/**
 * Class RawResponse
 *
 * @package Eventerra;
 */
class RawResponse {
	/**
	 * @var array The response headers in the form of an associative array.
	 */
	protected $headers;
	/**
	 * @var string The raw response body.
	 */
	protected $body;
	/**
	 * @var int The HTTP status response code.
	 */
	protected $httpResponseCode;

	/**
	 * Creates a new RawResponse entity.
	 *
	 * @param string|array $headers        The headers as a raw string or array.
	 * @param string       $body           The raw response body.
	 * @param int          $httpStatusCode The HTTP response code (if sending headers as parsed array).
	 */
	public function __construct($headers, $body, $httpStatusCode = null) {
		if (is_numeric($httpStatusCode)) {
			$this->httpResponseCode = (int) $httpStatusCode;
		}
		if (is_array($headers)) {
			$this->headers = $headers;
		} else {
			$this->setHeadersFromString($headers);
		}
		$this->body = $body;
	}

	/**
	 * Return the response headers.
	 *
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * Return the body of the response.
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Return the HTTP response code.
	 *
	 * @return int
	 */
	public function getHttpResponseCode() {
		return $this->httpResponseCode;
	}

	/**
	 * Sets the HTTP response code from a raw header.
	 *
	 * @param string $rawResponseHeader
	 */
	public function setHttpResponseCodeFromHeader($rawResponseHeader) {
		preg_match('|HTTP/\d\.\d\s+(\d+)\s+.*|', $rawResponseHeader, $match);
		$this->httpResponseCode = (int) $match[1];
	}

	/**
	 * Parse the raw headers and set as an array.
	 *
	 * @param string $rawHeaders The raw headers from the response.
	 */
	protected function setHeadersFromString($rawHeaders) {
		// Normalize line breaks
		$rawHeaders = str_replace("\r\n", "\n", $rawHeaders);
		// There will be multiple headers if a 301 was followed
		// or a proxy was followed, etc
		$headerCollection = explode("\n\n", trim($rawHeaders));
		// We just want the last response (at the end)
		$rawHeader = array_pop($headerCollection);
		$headerComponents = explode("\n", $rawHeader);
		foreach ($headerComponents as $line) {
			if (strpos($line, ': ') === false) {
				$this->setHttpResponseCodeFromHeader($line);
			} else {
				list($key, $value) = explode(': ', $line, 2);
				$this->headers[$key] = $value;
			}
		}
	}
}