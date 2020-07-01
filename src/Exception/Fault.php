<?php

namespace Supervisor\Exception;

use fXmlRpc\Exception\FaultException;

/**
 * Fault codes are taken from the source code, not the documentation.
 * The most common ones are covered by the XML-RPC doc.
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Fault extends \RuntimeException
{
    /**
     * Fault responses.
     */
    private const UNKNOWN_METHOD = 1;
    private const INCORRECT_PARAMETERS = 2;
    private const BAD_ARGUMENTS = 3;
    private const SIGNATURE_UNSUPPORTED = 4;
    private const SHUTDOWN_STATE = 6;
    private const BAD_NAME = 10;
    private const BAD_SIGNAL = 11;
    private const NO_FILE = 20;
    private const NOT_EXECUTABLE = 21;
    private const FAILED = 30;
    private const ABNORMAL_TERMINATION = 40;
    private const SPAWN_ERROR = 50;
    private const ALREADY_STARTED = 60;
    private const NOT_RUNNING = 70;
    private const SUCCESS = 80;
    private const ALREADY_ADDED = 90;
    private const STILL_RUNNING = 91;
    private const CANT_REREAD = 92;

    /**
     * @var array
     */
    private static $exceptionMap = [
        self::UNKNOWN_METHOD => Fault\UnknownMethod::class,
        self::INCORRECT_PARAMETERS => Fault\IncorrectParameters::class,
        self::BAD_ARGUMENTS => Fault\BadArguments::class,
        self::SIGNATURE_UNSUPPORTED => Fault\SignatureUnsupported::class,
        self::SHUTDOWN_STATE => Fault\ShutdownState::class,
        self::BAD_NAME => Fault\BadName::class,
        self::BAD_SIGNAL => Fault\BadSignal::class,
        self::NO_FILE => Fault\NoFile::class,
        self::NOT_EXECUTABLE => Fault\NotExecutable::class,
        self::FAILED => Fault\Failed::class,
        self::ABNORMAL_TERMINATION => Fault\AbnormalTermination::class,
        self::SPAWN_ERROR => Fault\SpawnError::class,
        self::ALREADY_STARTED => Fault\AlreadyStarted::class,
        self::NOT_RUNNING => Fault\NotRunning::class,
        self::SUCCESS => Fault\Success::class,
        self::ALREADY_ADDED => Fault\AlreadyAdded::class,
        self::STILL_RUNNING => Fault\StillRunning::class,
        self::CANT_REREAD => Fault\CantReread::class,
    ];

    /**
     * Creates a new Fault exception if a named one from the table above is present.
     *
     * @param FaultException $faultException
     *
     * @return FaultException|self
     */
    public static function create(FaultException $faultException)
    {
        $faultCode = $faultException->getFaultCode();
        $faultString = $faultException->getFaultString();

        if (!isset(self::$exceptionMap[$faultCode])) {
            return $faultException;
        }

        return new self::$exceptionMap[$faultCode]($faultString, $faultCode);
    }
}
