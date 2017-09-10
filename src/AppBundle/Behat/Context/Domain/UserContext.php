<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Behat\Context\Domain;

use AppBundle\Behat\Context\DefaultContext;
use AppBundle\Entity\User;
use AppBundle\Services\UserManager;

/**
 * Class UserContext.
 */
class UserContext extends DefaultContext
{
    /**
     * @Given /^the users?:$/
     *
     * @param array $users
     */
    public function givenTheUsers(array $users)
    {
        /** @var UserManager $userManager */
        $userManager = $this->getService('arena.manager.user');

        $userManager->save($users);
    }

    /**
     * @Given the user :user
     *
     * @param User $user
     */
    public function givenTheUser(User $user)
    {
        /** @var UserManager $userManager */
        $userManager = $this->getService('arena.manager.user');

        $userManager->saveOne($user);

        $this->sharedStorage->set('user', $user);
    }
}
