<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateEmailAPIRequest;
use App\Http\Requests\Api\UpdateEmailAPIRequest;
use App\Models\Email;
use App\Repositories\Admin\EmailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;

/**
 * Class EmailController
 * @package App\Http\Controllers\Api
 */

class EmailAPIController extends AppBaseController
{
    /** @var  EmailRepository */
    private $emailRepository;

    public function __construct(EmailRepository $emailRepo)
    {
        $this->emailRepository = $emailRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return Response
     *
     * @SWG\Get(
     *      path="/emails",
     *      summary="Get a listing of the Emails.",
     *      tags={"Email"},
     *      description="Get all Emails",
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
     *                  @SWG\Items(ref="#/definitions/Email")
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
        $emails = $this->emailRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new LimitOffsetCriteria($request))
            //->pushCriteria(new emailCriteria($request))
            ->all();

        return $this->sendResponse($emails->toArray(), 'Emails retrieved successfully');
    }

    /**
     * @param CreateEmailAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/emails",
     *      summary="Store a newly created Email in storage",
     *      tags={"Email"},
     *      description="Store Email",
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
     *          description="Email that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Email")
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
     *                  ref="#/definitions/Email"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateEmailAPIRequest $request)
    {
        $emails = $this->emailRepository->saveRecord($request);

        return $this->sendResponse($emails->toArray(), 'Email saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/emails/{id}",
     *      summary="Display the specified Email",
     *      tags={"Email"},
     *      description="Get Email",
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
     *          description="id of Email",
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
     *                  ref="#/definitions/Email"
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
        /** @var Email $email */
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            return $this->sendErrorWithData(['Email not found']);
        }

        return $this->sendResponse($email->toArray(), 'Email retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateEmailAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/emails/{id}",
     *      summary="Update the specified Email in storage",
     *      tags={"Email"},
     *      description="Update Email",
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
     *          description="id of Email",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Email that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Email")
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
     *                  ref="#/definitions/Email"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateEmailAPIRequest $request)
    {
        /** @var Email $email */
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            return $this->sendErrorWithData(['Email not found']);
        }

        $email = $this->emailRepository->updateRecord($request, $email);

        return $this->sendResponse($email->toArray(), 'Email updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/emails/{id}",
     *      summary="Remove the specified Email from storage",
     *      tags={"Email"},
     *      description="Delete Email",
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
     *          description="id of Email",
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
        /** @var Email $email */
        $email = $this->emailRepository->findWithoutFail($id);

        if (empty($email)) {
            return $this->sendErrorWithData(['Email not found']);
        }

        $this->emailRepository->deleteRecord($id);

        return $this->sendResponse($id, 'Email deleted successfully');
    }



    public function getMail()
    {
        $email = Email::where('status', 0)
            ->latest('id')
            ->first();
        if (empty($email)) {
            return $this->sendErrorWithData(['Email not found']);
        }
        return $this->sendResponse($email->toArray(), 'Email retrieved successfully');
    }


    public function getMail2()
    {
        $email = Email::where('status', 0)
            ->latest('id')
            ->first();
        if (empty($email)) {
            return $this->sendErrorWithData(['Email not found']);
        }
        return $this->sendResponse($email->toArray(), 'Email retrieved successfully');
    }
}
