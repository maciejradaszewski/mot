<?php

require_once 'configure_autoload.php';

use Zend\Log\Formatter\Simple;
use Zend\Log\Writer\Syslog;
use Zend\Log\Logger;
use Zend\Log\Filter\Priority;


/**
 * debug a message to /var/log/dvsa/mot-fitnesse.log. This will use syslog to
 * avoid to deal with file io. To view debug logs, add a rsyslog config file
 *
 *      /etc/rsyslog.d/dvsa-mot-fitnesse.conf
 *
 * with the contents
 *
 *      if $programname == 'dvsa-mot-fitnesse' and $syslogseverity <= '7' then /var/log/dvsa/mot-fitnesse.log
 *
 * make sure rsyslog is started.
 *
 *      sudo service rsyslog start
 *
 * @param $message
 * @param array $data
 */
function debug($message, $data = [])
{
    // only debug when we explicitly turn on debugging.
    if (getenv('DEBUG') !== '1') {
        return;
    }

    if (!is_array($data)) {
        $data = [$data];
    }

    $formatter = new Simple('%priorityName% (%priority%): %message% %extra%');
    $writer = new Syslog(['application' => 'dvsa-mot-fitnesse']);
    $writer->addFilter(new Priority(Logger::DEBUG));
    $writer->setFormatter($formatter);
    $logger = new Logger();
    $logger->addWriter($writer);
    $logger->debug($message, $data);
}

/**
 * Includes commonly used features by Fitnesse
 */
class SetUp
{
    /**
     * Resets database. Takes about 5 secs. Please make sure you use only when necessary.
     * It should not be used for any new tests!
     * @deprecated
     * @return bool
     */
    public function resetDatabase()
    {
        // All auth tokens will have been cleared by the DB reset
        \TokenCache::clearAuthCache();

        FitMotApiClient::create('schememgt', \MotFitnesse\Util\TestShared::PASSWORD)->delete(
            (new \MotFitnesse\Util\TestSupportUrlBuilder())->dbReset()
        );

        return true;
    }

    /**
     * At the moment, its empty. Its called by every single fitnesse tests before they start.
     * @return bool
     */
    public function init()
    {
        return true;
    }
}
