<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Action;

use AppBundle\Entity\Registration;
use AppBundle\Services\RegistrationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class RegistrationDeleteAction.
 */
class RegistrationDeleteAction
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RegistrationManager
     */
    private $registrationManager;

    /**
     * RegistrationDeleteAction constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param RegistrationManager   $registrationManager
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RegistrationManager $registrationManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->registrationManager = $registrationManager;
    }

    /**
     * @Route(
     *     name="api_registrations_delete_item",
     *     path="/registrations/{id}",
     *     defaults={"_api_resource_class"=Registration::class, "_api_item_operation_name"="delete"}
     * )
     * @Security("has_role('ROLE_USER')")
     *
     * @Method("DELETE")
     *
     * @param Registration $data
     *
     * @return Response
     */
    public function __invoke($data)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $activity = $data->getActivity();
        if ($activity->getOwner()->getId() !== $user->getId()) {
            throw new AccessDeniedException('User is not the owner');
        }

        if (!($data->isAccepted() || $data->isInvitation())) {
            throw new AccessDeniedException('Registration is not an invitation or accepted');
        }

        $this->registrationManager->delete($data);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
