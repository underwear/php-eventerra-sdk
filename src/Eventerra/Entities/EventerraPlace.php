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
 * Class EventerraPlace
 *
 * @property string $block
 * @property string $row
 * @property string $place
 * @property float  $price
 * @property int    $x
 * @property int    $y
 *
 * @package Eventerra
 */
class EventerraPlace extends EventerraBaseEntity {

	/**
	 * @var array Allowed fields for the EventerraPlace
	 */
	protected $fields = [
		'block',
		'row',
		'place',
		'price',
		'x',
		'y'
	];

	/**
	 * Sanitize value for X
	 *
	 * @param $value
	 *
	 * @return null
	 */
	protected function sanitizeX($value) {
		return ctype_digit($value) ? $value : null;
	}

	/**
	 * Sanitize value for Y
	 *
	 * @param $value
	 *
	 * @return null
	 */
	protected function sanitizeY($value) {
		return ctype_digit($value) ? $value : null;
	}
}