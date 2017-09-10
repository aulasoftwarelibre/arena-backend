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
use AppBundle\Entity\Registration;
use AppBundle\Event\AcceptedApplicationEvent;
use AppBundle\Event\AcceptedInvitationEvent;
use AppBundle\Event\CreatedApplicationEvent;
use AppBundle\Event\CreatedInvitationEvent;
use AppBundle\Event\DeletedRegistrationEvent;
use AppBundle\Event\RefusedApplicationEvent;
use AppBundle\Event\RefusedInvitationEvent;

/**
 * Class RegistrationEventDispatcher.
 */
class RegistrationEventDispatcher extends AbstractEventDispatcher
{
    /**
     * @param Registration $application
     *
     * @return RegistrationEventDispatcher
     */
    public function createdApplicationEvent(Registration $application): RegistrationEventDispatcher
    {
        $event = new CreatedApplicationEvent($application);

        $this->eventDispatcher->dispatch(ArenaEvents::CREATED_APPLICATION, $event);

        return $this;
    }

    /**
     * @param Registration $application
     *
     * @return RegistrationEventDispatcher
     */
    public function acceptedApplicationEvent(Registration $application): RegistrationEventDispatcher
    {
        $event = new AcceptedApplicationEvent($application);

        $this->eventDispatcher->dispatch(ArenaEvents::ACCEPTED_APPLICATION, $event);

        return $this;
    }

    /**
     * @param Registration $application
     *
     * @return RegistrationEventDispatcher
     */
    public function refusedApplicationEvent(Registration $application): RegistrationEventDispatcher
    {
        $event = new RefusedApplicationEvent($application);

        $this->eventDispatcher->dispatch(ArenaEvents::REFUSED_APPLICATION, $event);

        return $this;
    }

    /**
     * @param Registration $invitation
     *
     * @return RegistrationEventDispatcher
     */
    public function createdInvitationEvent(Registration $invitation): RegistrationEventDispatcher
    {
        $event = new CreatedInvitationEvent($invitation);

        $this->eventDispatcher->dispatch(ArenaEvents::CREATED_INVITATION, $event);

        return $this;
    }

    /**
     * @param Registration $invitation
     *
     * @return RegistrationEventDispatcher
     */
    public function acceptedInvitationEvent(Registration $invitation): RegistrationEventDispatcher
    {
        $event = new AcceptedInvitationEvent($invitation);

        $this->eventDispatcher->dispatch(ArenaEvents::ACCEPTED_INVITATION, $event);

        return $this;
    }

    /**
     * @param Registration $invitation
     *
     * @return RegistrationEventDispatcher
     */
    public function refusedInvitationEvent(Registration $invitation): RegistrationEventDispatcher
    {
        $event = new RefusedInvitationEvent($invitation);

        $this->eventDispatcher->dispatch(ArenaEvents::REFUSED_INVITATION, $event);

        return $this;
    }

    /**
     * @param Registration $registration
     *
     * @return RegistrationEventDispatcher
     */
    public function deletedRegistrationEvent(Registration $registration): RegistrationEventDispatcher
    {
        $event = new DeletedRegistrationEvent($registration);

        $this->eventDispatcher->dispatch(ArenaEvents::DELETED_REGISTRATION, $event);

        return $this;
    }
}
