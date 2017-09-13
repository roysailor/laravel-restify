<?php
return <<<HTML
<?php
namespace {$rootNameSpace}RestModels;

use RoySailor\Restify\QueryBuilder;
use RoySailor\Restify\RequestMapper;

class {$className} extends QueryBuilder
{

    protected \$tableName = '{$tableName}';

    protected \$filterables = [
      
    ];

    protected \$sortables = [
      
    ];

    protected \$searchables = [
       
    ];

    protected \$joins = [

    ];

    protected \$fields = [

    ];

    public function __construct(RequestMapper \$mapper)
    {
        parent::__construct(\$mapper);
    }

}
HTML;
