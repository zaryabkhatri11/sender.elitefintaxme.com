<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateSheetAPIRequest;
use App\Http\Requests\Api\UpdateSheetAPIRequest;
use App\Models\Sheet;
use App\Repositories\Admin\SheetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;

/**
 * Class SheetController
 * @package App\Http\Controllers\Api
 */

class SheetAPIController extends AppBaseController
{
    /** @var  SheetRepository */
    private $sheetRepository;

    public function __construct(SheetRepository $sheetRepo)
    {
        $this->sheetRepository = $sheetRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return Response
     *
     * @SWG\Get(
     *      path="/sheets",
     *      summary="Get a listing of the Sheets.",
     *      tags={"Sheet"},
     *      description="Get all Sheets",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="User Auth Token{ Bearer ABC123 }",
     *          type="string",
     *          required=true,
     *          default="Bearer ABC123",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="orderBy",
     *          description="Pass the property name you want to sort your response. If not found, Returns All Records in DB without sorting.",
     *          type="string",
     *          required=false,
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="sortedBy",
     *          description="Pass 'asc' or 'desc' to define the sorting method. If not found, 'asc' will be used by default",
     *          type="string",
     *          required=false,
     *          in="query"
     *      ),
     *      @SWG\Parameter(
     *          name="limit",
     *          description="Change the Default Record Count. If not found, Returns All Records in DB.",
     *          type="integer",
     *          required=false,
     *          in="query"
     *      ),
     *     @SWG\Parameter(
     *          name="offset",
     *          description="Change the Default Offset of the Query. If not found, 0 will be used.",
     *          type="integer",
     *          required=false,
     *          in="query"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Sheet")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $sheets = $this->sheetRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new LimitOffsetCriteria($request))
            //->pushCriteria(new sheetCriteria($request))
            ->all();

        return $this->sendResponse($sheets->toArray(), 'Sheets retrieved successfully');
    }

    /**
     * @param CreateSheetAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/sheets",
     *      summary="Store a newly created Sheet in storage",
     *      tags={"Sheet"},
     *      description="Store Sheet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="User Auth Token{ Bearer ABC123 }",
     *          type="string",
     *          required=true,
     *          default="Bearer ABC123",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Sheet that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Sheet")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Sheet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSheetAPIRequest $request)
    {
        $sheets = $this->sheetRepository->saveRecord($request);

        return $this->sendResponse($sheets->toArray(), 'Sheet saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/sheets/{id}",
     *      summary="Display the specified Sheet",
     *      tags={"Sheet"},
     *      description="Get Sheet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="User Auth Token{ Bearer ABC123 }",
     *          type="string",
     *          required=true,
     *          default="Bearer ABC123",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Sheet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Sheet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Sheet $sheet */
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            return $this->sendErrorWithData(['Sheet not found']);
        }

        return $this->sendResponse($sheet->toArray(), 'Sheet retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSheetAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/sheets/{id}",
     *      summary="Update the specified Sheet in storage",
     *      tags={"Sheet"},
     *      description="Update Sheet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="User Auth Token{ Bearer ABC123 }",
     *          type="string",
     *          required=true,
     *          default="Bearer ABC123",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Sheet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Sheet that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Sheet")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Sheet"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateSheetAPIRequest $request)
    {
        /** @var Sheet $sheet */
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            return $this->sendErrorWithData(['Sheet not found']);
        }

        $sheet = $this->sheetRepository->updateRecord($request, $sheet);

        return $this->sendResponse($sheet->toArray(), 'Sheet updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/sheets/{id}",
     *      summary="Remove the specified Sheet from storage",
     *      tags={"Sheet"},
     *      description="Delete Sheet",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="User Auth Token{ Bearer ABC123 }",
     *          type="string",
     *          required=true,
     *          default="Bearer ABC123",
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Sheet",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Sheet $sheet */
        $sheet = $this->sheetRepository->findWithoutFail($id);

        if (empty($sheet)) {
            return $this->sendErrorWithData(['Sheet not found']);
        }

        $this->sheetRepository->deleteRecord($id);

        return $this->sendResponse($id, 'Sheet deleted successfully');
    }
}
