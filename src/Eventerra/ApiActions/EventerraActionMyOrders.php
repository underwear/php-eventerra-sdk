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

/**
 * Class EventerraActionMyOrders
 *
 * @package Eventerra
 */
class EventerraActionMyOrders extends EventerraActionBaseClass {

	/**
	 * Return info about orders
	 *
	 * @param int|null $orderId Filter by order_id
	 *
	 * @return EventerraOrder[]
	 * @throws \Eventerra\Exceptions\EventerraSDKException
	 */
	public function request($orderId = null) {
		$params = [];

		if (!is_null($orderId)) {
			$params['order_id'] = $orderId;
		}

		$response = $this->eventerra
			->post('my_orders', $params)
			->getDecodedBody();

		$orders = [];
		foreach ($response as $item) {
			$ticketsRaw = unserialize($item['order']);
			$orderItems = [];
			foreach ($ticketsRaw as $ticketRaw) {
				$orderItems[] = new EventerraOrderItem([
					'block' => $ticketRaw['block'],
					'row' => $ticketRaw['row'],
					'place' => $ticketRaw['place'],
					'price' => $ticketRaw['price'],
					'barcode' => $ticketRaw['barcode'],
				]);
			}

			$orders[] = new EventerraOrder([
				'id' => $item['order_id'],
				'hash' => $item['hash'],
				'addTime' => $item['add_time'],
				'status' => $item['status'],
				'concertId' => $item['id_concert'],
				'ticketsAmount' => $item['tickets'],
				'totalSum' => $item['total'],
				'items' => $orderItems,
				'linkPdfRechnung' => $item['link_pdf_rechnung'],
				'linkPdfReserv' => $item['link_pdf_reservierungsbestätigung'],
				'linkPdfTickets' => $item['link_pdf_tickets'],
			]);
		}

		return $orders;
	}
}