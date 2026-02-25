<?php

namespace App\Repositories\Admin;

use App\Models\Sheet;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SheetRepository
 * @package App\Repositories\Admin
 * @version November 18, 2025, 8:09 pm UTC
 *
 * @method Sheet findWithoutFail($id, $columns = ['*'])
 * @method Sheet find($id, $columns = ['*'])
 * @method Sheet first($columns = ['*'])
*/
class SheetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'link',
        'status',
        'created_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Sheet::class;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function saveRecord($request)
    {

        $input = $request->all();
        $sheet = $this->create($input);

        return $sheet;
    }

    /**
     * @param $request
     * @param $sheet
     * @return mixed
     */
    public function updateRecord($request, $sheet)
    {
        $input = $request->all();
        $sheet = $this->update($input, $sheet->id);
        return $sheet;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteRecord($id)
    {
        $sheet = $this->delete($id);
        return $sheet;
    }
}
