<?php

namespace App\Http\Controllers;

use App\Banner;
use Illuminate\Http\Request;
use App\Helpers\QueryHelper;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Response $response): Response
    {
        $queryHelper = new QueryHelper($request);
        $filter = $queryHelper->setfilter([]);
        $distinct = null;
        $select = [
            "id" => "id",
            "name" => 'name',
            "url" => 'url',
            "description" => 'description',
            "create_at" => 'create_at',
            "update_at" => 'update_at',
            "create_by" => 'create_by'
        ];
        $from = '
            FROM "banner"
        ';
        try {
            return $response->withJson($queryHelper->get($select, $from, $distinct, $filter), StatusCode::HTTP_OK);
        } catch (QueryException $e) {
            return $response->withJson([
                'message' => 'Caught exception: ' . end($e->errorInfo),
                'status' => false
            ], StatusCode::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        //
    }
}
