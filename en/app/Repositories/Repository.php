<?php
/**
 * Created by PhpStorm.
 * User: rezzakia
 * Date: 1/15/2020
 * Time: 23:02 PM
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }


    public function getOwner(array $condition = null)
    {
        if (!empty($condition)) {
            return $this->model->owner()->where($condition)->latest()->get();
        }
        return $this->model->owner()->latest()->get();
    }

    public function get(array $condition = null)
    {
        if (!empty($condition)) {
            return $this->model->where($condition)->latest()->get();
        }
        return $this->model->latest()->get();
    }

    // paginate all instances of model
    public function paginate($count)
    {
        return $this->model->latest()->paginate($count);
    }


    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // update record in the database
    public function update(array $data, $model)
    {
        return $model->update($data);
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model->find($id);
    }

    public function showOwner($id)
    {
        return $this->model->owner()->find($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    public function sync($find, $relation, array $data = null,$attribute = null)
    {
        if ($data != null || is_array($data)) {
            return $find->{$relation}()->sync($data,$attribute);
        }
    }

    public function attach($find, $relation, array $data = null,$attribute = null)
    {
        if ($data != null || is_array($data)) {
            foreach ($data as $item){
                $find->{$relation}()->attach($item,$attribute);
            }
            return true;
        }
    }

}
