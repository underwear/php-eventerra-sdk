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

namespace Eventerra\HttpClients;

use Eventerra\Http\RawResponse;
use Eventerra\Exceptions\EventerraSDKException;

/**
 * Class EventerraCurlHttpClient
 *
 * @package Eventerra
 */
class EventerraCurlHttpClient implements EventerraHttpClientInterface {
/**
     * @var string The client error message
     */
    protected $curlErrorMessage = '';
    /**
     * @var int The curl client error code
     */
    protected $curlErrorCode = 0;
    /**
     * @var string|boolean The raw response from the server
     */
    protected $rawResponse;
    /**
     * @var EventerraCurl Procedural curl as object
     */
    protected $facebookCurl;
    /**
     * @param EventerraCurl|null Procedural curl as object
     */
    public function __construct(EventerraCurl $facebookCurl = null)
    {
        $this->facebookCurl = $facebookCurl ?: new EventerraCurl();
    }
    /**
     * @inheritdoc
     */
    public function send($url, $method, $body, array $headers, $timeOut)
    {
        $this->openConnection($url, $method, $body, $headers, $timeOut);
        $this->sendRequest();
        if ($curlErrorCode = $this->facebookCurl->errno()) {
            throw new EventerraSDKException($this->facebookCurl->error(), $curlErrorCode);
        }
        // Separate the raw headers from the raw body
        list($rawHeaders, $rawBody) = $this->extractResponseHeadersAndBody();
        $this->closeConnection();
        return new RawResponse($rawHeaders, $rawBody);
    }
    /**
     * Opens a new curl connection.
     *
     * @param string $url     The endpoint to send the request to.
     * @param string $method  The request method.
     * @param string $body    The body of the request.
     * @param array  $headers The request headers.
     * @param int    $timeOut The timeout in seconds for the request.
     */
    public function openConnection($url, $method, $body, array $headers, $timeOut)
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $this->compileRequestHeaders($headers),
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => $timeOut,
            CURLOPT_RETURNTRANSFER => true, // Return response as string
            CURLOPT_HEADER => true, // Enable header processing
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
        ];
        if ($method !== "GET") {
            $options[CURLOPT_POSTFIELDS] = $body;
        }
        $this->facebookCurl->init();
        $this->facebookCurl->setoptArray($options);
    }
    /**
     * Closes an existing curl connection
     */
    public function closeConnection()
    {
        $this->facebookCurl->close();
    }
    /**
     * Send the request and get the raw response from curl
     */
    public function sendRequest()
    {
        $this->rawResponse = $this->facebookCurl->exec();
    }
    /**
     * Compiles the request headers into a curl-friendly format.
     *
     * @param array $headers The request headers.
     *
     * @return array
     */
    public function compileRequestHeaders(array $headers)
    {
        $return = [];
        foreach ($headers as $key => $value) {
            $return[] = $key . ': ' . $value;
        }
        return $return;
    }
    /**
     * Extracts the headers and the body into a two-part array
     *
     * @return array
     */
    public function extractResponseHeadersAndBody()
    {
        $parts = explode("\r\n\r\n", $this->rawResponse);
        $rawBody = array_pop($parts);
        $rawHeaders = implode("\r\n\r\n", $parts);
        return [trim($rawHeaders), trim($rawBody)];
    }
}