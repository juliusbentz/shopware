<?php declare(strict_types=1);

namespace Shopware\AreaCountryState\Searcher;

use Doctrine\DBAL\Connection;
use Shopware\Api\Search\Criteria;
use Shopware\Api\Search\Parser\SqlParser;
use Shopware\Api\Search\QueryBuilder;
use Shopware\Api\Search\Searcher;
use Shopware\Api\Search\SearchResultInterface;
use Shopware\Api\Search\UuidSearchResult;
use Shopware\AreaCountryState\Factory\AreaCountryStateBasicFactory;
use Shopware\AreaCountryState\Reader\AreaCountryStateBasicReader;
use Shopware\Context\Struct\TranslationContext;

class AreaCountryStateSearcher extends Searcher
{
    /**
     * @var AreaCountryStateBasicFactory
     */
    private $factory;

    /**
     * @var AreaCountryStateBasicReader
     */
    private $reader;

    public function __construct(Connection $connection, SqlParser $parser, AreaCountryStateBasicFactory $factory, AreaCountryStateBasicReader $reader)
    {
        parent::__construct($connection, $parser);
        $this->factory = $factory;
        $this->reader = $reader;
    }

    protected function createQuery(Criteria $criteria, TranslationContext $context): QueryBuilder
    {
        return $this->factory->createSearchQuery($criteria, $context);
    }

    protected function load(UuidSearchResult $uuidResult, Criteria $criteria, TranslationContext $context): SearchResultInterface
    {
        $collection = $this->reader->readBasic($uuidResult->getUuids(), $context);

        $result = new AreaCountryStateSearchResult($collection->getElements());

        $result->setTotal($uuidResult->getTotal());
        $result->setCriteria($criteria);
        $result->setContext($context);

        return $result;
    }
}