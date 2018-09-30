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


use Eventerra\Entities\EventerraTour;

class EventerraActionGetTours extends EventerraActionBaseClass {

	/**
	 * Return array of EventerraTours
	 *
	 * @return EventerraTour[]
	 * @throws \Eventerra\Exceptions\EventerraSDKException
	 */
	public function request($tourId = null) {
		$params = [];

		$response = $this->eventerra
			->post('get_tours', $params)
			->getDecodedBody();

		$tours = [];
		foreach ($response as $item) {
			$tours[] = new EventerraTour([
				'id' => $item['id_tour'],
				'nameRu' => $item['name'],
				'nameDe' => $item['name_de'],
				'photo' => $item['photo_1'],
				'shortTextRu' => $item['short_text'],
				'shortTextDe' => $item['short_text_de'],
				'fullTextRu' => $item['full_text'],
				'fullTextDe' => $item['full_text_de'],
				'dateStartUnix' => $item['date_start_unix'],
				'dateEndUnix' => $item['date_end_unix']
			]);
		}

		return $tours;
	}

}