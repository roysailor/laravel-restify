<?php

namespace RoySailor\Restify;

use Illuminate\Http\Request;

class RequestMapper
{

    protected $request;

    protected $fields;

    protected $filters;

    protected $sorts;

    protected $searches;

    protected $page;

    protected $perPage;

    protected $embeds;

    public function __construct(Request $request)
    {

        $this->request = $request;

        $this->setFields();

        $this->setFilters();

        $this->setSorts();

        $this->setSearches();

        $this->setEmbeds();

        $this->setPage();

        $this->setPerPage();

    }

    public function setFields(){

        if($this->request->has('fields')){

            $fieldValue = $this->request->input('fields');

            $fieldValue = explode(',', $fieldValue);

            $this->fields = $fieldValue;

        } else{

            $this->fields = '*';

        }

    }

    public function getFields(){

        return $this->fields;

    }

    public function setFilters(){

        $this->filters = $this->request->except(['sort', 'q', 'page', 'per_page', 'embed', 'fields']);

    }

    public function getFilters(){

        return $this->filters;

    }

    public function setSorts(){

        $this->sorts = [];

        if($this->request->has('sort')){

            $sortValue = $this->request->input('sort');

            $sortValue = explode(',', $sortValue);

            foreach ($sortValue as $sort){

                $order = substr($sort,0, 1);

                if("-" == $order){

                    $fieldName = substr($sort, 1, strlen($sort) - 1);

                    $this->sorts[$fieldName] = 'desc';

                } else{

                    $this->sorts[$sort] = 'asc';

                }

            }
        } else{

            $this->sorts = [];

        }


    }

    public function getSorts(){

        return $this->sorts;

    }

    public function setSearches(){

        $this->searches = $this->request->has('q') ? $this->request->input('q') : null;

    }

    public function getSearches(){

        return $this->searches;

    }

    public function setEmbeds(){

        if($this->request->has('embed')){

            $embedValue = $this->request->input('embed');

            $embedValue = explode(',', $embedValue);

            $this->embeds = $embedValue;

        } else{

            $this->embeds = [];

        }

    }

    public function getEmbeds(){

        return $this->embeds;

    }

    public function setPage(){

        $this->page = $this->request->has('page') ? $this->request->input('page') : 1;

    }

    public function getPage(){

       return $this->page;

    }

    public function setPerPage(){

        $this->perPage = $this->request->has('per_page') ? $this->request->input('per_page') : 10;

    }

    public function getPerPage(){

        return $this->perPage;

    }

}