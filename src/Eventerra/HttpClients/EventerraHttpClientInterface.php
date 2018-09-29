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

/**
 * Interface EventerraHttpClientInterface
 *
 * @package Eventerra
 */
interface EventerraHttpClientInterface {
	/**
	 * Sends a request to the server and returns the raw response.
	 *
	 * @param string $url     The endpoint to send the request to.
	 * @param string $method  The request method.
	 * @param string $body    The body of the request.
	 * @param array  $headers The request headers.
	 * @param int    $timeOut The timeout in seconds for the request.
	 *
	 * @return \Eventerra\Http\RawResponse Raw response from the server.
	 *
	 * @throws \Eventerra\Exceptions\EventerraSDKException
	 */
	public function send($url, $method, $body, array $headers, $timeOut);
}