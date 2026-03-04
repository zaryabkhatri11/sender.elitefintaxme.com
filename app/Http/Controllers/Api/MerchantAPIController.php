<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateMerchantAPIRequest;
use App\Http\Requests\Api\UpdateMerchantAPIRequest;
use App\Models\Merchant;
use App\Repositories\Admin\MerchantRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;

/**
 * Class MerchantController
 * @package App\Http\Controllers\Api
 */

class MerchantAPIController extends AppBaseController
{
    /** @var  MerchantRepository */
    private $merchantRepository;

    public function __construct(MerchantRepository $merchantRepo)
    {
        $this->merchantRepository = $merchantRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return Response
     *
     * @SWG\Get(
     *      path="/merchants",
     *      summary="Get a listing of the Merchants.",
     *      tags={"Merchant"},
     *      description="Get all Merchants",
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
     *                  @SWG\Items(ref="#/definitions/Merchant")
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
        $merchants = $this->merchantRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new LimitOffsetCriteria($request))
            //->pushCriteria(new merchantCriteria($request))
            ->all();

        return $this->sendResponse($merchants->toArray(), 'Merchants retrieved successfully');
    }

    /**
     * @param CreateMerchantAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/merchants",
     *      summary="Store a newly created Merchant in storage",
     *      tags={"Merchant"},
     *      description="Store Merchant",
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
     *          description="Merchant that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Merchant")
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
     *                  ref="#/definitions/Merchant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMerchantAPIRequest $request)
    {
        $merchants = $this->merchantRepository->saveRecord($request);

        return $this->sendResponse($merchants->toArray(), 'Merchant saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/merchants/{id}",
     *      summary="Display the specified Merchant",
     *      tags={"Merchant"},
     *      description="Get Merchant",
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
     *          description="id of Merchant",
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
     *                  ref="#/definitions/Merchant"
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
        /** @var Merchant $merchant */
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            return $this->sendErrorWithData(['Merchant not found']);
        }

        return $this->sendResponse($merchant->toArray(), 'Merchant retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMerchantAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/merchants/{id}",
     *      summary="Update the specified Merchant in storage",
     *      tags={"Merchant"},
     *      description="Update Merchant",
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
     *          description="id of Merchant",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Merchant that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Merchant")
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
     *                  ref="#/definitions/Merchant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateMerchantAPIRequest $request)
    {
        /** @var Merchant $merchant */
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            return $this->sendErrorWithData(['Merchant not found']);
        }

        $merchant = $this->merchantRepository->updateRecord($request, $merchant);

        return $this->sendResponse($merchant->toArray(), 'Merchant updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/merchants/{id}",
     *      summary="Remove the specified Merchant from storage",
     *      tags={"Merchant"},
     *      description="Delete Merchant",
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
     *          description="id of Merchant",
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
        /** @var Merchant $merchant */
        $merchant = $this->merchantRepository->findWithoutFail($id);

        if (empty($merchant)) {
            return $this->sendErrorWithData(['Merchant not found']);
        }

        $this->merchantRepository->deleteRecord($id);

        return $this->sendResponse($id, 'Merchant deleted successfully');
    }
}
