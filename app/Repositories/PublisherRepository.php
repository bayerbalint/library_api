<?php
namespace App\Repositories;

class PublisherRepository extends BaseRepository
{
    public string $tableName = 'publishers';

    public function create(array $data): ?int
    {
        if (!isset($data['name'])) {
            throw new \Exception("PublisherRepository error: name is required.");
        }

        return parent::create($data);
    }

    public function delete(int $id)
    {
        if (!isset($id)){
            throw new \Exception("PublisherRepository error: id is required.");
        }

        return parent::delete($id);
    }

    public function update(int $id, array $data){
        if (!isset($id) || !isset($data["name"])){
            throw new \Exception("PublisherRepository error: name is required.");
        }

        return parent::update($id, $data);
    }

    public function find(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("PublisherRepository error: id is required.");
        }

        return parent::find($id);
    }
}