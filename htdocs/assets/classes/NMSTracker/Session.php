<?php
/**
 * File containing the {@see NMSTracker_Session} class.
 *
 * @package NMSTracker
 * @subpackage Core
 * @template-version 1.2
 *
 * @see NMSTracker_Session
 */

declare(strict_types=1);

/**
 * Session handling class, based on the native PHP sessions.
 *
 * @package NMSTracker
 * @subpackage Core
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 * @see Application_Session_Native
 */
class NMSTracker_Session extends Application_Session_Native
{
    use Application_Session_AuthTypes_None;

    protected function _getPrefix(): string
    {
        return 'nmstracker';
    }
}
