<?php


namespace Iamamirsalehi\LaravelBalance\src\Services\Balance\Validator;


use Iamamirsalehi\LaravelBalance\src\Services\Balance\Exceptions\RepositoryMustBeExistedInTheDataException;
use Iamamirsalehi\LaravelBalance\src\Services\Balance\Exceptions\UserIdMustBeExistedInDataException;
use Iamamirsalehi\LaravelBalance\src\Services\Balance\Exceptions\UserIdMustBeIntegerException;
use Illuminate\Database\Eloquent\Model;

class Validator
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getUserId()
    {
        if(!array_key_exists('user_id', $this->data))
            throw new UserIdMustBeExistedInDataException('User id must be existed in the data');

        if(!is_int($this->data['user_id']))
            throw new UserIdMustBeIntegerException('User id must be an integer');

        return $this->data['user_id'];
    }

    public function getRepository()
    {
        if(!array_key_exists('repository', $this->data))
            throw new RepositoryMustBeExistedInTheDataException('Repository must be existed in the data (it can be a model class or a repository class)');

        if(is_subclass_of($this->data['repository'], Model::class))
            return (new $this->data['repository']);

        return resolve($this->data['repository']);
    }
}