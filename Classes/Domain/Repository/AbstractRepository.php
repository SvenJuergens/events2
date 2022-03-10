<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/events2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Events2\Domain\Repository;

use JWeiland\Events2\Helper\OverlayHelper;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

/*
 * Abstract repository with helpful methods for all repos
 */
class AbstractRepository extends Repository
{
    protected OverlayHelper $overlayHelper;

    public function injectOverlayHelper(OverlayHelper $overlayHelper): void
    {
        $this->overlayHelper = $overlayHelper;
    }

    protected function getQueryBuilderForTable(string $table, string $alias, bool $useLangStrict = false): QueryBuilder
    {
        $extbaseQuery = $this->createQuery();

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        $queryBuilder
            ->from($table, $alias)
            ->andWhere(
                $queryBuilder->expr()->in(
                    $alias . '.pid',
                    $queryBuilder->createNamedParameter(
                        $extbaseQuery->getQuerySettings()->getStoragePageIds(),
                        Connection::PARAM_INT_ARRAY
                    )
                )
            );

        $this->overlayHelper->addWhereForOverlay($queryBuilder, $table, $alias, $useLangStrict);

        return $queryBuilder;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return GeneralUtility::makeInstance(ConnectionPool::class);
    }
}
