<?php

namespace App\Http\Controllers;

use App\Libraries\ResponseLibrary;
use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    /**
     * List
     * 
     * Display a listing of the todo.
     * 
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $todos = $request->user()->todos;
        return ResponseLibrary::successResponse('Success', $todos);
    }

    /**
     * Store
     * 
     * Store todo.
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|max:255',
        ]);
        if ($validate->fails()) {
            return ResponseLibrary::errorResponse('Form inputs not valid', $validate->errors(), 422);
        }
        $data = $validate->validated();
        $userId = auth('api')->user()->id;
        $data['created_by'] = $userId;
        try {
            $todo = Todo::create($data);
            return ResponseLibrary::successResponse('Todo created successfully', $todo);
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    /**
     * Show
     * 
     * Detail todo.
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $id)
    {
        return ResponseLibrary::successResponse('Success', $id);
    }

    /** 
     * Update
     * 
     * Update todo
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $id)
    {
        $validate = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string|max:255',
        ]);
        if ($validate->fails()) {
            return ResponseLibrary::errorResponse('Form inputs not valid', $validate->errors(), 422);
        }
        $data = $validate->validated();
        // $request->validate([]);
        $userId = auth('api')->user()->id;
        try {
            // $todo = Todo::find($id);
            $id->title = $request->title;
            $id->description = $request->description;
            $id->updated_by = $userId;
            $id->save();
            return ResponseLibrary::successResponse('Todo updated successfully', $id);
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    /** 
     * Delete
     * 
     * Delete todo
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $id)
    {
        // $todo = Todo::find($id);
        try {
            $id->delete();
            return ResponseLibrary::successResponse('Todo deleted successfully');
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }
}
