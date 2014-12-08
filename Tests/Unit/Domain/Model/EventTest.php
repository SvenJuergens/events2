<?php
namespace JWeiland\Events2\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Stefan Froemken <projects@jweiland.net>, jweiland.net
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use JWeiland\Events2\Domain\Model\Category;
use JWeiland\Events2\Domain\Model\Day;
use JWeiland\Events2\Domain\Model\Exception;
use JWeiland\Events2\Domain\Model\Link;
use JWeiland\Events2\Domain\Model\Organizer;
use JWeiland\Events2\Domain\Model\Location;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use JWeiland\Events2\Domain\Model\Event;
use JWeiland\Events2\Domain\Model\Time;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Test case.
 *
 * @subpackage Events
 * @author Stefan Froemken <projects@jweiland.net>
 */
class EventTest extends UnitTestCase {

	/**
	 * @var \JWeiland\Events2\Domain\Model\Event
	 */
	protected $subject;

	/**
	 * set up
	 *
	 * @return void
	 */
	public function setUp() {
		$this->subject = new Event();
	}

	/**
	 * tear down
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getHiddenInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getHidden()
		);
	}

	/**
	 * @test
	 */
	public function setHiddenSetsHidden() {
		$this->subject->setHidden(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getHidden()
		);
	}

	/**
	 * @test
	 */
	public function setHiddenWithStringReturnsTrue() {
		$this->subject->setHidden('foo bar');
		$this->assertTrue($this->subject->getHidden());
	}

	/**
	 * @test
	 */
	public function setHiddenWithZeroReturnsFalse() {
		$this->subject->setHidden(0);
		$this->assertFalse($this->subject->getHidden());
	}

	
	/**
	 * @test
	 */
	public function getTitleInitiallyReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function setTitleSetsTitle() {
		$this->subject->setTitle('foo bar');
	
		$this->assertSame(
			'foo bar',
			$this->subject->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function setTitleWithIntegerResultsInString() {
		$this->subject->setTitle(123);
		$this->assertSame('123', $this->subject->getTitle());
	}
	
	/**
	 * @test
	 */
	public function setTitleWithBooleanResultsInString() {
		$this->subject->setTitle(TRUE);
		$this->assertSame('1', $this->subject->getTitle());
	}

	/**
	 * @test
	 */
	public function getTopOfListInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getTopOfList()
		);
	}

	/**
	 * @test
	 */
	public function setTopOfListSetsTopOfList() {
		$this->subject->setTopOfList(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getTopOfList()
		);
	}

	/**
	 * @test
	 */
	public function setTopOfListWithStringReturnsTrue() {
		$this->subject->setTopOfList('foo bar');
		$this->assertTrue($this->subject->getTopOfList());
	}

	/**
	 * @test
	 */
	public function setTopOfListWithZeroReturnsFalse() {
		$this->subject->setTopOfList(0);
		$this->assertFalse($this->subject->getTopOfList());
	}


	/**
	 * @test
	 */
	public function getTeaserInitiallyReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->subject->getTeaser()
		);
	}
	
	/**
	 * @test
	 */
	public function setTeaserSetsTeaser() {
		$this->subject->setTeaser('foo bar');
	
		$this->assertSame(
			'foo bar',
			$this->subject->getTeaser()
		);
	}
	
	/**
	 * @test
	 */
	public function setTeaserWithIntegerResultsInString() {
		$this->subject->setTeaser(123);
		$this->assertSame('123', $this->subject->getTeaser());
	}
	
	/**
	 * @test
	 */
	public function setTeaserWithBooleanResultsInString() {
		$this->subject->setTeaser(TRUE);
		$this->assertSame('1', $this->subject->getTeaser());
	}

	/**
	 * @test
	 */
	public function getEventBeginInitiallyReturnsNull() {
		$this->assertNull(
			$this->subject->getEventBegin()
		);
	}

	/**
	 * @test
	 */
	public function setEventBeginSetsEventBegin() {
		$date = new \DateTime();
		$this->subject->setEventBegin($date);

		$this->assertSame(
			$date,
			$this->subject->getEventBegin()
		);
	}

	/**
	 * @test
	 * @expectedException \Exception
	 */
	public function setEventBeginWithTimestampResultsInException() {
		$this->subject->setEventBegin(1234567890);
	}

	/**
	 * @test
	 */
	public function getEventTimeInitiallyReturnsNull() {
		$this->assertNull($this->subject->getEventTime());
	}

	/**
	 * @test
	 */
	public function getEventTimeWithoutAnyTimesInAnyRelationsResultsInTimeOfCurrentEvent() {
		$time = new Time();
		$time->setTimeBegin('09:34');
		$this->subject->setEventTime($time);
		$this->assertEquals(
			$time,
			$this->subject->getEventTime()
		);
	}

	/**
	 * @test
	 */
	public function setEventTimeSetsEventTime() {
		$instance = new Time();
		$this->subject->setEventTime($instance);

		$this->assertSame(
			$instance,
			$this->subject->getEventTime()
		);
	}

	/**
	 * @test
	 */
	public function getDaysOfEventsTakingDaysWithEqualDaysReturnsZero() {
		$eventBegin = new \DateTime();
		$eventEnd = new \DateTime();
		$eventEnd->modify('+20 seconds');
		$this->subject->setEventBegin($eventBegin);
		$this->subject->setEventEnd($eventEnd);

		$this->assertSame(
			0,
			$this->subject->getDaysOfEventsTakingDays()
		);
	}

	/**
	 * @test
	 */
	public function getDaysOfEventsTakingDaysWithNoneEventEndResultsInZero() {
		$eventBegin = new \DateTime();
		$this->subject->setEventBegin($eventBegin);

		$this->assertSame(
			0,
			$this->subject->getDaysOfEventsTakingDays()
		);
	}

	/**
	 * @test
	 */
	public function getDaysOfEventsTakingDaysWithDifferentDatesResultsInFourDays() {
		$eventBegin = new \DateTime();
		$eventEnd = new \DateTime(); // f.e. monday
		$eventEnd->modify('+4 days'); // mo + 4 = 5 days: mo->tu->we->th->fr
		$this->subject->setEventBegin($eventBegin);
		$this->subject->setEventEnd($eventEnd);

		$this->assertSame(
			5,
			$this->subject->getDaysOfEventsTakingDays()
		);
	}

	/**
	 * @test
	 */
	public function getEventEndInitiallyReturnsNull() {
		$this->assertNull($this->subject->getEventEnd());
	}

	/**
	 * @test
	 */
	public function setEventEndSetsEventEnd() {
		$instance = new \DateTime();
		$this->subject->setEventEnd($instance);

		$this->assertSame(
			$instance,
			$this->subject->getEventEnd()
		);
	}

	/**
	 * @test
	 */
	public function getRecurringEventInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getRecurringEvent()
		);
	}

	/**
	 * @test
	 */
	public function setRecurringEventSetsRecurringEvent() {
		$this->subject->setRecurringEvent(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getRecurringEvent()
		);
	}

	/**
	 * @test
	 */
	public function setRecurringEventWithStringReturnsTrue() {
		$this->subject->setRecurringEvent('foo bar');
		$this->assertTrue($this->subject->getRecurringEvent());
	}

	/**
	 * @test
	 */
	public function setRecurringEventWithZeroReturnsFalse() {
		$this->subject->setRecurringEvent(0);
		$this->assertFalse($this->subject->getRecurringEvent());
	}

	/**
	 * @test
	 */
	public function getSameDayInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getSameDay()
		);
	}

	/**
	 * @test
	 */
	public function setSameDaySetsSameDay() {
		$this->subject->setSameDay(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getSameDay()
		);
	}

	/**
	 * @test
	 */
	public function setSameDayWithStringReturnsTrue() {
		$this->subject->setSameDay('foo bar');
		$this->assertTrue($this->subject->getSameDay());
	}

	/**
	 * @test
	 */
	public function setSameDayWithZeroReturnsFalse() {
		$this->subject->setSameDay(0);
		$this->assertFalse($this->subject->getSameDay());
	}

	/**
	 * @test
	 */
	public function getMultipleTimesInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getMultipleTimes()
		);
	}

	/**
	 * @test
	 */
	public function setMultipleTimesSetsMultipleTimes() {
		$instance = new ObjectStorage();
		$this->subject->setMultipleTimes($instance);

		$this->assertSame(
			$instance,
			$this->subject->getMultipleTimes()
		);
	}

	/**
	 * @test
	 */
	public function getXthInitiallyResultsInArrayWhereAllValuesAreZero() {
		$expectedArray = array(
			'first' => 0,
			'second' => 0,
			'third' => 0,
			'fourth' => 0,
			'fifth' => 0
		);

		$this->assertSame(
			$expectedArray,
			$this->subject->getXth()
		);
	}

	/**
	 * @test
	 */
	public function setXthWithZwentyThreeResultsInArrayWithDifferentValues() {
		$expectedArray = array(
			'first' => 1,
			'second' => 2,
			'third' => 4,
			'fourth' => 0,
			'fifth' => 16
		);
		$this->subject->setXth(23);

		$this->assertSame(
			$expectedArray,
			$this->subject->getXth()
		);
	}

	/**
	 * @test
	 */
	public function getWeekdayInitiallyResultsInArrayWhereAllValuesAreZero() {
		$expectedArray = array(
			'monday' => 0,
			'tuesday' => 0,
			'wednesday' => 0,
			'thursday' => 0,
			'friday' => 0,
			'saturday' => 0,
			'sunday' => 0
		);

		$this->assertSame(
			$expectedArray,
			$this->subject->getWeekday()
		);
	}

	/**
	 * @test
	 */
	public function setWeekdayWithEightySevenResultsInArrayWithDifferentValues() {
		$expectedArray = array(
			'monday' => 1,
			'tuesday' => 2,
			'wednesday' => 4,
			'thursday' => 0,
			'friday' => 16,
			'saturday' => 0,
			'sunday' => 64
		);
		$this->subject->setWeekday(87);

		$this->assertSame(
			$expectedArray,
			$this->subject->getWeekday()
		);
	}

	/**
	 * @test
	 */
	public function getDifferentTimesInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getDifferentTimes()
		);
	}

	/**
	 * @test
	 */
	public function setDifferentTimesSetsDifferentTimes() {
		$object = new Time();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDifferentTimes($objectStorage);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDifferentTimes()
		);
	}

	/**
	 * @test
	 */
	public function addDifferentTimeAddsOneDifferentTime() {
		$objectStorage = new ObjectStorage();
		$this->subject->setDifferentTimes($objectStorage);

		$object = new Time();
		$this->subject->addDifferentTime($object);

		$objectStorage->attach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDifferentTimes()
		);
	}

	/**
	 * @test
	 */
	public function removeDifferentTimeRemovesOneDifferentTime() {
		$object = new Time();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDifferentTimes($objectStorage);

		$this->subject->removeDifferentTime($object);
		$objectStorage->detach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDifferentTimes()
		);
	}

	/**
	 * @test
	 */
	public function getEachWeeksInitiallyReturnsZero() {
		$this->assertSame(
			0,
			$this->subject->getEachWeeks()
		);
	}

	/**
	 * @test
	 */
	public function setEachWeeksSetsEachWeeks() {
		$this->subject->setEachWeeks(123456);

		$this->assertSame(
			123456,
			$this->subject->getEachWeeks()
		);
	}

	/**
	 * @test
	 */
	public function getExceptionsInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getExceptions()
		);
	}

	/**
	 * @test
	 */
	public function setExceptionsSetsExceptions() {
		$object = new Exception();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setExceptions($objectStorage);

		$this->assertSame(
			$objectStorage,
			$this->subject->getExceptions()
		);
	}

	/**
	 * @test
	 */
	public function addExceptionAddsOneDifferentTime() {
		$objectStorage = new ObjectStorage();
		$this->subject->setExceptions($objectStorage);

		$object = new Exception();
		$this->subject->addException($object);

		$objectStorage->attach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getExceptions()
		);
	}

	/**
	 * @test
	 */
	public function removeExceptionRemovesOneException() {
		$object = new Exception();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setExceptions($objectStorage);

		$this->subject->removeException($object);
		$objectStorage->detach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getExceptions()
		);
	}

	/**
	 *
	 */
	public function getFutureExeptions() {
		// skip
	}

	/**
	 * @test
	 */
	public function getDetailInformationsInitiallyReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->subject->getDetailInformations()
		);
	}

	/**
	 * @test
	 */
	public function setDetailInformationsSetsDetailInformations() {
		$this->subject->setDetailInformations('foo bar');

		$this->assertSame(
			'foo bar',
			$this->subject->getDetailInformations()
		);
	}

	/**
	 * @test
	 */
	public function setDetailInformationsWithIntegerResultsInString() {
		$this->subject->setDetailInformations(123);
		$this->assertSame('123', $this->subject->getDetailInformations());
	}

	/**
	 * @test
	 */
	public function setDetailInformationsWithBooleanResultsInString() {
		$this->subject->setDetailInformations(TRUE);
		$this->assertSame('1', $this->subject->getDetailInformations());
	}

	/**
	 * @test
	 */
	public function getFreeEntryInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getFreeEntry()
		);
	}

	/**
	 * @test
	 */
	public function setFreeEntrySetsFreeEntry() {
		$this->subject->setFreeEntry(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getFreeEntry()
		);
	}

	/**
	 * @test
	 */
	public function setFreeEntryWithStringReturnsTrue() {
		$this->subject->setFreeEntry('foo bar');
		$this->assertTrue($this->subject->getFreeEntry());
	}

	/**
	 * @test
	 */
	public function setFreeEntryWithZeroReturnsFalse() {
		$this->subject->setFreeEntry(0);
		$this->assertFalse($this->subject->getFreeEntry());
	}

	/**
	 * @test
	 */
	public function getTicketLinkInitiallyReturnsNull() {
		$this->assertNull($this->subject->getTicketLink());
	}

	/**
	 * @test
	 */
	public function setTicketLinkSetsTicketLink() {
		$instance = new Link();
		$this->subject->setTicketLink($instance);

		$this->assertSame(
			$instance,
			$this->subject->getTicketLink()
		);
	}

	/**
	 * @test
	 */
	public function getCategoriesInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function setCategoriesSetsCategories() {
		$object = new Category();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setCategories($objectStorage);

		$this->assertSame(
			$objectStorage,
			$this->subject->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function addCategoryAddsOneDifferentTime() {
		$objectStorage = new ObjectStorage();
		$this->subject->setCategories($objectStorage);

		$object = new Category();
		$this->subject->addCategory($object);

		$objectStorage->attach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function removeCategoryRemovesOneCategory() {
		$object = new Category();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setCategories($objectStorage);

		$this->subject->removeCategory($object);
		$objectStorage->detach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getCategories()
		);
	}

	/**
	 * @test
	 */
	public function getDaysInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getDays()
		);
	}

	/**
	 * @test
	 */
	public function setDaysSetsDays() {
		$object = new Day();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDays($objectStorage);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDays()
		);
	}

	/**
	 * @test
	 */
	public function addDayAddsOneDifferentTime() {
		$objectStorage = new ObjectStorage();
		$this->subject->setDays($objectStorage);

		$object = new Day();
		$this->subject->addDay($object);

		$objectStorage->attach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDays()
		);
	}

	/**
	 * @test
	 */
	public function removeDayRemovesOneDay() {
		$object = new Day();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDays($objectStorage);

		$this->subject->removeDay($object);
		$objectStorage->detach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDays()
		);
	}

	/**
	 * @test
	 */
	public function getLocationInitiallyReturnsNull() {
		$this->assertNull($this->subject->getLocation());
	}

	/**
	 * @test
	 */
	public function setLocationSetsLocation() {
		$instance = new Location();
		$this->subject->setLocation($instance);

		$this->assertSame(
			$instance,
			$this->subject->getLocation()
		);
	}

	/**
	 * @test
	 */
	public function getOrganizerInitiallyReturnsNull() {
		$this->assertNull($this->subject->getOrganizer());
	}

	/**
	 * @test
	 */
	public function setOrganizerSetsOrganizer() {
		$instance = new Organizer();
		$this->subject->setOrganizer($instance);

		$this->assertSame(
			$instance,
			$this->subject->getOrganizer()
		);
	}

	/**
	 * @test
	 */
	public function getImagesInitiallyReturnsArray() {
		$this->assertEquals(
			array(),
			$this->subject->getImages()
		);
	}

	/**
	 * @test
	 */
	public function setImagesSetsImages() {
		$object = new Time();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setImages($objectStorage);

		$this->assertSame(
			array(0 => $object),
			$this->subject->getImages()
		);
	}

	/**
	 * @test
	 */
	public function getVideoLinkInitiallyReturnsNull() {
		$this->assertNull($this->subject->getVideoLink());
	}

	/**
	 * @test
	 */
	public function setVideoLinkSetsVideoLink() {
		$instance = new Link();
		$this->subject->setVideoLink($instance);

		$this->assertSame(
			$instance,
			$this->subject->getVideoLink()
		);
	}

	/**
	 * @test
	 */
	public function getDownloadLinksInitiallyReturnsObjectStorage() {
		$this->assertEquals(
			new ObjectStorage(),
			$this->subject->getDownloadLinks()
		);
	}

	/**
	 * @test
	 */
	public function setDownloadLinksSetsDownloadLinks() {
		$object = new Link();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDownloadLinks($objectStorage);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDownloadLinks()
		);
	}

	/**
	 * @test
	 */
	public function addDownloadLinkAddsOneDifferentTime() {
		$objectStorage = new ObjectStorage();
		$this->subject->setDownloadLinks($objectStorage);

		$object = new Link();
		$this->subject->addDownloadLink($object);

		$objectStorage->attach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDownloadLinks()
		);
	}

	/**
	 * @test
	 */
	public function removeDownloadLinkRemovesOneDownloadLink() {
		$object = new Link();
		$objectStorage = new ObjectStorage();
		$objectStorage->attach($object);
		$this->subject->setDownloadLinks($objectStorage);

		$this->subject->removeDownloadLink($object);
		$objectStorage->detach($object);

		$this->assertSame(
			$objectStorage,
			$this->subject->getDownloadLinks()
		);
	}

	/**
	 * @test
	 */
	public function getSuitabilityCultureInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getSuitabilityCulture()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityCultureSetsSuitabilityCulture() {
		$this->subject->setSuitabilityCulture(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getSuitabilityCulture()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityCultureWithStringReturnsTrue() {
		$this->subject->setSuitabilityCulture('foo bar');
		$this->assertTrue($this->subject->getSuitabilityCulture());
	}

	/**
	 * @test
	 */
	public function setSuitabilityCultureWithZeroReturnsFalse() {
		$this->subject->setSuitabilityCulture(0);
		$this->assertFalse($this->subject->getSuitabilityCulture());
	}

	/**
	 * @test
	 */
	public function getSuitabilityUserInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getSuitabilityUser()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityUserSetsSuitabilityUser() {
		$this->subject->setSuitabilityUser(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getSuitabilityUser()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityUserWithStringReturnsTrue() {
		$this->subject->setSuitabilityUser('foo bar');
		$this->assertTrue($this->subject->getSuitabilityUser());
	}

	/**
	 * @test
	 */
	public function setSuitabilityUserWithZeroReturnsFalse() {
		$this->subject->setSuitabilityUser(0);
		$this->assertFalse($this->subject->getSuitabilityUser());
	}

	/**
	 * @test
	 */
	public function getSuitabilityGroupsInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getSuitabilityGroups()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityGroupsSetsSuitabilityGroups() {
		$this->subject->setSuitabilityGroups(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getSuitabilityGroups()
		);
	}

	/**
	 * @test
	 */
	public function setSuitabilityGroupsWithStringReturnsTrue() {
		$this->subject->setSuitabilityGroups('foo bar');
		$this->assertTrue($this->subject->getSuitabilityGroups());
	}

	/**
	 * @test
	 */
	public function setSuitabilityGroupsWithZeroReturnsFalse() {
		$this->subject->setSuitabilityGroups(0);
		$this->assertFalse($this->subject->getSuitabilityGroups());
	}

	/**
	 * @test
	 */
	public function getFacebookInitiallyReturnsFalse() {
		$this->assertSame(
			FALSE,
			$this->subject->getFacebook()
		);
	}

	/**
	 * @test
	 */
	public function setFacebookSetsFacebook() {
		$this->subject->setFacebook(TRUE);
		$this->assertSame(
			TRUE,
			$this->subject->getFacebook()
		);
	}

	/**
	 * @test
	 */
	public function setFacebookWithStringReturnsTrue() {
		$this->subject->setFacebook('foo bar');
		$this->assertTrue($this->subject->getFacebook());
	}

	/**
	 * @test
	 */
	public function setFacebookWithZeroReturnsFalse() {
		$this->subject->setFacebook(0);
		$this->assertFalse($this->subject->getFacebook());
	}

	/**
	 * @test
	 */
	public function getReleaseDateInitiallyReturnsNull() {
		$this->assertNull($this->subject->getReleaseDate());
	}

	/**
	 * @test
	 */
	public function setReleaseDateSetsReleaseDate() {
		$instance = new Day();
		$this->subject->setReleaseDate($instance);

		$this->assertSame(
			$instance,
			$this->subject->getReleaseDate()
		);
	}

	/**
	 * @test
	 */
	public function getSocialTeaserInitiallyReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->subject->getSocialTeaser()
		);
	}

	/**
	 * @test
	 */
	public function setSocialTeaserSetsSocialTeaser() {
		$this->subject->setSocialTeaser('foo bar');

		$this->assertSame(
			'foo bar',
			$this->subject->getSocialTeaser()
		);
	}

	/**
	 * @test
	 */
	public function setSocialTeaserWithIntegerResultsInString() {
		$this->subject->setSocialTeaser(123);
		$this->assertSame('123', $this->subject->getSocialTeaser());
	}

	/**
	 * @test
	 */
	public function setSocialTeaserWithBooleanResultsInString() {
		$this->subject->setSocialTeaser(TRUE);
		$this->assertSame('1', $this->subject->getSocialTeaser());
	}

	/**
	 * @test
	 */
	public function getFacebookChannelInitiallyReturnsZero() {
		$this->assertSame(
			0,
			$this->subject->getFacebookChannel()
		);
	}

	/**
	 * @test
	 */
	public function setFacebookChannelSetsFacebookChannel() {
		$this->subject->setFacebookChannel(123456);

		$this->assertSame(
			123456,
			$this->subject->getFacebookChannel()
		);
	}

	/**
	 * @test
	 */
	public function getTheaterDetailsInitiallyReturnsEmptyString() {
		$this->assertSame(
			'',
			$this->subject->getTheaterDetails()
		);
	}

	/**
	 * @test
	 */
	public function setTheaterDetailsSetsTheaterDetails() {
		$this->subject->setTheaterDetails('foo bar');

		$this->assertSame(
			'foo bar',
			$this->subject->getTheaterDetails()
		);
	}

	/**
	 * @test
	 */
	public function setTheaterDetailsWithIntegerResultsInString() {
		$this->subject->setTheaterDetails(123);
		$this->assertSame('123', $this->subject->getTheaterDetails());
	}

	/**
	 * @test
	 */
	public function setTheaterDetailsWithBooleanResultsInString() {
		$this->subject->setTheaterDetails(TRUE);
		$this->assertSame('1', $this->subject->getTheaterDetails());
	}

	/**
	 * @test
	 */
	public function getDayInitiallyReturnsNull() {
		$this->assertNull($this->subject->getDay());
	}

	/**
	 * @test
	 */
	public function setDaySetsDay() {
		$instance = new Day();
		$this->subject->setDay($instance);

		$this->assertSame(
			$instance,
			$this->subject->getDay()
		);
	}



}