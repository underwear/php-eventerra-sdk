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

use Eventerra\Entities\EventerraPlace;

/**
 * Class EventerraActionLockPlace
 *
 * @package Eventerra
 */
class EventerraActionLockPlace extends EventerraActionBaseClass {

	/**
	 * Lock place for 30 min
	 *
	 * @param int            $concertId
	 * @param EventerraPlace $place
	 *
	 * @return bool
	 * @throws \Eventerra\Exceptions\EventerraSDKException
	 * @throws \Exception
	 * @throws \Http\Client\Exception
	 */
	public function request($concertId, EventerraPlace $place) {
		$place_info = [
			'block' => $place->block,
			'row' => $place->row,
			'place' => $place->place,
			'price' => $place->price
		];

		$params = [
			'id_concert' => $concertId,
			'checked_place' => base64_encode(serialize([$place_info]))
		];

		$response = $this->eventerra
			->post('lock_place', $params)
			->getDecodedBody();

		if (isset($response->error)) {
			return false;
		}

		return true;
	}

}