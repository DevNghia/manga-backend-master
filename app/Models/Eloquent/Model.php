<?php

namespace App\Models\Eloquent;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    // By default, cache is not enabled, we will enable it manually
    protected $cacheFor = 0;

    protected $guarded = [];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    // Setting for revision, any models with RevisionableTrait trait will be affected by these settings
    protected $revisionCleanup = true;
    protected $historyLimit = 100;  // Maintain maximum 100 revisions

    /**
     * Serialize all date/datetime field to timestamp
     *
     * @param DateTimeInterface $date
     * @return false|int
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }
}
