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
use Eventerra\Entities\EventerraConcert;

/**
 * Class EventerraActionGetConcertsForTour
 *
 * @package Eventerra
 */
class EventerraActionGetConcertsForTour extends EventerraActionBaseClass {

	/**
	 * Returns concerts for tour
	 *
	 * @param int      $tourId
	 * @param int|null $concertId
	 *
	 * @return EventerraConcert[]
	 *
	 * @throws \Eventerra\Exceptions\EventerraSDKException
	 */
	public function request($tourId, $concertId = null) {
		$params = [
			'id_tour' => $tourId
		];

		if (!is_null($concertId)) {
			$params['id_concert'] = $concertId;
		}

		$response = $this->eventerra
			->post('get_concerts_for_tour', $params)
			->getDecodedBody();

		$concerts = [];
		foreach ($response as $item) {
			$concerts[] = new EventerraConcert([
				'id' => $item['id_concert'],
				'status' => $item['status'],
				'dateUnix' => $item['date_unix'],
				'cityName' => $item['city_name'],
				'hallName' => $item['hall_name'],
				'descriptionRu' => $item['description'],
				'descriptionDe' => $item['description_de']
			]);
		}

		return $concerts;
	}
}