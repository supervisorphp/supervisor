<?php

namespace Supervisor;

/**
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
enum ServiceStates: int
{
    case Shutdown = -1;
    case Restarting = 0;
    case Running = 1;
    case Fatal = 2;
}
