<?php
/**
 * This file is part of the 'arena' project'.
 *
 * (c) Víctor Monserrat Villatoro <victor1995mv@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Services;

use AppBundle\Entity\Sport;
use AppBundle\EventDispatcher\SportEventDispatcher;
use AppBundle\Factory\SportFactory;
use AppBundle\Repository\SportRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SportManager.
 */
class SportManager
{
    /**
     * @var SportFactory
     */
    private $sportFactory;

    /**
     * @var SportRepository
     */
    private $sportRepository;

    /**
     * @var SportEventDispatcher
     */
    private $sportEventDispatcher;

    /**
     * @var ObjectManager
     */
    private $sportObjectManager;

    /**
     * SportManager constructor.
     *
     * @param SportFactory         $sportFactory
     * @param SportRepository      $sportRepository
     * @param SportEventDispatcher $sportEventDispatcher
     * @param ObjectManager        $sportObjectManager
     */
    public function __construct(
        SportFactory $sportFactory,
        SportRepository $sportRepository,
        SportEventDispatcher $sportEventDispatcher,
        ObjectManager $sportObjectManager
    ) {
        $this->sportFactory = $sportFactory;
        $this->sportRepository = $sportRepository;
        $this->sportEventDispatcher = $sportEventDispatcher;
        $this->sportObjectManager = $sportObjectManager;
    }

    /**
     * @param Sport $sport
     * @param bool  $flush
     *
     * @return SportManager
     */
    public function saveOne(Sport $sport, bool $flush = true): SportManager
    {
        $this->sportObjectManager->persist($sport);
        if ($flush) {
            $this->sportObjectManager->flush();
        }

        return $this;
    }

    /**
     * @param array $sports
     * @param bool  $flush
     *
     * @return SportManager
     */
    public function save(array $sports, bool $flush = true): SportManager
    {
        foreach ($sports as $sport) {
            $this->saveOne($sport, false);
        }
        if ($flush) {
            $this->sportObjectManager->flush();
        }

        return $this;
    }

    /**
     * @param Sport $sport
     *
     * @return SportManager
     */
    public function create(Sport $sport): SportManager
    {
        $this->saveOne($sport);
        $this->sportEventDispatcher->createdSportEvent($sport);

        return $this;
    }

    /**
     * @param Sport $sport
     *
     * @return SportManager
     */
    public function update(Sport $sport): SportManager
    {
        $this->saveOne($sport);
        $this->sportEventDispatcher->updatedSportEvent($sport);

        return $this;
    }

    /**
     * @param Sport $sport
     * @param bool  $flush
     *
     * @return SportManager
     */
    public function removeOne(Sport $sport, bool $flush = true): SportManager
    {
        $this->sportObjectManager->remove($sport);
        if ($flush) {
            $this->sportObjectManager->flush();
        }

        return $this;
    }

    /**
     * @param array $sports
     * @param bool  $flush
     *
     * @return SportManager
     */
    public function remove(array $sports, bool $flush = true): SportManager
    {
        foreach ($sports as $sport) {
            $this->removeOne($sport, false);
        }
        if ($flush) {
            $this->sportObjectManager->flush();
        }

        return $this;
    }

    /**
     * @param Sport $sport
     *
     * @return SportManager
     */
    public function delete(Sport $sport): SportManager
    {
        $this->removeOne($sport);
        $this->sportEventDispatcher->deletedSportEvent($sport);

        return $this;
    }
}
