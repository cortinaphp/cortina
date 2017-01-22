<?php
/**
 * Cortina : PHP Framework
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Ross Chater. (http://rosschater.com)
 * @link          http://rosschater.com Project Cortina
 * @since         0.0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cortina\Network;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter as ZendSapiEmitter;
/**
 * Default Sapi Emitter
 */
class SapiEmitter extends ZendSapiEmitter
{

    /**
     * Emit response if not in silent mode
     * @param  ResponseInterface $response
     * @param  int               $maxBufferLevel
     * @param  bool              $silentMode
     * @return void|\Psr\Http\Message\ResponseInterface
     */
    public function safeEmit(ResponseInterface $response, $maxBufferLevel = null, bool $silentMode = null)
    {
        if (isset($silentMode) && $silentMode === true) {
            return $response->getBody();
        }
        parent::emit($response);
    }

}
