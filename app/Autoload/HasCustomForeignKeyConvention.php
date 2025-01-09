<?php
namespace App\Autoload;

use Illuminate\Support\Str;

trait HasCustomForeignKeyConvention
{
    /**
     * Get the foreign key for the model.
     *
     * @param  string  $related
     * @param  string|null  $owner
     * @param  string|null  $relation
     * @return string
     */
    public function getForeignKey($related = '', $owner = null, $relation = null)
    {
        if (! isset($this->foreignKey)) {
            $this->foreignKey = snake_case(class_basename($related)).'_id';
        }

        return $this->foreignKey;
    }
}
