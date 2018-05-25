<?php

namespace Src\Model;

use Aura\SqlQuery\QueryFactory;
use Slim\Container;

class UsersModel
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

        $this->table = 'tblUsers';
        $this->id = 'fldUserId';

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

    public function user_task($where, $bind)
    {
        $select = [
            'fldUserId',
            'fldUserFName',
            
        ];

        $this->select = $this->query_factory->newSelect();
        $this->select
            ->cols($select)
            ->where($where)
            ->join('LEFT', 'tblTask', 'fldUserId = tblTask.fldTaskAssignee')
            ->from($this->table);

        $sth = $this->select->getStatement();

        return $this->pdo->fetchOne($sth, $bind);
    }

    public function testimonies() 
    {
        return [
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Aldrin',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Irene',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Aldrin',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Irene',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
        ];
    }

    public function students() 
    {
        return [
            [
                'title' => 'Aldrin',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Irene',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Aldrin',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Irene',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Jasvir',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'title' => 'Vilma',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
        ];
    }

    public function universities() 
    {
        return [
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Charlse Darwin University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectutem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Oxford University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'University of the Philippines',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Bataan Peninsula State University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. tem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'University of Sydney',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Charlse Darwin University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'MIT',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Hardvard',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Charlse Darwin University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectutem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Oxford University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'University of the Philippines',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Bataan Peninsula State University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. tem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'University of Sydney',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Charlse Darwin University',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'MIT',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioesentium odio voluptatem, error autem atque, voluptates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
            [
                'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                'title' => 'Hardvard',
                'description' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore ratioates magni, quibusdam nobis quasi qui fugit blanditiis enim?<a href='#'>@oxford</a><a href='#'>#england</a><br><time datetime='2016-1-1'>11:09 PM - 1 Jan 2016</time>",
            ],
        ];
    }
}
