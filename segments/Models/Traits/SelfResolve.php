<?php

namespace Models\Traits;

use Bones\Database;
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

        // foreach ($this->with as $with) {

        //     if (in_array($with, $this->without)) continue;

        //     $modelObj->$with = $modelObj->$with();
        //     if (!empty($executableCallRelated = $modelObj->callRelated($with))) {
        //         if (is_object($modelObj->$with)) {
        //             $modelObj->$with = $modelObj->$with->$executableCallRelated();
        //         }
        //     }
        // }

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

    public function ___buildRelationalData($with, $entries, $result, $relationalProps)
    {
        // $result->$with = null;
        
        if ($relationalProps['type'] == 'hasMany') {
                    
            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relationalProps) {
                    return $item->{$relationalProps['local_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relationalProps['local_key']}];
            }

            if (!empty($forein_key_values_to_map)) {
                
                $relationalData = (new $relationalProps['related_model']())->___clearWhere()->whereIn($relationalProps['foreign_key'], $forein_key_values_to_map)->get();

                // $result->$with = [];

                if ($relationalData != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            foreach ($relationalData as $withEntry) {
                                if ($entry->{$relationalProps['local_key']} == $withEntry->{$relationalProps['foreign_key']}) {
                                    if (!isset($entry->$with)) $entry->$with = [];
                                    $entry->$with[] = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relationalData;
                    }
                }
            }

        } else if ($relationalProps['type'] == 'parallelTo') {

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relationalProps) {
                    return $item->{$relationalProps['foreign_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relationalProps['foreign_key']}];
            }

            if (!empty($forein_key_values_to_map)) {

                $relationalData = (new $relationalProps['related_model']())->___clearWhere()->whereIn($relationalProps['local_key'], $forein_key_values_to_map)->get();

                if ($relationalData != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            foreach ($relationalData as $withEntry) {
                                if ($entry->{$relationalProps['foreign_key']} == $withEntry->{$relationalProps['local_key']}) {
                                    if (!isset($entry->$with)) $entry->$with = null;
                                    $entry->$with = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relationalData[0];
                    }
                }
            }

        } else if ($relationalProps['type'] == 'hasOne') {

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relationalProps) {
                    return $item->{$relationalProps['local_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relationalProps['local_key']}];
            }

            if (!empty($forein_key_values_to_map)) {

                $relationalData = (new $relationalProps['related_model']())->___clearWhere()->whereIn($relationalProps['foreign_key'], $forein_key_values_to_map)->get();

                if ($relationalData != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            foreach ($relationalData as $withEntry) {
                                if ($entry->{$relationalProps['local_key']} == $withEntry->{$relationalProps['foreign_key']}) {
                                    if (!isset($entry->$with)) $entry->$with = null;
                                    $entry->$with = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relationalData[0];
                    }
                }
            }

        } else if ($relationalProps['type'] == 'hasOneVia') {

            $final_model = $relationalProps['final_model'];
            $intermediate_model = $relationalProps['intermediate_model'];
            $intermediate_model_foreign_key = $relationalProps['intermediate_model_foreign_key'];
            $final_model_foreign_key = $relationalProps['final_model_foreign_key'];
            $local_key = $relationalProps['local_key'];
            $intermediate_model_local_key = $relationalProps['intermediate_model_local_key'];

            if (is_array($entries)) {
                $intermediate_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $intermediate_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($intermediate_values_to_map)) {
                $interMediateData = (new $intermediate_model())->___clearWhere()->select($intermediate_model_local_key, $intermediate_model_foreign_key)->whereIn($intermediate_model_foreign_key, $intermediate_values_to_map)->___clearLimit()->getAsArray();
                
                if (!empty($interMediateData)) {
                    $finalData = (new $final_model())->___clearWhere()->whereIn($final_model_foreign_key, array_column($interMediateData, $intermediate_model_foreign_key))->get();

                    if (!empty($finalData)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                foreach ($interMediateData as $interMediate) {
                                    if ($interMediate[$intermediate_model_foreign_key] == $entry->{$local_key}) {
                                        if (!isset($entry->$with)) $entry->$with = null;
                                        $loop = true;
                                        foreach ($finalData as $final) {
                                            if ($loop && $final->{$final_model_foreign_key} == $interMediate[$intermediate_model_local_key]) {
                                                $entry->$with = $final;
                                                $loop = false;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $finalData[0];
                        }
                    }

                }

            }

        } else if ($relationalProps['type'] == 'hasManyVia') {

            $final_model = $relationalProps['final_model'];
            $intermediate_model = $relationalProps['intermediate_model'];
            $intermediate_model_foreign_key = $relationalProps['intermediate_model_foreign_key'];
            $final_model_foreign_key = $relationalProps['final_model_foreign_key'];
            $local_key = $relationalProps['local_key'];
            $intermediate_model_local_key = $relationalProps['intermediate_model_local_key'];

            if (is_array($entries)) {
                $intermediate_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $intermediate_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($intermediate_values_to_map)) {
                $interMediateData = (new $intermediate_model())->___clearWhere()->select($intermediate_model_local_key, $intermediate_model_foreign_key)->whereIn($intermediate_model_foreign_key, $intermediate_values_to_map)->___clearLimit()->getAsArray();
                
                if (!empty($interMediateData)) {
                    $finalData = (new $final_model())->___clearWhere()->whereIn($final_model_foreign_key, array_column($interMediateData, $intermediate_model_foreign_key))->get();

                    if (!empty($finalData)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                foreach ($interMediateData as $interMediate) {
                                    if ($interMediate[$intermediate_model_foreign_key] == $entry->{$local_key}) {
                                        if (!isset($entry->$with)) $entry->$with = [];
                                        $loop = true;
                                        foreach ($finalData as $final) {
                                            if ($loop && $final->{$final_model_foreign_key} == $interMediate[$intermediate_model_local_key]) {
                                                $entry->$with[] = $final;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $finalData;
                        }
                    }

                }

            }

        } else if ($relationalProps['type'] == 'belongsToMany') {

            // opd($relationalProps);

            $final_model = $relationalProps['final_model'];
            $intermediate_table = $relationalProps['intermediate_table'];
            $intermediate_to_primary_foreign_key = $relationalProps['intermediate_to_primary_foreign_key'];
            $intermediate_to_secondary_foreign_key = $relationalProps['intermediate_to_secondary_foreign_key'];
            $local_key = $relationalProps['local_key'];
            $final_model_local_key = $relationalProps['final_model_local_key'];

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($forein_key_values_to_map)) {
                
                $interMediateData = Database::getInstance()->__clearWhere();
                $interMediateData = $interMediateData->whereIn($intermediate_to_primary_foreign_key, $forein_key_values_to_map)->pluck([$intermediate_to_primary_foreign_key, $intermediate_to_secondary_foreign_key], $intermediate_table);
                
                if (!empty($interMediateData)) {
                    $finalData = (new $final_model())->___clearWhere()->whereIn($final_model_local_key, array_column($interMediateData, $intermediate_to_secondary_foreign_key))->get();

                    if (!empty($finalData)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                foreach ($interMediateData as $interMediate) {
                                    if ($interMediate->$intermediate_to_primary_foreign_key == $entry->{$local_key}) {
                                        if (!isset($entry->$with)) $entry->$with = [];
                                        $loop = true;
                                        foreach ($finalData as $final) {
                                            if ($loop && $final->{$final_model_local_key} == $interMediate->$intermediate_to_secondary_foreign_key) {
                                                $entry->$with[] = $final;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $finalData;
                        }
                    }
                    
                }
            }

        }

        return $result;
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