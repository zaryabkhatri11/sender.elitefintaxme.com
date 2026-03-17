<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateMessagesLogAPIRequest;
use App\Http\Requests\Api\UpdateMessagesLogAPIRequest;
use App\Models\MessagesLog;
use App\Repositories\Admin\MessagesLogRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Response;

/**
 * Class MessagesLogController
 * @package App\Http\Controllers\Api
 */

class MessagesLogAPIController extends AppBaseController
{
    /** @var  MessagesLogRepository */
    private $messagesLogRepository;

    public function __construct(MessagesLogRepository $messagesLogRepo)
    {
        $this->messagesLogRepository = $messagesLogRepo;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages-logs",
     *      summary="Get a listing of the MessagesLogs.",
     *      tags={"MessagesLog"},
     *      description="Get all MessagesLogs",
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
     *                  @SWG\Items(ref="#/definitions/MessagesLog")
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
        $messagesLogs = $this->messagesLogRepository
            ->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(new LimitOffsetCriteria($request))
            //->pushCriteria(new messagesLogCriteria($request))
            ->all();

        return $this->sendResponse($messagesLogs->toArray(), 'Messages Logs retrieved successfully');
    }

    /**
     * @param CreateMessagesLogAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/messages-logs",
     *      summary="Store a newly created MessagesLog in storage",
     *      tags={"MessagesLog"},
     *      description="Store MessagesLog",
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
     *          description="MessagesLog that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/MessagesLog")
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
     *                  ref="#/definitions/MessagesLog"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMessagesLogAPIRequest $request)
    {
        $messagesLogs = $this->messagesLogRepository->saveRecord($request);

        return $this->sendResponse($messagesLogs->toArray(), 'Messages Log saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages-logs/{id}",
     *      summary="Display the specified MessagesLog",
     *      tags={"MessagesLog"},
     *      description="Get MessagesLog",
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
     *          description="id of MessagesLog",
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
     *                  ref="#/definitions/MessagesLog"
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
        /** @var MessagesLog $messagesLog */
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            return $this->sendErrorWithData(['Messages Log not found']);
        }

        return $this->sendResponse($messagesLog->toArray(), 'Messages Log retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMessagesLogAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/messages-logs/{id}",
     *      summary="Update the specified MessagesLog in storage",
     *      tags={"MessagesLog"},
     *      description="Update MessagesLog",
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
     *          description="id of MessagesLog",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="MessagesLog that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/MessagesLog")
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
     *                  ref="#/definitions/MessagesLog"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateMessagesLogAPIRequest $request)
    {
        /** @var MessagesLog $messagesLog */
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            return $this->sendErrorWithData(['Messages Log not found']);
        }

        $messagesLog = $this->messagesLogRepository->updateRecord($request, $messagesLog);

        return $this->sendResponse($messagesLog->toArray(), 'MessagesLog updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/messages-logs/{id}",
     *      summary="Remove the specified MessagesLog from storage",
     *      tags={"MessagesLog"},
     *      description="Delete MessagesLog",
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
     *          description="id of MessagesLog",
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
        /** @var MessagesLog $messagesLog */
        $messagesLog = $this->messagesLogRepository->findWithoutFail($id);

        if (empty($messagesLog)) {
            return $this->sendErrorWithData(['Messages Log not found']);
        }

        $this->messagesLogRepository->deleteRecord($id);

        return $this->sendResponse($id, 'Messages Log deleted successfully');
    }
}
