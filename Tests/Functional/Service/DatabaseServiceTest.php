<?php

namespace JWeiland\Events2\Tests\Functional\Service;

/*
 * This file is part of the events2 project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use JWeiland\Events2\Configuration\ExtConf;
use JWeiland\Events2\Domain\Model\Event;
use JWeiland\Events2\Domain\Model\Location;
use JWeiland\Events2\Domain\Model\Organizer;
use JWeiland\Events2\Domain\Repository\DayRepository;
use JWeiland\Events2\Domain\Repository\EventRepository;
use JWeiland\Events2\Service\DatabaseService;
use JWeiland\Events2\Service\DayRelationService;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

/**
 * Functional test for DatabaseService
 */
class DatabaseServiceTest extends FunctionalTestCase
{
    /**
     * @var DayRepository
     */
    protected $dayRepository;

    /**
     * @var QuerySettingsInterface
     */
    protected $querySettings;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/events2',
        'typo3conf/ext/maps2'
    ];

    public function setUp()
    {
        parent::setUp();

        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->dayRepository = $this->objectManager->get(DayRepository::class);
        $this->querySettings = $this->objectManager->get(QuerySettingsInterface::class);
        $this->querySettings->setStoragePageIds([11, 40]);
        $this->dayRepository->setDefaultQuerySettings($this->querySettings);
        $persistenceManager = $this->objectManager->get(PersistenceManager::class);
        $dayRelationService = $this->objectManager->get(DayRelationService::class);
        $eventRepository = $this->objectManager->get(EventRepository::class);
        $eventRepository->setDefaultQuerySettings($this->querySettings);

        $organizer = new Organizer();
        $organizer->setPid(11);
        $organizer->setOrganizer('Stefan');

        $location = new Location();
        $location->setPid(11);
        $location->setLocation('Market');

        $eventBegin = new \DateTime('midnight');
        $eventBegin->modify('first day of this month')->modify('+4 days')->modify('-2 months');

        $event = new Event();
        $event->setPid(11);
        $event->setEventType('recurring');
        $event->setTopOfList(false);
        $event->setTitle('Week market');
        $event->setTeaser('');
        $event->setEventBegin($eventBegin);
        $event->setXth(31);
        $event->setWeekday(16);
        $event->setEachWeeks(0);
        $event->setEachMonths(0);
        $event->setRecurringEnd(null);
        $event->setFreeEntry(false);
        $event->setOrganizer($organizer);
        $event->setLocation($location);
        $persistenceManager->add($event);

        $persistenceManager->persistAll();

        $extConf = GeneralUtility::makeInstance(ExtConf::class);
        $extConf->setRecurringPast(3);
        $extConf->setRecurringFuture(6);
        $events = $eventRepository->findAll();
        foreach ($events as $event) {
            $dayRelationService->createDayRelations($event->getUid());
        }
    }

    public function tearDown()
    {
        unset($this->dayRepository);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getDaysInRangeWillFindDaysForCurrentMonth()
    {
        $eventBegin = new \DateTime('today midnight');
        $eventBegin->modify('first day of this month');
        $eventEnd = new \DateTime('today midnight');
        $eventEnd->modify('last day of this month');

        $databaseService = $this->objectManager->get(DatabaseService::class);
        var_dump($eventBegin->format('U'));
        var_dump($eventEnd->format('U'));
        $days = $databaseService->getDaysInRange($eventBegin, $eventEnd, [11]);

        $this->assertGreaterThanOrEqual(
            3,
            count($days)
        );
    }
}
