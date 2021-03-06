<?php

namespace Src\Model;

use Aura\SqlQuery\QueryFactory;
use Slim\Container;

class RequirementsModel
{
	protected $table;
    protected $id;

	protected $pdo;
	protected $query_factory;

	protected $select;
	protected $insert;
	protected $update;
	protected $delete;

	public function __construct(Container $c)
	{
		$this->pdo = $c->get('db');

        $this->table = 'tblRequirements';
        $this->id = 'fldRequirementId';

		$this->query_factory = new QueryFactory('mysql');
	}

    // custom query
    public function createQuery($query)
    {
        $result = $this->pdo->fetchAll($query);
        return $result;
    }

    // locate single record
    public function find($select = [], $id)
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols($select)
			->where($this->id . " = :id")
			->from($this->table);

        $bind = ["id" => $id];
		$stm = $this->select->getStatement();

		return $this->pdo->fetchOne($stm, $bind);
    }

    // locate single record with where
    public function findBy($select = [], $where, $bind)
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols($select)
			->where($where)
			->from($this->table);

		$stm = $this->select->getStatement();

		return $this->pdo->fetchOne($stm, $bind);
    }

    // locate all file
    public function findAll($select = [])
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols($select)
			->from($this->table);

		$stm = $this->select->getStatement();

        $result = $this->pdo->fetchAll($stm);
		return $result;
    }

    // Locate all file with where
    public function findAllBy($select = [], $where, $bind)
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols($select)
			->where($where)
			->from($this->table);

		$stm = $this->select->getStatement();

		return $this->pdo->fetchAll($stm, $bind);
    }

    // add new data
    public function insert($values = [])
    {
        $this->insert = $this->query_factory->newInsert();
		$this->insert
			->into($this->table)
			->cols($values);

		$sth = $this->pdo->prepare($this->insert->getStatement());
		$sth->execute($this->insert->getBindValues());

        $name = $this->insert->getLastInsertIdName($this->id);
        $id = $this->pdo->lastInsertId($name);

        return $id;
    }

    // edit record
    public function update($select = [], $where, $bind)
    {
        $this->update = $this->query_factory->newUpdate();
        $this->update
            ->table($this->table)
            ->cols($select)
            ->where($where);

        $stm = $this->update->getStatement();
        $row_count = $this->pdo->fetchAffected($stm, $bind);

        if ($row_count > 0) {
            return true;
        } elseif ($row_count === 0) {
            return false;
        }
    }

    // delete record
    public function delete($where, $bind)
    {
        $this->delete = $this->query_factory->newDelete();
        $this->delete
            ->from($this->table)
            ->where($where);

        $stm = $this->delete->getStatement();
        $row_count = $this->pdo->fetchAffected($stm, $bind);

        if ($row_count > 0) {
            return true;
        } elseif ($row_count === 0) {
            return false;
        }
    }

    // count all
    public function countAll()
    {
        $this->select = $this->query_factory->newSelect();
        $this->select
            ->cols(array('COUNT(*) AS cnt'))
            ->from($this->table);

        $sth = $this->select->getStatement();

        return $this->pdo->fetchOne($sth);
    }

    // count by
    public function countBy($where, $bind)
    {
        $this->select = $this->query_factory->newSelect();
        $this->select
            ->cols(array('COUNT(*) AS cnt'))
            ->where($where)
            ->from($this->table);

        $sth = $this->select->getStatement();

        return $this->pdo->fetchOne($sth, $bind);
    }

    public function visa($id)
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols($select)
			->where($where)
			->from($this->table);

		$stm = $this->select->getStatement();

		return $this->pdo->fetchAll($stm, $bind);
    }

    public function uni($id)
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols(['*'])
            ->where('fldRequirementType IN (:docs)')
            ->where('tblUni_fldUniId = :id')
			->from($this->table);

        $stm = $this->select->getStatement();
        
        $bind = [
            'docs' => [
                'Step 1 Uni',
                'Step 2 Uni',
                'Step 2 Doc',
                'Step 3 Uni',
                'Step 4 Uni',
                'Step 4 Doc',
                'Step 5 Uni',
                'Step 6 Uni',
                'Step 6 Doc'
            ],
            'id' => $id
        ];

		return $this->pdo->fetchAll($stm, $bind);
    }

    public function visaReq()
    {
        $this->select = $this->query_factory->newSelect();
		$this->select
			->cols(['*'])
            ->where('fldRequirementType IN (:docs)')
          	->from($this->table);

        $stm = $this->select->getStatement();
        
        $bind = [
            'docs' => [
                'Visa',
            ]
        ];

		return $this->pdo->fetchAll($stm, $bind);
    }
}
