<?php

namespace App\Http\Controllers\Admin;

use App\Helper\BreadcrumbsRegister;
use App\DataTables\Admin\VideoDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Repositories\Admin\VideoRepository;
use App\Http\Controllers\AppBaseController;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;

class VideoController extends AppBaseController
{
    /** ModelName */
    private $ModelName;

    /** BreadCrumbName */
    private $BreadCrumbName;

    /** @var  VideoRepository */
    private $videoRepository;

    public function __construct(VideoRepository $videoRepo)
    {
        $this->videoRepository = $videoRepo;
        $this->ModelName = 'videos';
        $this->BreadCrumbName = 'Videos';
    }

    /**
     * Display a listing of the Video.
     *
     * @param VideoDataTable $videoDataTable
     * @return Response
     */
    public function index(VideoDataTable $videoDataTable)
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return $videoDataTable->render('admin.videos.index', ['title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for creating a new Video.
     *
     * @return Response
     */
    public function create()
    {
        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName);
        return view('admin.videos.create')->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Store a newly created Video in storage.
     *
     * @param CreateVideoRequest $request
     *
     * @return Response
     */
    public function store(CreateVideoRequest $request)
    {
        $video = $this->videoRepository->saveRecord($request);

        Flash::success($this->BreadCrumbName . ' saved successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.videos.create'));
        } elseif (isset($request->translation)) {
            $redirect_to = redirect(route('admin.videos.edit', $video->id));
        } else {
            $redirect_to = redirect(route('admin.videos.index'));
        }
        return $redirect_to->with([
            'title' => $this->BreadCrumbName
        ]);
    }

    /**
     * Display the specified Video.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $video = $this->videoRepository->findWithoutFail($id);

        if (empty($video)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.videos.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $video);
        return view('admin.videos.show')->with(['video' => $video, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Show the form for editing the specified Video.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $video = $this->videoRepository->findWithoutFail($id);

        if (empty($video)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.videos.index'));
        }

        BreadcrumbsRegister::Register($this->ModelName,$this->BreadCrumbName, $video);
        return view('admin.videos.edit')->with(['video' => $video, 'title' => $this->BreadCrumbName]);
    }

    /**
     * Update the specified Video in storage.
     *
     * @param  int              $id
     * @param UpdateVideoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVideoRequest $request)
    {
        $video = $this->videoRepository->findWithoutFail($id);

        if (empty($video)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.videos.index'));
        }

        $video = $this->videoRepository->updateRecord($request, $video);

        Flash::success($this->BreadCrumbName . ' updated successfully.');
        if (isset($request->continue)) {
            $redirect_to = redirect(route('admin.videos.create'));
        } else {
            $redirect_to = redirect(route('admin.videos.index'));
        }
        return $redirect_to->with(['title' => $this->BreadCrumbName]);
    }

    /**
     * Remove the specified Video from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $video = $this->videoRepository->findWithoutFail($id);

        if (empty($video)) {
            Flash::error($this->BreadCrumbName . ' not found');
            return redirect(route('admin.videos.index'));
        }

        $this->videoRepository->deleteRecord($id);

        Flash::success($this->BreadCrumbName . ' deleted successfully.');
        return redirect(route('admin.videos.index'))->with(['title' => $this->BreadCrumbName]);
    }
}
