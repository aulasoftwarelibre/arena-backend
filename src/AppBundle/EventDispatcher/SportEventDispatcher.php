<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventDispatcher;

use AppBundle\ArenaEvents;
use AppBundle\Entity\Sport;
use AppBundle\Event\CreatedSportEvent;
use AppBundle\Event\DeletedSportEvent;
use AppBundle\Event\UpdatedSportEvent;

/**
 * Class SportEventDispatcher.
 */
class SportEventDispatcher extends AbstractEventDispatcher
{
    /**
     * @param Sport $sport
     *
     * @return SportEventDispatcher
     */
    public function createdSportEvent(Sport $sport): SportEventDispatcher
    {
        $event = new CreatedSportEvent($sport);

        $this->eventDispatcher->dispatch(ArenaEvents::CREATED_SPORT, $event);

        return $this;
    }

    /**
     * @param Sport $sport
     *
     * @return SportEventDispatcher
     */
    public function updatedSportEvent(Sport $sport): SportEventDispatcher
    {
        $event = new UpdatedSportEvent($sport);

        $this->eventDispatcher->dispatch(ArenaEvents::UPDATED_SPORT, $event);

        return $this;
    }

    /**
     * @param Sport $sport
     *
     * @return SportEventDispatcher
     */
    public function deletedSportEvent(Sport $sport): SportEventDispatcher
    {
        $event = new DeletedSportEvent($sport);

        $this->eventDispatcher->dispatch(ArenaEvents::DELETED_SPORT, $event);

        return $this;
    }
}
