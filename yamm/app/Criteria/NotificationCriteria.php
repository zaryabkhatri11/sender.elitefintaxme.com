<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class NotificationCriteria.
 *
 * @package namespace App\Criteria;
 */
class NotificationCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $unread = null;

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */

    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->isset('unread')) {
            $model = $model->where('read_at', null);
        }
        $model = $model->where('notifiable_id', \Auth::id());
        return $model;
    }
}
