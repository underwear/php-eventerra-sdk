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
use Eventerra\Helpers\Arr;

class EventerraActionGetFreePlaces extends EventerraActionBaseClass {
	public function request($concertId) {
		$params = [
			'type' => 0,
			'id_concert' => $concertId
		];

		$response = $this->eventerra
			->post('get_free_places', $params)
			->getDecodedBody();

		$places = [];
		foreach ($response['places'] as $item) {
			$places[] = new EventerraPlace([
				'block' => Arr::get($item, 'block'),
				'row' => Arr::get($item, 'row'),
				'place' => Arr::get($item, 'place'),
				'price' => Arr::get($item, 'price'),
				'x' => Arr::get($item, 'c_x'),
				'y' => Arr::get($item, 'c_y')
			]);
		}

		return $places;
	}
}