<?php
/**=
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

use Eventerra\ApiActions\EventerraActionCancelOrder;
use Eventerra\ApiActions\EventerraActionGetConcertsForTour;
use Eventerra\ApiActions\EventerraActionGetFreePlaces;
use Eventerra\ApiActions\EventerraActionGetTours;
use Eventerra\ApiActions\EventerraActionMyOrders;
use Eventerra\ApiActions\EventerraActionOrder;
use Eventerra\Entities\EventerraConcert;
use Eventerra\Entities\EventerraOrder;
use Eventerra\Entities\EventerraPlace;
use Eventerra\Exceptions\EventerraSDKException;
use Psr\Log\LoggerInterface;

/**
 * Class Eventerra
 *
 * Point of entrance for PHP Eventerra SDK
 *
 * You should make an instance of this class, passing 'aid' and 'secret' in the array
 * as first argument in the constructor
 *
 * @package Eventerra
 */
class Eventerra {
	/**
	 * @const string Version number of the Eventerra PHP SDK.
	 */
	const VERSION = '1.0.0';

	/**
	 * @var EventerraApp The EventerraApp entity.
	 */
	protected $app;

	/**
	 * @var EventerraClient The Eventerra client service.
	 */
	protected $client;

	/**
	 * @var EventerraResponse|null Stores the last request made to API Eventerra.
	 */
	protected $lastResponse;

	/**
	 * Eventerra constructor.
	 *
	 * @param                      $config array
	 *                                     - int aid: Application ID
	 *                                     - string secret: Secret-word for Application
	 * @param LoggerInterface|null $logger
	 *
	 * @throws \Exception
	 */
	public function __construct(array $config = [], LoggerInterface $logger = null) {
		if (!$config['aid']) {
			throw new EventerraSDKException('Required "aid" key not supplied in config');
		}
		if (!$config['secret']) {
			throw new EventerraSDKException('Required "secret" key not supplied in config');
		}

		$this->app = new EventerraApp($config['aid'], $config['secret']);

		$this->client = new EventerraClient($logger);
	}

	/**
	 * Instantiates a new EventerraRequest entity.
	 *
	 * @param string $method
	 * @param string $action
	 * @param array  $params
	 *
	 * @return EventerraRequest
	 *
	 * @throws EventerraSDKException
	 */
	public function request($method, $action, array $params = []) {

		return new EventerraRequest(
			$this->app,
			$method,
			$action,
			$params
		);
	}

	/**
	 * Sends a request to Eventerra API and returns the result.
	 *
	 * @param string $method
	 * @param string $action
	 * @param array  $params
	 *
	 * @return EventerraResponse
	 *
	 * @throws EventerraSDKException
	 * @throws \Exception
	 * @throws \Http\Client\Exception
	 */
	public function sendRequest($method, $action, array $params = []) {
		$request = $this->request($method, $action, $params);
		$response = $this->client->sendRequest($request);
		$this->lastResponse = $response;
		return $response;
	}

	/**
	 * Return last response from Eventerra API
	 *
	 * @return EventerraResponse|null
	 */
	public function getLastResponse() {
		return $this->lastResponse;
	}

	/**
	 * Sends a POST request to Eventerra API and returns the result.
	 *
	 * @param string $action
	 * @param array  $params
	 *
	 * @return EventerraResponse
	 * @throws EventerraSDKException
	 * @throws \Exception
	 * @throws \Http\Client\Exception
	 */
	public function post($action, array $params = []) {
		return $this->sendRequest(
			'POST',
			$action,
			$params
		);
	}

	////////////////////////////////////////////////////////
	///////////////////// API ACTIONS //////////////////////
	////////////////////////////////////////////////////////

	/**
	 * Return available tours
	 *
	 * @return Entities\EventerraTour[]
	 * @throws EventerraSDKException
	 */
	public function getTours() {
		$action = new EventerraActionGetTours($this);
		return $action->request();
	}

	/**
	 * Returns tour by Id
	 *
	 * @param int $id Tour ID
	 *
	 * @return Entities\EventerraTour|null
	 * @throws EventerraSDKException
	 */
	public function getTour($id) {
		$action = new EventerraActionGetTours($this);
		$array = $action->request($id);
		return array_shift($array);
	}

	/**
	 * Returns concerts for tour
	 *
	 * @param int $tourId
	 *
	 * @return EventerraConcert[]
	 * @throws EventerraSDKException
	 */
	public function getConcertsForTour($tourId) {
		$action = new EventerraActionGetConcertsForTour($this);
		return $action->request($tourId);
	}

	/**
	 * Returns available places for selling for concert
	 *
	 * @param int $concertId Concert ID
	 *
	 * @return EventerraPlace[]
	 * @throws EventerraSDKException
	 */
	public function getFreePlacesForConcert($concertId) {
		$action = new EventerraActionGetFreePlaces($this);
		return $action->request($concertId);
	}

	/**
	 * Make a new order for places
	 *
	 * @param int              $concertId
	 * @param EventerraPlace[] $places
	 *
	 * @return EventerraOrder
	 * @throws EventerraSDKException
	 */
	public function newOrder($concertId, $places) {
		$action = new EventerraActionOrder($this);
		return $action->request($concertId, $places);
	}

	/**
	 * Cancel order by id
	 *
	 * @param int $orderId
	 *
	 * @return bool
	 * @throws EventerraSDKException
	 */
	public function cancelOrder($orderId) {
		$action = new EventerraActionCancelOrder($this);
		return $action->request($orderId);
	}

	/**
	 * Return info about order
	 *
	 * @param $orderId
	 *
	 * @return EventerraOrder|null
	 * @throws EventerraSDKException
	 */
	public function getOrder($orderId) {
		$action = new EventerraActionMyOrders($this);
		try {
			$array = $action->request($orderId);
		} catch (\Eventerra\Exceptions\EventerraSDKException $exception) {
			if ($exception->getCode() == 5) {
				return null;
			} else {
				throw $exception;
			}
		}
		return array_shift($array);
	}


	/**
	 * Return all orders
	 *
	 * @return EventerraOrder[]
	 * @throws EventerraSDKException
	 */
	public function getAllOrders() {
		$action = new EventerraActionMyOrders($this);
		return $action->request();
	}
}