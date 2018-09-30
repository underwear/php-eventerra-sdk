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
 * Class EventerraConcert
 *
 * @property int    id
 * @property int    status
 * @property int    dateUnix
 * @property string cityName
 * @property string hallName
 * @property string descriptionRu
 * @property string descriptionDe
 *
 * @package Eventerra
 */
class EventerraConcert extends EventerraBaseEntity {
	const STATUS_STOP_SELLING = 0;
	const STATUS_SELLING = 1;
	const STATUS_COMING_SOON = 2;

	/**
	 * @var array Allowed fields for the EventerraConcert
	 */
	protected $fields = [
		'id',
		'status',
		'dateUnix',
		'cityName',
		'hallName',
		'descriptionRu',
		'descriptionDe'
	];
}