<?php

namespace FondOfSpryker\Zed\PriceListGui\Communication;

use FondOfSpryker\Zed\PriceListGui\Communication\Form\DataProvider\PriceListFormDataProvider;
use FondOfSpryker\Zed\PriceListGui\Communication\Form\PriceListForm;
use FondOfSpryker\Zed\PriceListGui\Communication\Table\PriceListTable;
use FondOfSpryker\Zed\PriceListGui\Dependency\Facade\PriceListGuiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListGui\Dependency\Service\PriceListGuiToUtilDateTimeServiceInterface;
use FondOfSpryker\Zed\PriceListGui\PriceListGuiDependencyProvider;
use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \FondOfSpryker\Zed\PriceListGui\PriceListGuiConfig getConfig()
 */
class PriceListGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \FondOfSpryker\Zed\PriceListGui\Communication\Table\PriceListTable
     */
    public function createPriceListTable(): PriceListTable
    {
        return new PriceListTable($this->getPriceListPropelQuery(), $this->getUtilDateTimeService());
    }

    /**
     * @return \Orm\Zed\PriceList\Persistence\FosPriceListQuery
     */
    protected function getPriceListPropelQuery(): FosPriceListQuery
    {
        return $this->getProvidedDependency(PriceListGuiDependencyProvider::PROPEL_QUERY_FOS_PRICE_LIST);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListGui\Dependency\Facade\PriceListGuiToPriceListFacadeInterface
     */
    public function getPriceListFacade(): PriceListGuiToPriceListFacadeInterface
    {
        return $this->getProvidedDependency(PriceListGuiDependencyProvider::FACADE_PRICE_LIST);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListGui\Communication\Form\DataProvider\PriceListFormDataProvider
     */
    public function createPriceListFormDataProvider(): PriceListFormDataProvider
    {
        return new PriceListFormDataProvider($this->getPriceListFacade());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPriceListForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(PriceListForm::class, $data, $options);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListGui\Dependency\Service\PriceListGuiToUtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService(): PriceListGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(PriceListGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }
}
