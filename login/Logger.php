<?php

class Logger {
    private $logFile;

    public function __construct($logFile = "app.log") {
        $this->logFile = $logFile;
    }

    public function log($message, $level = 'INFO') {
        $date = date("Y-m-d H:i:s");
        $logMessage = "[$date] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }

    public function logError($message) {
        $this->log($message, 'ERROR');
    }

    public function logInfo($message) {
        $this->log($message, 'INFO');
    }

    public function logWarning($message) {
        $this->log($message, 'WARNING');
    }
}

?>
