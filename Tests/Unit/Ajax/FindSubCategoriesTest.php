<?php

namespace JWeiland\Events2\Tests\Unit\Ajax;

/*
 * This file is part of the TYPO3 CMS project.
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
use JWeiland\Events2\Ajax\FindSubCategories;
use JWeiland\Events2\Domain\Model\Category;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;

/**
 * Test case.
 *
 * @author Stefan Froemken <projects@jweiland.net>
 */
class FindSubCategoriesTest extends UnitTestCase
{
    /**
     * @var \JWeiland\Events2\Ajax\FindSubCategories
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new FindSubCategories();
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * this test also tests the protected method reduceCategoryData.
     *
     * @test
     */
    public function processAjaxRequest()
    {
        $categories = array();
        $category1 = new Category();
        $category1->_setProperty('uid', 123);
        $category1->setTitle('BMW');
        $category1->setDescription('foo');
        $category1->setIcon('fileadmin/img1.jpg');
        $categories[] = $category1;
        $category2 = new Category();
        $category2->_setProperty('uid', 456);
        $category2->setTitle('Audi');
        $category1->setDescription('bar');
        $category1->setIcon('fileadmin/img2.jpg');
        $categories[] = $category2;

        /* @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface $categories */
        $queryResult = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\QueryResult', array('dummy'), array(new Query('fooBarType')));
        $queryResult->_set('queryResult', $categories);

        /** @var \JWeiland\Events2\Domain\Repository\CategoryRepository|\PHPUnit_Framework_MockObject_MockObject $categoryRepository */
        $categoryRepository = $this->getMock('JWeiland\\Events2\\Domain\\Repository\\CategoryRepository', array('getSubCategories'), array(), '', false);
        $categoryRepository->expects($this->once())->method('getSubCategories')->with(284)->will($this->returnValue($queryResult));

        $this->subject->injectCategoryRepository($categoryRepository);

        $this->assertSame(
            '{"123":"BMW","456":"Audi"}',
            $this->subject->processAjaxRequest(array('category' => '284')) // test if case to int works
        );
    }
}
