<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\TermController\CreateRequest;
use App\Http\Requests\Api\v1\TermController\UpdateRequest;
use App\Services\TermService;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function __construct(
        protected TermService $termService
    )
    {
        $this->middleware(['auth:api', 'is_admin'])->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'terms' => $this->termService->index($request),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Api\v1\TermController\CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        return response()->json([
            'success' => true,
            'messages' => [
                [
                    'title' => 'Выполнено успешно',
                    'text' => 'Вы создали новый термин',
                ]
            ],
            'data' => [
                'term_id' => $this->termService->store($request)
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'term' => $this->termService->show($id)
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Api\v1\TermController\UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        $this->termService->update($request, $id);
        return response()->json([
            'success' => true,
            'messages' => [
                [
                    'title' => 'Выполнено успешно',
                    'text' => 'Вы обновили термин',
                ]
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->termService->destroy($id);
        return response()->json([
            'success' => true,
            'messages' => [
                [
                    'title' => 'Выполнено успешно',
                    'text' => 'Вы удалили термин',
                ]
            ]
        ]);
    }
}
