<?php

namespace App\Repositories\Admin;

use App\Models\Video;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class VideoRepository
 * @package App\Repositories\Admin
 * @version September 12, 2025, 8:14 pm UTC
 *
 * @method Video findWithoutFail($id, $columns = ['*'])
 * @method Video find($id, $columns = ['*'])
 * @method Video first($columns = ['*'])
*/
class VideoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'video_1',
        'video_2',
        'email'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Video::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {
        $input = $request->all();
        $video = $this->create($input);
        return $video;
    }

    /**
     * @param $request
     * @param $video
     * @return mixed
     */
    public function updateRecord($request, $video)
    {
        $input = $request->all();
        $video = $this->update($input, $video->id);
        return $video;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $video = $this->delete($id);
        return $video;
    }
}
