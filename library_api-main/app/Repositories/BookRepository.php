<?php
namespace App\Repositories;

class BookRepository extends BaseRepository
{
    public string $tableName = 'books';

    public function create(array $data): ?int
    {
        if (!isset($data['writerId']) || !isset($data['publisherId']) || !isset($data['categoryId']) || !isset($data['title']) || !isset($data['coverImage']) || !isset($data['ISBN']) || !isset($data['price']) || !isset($data['content'])) {
            throw new \Exception("BookRepository error: writerId, publisherId, categoryId, title, coverImage, ISBN, price and content is required.");
        }

        return parent::create($data);
    }

    public function delete(int $id)
    {
        if (!isset($id)){
            throw new \Exception("BookRepository error: id is required.");
        }

        return parent::delete($id);
    }

    public function update(int $id, array $data)
    {
        if (!isset($id) || !isset($data['writerId']) || !isset($data['publisherId']) || !isset($data['categoryId']) || !isset($data['title']) || !isset($data['coverImage']) || !isset($data['ISBN']) || !isset($data['price']) || !isset($data['content'])){
            throw new \Exception("BookRepository error: writerId, publisherId, categoryId, title, coverImage, ISBN, price and content is required.");
        }

        return parent::update($id, $data);
    }

    public function find(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("BookRepository error: id is required.");
        }

        return parent::find($id);
    }

    public function getByPublisher(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("BookRepository error: id is required.");
        }

        $query = $this->select() . "WHERE publisherId = $id ORDER BY title";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getByWriter(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("BookRepository error: id is required.");
        }

        $query = $this->select() . "WHERE writerId = $id ORDER BY title";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategory(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("BookRepository error: id is required.");
        }

        $query = $this->select() . "WHERE categoryId = $id ORDER BY title";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }
}