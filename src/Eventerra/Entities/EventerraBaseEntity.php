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

namespace Eventerra\Entities;

/**
 * Class EventerraBaseEntity
 *
 * @package Eventerra
 */
abstract class EventerraBaseEntity {
	/**
	 * @var array Allowed fields for the Entity
	 */
	protected $fields = [];

	/**
	 * @var array Values of fields
	 */
	protected $values = [];

	/**
	 * EventerraBaseEntity constructor.
	 *
	 * @param array $values Array with values for Entity fields
	 */
	public function __construct($values = []) {
		foreach($values as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Magic method.
	 * Get field with name $key
	 *
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function __get($key) {
		if (!in_array($key, $this->fields)) {
			throw new \OutOfBoundsException("Entity " . get_class($this) . " doesn't this property '{$key}'");
		}

		if (!key_exists($key, $this->values)) {
			return null;
		}

		return $this->values[$key];
	}

	/**
	 * Magic method.
	 * Set field with name $key
	 *
	 * @param string key
	 * @param mixed $value
	 */
	public function __set($key, $value) {
		if (!in_array($key, $this->fields)) {
			throw new \OutOfBoundsException("Entity " . get_class($this) . " doesn't have property '{$key}'");
		}

		$sanitizeFunc = 'sanitize' . ucfirst($key);
		if(method_exists($this, $sanitizeFunc)) {
			$value = $this->$sanitizeFunc($value);
		}

		$this->values[$key] = $value;
	}
}