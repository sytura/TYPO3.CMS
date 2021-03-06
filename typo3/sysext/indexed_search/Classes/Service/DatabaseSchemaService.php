<?php
namespace TYPO3\CMS\IndexedSearch\Service;

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
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This service provides the mysql specific changes of the schema definition
 */
class DatabaseSchemaService
{
    /**
     * A slot method to inject the required mysql fulltext definition
     * to schema migration
     *
     * @param array $sqlString
     * @return array
     */
    public function addMysqlFulltextIndex(array $sqlString)
    {
        $useMysqlFulltext = (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('indexed_search', 'useMysqlFulltext');
        if ($useMysqlFulltext) {
            // @todo: With MySQL 5.7 fulltext index on InnoDB is possible, check for that and keep inno if so.
            $sqlString[] = 'CREATE TABLE index_fulltext ('
                . LF . 'fulltextdata mediumtext,'
                . LF . 'metaphonedata mediumtext,'
                . LF . 'FULLTEXT fulltextdata (fulltextdata),'
                . LF . 'FULLTEXT metaphonedata (metaphonedata)'
                . LF . ') ENGINE=MyISAM;';
        }
        return [$sqlString];
    }
}
