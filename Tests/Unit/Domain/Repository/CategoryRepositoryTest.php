<?php
/**
 ************************************************************************
 * Copyright (c) 2005-2019 Stefan (Muetze) Horst                        *
 ************************************************************************
 * I don't have the time to read through all the licences to find out   *
 * what the exactly say. But it's simple. It's free for non commercial  *
 * projects, but as soon as you make money with it, i want my share :-) *
 * (License : Free for non-commercial use)                              *
 ************************************************************************
 * Authors: Stefan (Muetze) Horst <muetze@DPSG-Liblar.de>               *
 ************************************************************************
 */

namespace ScoutNet\ShScoutnetWebservice\Tests\Unit\Domain\Repository;

use Prophecy\Argument;
use Prophecy\Prophet;
use ScoutNet\ShScoutnetWebservice\Domain\Model\Category;
use ScoutNet\ShScoutnetWebservice\Domain\Repository\CategoryRepository;
use ScoutNet\ShScoutnetWebservice\Helpers\JsonRPCClientHelper;
use ScoutNet\ShScoutnetWebservice\Tests\Unit\Fixtures\JsonRPCClientHelperFixture;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CategoryRepositoryTest extends UnitTestCase
{
    protected $categoryRepository;
    private $prophet;

    const CATEGORY_1_ARRAY = [
        'ID' => 1,
        'Text' => 'testCategory',
        'Selected' => 'no',
    ];
    const CATEGORY_2_ARRAY = [
        'ID' => 2,
        'Text' => 'testCategory2',
        'Selected' => 'yes',
    ];

    /**
     * @return \ScoutNet\ShScoutnetWebservice\Domain\Model\Category[]
     */
    private static function generateCategories(): array
    {
        $cat1 = new Category();
        $cat1->setUid(1);
        $cat1->setText('testCategory');
        $cat1->setAvailable(false);

        $cat2 = new Category();
        $cat2->setUid(2);
        $cat2->setText('testCategory2');
        $cat2->setAvailable(true);

        return [
            $cat1,
            $cat2,
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository();
        $this->prophet = new Prophet();
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testFindAll()
    {
        list($cat1, $cat2) = self::generateCategories();

        // mock json rpc client
        $sn = $this->prophet->prophesize(JsonRPCClientHelperFixture::class);
        $sn->get_data_by_global_id(null, ['categories' => ['all' => true]])->willReturn([
            // TODO: set correct ids like the API does
            [
                'type' => 'categorie',
                'content'=> self::CATEGORY_1_ARRAY,
            ],
            [
                'type' => 'categorie',
                'content'=> self::CATEGORY_2_ARRAY,
            ],
        ]);

        GeneralUtility::addInstance(JsonRPCClientHelper::class, $sn->reveal());

        // fix extension Configuration
        $em = $this->prophet->prophesize(ExtensionConfiguration::class);

        $em->get('sh_scoutnet_webservice')->willReturn(
            [
                'AES_key' => '12345678901234567890123456789012',
                'AES_iv' => '1234567890123456',
                'ScoutnetLoginPage' => 'https://www.scoutnet.de/auth',
                'ScoutnetProviderName' => 'unitTest',
            ]
        );

        GeneralUtility::addInstance(ExtensionConfiguration::class, $em->reveal());

        $this->categoryRepository->initializeObject();

        $act = $this->categoryRepository->findAll();

        self::assertEquals([$cat1, $cat2], $act);
    }

    public function dataProviderFindByUid()
    {
        list($cat1, $cat2) = self::generateCategories();

        return [
            'cat1' => [
                1,
                $cat1,
            ],
            'cat2' => [
                2,
                $cat2,
            ],
        ];
    }

    /**
     * @param $uid
     * @param $exp
     *
     * @dataProvider dataProviderFindByUid
     */
    public function testFindByUid($uid, $exp)
    {
        // mok json rpc client
        $sn = $this->prophet->prophesize(JsonRPCClientHelperFixture::class);
        $sn->get_data_by_global_id(null, Argument::any())->will(
            function ($args) {
                $req = $args[1];

                $uid = $req['categories']['uid'];

                if ($uid == 1) {
                    $cat = self::CATEGORY_1_ARRAY;
                } elseif ($uid == 2) {
                    $cat = self::CATEGORY_2_ARRAY;
                }

                return [[
                    'type' => 'categorie',
                    'content'=> $cat,
                ]];
            }
        );

        GeneralUtility::addInstance(JsonRPCClientHelper::class, $sn->reveal());

        // fix extension Configuration
        $em = $this->prophet->prophesize(ExtensionConfiguration::class);

        $em->get('sh_scoutnet_webservice')->willReturn(
            [
                'AES_key' => '12345678901234567890123456789012',
                'AES_iv' => '1234567890123456',
                'ScoutnetLoginPage' => 'https://www.scoutnet.de/auth',
                'ScoutnetProviderName' => 'unitTest',
            ]
        );

        GeneralUtility::addInstance(ExtensionConfiguration::class, $em->reveal());

        $this->categoryRepository->initializeObject();

        $act = $this->categoryRepository->findByUid($uid);

        self::assertEquals($exp, $act);
    }

    public function dataProviderConvertToCategory()
    {
        list($cat1, $cat2) = self::generateCategories();

        return [
            'cat1' => [
                self::CATEGORY_1_ARRAY,
                $cat1,
            ],
            'cat2' => [
                self::CATEGORY_2_ARRAY,
                $cat2,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderConvertToCategory
     *
     * @param array                                                $test
     * @param \ScoutNet\ShScoutnetWebservice\Domain\Model\Category $exp
     */
    public function testConvertToCategory(array $test, Category $exp)
    {
        $act = $this->categoryRepository->convertToCategory($test);

        self::assertEquals($exp, $act);
    }

    public function testGetAllCategoriesForStructureAndEvent()
    {
    }
}
