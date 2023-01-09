<?php
namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2023 André Flemming <andre@scoutnet.de>, ScoutNet
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use ScoutNet\ShScoutnetWebservice\Domain\Model\Event;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
class EventTest extends UnitTestCase {
    private function dataproviderEventStart() {

    }

    /**
     * @test
     * @return void
     */
    public function eventStartDateReturns()
    {
        $startDate = \DateTime::createFromFormat("U", "703382238");

        $testEntity = new Event();
        $testEntity->setStartDate($startDate);
        $testEntity->setStartTime($startDate->format("H:i:s"));

        $this->assertEquals($startDate, $testEntity->getStart(), "test getStart()");
        $this->assertEquals($startDate, $testEntity->getStartTimestamp(), "test getStartTimestamp()");
        $this->assertEquals($startDate->format("H:i:s"), $testEntity->getStartTime(), "test getStartTime()");
        $this->assertEquals($startDate->modify("midnight"), $testEntity->getStartDate(), "test getStartDate()");
    }

    /**
     * @test
     * @return void
     */
    public function eventEndDateReturns()
    {
        $endDate = \DateTime::createFromFormat("U", "1673303929");

        $testEntity = new Event();
        $testEntity->setEndDate($endDate);
        $testEntity->setEndTime($endDate->format("H:i:s"));

        $this->assertEquals($endDate, $testEntity->getEnd(), "test getEnd()");
        $this->assertEquals($endDate, $testEntity->getEndTimestamp(), "test getEndTimestamp()");
        $this->assertEquals($endDate->format("H:i:s"), $testEntity->getEndTime(), "test getEndTime()");
        $this->assertEquals($endDate->modify("midnight"), $testEntity->getEndDate(), "test getEndDate()");
    }
}