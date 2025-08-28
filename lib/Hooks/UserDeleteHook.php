<?php
/**
 * Nextcloud - News
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author    Alessandro Cosentino <cosenal@gmail.com>
 * @author    Bernhard Posselt <dev@bernhard-posselt.com>
 * @copyright 2012 Alessandro Cosentino
 * @copyright 2012-2014 Bernhard Posselt
 */

namespace OCA\NewsYawks\Hooks;

use OCA\NewsYawks\AppInfo\Application;
use OCA\NewsYawks\Service\FeedServiceV2;
use OCA\NewsYawks\Service\FolderServiceV2;
use OCA\NewsYawks\Service\ItemServiceV2;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\User\Events\BeforeUserDeletedEvent;

class UserDeleteHook implements IEventListener
{

    /**
     * Handle user deletion
     *
     * @param BeforeUserDeletedEvent $event
     */
    public function handle(Event $event): void
    {
        $userId = $event->getUser()->getUID();

        $app = new Application();
        $container = $app->getContainer();

        // order is important!
        $container->get(ItemServiceV2::class)->deleteUser($userId);
        $container->get(FeedServiceV2::class)->deleteUser($userId);
        $container->get(FolderServiceV2::class)->deleteUser($userId);
    }
}
