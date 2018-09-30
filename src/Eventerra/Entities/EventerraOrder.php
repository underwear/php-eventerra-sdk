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
 * Class EventerraOrder
 *
 * @property int    id
 * @property string hash
 * @property int    addTime
 * @property int    status
 * @property int    concertId
 * @property int    ticketsAmount
 * @property float  totalSum
 * @property \Eventerra\Entities\EventerraOrderItem[] items
 * @property string linkPdfRechnung
 * @property string linkPdfReserv
 * @property string linkPdfTickets
 *
 * @package Eventerra
 */
class EventerraOrder extends EventerraBaseEntity {

	// status - статус заказа в системе: 0 обрабатывается, 3 оплачен, 4 отменен, 5 билеты отправлены
	const STATUS_IN_PROCESS = 0;
	const STATUS_PAYED = 3;
	const STATUS_CANCELED = 4;
	const STATUS_TICKETS_SENT = 5;

	protected $fields = [
		'id',
		'hash',
		'addTime',
		'status',
		'concertId',
		'ticketsAmount',
		'totalSum',
		'items',
		'linkPdfRechnung',
		'linkPdfReserv',
		'linkPdfTickets'
	];
}