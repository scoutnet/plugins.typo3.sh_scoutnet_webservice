<?php
namespace ScoutNet\ShScoutnetWebservice\Tests\Acceptance\Backend;

use ScoutNet\ShScoutnetWebservice\Tests\Acceptance\Support\BackendTester;
use TYPO3\TestingFramework\Core\Acceptance\Helper\Topbar;

/**
 * Tests the sh_scoutnet_webservice backend module can be loaded
 */
class ModuleCest
{
    /**
     * @param BackendTester $I
     */
    public function _before(BackendTester $I)
    {
//        $I->useExistingSession('admin');
    }

    /**
     * @param BackendTester $I
     */
    public function dummyTest(BackendTester $I)
    {
    }

}
