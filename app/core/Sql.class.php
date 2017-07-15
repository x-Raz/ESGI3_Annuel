<?php

class Sql extends Model
{
    const ID = 'id';
    const PREFIX_ID = 'id_';
    const DEFAULT_LIMIT = 1;
    const DEFAULT_OFFSET = 0;
    const TABLE = '';
    private $pdo;
    private $query;
    private $table;
    private $data = [];
    private $condition = ' WHERE 1 = 1 ';
    private $limit;
    private $order;

    /**
     * @param mixed $data
     * set Instance of PDO
     * set Name of the table
     * called parent method
    */
    function __construct($data = '')
    {
        $this->pdo = Db::getInstance();
        $this->table = lcfirst(get_called_class());
        parent::__construct($data);
    }

    /**
     * Set class object with the foreign id of the table called
     * @param string $table
     * @var string $id 'id_tableName'
     * @var object of the table called -> populate by id
     * set current object with the populate class
     * unset foreign key of the current object
     * how to use : see parent method __call($method,$arguments)
     */
    protected function queryBelongsTo($table){
        $id = self::PREFIX_FOREIGN.$table;
        $class = new $table();
        $class->populate(['id' => $this->$id()]);
        $this->$table = $class;
        unset($this->$id);
    }

    /**
     * set array with multiple class of the table called
     * @param string $table
     * @var string $destinationTable contain the name table on plural
     * @var string $id 'id_currentTableName'
     * @var object $class of the table called
     * @var array $class -> fill by id from getAll
     * set $destinationTable with the array of $class
     * how to use : see parent method __call($method,$arguments)
     */
    protected function queryHasMany($table){
        $destinationTable = Helpers::renameValuePlural($table);
        $id = self::PREFIX_FOREIGN.$this->table;
        $class = new $table();
        $listItem = $class->getAll([$id => $this->id]);
        foreach ($listItem as $item) {
            $this->$destinationTable[] = $item;
        }
    }

    /**
     * set array with multiple class of the table called
     * @param string $table
     * @var string $destinationTable contain the name table on plural
     * @var array $array contain both tableName -> sorted
     * @var string $joinTable contain the name of the joinTable
     * @var object $classJoin -> populate by foreign id of this table
     * @var array $tmpStock -> fill by foreign id of this table from getAll
     * @var string $id 'id_tableName'
     * @var object $class -> populate by id of the current tmp $id
     * set $destinationTable with the array of $class
     * how to use : see parent method __call($method,$arguments)
     */
    protected function queryManyMany($table){
        $destinationTable = Helpers::renameValuePlural($table);
        $array = [ucfirst($this->table), ucfirst($table)];
        sort($array);
        $joinTable = implode('_', $array);
        $classJoin = new $joinTable();

        $tmpStock = $classJoin->getAll([self::PREFIX_FOREIGN.$this->table => $this->id]);

        foreach ($tmpStock as $tmp) {
            $id = self::PREFIX_FOREIGN.$table;
            $class = new $table();
            $class->populate([self::ID => $tmp->$id]);
            $this->$destinationTable[] = $class;
        }
    }

    /**
     * @param array $condition
     * Execute SQL query on the current object with condition set
     * @return int number of occurrence
     * how to use : $count = $class->count()
     */
    public function count(array $condition = [])
    {
        $this->where($condition);
        $this->query = $this->pdo->prepare('SELECT count(*) as count FROM ' . $this->table . $this->condition);
        $this->query->execute($this->data);
        return $this->query->fetch(PDO::FETCH_ASSOC)['count'];
    }

    /**
     * @param array $condition
     * @param array $limitQuery
     * @param array $orderQuery
     * Execute SQL SELECT (multiple) query on the current object with condition & order & limit set
     * @return array $listObject with all object occurence getted on the sql method
     * how to use : $listClass = $class->getAll();
     */
    public function getAll(array $condition = [], array $limitQuery = [], array $orderQuery = [])
    {
        $this->where($condition);
        $this->ordonate($orderQuery);
        $this->limitate($limitQuery);
        $this->query = $this->pdo->prepare('SELECT * FROM ' . $this->table . $this->condition . $this->order . $this->limit);
        $this->query->execute($this->data);
        $this->query->setFetchMode(PDO::FETCH_CLASS, ucfirst($this->table));
        $listObject = $this->query->fetchAll();
        return $listObject;
    }

    /**
     * @param array $condition
     * Execute SQL SELECT (one) query on the current object with condition set
     * set current object with the toClass Method
     * how to use : $class->populate(['id' => 1]);
     */
    public function populate(array $condition = [])
    {
        $this->where($condition);
        $this->query = $this->pdo->prepare('SELECT * FROM ' . $this->table . $this->condition);
        $this->query->execute($this->data);
        $data = $this->query->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->toClass($data);
        }
    }

    /**
     * set data with array from toArray method
     * if id index if empty -> save
     * else update
     * then Execute query prepared
     * @return int $lastInsertId | null
     */
    public function save()
    {
        $this->data = $this->toArray();
        if (empty($this->id)) {
            unset($this->data[self::ID]);
            $this->insert();
        } else {
            $this->update();
        }
        try {
            $this->query->execute($this->data);

            return ($this->pdo->lastInsertId() != null) ? (int) $this->pdo->lastInsertId() : null;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Prepare query for INSERT object with data called method
     */
    private function insert()
    {
        $this->query = $this->pdo->prepare(
            'INSERT INTO ' . $this->table . ' (' .
                implode(', ', array_keys($this->data)) .
            ' ) VALUES (:' .
                implode(', :', array_keys($this->data)) .
            ')'
        );
    }

    /**
     * Prepare query for UPDATE object with data called method
     */
    private function update()
    {
        $queryString = 'UPDATE ' . $this->table . ' SET ';
        foreach ($this->data as $column => $value) {
            if ($column != self::ID) {
                $queryString .= $column. ' =:'.$column . ',';
            }
        }
        $queryString .= ' updated_at = sysdate()' . $this->condition . ' AND ' . self::ID . ' =:' . self::ID;
        $this->query = $this->pdo->prepare($queryString);
    }

    /**
     * @param array $conditionQuery
     * Execute SQL DELETE query on the current object with condition set
     * how to use : $class->delete(['id' => 1]);
     */
    public function delete(array $conditionQuery = [])
    {
        $this->where($conditionQuery);
        $this->query = $this->pdo->prepare('DELETE FROM ' . $this->table . $this->condition);
        try {
            $this->query->execute($this->data);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * @param array $conditionQuery
     * $conditionQuery = ['column1' => x, 'column2' => x]
     * set condition with content of the @param
     * set data with the content of the @param
     */
    private function where(array $conditionQuery)
    {
        foreach ($conditionQuery as $column => $value) {
            $this->condition .= ' AND ' . $column . '=:' . $column;
            $this->data[$column] = $value;
        }
    }

    /**
     * @param array $limitQuery
     * $limitQuery = ['limit' => x, 'offset' => x] | ['limit' => x] | ['offset' => x] |
     * set limit with content of the @param
     */
    private function limitate(array $limitQuery = [])
    {
        if (!empty($limitQuery)) {
            $this->limit = ' LIMIT ' . (isset($limitQuery['limit']) ? $limitQuery['limit'] : self::DEFAULT_LIMIT) . ' OFFSET ' . (isset($limitQuery['offset']) ? $limitQuery['offset'] : self::DEFAULT_OFFSET);
        }
    }

    /**
     * @param array $orderQuery
     * $orderQuery = ['column1' => 'ASC', 'column2' => 'DESC']
     * @var string $orderString
     * set order with content of the @param
     */
    private function ordonate(array $orderQuery = [])
    {
        if (!empty($orderQuery)) {
            $orderString = ' ORDER BY ';
            foreach ($orderQuery as $column => $order) {
                $orderString .= $column . ' ' . $order . ',';
            }
            $this->order = substr($orderString, 0, -1);
        }
    }
}