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

namespace Eventerra\ApiActions;

use Eventerra\Entities\EventerraOrder;
use Eventerra\Entities\EventerraOrderItem;
use Eventerra\Entities\EventerraPlace;
use Eventerra\Exceptions\EventerraSDKException;

/**
 * Class EventerraActionOrder
 *
 * @package Eventerra
 */
class EventerraActionOrder extends EventerraActionBaseClass {

	/**
	 * Create new order for places
	 *
	 * @param int   $concertId
	 * @param array $places
	 *
	 * @return bool|EventerraOrder
	 * @throws EventerraSDKException
	 * @throws \Exception
	 * @throws \Http\Client\Exception
	 */
	public function request($concertId, Array $places) {
		$places = array_values($places); // Приводим к индексируемому массиву

		$places_as_array = [];
		foreach ($places as $place) {
			if (!($place instanceof EventerraPlace)) {
				throw new EventerraSDKException("Passed array member is not instance of " . EventerraPlace::class);
			}

			$places_as_array[] = [
				'block' => $place->block,
				'row' => $place->row,
				'place' => $place->place,
				'price' => $place->price,
			];
		}

		$result = $this->eventerra
			->post('order', [
				'id_concert' => $concertId,
				'checked_places' => base64_encode(serialize($places_as_array))
			])
			->getDecodedBody();

		if (isset($result['error'])) {
			return false;
		}

		$result = current($result);

		// Разбираем билеты
		$orderItems = [];

		$tickets = unserialize($result['order']);
		foreach ($tickets as $key => $item) {
			$orderItems[] = new EventerraOrderItem([
				'block' => $item['block'],
				'row' => $item['row'],
				'place' => $item['place'],
				'barcode' => $item['barcode']
			]);
		}

		$order = new EventerraOrder([
			'id' => $result['order_id'],
			'status' => trim($result['status']),
			'hash' => trim($result['hash']),
			'items' => $orderItems
		]);

		return $order;
	}
}