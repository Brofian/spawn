
use \webu\system\Core\Base\Database\Query\QueryBuilder;
use \webu\system\Core\Base\Database\Query\Types\QuerySelect;
use \webu\system\Core\Base\Database\DatabaseConnection;

$dbconnection = new DatabaseConnection(hier am besten die verbindung nutzen, die vom framework übergeben wird);

$queryBuilder = new QueryBuilder();
$select =  $queryBuilder->select('*')
                        ->from('')
                        ->join('andereTabelle', 'Spalte1', 'Spalte2', QuerySelect::LEFT_JOIN)
                        ->join('dritteTabelle', 'Spalte1', 'Spalte3')
                        ->where('Spalte', 'Wert')
                        ->where('Spalte2', ':key1') //you can use ":key" or "?"
                        ->orderby('Spalte', false)
                        ->limit(5,10);

$return =   $select     ->bindParam(':key1', 34) //you can use ->bindValue('value') for "?"
                        ->execute($dbconnection);
                        