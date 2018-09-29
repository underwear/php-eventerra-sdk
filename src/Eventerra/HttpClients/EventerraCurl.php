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
 * Class EventerraCurl
 *
 * @package Eventerra
 */
class EventerraCurl {
	/**
	 * @var resource Curl resource instance
	 */
	protected $curl;

	/**
	 * Make a new curl reference instance
	 */
	public function init() {
		$this->curl = curl_init();
	}

	/**
	 * Set a curl option
	 *
	 * @param $key
	 * @param $value
	 */
	public function setopt($key, $value) {
		curl_setopt($this->curl, $key, $value);
	}

	/**
	 * Set an array of options to a curl resource
	 *
	 * @param array $options
	 */
	public function setoptArray(array $options) {
		curl_setopt_array($this->curl, $options);
	}

	/**
	 * Send a curl request
	 *
	 * @return mixed
	 */
	public function exec() {
		return curl_exec($this->curl);
	}

	/**
	 * Return the curl error number
	 *
	 * @return int
	 */
	public function errno() {
		return curl_errno($this->curl);
	}

	/**
	 * Return the curl error message
	 *
	 * @return string
	 */
	public function error() {
		return curl_error($this->curl);
	}

	/**
	 * Get info from a curl reference
	 *
	 * @param $type
	 *
	 * @return mixed
	 */
	public function getinfo($type) {
		return curl_getinfo($this->curl, $type);
	}

	/**
	 * Get the currently installed curl version
	 *
	 * @return array
	 */
	public function version() {
		return curl_version();
	}

	/**
	 * Close the resource connection to curl
	 */
	public function close() {
		curl_close($this->curl);
	}
}