<?php
/**
 * Main Script of phpWebSockets
 *
 * Run this file in a shell or windows cmd to start the socket server.
 * Sorry for calling this daemon but the goal is that this server run
 * as daemon in near future.
 *
 * @author Moritz Wutz <moritzwutz@gmail.com>
 * @version 0.1
 * @package phpWebSockets
 */

ob_implicit_flush(true);

require '../../config.php';
require '../helpers/functions.php';
require 'socket.class.php';
require 'socketWebSocket.class.php';
$wshost=between('ws://',':',WEBSOCKET_SERVER);
$wsport=after_last(':',WEBSOCKET_SERVER);
$WebSocket = new socketWebSocket($wshost,$wsport);

?>