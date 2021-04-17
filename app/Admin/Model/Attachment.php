<?php

declare(strict_types=1);

namespace App\Admin\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * 附件
 */
class Attachment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attachment';
}
