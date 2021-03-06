<?php

namespace JWeiland\Events2\ViewHelpers;

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
use JWeiland\Events2\Domain\Model\Event;
use JWeiland\Events2\Service\EventService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Get exceptions from an event to a specific date
 */
class GetExceptionsFromEventForSpecificDateViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('event', Event::class, 'Get the exceptions from event', true);
        $this->registerArgument('date', \DateTime::class, 'Get the exceptions from event to this specific date', true);
        $this->registerArgument('type', 'string', 'Get exceptions of specified type. remove, add, time or info. You can combine them with comma', false, '');
    }

    /**
     * Get exception from an event to a specific date
     *
     * @param array $arguments
     * @param \Closure $childClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $childClosure, RenderingContextInterface $renderingContext)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $eventService = $objectManager->get(EventService::class);

        return $eventService->getExceptionsForDate(
            $arguments['event'],
            $arguments['date'],
            $arguments['type']
        );
    }
}
