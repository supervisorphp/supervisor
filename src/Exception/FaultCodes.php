<?php

namespace Supervisor\Exception;

/**
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
enum FaultCodes: int
{
    case UnknownMethod = 1;
    case IncorrectParameters = 2;
    case BadArguments = 3;
    case SignatureUnsupported = 4;
    case ShutdownState = 6;
    case BadName = 10;
    case BadSignal = 11;
    case NoFile = 20;
    case NotExecutable = 21;
    case Failed = 30;
    case AbnormalTermination = 40;
    case SpawnError = 50;
    case AlreadyStarted = 60;
    case NotRunning = 70;
    case Success = 80;
    case AlreadyAdded = 90;
    case StillRunning = 91;
    case CantReread = 92;

    public function getExceptionClass(): string
    {
        return match($this) {
            self::UnknownMethod => Fault\UnknownMethodException::class,
            self::IncorrectParameters => Fault\IncorrectParametersException::class,
            self::BadArguments => Fault\BadArgumentsException::class,
            self::SignatureUnsupported => Fault\SignatureUnsupportedException::class,
            self::ShutdownState => Fault\ShutdownStateException::class,
            self::BadName => Fault\BadNameException::class,
            self::BadSignal => Fault\BadSignalException::class,
            self::NoFile => Fault\NoFileException::class,
            self::NotExecutable => Fault\NotExecutableException::class,
            self::Failed => Fault\FailedException::class,
            self::AbnormalTermination => Fault\AbnormalTerminationException::class,
            self::SpawnError => Fault\SpawnErrorException::class,
            self::AlreadyStarted => Fault\AlreadyStartedException::class,
            self::NotRunning => Fault\NotRunningException::class,
            self::Success => Fault\SuccessException::class,
            self::AlreadyAdded => Fault\AlreadyAddedException::class,
            self::StillRunning => Fault\StillRunningException::class,
            self::CantReread => Fault\CantRereadException::class,
        };
    }
}
