<?php

namespace FondOfSpryker\Zed\PriceListGui\Communication\Form\DataProvider;

use FondOfSpryker\Zed\PriceListGui\Dependency\Facade\PriceListGuiToPriceListFacadeInterface;
use Generated\Shared\Transfer\PriceListTransfer;

class PriceListFormDataProvider
{
    /**
     * @var \FondOfSpryker\Zed\PriceListGui\Dependency\Facade\PriceListGuiToPriceListFacadeInterface
     */
    protected $priceListFacade;

    /**
     * @param \FondOfSpryker\Zed\PriceListGui\Dependency\Facade\PriceListGuiToPriceListFacadeInterface $priceListFacade
     */
    public function __construct(PriceListGuiToPriceListFacadeInterface $priceListFacade)
    {
        $this->priceListFacade = $priceListFacade;
    }

    /**
     * @param int $idPriceList
     *
     * @return array
     */
    public function getData(int $idPriceList): array
    {
        $priceListTransfer = (new PriceListTransfer())->setIdPriceList($idPriceList);

        $priceListTransfer = $this->priceListFacade->findPriceListById($priceListTransfer);

        if ($priceListTransfer === null) {
            return [];
        }

        return $priceListTransfer->toArray();
    }
}
