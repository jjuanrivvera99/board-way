<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Livewire\BoardManagement;
use App\Http\Resources\BoardResourse;
use App\Http\Resources\BoardCollection;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $collection = (new BoardCollection(Board::all()))->paginate();
        return response()->json($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoardRequest $request)
    {
        $board = Board::create($request->validated());
        return response()->json(new BoardResourse($board), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(new BoardResourse(Board::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoardRequest $request, string $id)
    {
        $board = Board::findOrFail($id);
        $board->update($request->validated());
        return response()->json(new BoardResourse($board));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Board::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
