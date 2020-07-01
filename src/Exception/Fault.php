<?php

namespace Supervisor\Exception;

/**
 * Fault codes are taken from the source code, not the documentation.
 * The most common ones are covered by the XML-RPC doc.
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Fault extends \Exception
{
    /**
     * Fault responses.
     */
    const UNKNOWN_METHOD = 1;
    const INCORRECT_PARAMETERS = 2;
    const BAD_ARGUMENTS = 3;
    const SIGNATURE_UNSUPPORTED = 4;
    const SHUTDOWN_STATE = 6;
    const BAD_NAME = 10;
    const BAD_SIGNAL = 11;
    const NO_FILE = 20;
    const NOT_EXECUTABLE = 21;
    const FAILED = 30;
    const ABNORMAL_TERMINATION = 40;
    const SPAWN_ERROR = 50;
    const ALREADY_STARTED = 60;
    const NOT_RUNNING = 70;
    const SUCCESS = 80;
    const ALREADY_ADDED = 90;
    const STILL_RUNNING = 91;
    const CANT_REREAD = 92;

    /**
     * @var array
     */
    private static $exceptionMap = [
        1 => Fault\UnknownMethod::class,
        2 => Fault\IncorrectParameters::class,
        3 => Fault\BadArguments::class,
        4 => Fault\SignatureUnsupported::class,
        6 => Fault\ShutdownState::class,
        10 => Fault\BadName::class,
        11 => Fault\BadSignal::class,
        20 => Fault\NoFile::class,
        21 => Fault\NotExecutable::class,
        30 => Fault\Failed::class,
        40 => Fault\AbnormalTermination::class,
        50 => Fault\SpawnError::class,
        60 => Fault\AlreadyStarted::class,
        70 => Fault\NotRunning::class,
        80 => Fault\Success::class,
        90 => Fault\AlreadyAdded::class,
        91 => Fault\StillRunning::class,
        92 => Fault\CantReread::class,
    ];

    /**
     * Creates a new Fault.
     *
     * If there is a mach for the fault code in the exception map then the matched exception will be returned
     *
     * @param string $faultString
     * @param int $faultCode
     *
     * @return self
     */
    public static function create($faultString, $faultCode)
    {
        if (!isset(self::$exceptionMap[$faultCode])) {
            return new self($faultString, $faultCode);
        }

        return new self::$exceptionMap[$faultCode]($faultString, $faultCode);
    }
}
