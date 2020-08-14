<?php
declare(strict_types=1);

namespace Simulation\Exception;

use RuntimeException;

/**
 * Exception to be thrown in case Players list is not unique in the sense of unique initial pixels (aka. "home pixels").
 */
class UniquePlayersListViolation extends RuntimeException
{

}
