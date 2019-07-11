<?php

namespace FondOfSpryker\Zed\PriceListGui\Communication\Table;

use FondOfSpryker\Zed\PriceListGui\Dependency\Service\PriceListGuiToUtilDateTimeServiceInterface;
use Orm\Zed\PriceList\Persistence\Base\FosPriceListQuery;
use Orm\Zed\PriceList\Persistence\Map\FosPriceListTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class PriceListTable extends AbstractTable
{
    public const ACTIONS = 'Actions';
    public const URL_PARAMETER_ID_PRICE_LIST = 'id-price-list';
    public const URL_UPDATE_PRICE_LIST = '/price-list-gui/price-list/update';
    public const URL_DELETE_PRICE_LIST = '/price-list-gui/price-list/delete';

    /**
     * @var \FondOfSpryker\Zed\PriceListGui\Dependency\Service\PriceListGuiToUtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Orm\Zed\PriceList\Persistence\Base\FosPriceListQuery
     */
    protected $fosPriceListQuery;

    /**
     * @param \Orm\Zed\PriceList\Persistence\Base\FosPriceListQuery $fosPriceListQuery
     * @param \FondOfSpryker\Zed\PriceListGui\Dependency\Service\PriceListGuiToUtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(
        FosPriceListQuery $fosPriceListQuery,
        PriceListGuiToUtilDateTimeServiceInterface $utilDateTimeService
    ) {
        $this->fosPriceListQuery = $fosPriceListQuery;
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            FosPriceListTableMap::COL_ID_PRICE_LIST => '#',
            FosPriceListTableMap::COL_NAME => 'Name',
            FosPriceListTableMap::COL_CREATED_AT => 'Created At',
            FosPriceListTableMap::COL_UPDATED_AT => 'Updated At',
            static::ACTIONS => static::ACTIONS,
        ]);

        $config->addRawColumn(static::ACTIONS);

        $config->setSortable([
            FosPriceListTableMap::COL_UPDATED_AT,
            FosPriceListTableMap::COL_CREATED_AT,
            FosPriceListTableMap::COL_NAME,
        ]);

        $config->setSearchable([
            FosPriceListTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $results = [];
        $query = $this->fosPriceListQuery;
        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $queryResult) {
            $results[] = [
                FosPriceListTableMap::COL_ID_PRICE_LIST => $queryResult[FosPriceListTableMap::COL_ID_PRICE_LIST],
                FosPriceListTableMap::COL_NAME => $queryResult[FosPriceListTableMap::COL_NAME],
                FosPriceListTableMap::COL_CREATED_AT => $this->utilDateTimeService->formatDateTime(
                    $queryResult[FosPriceListTableMap::COL_CREATED_AT]
                ),
                FosPriceListTableMap::COL_UPDATED_AT => $this->utilDateTimeService->formatDateTime(
                    $queryResult[FosPriceListTableMap::COL_UPDATED_AT]
                ),
                self::ACTIONS => implode(' ', $this->createTableActions($queryResult)),
            ];
        }

        return $results;
    }

    /**
     * @param array $queryResult
     *
     * @return array
     */
    protected function createTableActions(array $queryResult): array
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_UPDATE_PRICE_LIST, [
                static::URL_PARAMETER_ID_PRICE_LIST => $queryResult[FosPriceListTableMap::COL_ID_PRICE_LIST],
            ]),
            'Edit'
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::URL_DELETE_PRICE_LIST, [
                static::URL_PARAMETER_ID_PRICE_LIST => $queryResult[FosPriceListTableMap::COL_ID_PRICE_LIST],
            ]),
            'Delete'
        );

        return $buttons;
    }
}
