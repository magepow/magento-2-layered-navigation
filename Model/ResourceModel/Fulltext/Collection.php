<?php
/**
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 2:46 PM
 *  * @@Modify Date: 4/16/19 2:46 PM
 *
 *
 */

namespace Magepow\Layerednav\Model\ResourceModel\Fulltext;

use Magento\CatalogSearch\Model\Search\RequestGenerator;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;
use Magento\Framework\Search\Response\QueryResponse;
use Magento\Framework\Search\Request\EmptyRequestDataException;
use Magento\Framework\Search\Request\NonExistingRequestNameException;
use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ObjectManager;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{

    protected $queryResponse;

    protected $queryFactory = null;

    private $requestBuilder;

    private $searchEngine;

    private $queryText;

    private $order = null;

    private $searchRequestName;

    private $temporaryStorageFactory;

    private $search;

    private $searchCriteriaBuilder;

    private $searchResult;

    private $searchResultFactory;

    private $filterBuilder;

    public $collectionClone = null;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Catalog\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\Indexer\Product\Flat\State $catalogProductFlatState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\Product\OptionFactory $productOptionFactory,
        \Magento\Catalog\Model\ResourceModel\Url $catalogUrl,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Customer\Api\GroupManagementInterface $groupManagement,
        \Magento\Search\Model\QueryFactory $catalogSearchData,
        \Magento\Framework\Search\Request\Builder $requestBuilder,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        $searchRequestName = 'catalog_view_container',
        SearchResultFactory $searchResultFactory = null
    )
    {
        $this->queryFactory = $catalogSearchData;
        if ($searchResultFactory === null) {
            $this->searchResultFactory = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\Api\Search\SearchResultFactory');
        }
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $storeManager,
            $moduleManager,
            $catalogProductFlatState,
            $scopeConfig,
            $productOptionFactory,
            $catalogUrl,
            $localeDate,
            $customerSession,
            $dateTime,
            $groupManagement,
            $connection
        );
        $this->requestBuilder          = $requestBuilder;
        $this->searchEngine            = $searchEngine;
        $this->temporaryStorageFactory = $temporaryStorageFactory;
        $this->searchRequestName       = $searchRequestName;
    }

    private function getSearch()
    {
        if ($this->search === null) {
            $this->search = ObjectManager::getInstance()->get('\Magento\Search\Api\SearchInterface');
        }

        return $this->search;
    }

    public function setSearch(\Magento\Search\Api\SearchInterface $object)
    {
        $this->search = $object;
    }

    public function getSearchCriteriaBuilder()
    {
        if ($this->searchCriteriaBuilder === null) {
            $this->searchCriteriaBuilder = ObjectManager::getInstance()
                ->get('\Magepow\Layerednav\Model\Search\SearchCriteriaBuilder');
        }

        return $this->searchCriteriaBuilder;
    }

    public function getCollectionClone()
    {
        $collectionClone = clone $this->collectionClone;
        $collectionClone->setSearchCriteriaBuilder($this->collectionClone->getSearchCriteriaBuilder()->cloneObject());

        return $collectionClone;
    }

    public function cloneObject()
    {
        if ($this->collectionClone === null) {
            $this->collectionClone = clone $this;
            $this->collectionClone->setSearchCriteriaBuilder($this->searchCriteriaBuilder->cloneObject());
        }

        return $this;
    }

    public function removeAttributeSearch($attributeCode)
    {
        if(is_array($attributeCode)){
            foreach($attributeCode as $attCode){
                $this->searchCriteriaBuilder->removeFilter($attCode);
            }
        } else {
            $this->searchCriteriaBuilder->removeFilter($attributeCode);
        }

        $this->_isFiltersRendered = false;

        return $this->loadWithFilter();
    }

    public function setSearchCriteriaBuilder(\Magepow\Layerednav\Model\Search\SearchCriteriaBuilder $object)
    {
        $this->searchCriteriaBuilder = $object;
    }

    private function getFilterBuilder()
    {
        if ($this->filterBuilder === null) {
            $this->filterBuilder = ObjectManager::getInstance()->get('\Magento\Framework\Api\FilterBuilder');
        }

        return $this->filterBuilder;
    }

    public function setFilterBuilder(\Magento\Framework\Api\FilterBuilder $object)
    {
        $this->filterBuilder = $object;
    }

    public function addFieldToFilter($field, $condition = null)
    {
        if ($this->searchResult !== null) {
            throw new \RuntimeException('Illegal state');
        }

        $this->getSearchCriteriaBuilder();
        $this->getFilterBuilder();
        if (!empty($condition['ln_filter'])) {
            $this->filterBuilder->setField($field);
            $this->filterBuilder->setValue($condition['ln_filter']);
            $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        } elseif (!is_array($condition) || !in_array(key($condition), ['from', 'to'])) {
            $this->filterBuilder->setField($field);
            $this->filterBuilder->setValue($condition);
            $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        } else {
            if (!empty($condition['from'])) {
                $this->filterBuilder->setField("{$field}.from");
                $this->filterBuilder->setValue($condition['from']);
                $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
            }
            if (!empty($condition['to'])) {
                $this->filterBuilder->setField("{$field}.to");
                $this->filterBuilder->setValue($condition['to']);
                $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
            }
        }

        return $this;
    }

    public function addSearchFilter($query)
    {
        $this->queryText = trim($this->queryText . ' ' . $query);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        $this->getSearchCriteriaBuilder();
        $this->getFilterBuilder();
        $this->getSearch();

        if ($this->queryText) {
            $this->filterBuilder->setField('search_term');
            $this->filterBuilder->setValue($this->queryText);
            $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        }

        $priceRangeCalculation = $this->_scopeConfig->getValue(
            \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory::XML_PATH_RANGE_CALCULATION,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($priceRangeCalculation) {
            $this->filterBuilder->setField('price_dynamic_algorithm');
            $this->filterBuilder->setValue($priceRangeCalculation);
            $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        }

        $this->cloneObject();

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchCriteria->setRequestName($this->searchRequestName);

        try {
            $this->searchResult = $this->getSearch()->search($searchCriteria);
        } catch (EmptyRequestDataException $e) {
            /** @var \Magento\Framework\Api\Search\SearchResultInterface $searchResult */
            $this->searchResult = $this->searchResultFactory->create()->setItems([]);
        } catch (NonExistingRequestNameException $e) {
            $this->_logger->error($e->getMessage());
            throw new LocalizedException(__('Sorry, something went wrong. You can find out more in the error log.'));
        }

        $temporaryStorage = $this->temporaryStorageFactory->create();
        $table            = $temporaryStorage->storeApiDocuments($this->searchResult->getItems());

        $this->getSelect()->joinInner(
            [
                'search_result' => $table->getName(),
            ],
            'e.entity_id = search_result.' . TemporaryStorage::FIELD_ENTITY_ID,
            []
        );

        $this->_totalRecords = $this->searchResult->getTotalCount();

        if ($this->order && 'relevance' === $this->order['field']) {
            $this->getSelect()->order('search_result.' . TemporaryStorage::FIELD_SCORE . ' ' . $this->order['dir']);
        }

        return parent::_renderFiltersBefore();
    }

    protected function _renderFilters()
    {
        $this->_filters = [];

        return parent::_renderFilters();
    }

    public function setOrder($attribute, $dir = Select::SQL_DESC)
    {
        $this->order = ['field' => $attribute, 'dir' => $dir];
        if ($attribute != 'relevance') {
            parent::setOrder($attribute, $dir);
        }

        return $this;
    }

    public function setGeneralDefaultQuery()
    {
        return $this;
    }

    public function getFacetedData($field)
    {
        $this->_renderFilters();
        $result = [];

        $aggregations = $this->searchResult->getAggregations();
        // This behavior is for case with empty object when we got EmptyRequestDataException
        if (null !== $aggregations) {
            $bucket = $aggregations->getBucket($field . RequestGenerator::BUCKET_SUFFIX);
            if ($bucket) {
                foreach ($bucket->getValues() as $value) {
                    $metrics                   = $value->getMetrics();
                    $result[$metrics['value']] = $metrics;
                }
            } else {
                throw new StateException(__('Bucket does not exist'));
            }
        }

        return $result;
    }

    public function addCategoryFilter(\Magento\Catalog\Model\Category $category)
    {
        $this->addFieldToFilter('category_ids', $category->getId());

        return parent::addCategoryFilter($category);
    }

    public function setVisibility($visibility)
    {
        $this->addFieldToFilter('visibility', $visibility);

        return parent::setVisibility($visibility);
    }
}
