<?php

namespace Models\Traits;

use Bones\Str;
use JollyException\DatabaseException;
use Models\Base\Supporters\Transform;

trait SelfResolve
{
    protected $reserved_props = ['_reserved_model_prop_is_only', '_reserved_model_prop_is_cloned'];

    public function ___save()
    {
        $modelData = [];

        foreach ($this->dynamicAttributes as $attribute) {
            if (!in_array($attribute, array_merge($this->attaches, $this->reserved_props)))
                $modelData[$attribute] = $this->$attribute;
        }

        if ($this->isCloned()) {
            $modelData[$this->primary_key] = null;
        }

        if (!empty($modelData[$this->primary_key])) {
            if ($this->___clearWhere()->where($this->primary_key, $modelData[$this->primary_key])->update($modelData)) {
                return $this->___clearWhere()->where($this->primary_key, $modelData[$this->primary_key])->first();
            } else {
                throw new DatabaseException('Database error occured while updating ' . $this->model . ' for {' . $this->primary_key . '} with "'. $modelData[$this->primary_key] .'"');
            }
        } else {
            return $this->___clearWhere()->insert($modelData);
        }

        throw new DatabaseException('Database error occured while saving ' . $this->model);
    }

    public function ___clone()
    {
        $clone = (new $this->model());

        foreach ($this->dynamicAttributes as $attrName) {
            $clone->$attrName = $this->$attrName;
        }

        return $clone->___clearWhere()->where($clone->primary_key, $clone->{$clone->primary_key})->setCloned(true)->first();
    }

    public function ___build($modelObj, $attributes)
    {
        return $this->attachBehaviour($modelObj, $attributes);
    }

    public function ___attachBehaviour($modelObj, $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $modelObj->$attribute = $value;
        }

        foreach ($this->attaches as $attach) {
            $modelObj->$attach = $modelObj->$attach;
        }

        foreach ($this->with as $with) {

            if (in_array($with, $this->without)) continue;

            $modelObj->$with = $modelObj->$with();
            if (!empty($executableCallRelated = $modelObj->callRelated($with))) {
                if (is_object($modelObj->$with)) {
                    $modelObj->$with = $modelObj->$with->$executableCallRelated();
                }
            }
        }

        if (!empty($this->where_has)) {
            foreach ($this->where_has as $relation => $whereHas) {
                $modelObj->$relation = $modelObj->$relation();
                if (!empty($whereHas) && !empty($executableCallRelated = $modelObj->callRelated($relation))) {
                    $modelObj->$relation = call_user_func_array($whereHas, [$modelObj->$relation])->$executableCallRelated();
                    if (empty($modelObj->$relation)) {
                        $modelObj = null;
                    }
                }
            }
        }

        if (!empty($this->transforms)) {
            foreach ($modelObj as $elementName => &$elementVal) {
                if (array_key_exists($elementName, $this->transforms) && !in_array($elementName, $this->hidden)) {
                    $elementVal = $this->transformElement($this->transforms[$elementName], $elementVal, 'get');
                }
            }
        }

        foreach ($attributes as $attribute => $value) {
            $attributeMehod = 'get'.Str::decamelize($attribute).'Property';
            if (method_exists($this->model, $attributeMehod)) {
                $modelObj->$attribute = $modelObj->$attributeMehod();
            }
        }

        foreach ($this->hidden as $confidentialAttr) {
            if (isset($modelObj->$confidentialAttr)) 
                unset($modelObj->$confidentialAttr);
        }

        if ($this->isCloned()) {
            $modelObj->setCloned(true);
        }

        return $modelObj;
    }

    public function ___transformElement($transformType, $value, $operation = 'set')
    {
        return (new Transform($transformType, $value, $operation))->mutate();
    }

    public function ___isSelfOnly()
    {
        return (!empty($this->_reserved_model_prop_is_only) && $this->_reserved_model_prop_is_only);
    }

    public function ___setSelfOnly($isOnly = false)
    {
        $this->_reserved_model_prop_is_only = $isOnly;
        return $this;
    }

    public function ___isCloned()
    {
        return (!empty($this->_reserved_model_prop_is_cloned) && $this->_reserved_model_prop_is_cloned);
    }

    public function ___setCloned($isCloned = false)
    {
        $this->_reserved_model_prop_is_cloned = $isCloned;
        return $this;
    }
    
}