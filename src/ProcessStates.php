<?php

namespace Supervisor;

/**
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
enum ProcessStates: int
{
    case Stopped = 0;
    case Starting = 10;
    case Running = 20;
    case Backoff = 30;
    case Stopping = 40;
    case Exited = 100;
    case Fatal = 200;
    case Unknown = 1000;
}
