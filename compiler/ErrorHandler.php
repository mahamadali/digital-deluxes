<?php

use Jolly\Engine;

register_shutdown_function( "fatal_handler" );

function fatal_handler() {
    $error = error_get_last();
    if (!empty($error)) {
        $errorMessage = $error['message'];
        $errorMessage.= '<br>File: '.$error["file"];
        $errorMessage.= '<br>Line: '.$error["line"];
        ob_clean();
        return Engine::error([
            'error' => $errorMessage,
            'errorInfo' => $error
        ]);
    }
}