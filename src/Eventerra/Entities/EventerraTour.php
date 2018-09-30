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
 * Class EventerraTour
 *
 * @property int    $id
 * @property string $nameRu        Tour's name in russian
 * @property string $nameDe        Tour's name in german
 * @property string $shortTextRu   Tour's short description in russian
 * @property string $shortTextDe   Tour's short description in german
 * @property string $fullTextRu    Tour's full description in russian
 * @property string $fullTextDe    Tour's full description in german
 * @property int    $dateStartUnix Tour's start date as unix timestamp
 * @property int    $dateEndUnix   Tour's end date as unix timestamp
 * @property string $photo         Tour's photo, http url
 *
 * @package Eventerra
 */
class EventerraTour extends EventerraBaseEntity {

	/**
	 * @var array Allowed fields for the EventerraTour
	 */
	protected $fields = [
		'id',
		'nameRu',
		'nameDe',
		'shortTextRu',
		'shortTextDe',
		'fullTextRu',
		'fullTextDe',
		'dateStartUnix',
		'dateEndUnix',
		'photo'
	];
}

/* @todo Остались еще поля, но их eventerra заполняет корректно крайне редко (почти никогда)
 * photo_2, photo_3, photo_4, youtube_1, site, photo_search, link
 */