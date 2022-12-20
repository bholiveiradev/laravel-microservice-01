<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    protected $repository;
    private   $evaluationService;

    public function __construct(Company $model, EvaluationService $evaluationService)
    {
        $this->repository = $model;
        $this->evaluationService = $evaluationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = $this->repository->getCompanies($request->get('search', ''));

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CompanyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $dataRequest = $request->validated();
        $dataRequest['category_id'] = $request->category;

        $company = $this->repository->create($dataRequest);

        return response()->json(new CompanyResource($company), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();

        $evaluations = $this->evaluationService->getEvaluationsByCompanyUuid($uuid);

        return (new CompanyResource($company))
            ->additional([
                'evaluations' => $evaluations->json()['data']
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CompanyRequest  $request
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, $uuid)
    {
        $dataRequest = $request->validated();
        $dataRequest['category_id'] = $request->category;

        $this->repository->where('uuid', $uuid)
            ->firstOrFail()
            ->update($dataRequest);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $this->repository->where('uuid', $uuid)
            ->firstOrFail()
            ->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
