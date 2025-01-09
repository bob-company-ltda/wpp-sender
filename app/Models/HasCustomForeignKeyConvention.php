<?php
namespace App\Models;

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
        if (!isset($this->foreignKey)) {
            $relatedClass = $related ? $related : $this->getMorphClass();
            $relatedModel = new $relatedClass;
            $this->foreignKey = $relatedModel->getKeyName();
        }
    
        return $this->foreignKey;
    }
}
