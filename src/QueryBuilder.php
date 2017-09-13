<?php

namespace RoySailor\Restify;

use Illuminate\Support\Facades\DB;
use RoySailor\Restify\Exceptions\NoLegalFieldFoundException;

abstract class QueryBuilder
{

    protected $mapper;

    protected $tableName;

    protected $tableHandle;

    protected $fields;

    protected $filterables;

    protected $sortables;

    protected $searchables;

    protected $joins;

    public function __construct(RequestMapper $mapper)
    {

        $this->mapper = $mapper;

        $this->tableHandle = DB::table($this->tableName);

    }

    public function fetch(){

        $this->attachFields();
        $this->attachFilter();
        $this->attachSorts();
        $this->attachSearches();
        $this->attachJoins();

        //dd($this->tableHandle->toSql());

        return $this->tableHandle->paginate($this->mapper->getPerPage());

    }

    public function fetchJSON(){

        return response()->json($this->fetch());

    }

    protected function attachFields(){

        $fields = $this->mapper->getFields();

        if('*' == $fields){

            if(count($this->fields) > 0){

                $this->tableHandle->select(DB::raw(implode(',', $this->fields)));

            }

        } else{

            $fields = array_intersect($fields, $this->fields);

            if(0 == count($fields)){

                throw new NoLegalFieldFoundException();

            }

            $fieldString = implode(',', $fields);

            $this->tableHandle->select(DB::raw($fieldString));

        }

    }

    protected function attachFilter(){

        $filters = array_filter(
            $this->mapper->getFilters(),
            function ($key) {
                return in_array($key, $this->filterables);
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($filters as $field => $value){

            $this->tableHandle->where($field, '=', $value);

        }

    }

    protected function attachSorts(){

        $sorts = array_filter(
            $this->mapper->getSorts(),
            function ($key) {
                return in_array($key, $this->sortables);
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($sorts as $field => $order){

            $this->tableHandle->orderBy($field, $order);

        }

    }

    protected function attachSearches(){

        $this->tableHandle->where(function ($query) {

            $searchTerm = $this->mapper->getSearches();

            foreach ($this->searchables as $field){

                $query->orWhere($field, 'like', '%' . $searchTerm . '%');

            }

        });

    }

    protected function attachJoins(){

        foreach($this->joins as $k => $v){

            $leftTable = explode('.',$k);

            $rightTable = explode('.', $v);

            if(1 == count($leftTable)){

                $this->tableHandle->leftJoin($rightTable[0], $this->tableName . '.' . $leftTable[0], '=', $v);

                $this->tableHandle->addSelect($rightTable[0] . '.*');

            } else{

                $this->tableHandle->leftJoin($rightTable[0], $k, '=', $v);

                $this->tableHandle->addSelect($rightTable[0] . '.*');

            }

        }

    }

    /**
     * @param mixed $filterables
     */
    public function setFilterables($filterables)
    {
        $this->filterables = $filterables;
    }

    /**
     * @param mixed $sortables
     */
    public function setSortables($sortables)
    {
        $this->sortables = $sortables;
    }

    /**
     * @param mixed $searchables
     */
    public function setSearchables($searchables)
    {
        $this->searchables = $searchables;
    }

    /**
     * @param array $joins
     */
    public function setJoins($joins)
    {
        $this->joins = $joins;
    }



}