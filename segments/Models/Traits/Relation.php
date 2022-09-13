<?php

namespace Models\Traits;

use Bones\Database;
use Bones\Str;
use Closure;
use Bones\BadMethodException;
use Bones\ModelRelationException;

trait Relation
{
    protected $relation_captions = [];
    protected $where_has = [];

    public function hasMany($relatedModel, $foreignKey = null, $localKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $foreignKey = (!empty($foreignKey)) ? $foreignKey :  Str::singular($this->table) . '_id';
        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;

        $relatedModel = new $relatedModel();

        if (!empty($this->$localKey)) {
            $relatedModel = $relatedModel->where($foreignKey, $this->$localKey);
        }

        return $relatedModel->relationalProps([
            'local_model' => $this->model,
            'related_model' => $relatedModel->model,
            'foreign_key' => $foreignKey,
            'local_key' => $localKey,
            'type' => __FUNCTION__
        ]);
    }

    public function hasOne($relatedModel, $foreignKey = null, $localKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $foreignKey = (!empty($foreignKey)) ? $foreignKey :  Str::singular($relatedModel->table) . '_id';
        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;

        $relatedModel = new $relatedModel();
        
        if (!empty($this->$localKey)) {
            $relatedModel = $relatedModel->where($foreignKey, $this->$localKey);
        }

        return $relatedModel->relationalProps([
            'local_model' => $this->model,
            'related_model' => $relatedModel->___clearWhere()->model,
            'foreign_key' => $foreignKey,
            'local_key' => $localKey,
            'type' => __FUNCTION__
        ]);
    }

    public function parallelTo($relatedModel, $foreignKey = null, $localKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $relatedModel = new $relatedModel();
        $foreignKey = (!empty($foreignKey)) ? $foreignKey : Str::singular($relatedModel->table) . '_id';
        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;

        return $relatedModel->relationalProps([
            'local_model' => $this->___clearWhere()->model,
            'related_model' => $relatedModel->___clearWhere()->model,
            'foreign_key' => $foreignKey,
            'local_key' => $localKey,
            'type' => __FUNCTION__
        ]);
    }

    public function hasOneVia($finalModel, $interMediateModel, $interMediateForeignKey = null, $finalModelForeignKey = null, $localKey = null, $interMediateLocalKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $finalModel = new $finalModel();
        $interMediateModel = new $interMediateModel();

        $interMediateForeignKey = (!empty($interMediateForeignKey)) ? $interMediateForeignKey : Str::singular($this->table) . '_id';
        $finalModelForeignKey = (!empty($finalModelForeignKey)) ? $finalModelForeignKey : Str::singular($interMediateModel->table) . '_id';

        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;
        $interMediateLocalKey = (!empty($interMediateLocalKey)) ? $interMediateLocalKey : $interMediateModel->primary_key;

        $interMediateModel = $interMediateModel
                ->select($interMediateLocalKey, 'has_one_via_intermediate_model_value_' . $interMediateLocalKey)
                ->leftJoin($this->table, $this->table . '.' . $localKey . '=' . $interMediateModel->table . '.' . $interMediateForeignKey);

        if (!empty($this->$localKey)) {
            $interMediateModel = $interMediateModel->where($interMediateForeignKey, $this->$localKey);
        }

        $intersectData = array_column($interMediateModel->getAsArray(), $interMediateLocalKey);
        
        if (empty($intersectData)) {
            $intersectData = [-1];
        }

        return $finalModel->whereIn($localKey, $intersectData)->limit(1)->relationalProps([
            'final_model' => $finalModel->model,
            'intermediate_model' => $interMediateModel->model,
            'intermediate_model_foreign_key' => $interMediateForeignKey,
            'final_model_foreign_key' => $finalModelForeignKey,
            'local_key' => $localKey,
            'intermediate_model_local_key' => $interMediateLocalKey,
            'type' => __FUNCTION__
        ]);
    }

    public function hasManyVia($finalModel, $interMediateModel, $interMediateForeignKey = null, $finalModelForeignKey = null, $localKey = null, $interMediateLocalKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $finalModel = new $finalModel();
        $interMediateModel = new $interMediateModel();

        $interMediateForeignKey = (!empty($interMediateForeignKey)) ? $interMediateForeignKey : Str::singular($this->table) . '_id';
        $finalModelForeignKey = (!empty($finalModelForeignKey)) ? $finalModelForeignKey : Str::singular($interMediateModel->table) . '_id';

        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;
        $interMediateLocalKey = (!empty($interMediateLocalKey)) ? $interMediateLocalKey : $interMediateModel->primary_key;

        $interMediateModel = $interMediateModel
                ->select($interMediateLocalKey, 'has_one_via_intermediate_model_value_' . $interMediateLocalKey)
                ->leftJoin($this->table, $this->table . '.' . $localKey . '=' . $interMediateModel->table . '.' . $interMediateForeignKey);

        if (!empty($this->$localKey)) {
            $interMediateModel = $interMediateModel->where($interMediateForeignKey, $this->$localKey);
        }

        $intersectData = array_column($interMediateModel->getAsArray(), $interMediateLocalKey);
        
        if (empty($intersectData)) {
            $intersectData = [-1];
        }

        return $finalModel->whereIn($finalModelForeignKey, $intersectData)->relationalProps([
            'final_model' => $finalModel->model,
            'intermediate_model' => $interMediateModel->model,
            'intermediate_model_foreign_key' => $interMediateForeignKey,
            'final_model_foreign_key' => $finalModelForeignKey,
            'local_key' => $localKey,
            'intermediate_model_local_key' => $interMediateLocalKey,
            'type' => __FUNCTION__
        ]);
    }

    public function belongsToMany($finalModel, $interMediateTable = null, $primaryForeignKey = null, $secondaryForeignKey =  null, $localKey = null, $finalModelLocalKey = null)
    {
        $this->setRelationCaption(__FUNCTION__);

        $finalModel = new $finalModel();
        
        $interMediateTable = (!empty($interMediateTable)) ? $interMediateTable : strtolower(basename($finalModel->model)) . '_' . strtolower(basename($this->model));
        $primaryForeignKey = (!empty($primaryForeignKey)) ? $primaryForeignKey : Str::singular($this->table) . '_id';
        $secondaryForeignKey = (!empty($secondaryForeignKey)) ? $secondaryForeignKey : Str::singular(strtolower($finalModel->table)) . '_id';
        $localKey = (!empty($localKey)) ? $localKey : $this->primary_key;
        $finalModelLocalKey = (!empty($finalModelLocalKey)) ? $finalModelLocalKey : $finalModel->primary_key;

        if (!empty($this->{$localKey})) {
            $interMediateData = Database::getInstance();
            $interMediateModel = $interMediateData->where($primaryForeignKey, $this->{$localKey});
            $intersectData = array_column(
            (
                $interMediateModel->__getRows(null, $secondaryForeignKey, $interMediateTable)
            ), $secondaryForeignKey);
        }
        
        if (empty($intersectData)) {
            $intersectData = [-1];
        }

        return $finalModel->whereIn('id', $intersectData)->relationalProps([
            'final_model' => $finalModel->model,
            'intermediate_table' => $interMediateTable,
            'intermediate_to_primary_foreign_key' => $primaryForeignKey,
            'intermediate_to_secondary_foreign_key' => $secondaryForeignKey,
            'local_key' => $localKey,
            'final_model_local_key' => $finalModelLocalKey,
            'type' => __FUNCTION__
        ]);

    }

    public function ___whereHas($with, Closure $closure = null)
    {
        $this->where_has[$with] = $closure;

        return $this;
    }

    public function add(...$args)
    {
        if (!empty($relation = $this->getRelation('hasOne'))) {

            $relatedModel = $relation['related_model']->___clearWhere();

            $checkRelatedDataExistence = $relatedModel->where($relation['foreign_key'], $relation['related_model_value'])->first();

            if (!empty($checkRelatedDataExistence) && !empty($checkRelatedDataExistence->{$relation['foreign_key']})) {
                throw new ModelRelationException($relation['local_model'] . ' has defined ' . $relation['type'] . '() relation with ' . $relatedModel->model . '() so ' . '{' . $relation['foreign_key'] . '} column with value {' . $relation['related_model_value'] . '} must have only one entry in {' . $relatedModel->table . '} table');
            }

            $args[0][$relation['foreign_key']] = $relation['related_model_value'];

            $this->relationalProps = null;

            return $relatedModel->___create($args[0]);
        } else if (!empty($relation = $this->getRelation('hasMany'))) {

            if (is_array($args[0])) {

                $relatedModel = $relation['related_model']->___clearWhere();

                if (count($args[0]) == count($args[0], COUNT_RECURSIVE)) {
                    $args[0][$relation['foreign_key']] = $relation['related_model_value'];
                    return $relatedModel->insert($args[0]);
                } else {
                    array_walk($args[0], function (&$insertSet) use ($relation) {
                        $insertSet[$relation['foreign_key']] = $relation['related_model_value'];
                    });
                    return $relatedModel->insertMulti($args[0]);
                }
            }
        } else if (!empty($relation = $this->getRelation('parallelTo'))) {
            throw new ModelRelationException('Bad Usage: parallelTo is one to one bind relational so associated value can be set with update function on model directly');
        }

        throw new BadMethodException('Method {' . __FUNCTION__ . '} not found on ' . $this->model);
    }

    public function remove()
    {
        if (!empty($relation = $this->getRelation('hasOne')) || !empty($relation = $this->getRelation('hasMany'))) {

            $relatedModel = $relation['related_model']->___clearWhere();

            return $relatedModel->where($relation['foreign_key'], $relation['related_model_value'])->___delete();
        } else if (!empty($relation = $this->getRelation('parallelTo'))) {

            $relatedModel = $relation['related_model'];
            return $relatedModel->___delete();
        }

        throw new BadMethodException('Method {' . __FUNCTION__ . '} not found on ' . $this->model);
    }

    public function latestOfSet(string $sortingColumn = null)
    {
        if (empty($sortingColumn)) $sortingColumn = $this->primary_key;

        if (!empty($relation = $this->getRelation('hasOne'))) {
                $relatedModel = $relation['related_model']->orderBy($sortingColumn, 'DESC')->limit(1);

            return $relatedModel;
        }
    }

    public function oldestOfSet(string $sortingColumn = null)
    {
        if (empty($sortingColumn)) $sortingColumn = $this->primary_key;

        if (!empty($relation = $this->getRelation('hasOne'))) {
            if (empty($sortingValue))
                $relatedModel = $relation['related_model']->orderBy($sortingColumn, 'ASC')->limit(1);

            return $relatedModel;
        }
    }

    public function getRelation($relation = '')
    {
        if (empty($relation)) return null;

        if (!empty($this->relationalProps)) {
            if (!empty($this->relationalProps['related_model']) && !empty($this->relationalProps['type'])) {
                if ($this->relationalProps['type'] == $relation) {
                    return $this->relationalProps;
                }
            }
        }

        return null;
    }

    public function setRelationCaption($relation)
    {
        $this->relation_captions[debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[2]['function']] = $relation;
    }

    public function callRelated($relationCaption)
    {
        if (empty($this->relation_captions[$relationCaption])) {
            throw new ModelRelationException('{'.$relationCaption . '} Relation not attached to ' . $this->model);
        }

        $relation = $this->relation_captions[$relationCaption];

        switch ($relation) {
            case 'hasMany':
                $retrievalCommand = 'get';
                break;
            case 'hasOne':
                $retrievalCommand = 'first';
                break;
            case 'parallelTo':
                $retrievalCommand = 'first';
                break;
            case 'hasOneVia':
                $retrievalCommand = 'first';
                break;
            case 'hasManyVia':
                $retrievalCommand = 'get';
                break;
            case 'belongsToMany':
                $retrievalCommand = 'get';
                break;
            default:
                $retrievalCommand = null;
                break;
        }

        return $retrievalCommand;
    }
}