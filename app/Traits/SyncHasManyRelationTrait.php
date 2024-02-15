<?php

namespace App\Traits;

trait SyncHasManyRelationTrait
{
    /**
     * Syncs a hasMany relationship by adding, updating, or deleting related models.
     *
     * @param string $relation The name of the hasMany relationship to sync.
     * @param array $models An array of model data arrays to sync with the hasMany relationship.
     * @param callable|null $updateCallback An optional callback function to modify the currently updating model.
     * @param callable|null $deleteCallback An optional callback function to run before deleting each model.
     *
     * @return void
     */
    public function syncHasMany(
        $relation,
        array $models,
        ?callable $saveCallback = null,
        ?callable $deleteCallback = null
    ) {
        $relatedClass = $this->$relation()->getRelated();
        $existingModels = $this->$relation->keyBy('id');

        $newModelIdArr = [];

        foreach ($models as $modelData) {
            $id = $modelData['id'] ?? null;
            $relatedModel = $id ? ($existingModels[$id] ?? null) : null;
            $foreignAttrs = [$this->$relation()->getForeignKeyName() => $this->getKey()];
            $attributes = array_merge($modelData, $foreignAttrs);

            if ($relatedModel) {
                $saveCallback ? $saveCallback($relatedModel, $attributes) : null;
                $relatedModel->fill($attributes)->save();
            } else {
                $newModel = $relatedClass->newInstance($attributes);
                $saveCallback ? $saveCallback($newModel, $attributes) : null;

                if ($saved = $this->$relation()->save($newModel)) {
                    $newModelIdArr[] = $saved->id;
                }
            }
        }

        $existsIdArr = array_column($models, 'id');
        $idArr = array_merge($existsIdArr, $newModelIdArr);

        if ($deleteCallback) {
            $this->$relation()->whereNotIn('id', $idArr)->get()->each(
                function ($deletedModel) use ($deleteCallback) {
                    $deleteCallback ? $deleteCallback($deletedModel) : null;
                    $deletedModel->delete();
                }
            );
        } else {
            $this->$relation()->whereNotIn('id', $idArr)->delete();
        }
    }
}
