<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateTokenCommand.
 */
class GenerateTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('arena:security:token')
            ->setDescription('Create a new user token')
            ->addArgument('username', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $user = $this->getContainer()->get('doctrine')->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username]);
        if (!$user instanceof User) {
            throw new InvalidArgumentException('User could not be found');
        }

        $token = $this->getContainer()->get('lexik_jwt_authentication.jwt_manager')->create($user);

        $output->writeln('New JWT token for '.$username.':');
        $output->writeln('Bearer '.$token);
    }
}
