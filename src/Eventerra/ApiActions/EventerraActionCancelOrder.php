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
 * Class EventerraActionCancelOrder
 *
 * @package Eventerra
 */
class EventerraActionCancelOrder extends EventerraActionBaseClass {

	/**
	 * Cancel order by id
	 *
	 * @param int id_order
	 *
	 * @return bool
	 * @throws EventerraSDKException
	 */
	public function request($orderId) {
		$result = $this->eventerra
			->post('cancel_order', [
				'order_id' => $orderId
			])
			->getDecodedBody();

		if (isset($result->error)) {
			return false;
		}

		return true;
	}
}