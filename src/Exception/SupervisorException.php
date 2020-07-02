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
class SupervisorException extends \RuntimeException
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
        self::UNKNOWN_METHOD => Fault\UnknownMethodException::class,
        self::INCORRECT_PARAMETERS => Fault\IncorrectParametersException::class,
        self::BAD_ARGUMENTS => Fault\BadArgumentsException::class,
        self::SIGNATURE_UNSUPPORTED => Fault\SignatureUnsupportedException::class,
        self::SHUTDOWN_STATE => Fault\ShutdownStateException::class,
        self::BAD_NAME => Fault\BadNameException::class,
        self::BAD_SIGNAL => Fault\BadSignalException::class,
        self::NO_FILE => Fault\NoFileException::class,
        self::NOT_EXECUTABLE => Fault\NotExecutableException::class,
        self::FAILED => Fault\FailedException::class,
        self::ABNORMAL_TERMINATION => Fault\AbnormalTerminationException::class,
        self::SPAWN_ERROR => Fault\SpawnErrorException::class,
        self::ALREADY_STARTED => Fault\AlreadyStartedException::class,
        self::NOT_RUNNING => Fault\NotRunningException::class,
        self::SUCCESS => Fault\SuccessException::class,
        self::ALREADY_ADDED => Fault\AlreadyAddedException::class,
        self::STILL_RUNNING => Fault\StillRunningException::class,
        self::CANT_REREAD => Fault\CantRereadException::class,
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
