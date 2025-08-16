<?php

namespace App\Repositories\api;

use Exception;
use App\Models\Book;
use App\Exceptions\CustomException;

class BookRepository
{
    public function __construct(protected Book $model){}

    public function index()
    {
        $books = $this->model->all();

        return $books;
    }

    public function store($request)
    {
        info($request->all());
        // $book = $this->model->create($request->all());

        // return $book;
    }

}
