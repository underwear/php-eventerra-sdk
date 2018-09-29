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

/**
 * Class EventerraApp
 *
 * @package Eventerra
 */
class EventerraApp implements \Serializable {

	/**
	 * @var string The app ID.
	 */
	protected $id;

	/**
	 * @var string The app secret.
	 */
	protected $secret;

	/**
	 * @param int $id
	 * @param string $secret
	 */
	public function __construct($id, $secret) {
		$this->id = (int) $id;
		$this->secret = $secret;
	}

	/**
	 * Returns the app ID.
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns the app secret.
	 *
	 * @return string
	 */
	public function getSecret() {
		return $this->secret;
	}

	/**
	 * Serializes the EventerraApp entity as a string.
	 *
	 * @return string
	 */
	public function serialize() {
		return implode('|', [$this->id, $this->secret]);
	}

	/**
	 * Unserializes a string as a EventerraApp entity.
	 *
	 * @param string $serialized
	 */
	public function unserialize($serialized) {
		list($id, $secret) = explode('|', $serialized);
		$this->__construct($id, $secret);
	}
}