<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreatePaymentAccountAPIRequest;
use App\Http\Requests\Api\UpdatePaymentAccountAPIRequest;
use App\Models\PaymentAccount;
use App\Repositories\Admin\PaymentAccountRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;

/**
 * Class PaymentAccountController
 * @package App\Http\Controllers\Api
 */

class PaymentAccountAPIController extends AppBaseController
{
    /** @var  PaymentAccountRepository */
    private $paymentAccountRepository;

    public function __construct(PaymentAccountRepository $paymentAccountRepo)
    {
        $this->paymentAccountRepository = $paymentAccountRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return Response
     *
     * @SWG\Get(
     *      path="/payment-accounts",
     *      summary="Get a listing of the PaymentAccounts.",
     *      tags={"PaymentAccount"},
     *      description="Get all PaymentAccounts",
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
     *                  @SWG\Items(ref="#/definitions/PaymentAccount")
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
        $paymentAccounts = $this->paymentAccountRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new LimitOffsetCriteria($request))
            //->pushCriteria(new paymentAccountCriteria($request))
            ->all();

        return $this->sendResponse($paymentAccounts->toArray(), 'Payment Accounts retrieved successfully');
    }

    /**
     * @param CreatePaymentAccountAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/payment-accounts",
     *      summary="Store a newly created PaymentAccount in storage",
     *      tags={"PaymentAccount"},
     *      description="Store PaymentAccount",
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
     *          description="PaymentAccount that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PaymentAccount")
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
     *                  ref="#/definitions/PaymentAccount"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePaymentAccountAPIRequest $request)
    {
        $paymentAccounts = $this->paymentAccountRepository->saveRecord($request);

        return $this->sendResponse($paymentAccounts->toArray(), 'Payment Account saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/payment-accounts/{id}",
     *      summary="Display the specified PaymentAccount",
     *      tags={"PaymentAccount"},
     *      description="Get PaymentAccount",
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
     *          description="id of PaymentAccount",
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
     *                  ref="#/definitions/PaymentAccount"
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
        /** @var PaymentAccount $paymentAccount */
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            return $this->sendErrorWithData(['Payment Account not found']);
        }

        return $this->sendResponse($paymentAccount->toArray(), 'Payment Account retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePaymentAccountAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/payment-accounts/{id}",
     *      summary="Update the specified PaymentAccount in storage",
     *      tags={"PaymentAccount"},
     *      description="Update PaymentAccount",
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
     *          description="id of PaymentAccount",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="PaymentAccount that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/PaymentAccount")
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
     *                  ref="#/definitions/PaymentAccount"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePaymentAccountAPIRequest $request)
    {
        /** @var PaymentAccount $paymentAccount */
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            return $this->sendErrorWithData(['Payment Account not found']);
        }

        $paymentAccount = $this->paymentAccountRepository->updateRecord($request, $paymentAccount);

        return $this->sendResponse($paymentAccount->toArray(), 'PaymentAccount updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/payment-accounts/{id}",
     *      summary="Remove the specified PaymentAccount from storage",
     *      tags={"PaymentAccount"},
     *      description="Delete PaymentAccount",
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
     *          description="id of PaymentAccount",
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
        /** @var PaymentAccount $paymentAccount */
        $paymentAccount = $this->paymentAccountRepository->findWithoutFail($id);

        if (empty($paymentAccount)) {
            return $this->sendErrorWithData(['Payment Account not found']);
        }

        $this->paymentAccountRepository->deleteRecord($id);

        return $this->sendResponse($id, 'Payment Account deleted successfully');
    }
}
