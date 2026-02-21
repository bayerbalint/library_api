<?php
namespace App\Repositories;

class WriterRepository extends BaseRepository
{
    public string $tableName = 'writers';

    public function create(array $data): ?int
    {
        if (!isset($data['name']) || !isset($data['bio'])) {
            throw new \Exception("WriterRepository error: name and bio is required.");
        }

        return parent::create($data);
    }

    public function delete(int $id)
    {
        if (!isset($id)){
            throw new \Exception("WriterRepository error: id is required.");
        }

        return parent::delete($id);
    }

    public function update(int $id, array $data){
        if (!isset($id) || !isset($data["name"]) || !isset($data["bio"])){
            throw new \Exception("WriterRepository error: name and bio is required.");
        }

        return parent::update($id, $data);
    }

    public function find(int $id): array
    {
        if (!isset($id)){
            throw new \Exception("WriterRepository error: id is required.");
        }

        return parent::find($id);
    }
}