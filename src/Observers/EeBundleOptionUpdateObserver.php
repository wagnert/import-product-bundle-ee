<?php

/**
 * TechDivision\Import\Product\Bundle\Ee\Observers\EeBundleOptionUpdateObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Ee\Observers;

use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Bundle\Observers\BundleOptionUpdateObserver;
use TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface;
use TechDivision\Import\Product\Bundle\Ee\Actions\SequenceProductBundleOptionActionInterface;

/**
 * Oberserver that provides functionality for the bundle option add/update operation for the
 * Magento 2 EE edition.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle-ee
 * @link      http://www.techdivision.com
 */
class EeBundleOptionUpdateObserver extends BundleOptionUpdateObserver
{

    /**
     * The sequence product bundle option action instance.
     *
     * @var \TechDivision\Import\Product\Bundle\Ee\Actions\SequenceProductBundleOptionActionInterface
     */
    protected $sequenceProductBundleOptionAction;

    /**
     * Initialize the observer with the passed product bundle processor instance.
     *
     * @param \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface              $productBundleProcessor            The product bundle processor instance
     * @param \TechDivision\Import\Product\Bundle\Ee\Actions\SequenceProductBundleOptionActionInterface $sequenceProductBundleOptionAction The action instance
     */
    public function __construct(
        ProductBundleProcessorInterface $productBundleProcessor,
        SequenceProductBundleOptionActionInterface $sequenceProductBundleOptionAction
    ) {

        // initialize the parent instance
        parent::__construct($productBundleProcessor);

        // set the passed sequence product bundle option action instance
        $this->sequenceProductBundleOptionAction = $sequenceProductBundleOptionAction;
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // prepare the attributes
        $attr = parent::prepareAttributes();

        // query whether or not, we found a new product bundle option
        if ($attr[EntityStatus::MEMBER_NAME] === EntityStatus::STATUS_CREATE) {
            $attr[MemberNames::OPTION_ID] = $this->nextIdentifier();
        }

        // return the attributes
        return $attr;
    }

    /**
     * Returns the sequence product bundle option action instance.
     *
     * @return \TechDivision\Import\Product\Bundle\Ee\Actions\SequenceProductBundleOptionActionInterface The action instance
     */
    protected function getSequenceProductBundleOptionAction()
    {
        return $this->sequenceProductBundleOptionAction;
    }

    /**
     * Returns the next available product bundle option ID.
     *
     * @return integer The next available product bundle option ID
     */
    protected function nextIdentifier()
    {
        return $this->getSequenceProductBundleOptionAction()->nextIdentifier();
    }

    /**
     * Return the row ID for the passed SKU.
     *
     * @param string $sku The SKU to return the row ID for
     *
     * @return integer The mapped row ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSku($sku)
    {
        return $this->getSubject()->mapSkuToRowId($sku);
    }
}
