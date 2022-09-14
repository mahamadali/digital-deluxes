<?php

namespace Models\Traits;

use Bones\Database;
use Bones\Str;
use Bones\DatabaseException;
use Models\Base\Model;
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

    public function ___build($model, $attributes)
    {
        return $this->attachBehaviour($model, $attributes);
    }

    public function ___attachBehaviour($model, $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $model->$attribute = $value;
        }

        if (!$this->skip_attaches) {
            foreach ($this->attaches as $attach) {
                $model->$attach = $model->$attach;
            }
        }

        if (!empty($this->transforms)) {
            foreach ($model as $element_name => &$element_val) {
                if (array_key_exists($element_name, $this->transforms) && !in_array($element_name, $this->hidden)) {
                    $element_val = $this->transformElement($this->transforms[$element_name], $element_val, 'get');
                }
            }
        }

        foreach ($attributes as $attribute => $value) {
            $attribute_method = 'get'.Str::decamelize($attribute).'Property';
            if (method_exists($this->model, $attribute_method)) {
                $model->$attribute = $model->$attribute_method();
            }
        }

        foreach ($this->hidden as $confidential_attr) {
            if (isset($model->$confidential_attr)) 
                unset($model->$confidential_attr);
        }

        if ($this->isCloned()) {
            $model->setCloned(true);
        }

        return $model;
    }

    public function circularWiths($model)
    {
        $circular_withs = [];
        foreach ($model->with as $relative_with) {
            $relative_with_obj = $model->$relative_with();
            if (get_class($relative_with_obj) === get_class($this))
                $circular_withs[] = $relative_with;
        }

        return $circular_withs;
    }

    public function ___buildRelationalData($with, $entries, $result, $relational_props)
    {
        if ($this->skip_relationships) return $result;
        
        if ($relational_props['type'] == 'hasMany') {
                    
            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relational_props) {
                    return $item->{$relational_props['local_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relational_props['local_key']}];
            }

            if (!empty($forein_key_values_to_map)) {
                $related_model_obj = new $relational_props['related_model']();
                $related_model_obj = $related_model_obj->___clearWhere()->without($this->circularWiths($related_model_obj))->whereIn($relational_props['foreign_key'], array_unique($forein_key_values_to_map));

                if (!empty($this->where_has && !empty($this->where_has[$with])))
                    $related_model_obj = call_user_func_array($this->where_has[$with], [$related_model_obj]);

                $relational_data = $related_model_obj->get();

                if ($relational_data != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            $entry->$with = [];
                            foreach ($relational_data as $withEntry) {
                                if ($entry->{$relational_props['local_key']} == $withEntry->{$relational_props['foreign_key']}) {
                                    $entry->$with[] = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relational_data;
                    }
                } else {
                    $result = $this->setDroplets($result, $with, []);
                }
            }

        } else if ($relational_props['type'] == 'parallelTo') {

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relational_props) {
                    return $item->{$relational_props['foreign_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relational_props['foreign_key']}];
            }

            if (!empty($forein_key_values_to_map)) {
                $related_model_obj = new $relational_props['related_model']();
                
                $related_model_obj = $related_model_obj->___clearWhere()->without($this->circularWiths($related_model_obj))->whereIn($relational_props['local_key'], array_unique($forein_key_values_to_map));

                if (!empty($this->where_has && !empty($this->where_has[$with])))
                    $related_model_obj = call_user_func_array($this->where_has[$with], [$related_model_obj]);

                $relational_data = $related_model_obj->get();

                if ($relational_data != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            $entry->$with = null;
                            foreach ($relational_data as $withEntry) {
                                if ($entry->{$relational_props['foreign_key']} == $withEntry->{$relational_props['local_key']}) {
                                    $entry->$with = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relational_data[0];
                    }
                } else {
                    $result = $this->setDroplets($result, $with, null);
                }
            }

        } else if ($relational_props['type'] == 'hasOne') {

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($relational_props) {
                    return $item->{$relational_props['local_key']};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$relational_props['local_key']}];
            }

            if (!empty($forein_key_values_to_map)) {
                $related_model_obj = new $relational_props['related_model']();
                
                $related_model_obj = $related_model_obj->___clearWhere()->without($this->circularWiths($related_model_obj))->whereIn($relational_props['foreign_key'], array_unique($forein_key_values_to_map));

                if (!empty($this->where_has && !empty($this->where_has[$with])))
                    $related_model_obj = call_user_func_array($this->where_has[$with], [$related_model_obj]);

                $relational_data = $related_model_obj->get();

                if ($relational_data != null) {
                    if (is_array($entries)) {
                        foreach ($result as &$entry) {
                            $entry->$with = null;
                            foreach ($relational_data as $withEntry) {
                                if ($entry->{$relational_props['local_key']} == $withEntry->{$relational_props['foreign_key']}) {
                                    $entry->$with = $withEntry;
                                }
                            }
                        }
                    } else {
                        $result->$with = $relational_data[0];
                    }
                } else {
                    $result = $this->setDroplets($result, $with, null);
                }
            }

        } else if ($relational_props['type'] == 'hasOneVia') {

            $final_model = $relational_props['final_model'];
            $intermediate_model = $relational_props['intermediate_model'];
            $intermediate_model_foreign_key = $relational_props['intermediate_model_foreign_key'];
            $final_model_foreign_key = $relational_props['final_model_foreign_key'];
            $local_key = $relational_props['local_key'];
            $intermediate_model_local_key = $relational_props['intermediate_model_local_key'];

            if (is_array($entries)) {
                $intermediate_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $intermediate_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($intermediate_values_to_map)) {
                $intermediate_obj = new $intermediate_model();
                $intermediate_data = $intermediate_obj->___clearWhere()->without($this->circularWiths($intermediate_obj))->select($intermediate_model_local_key, $intermediate_model_foreign_key)->whereIn($intermediate_model_foreign_key, array_unique($intermediate_values_to_map))->___clearLimit()->getAsArray();
                
                if (!empty($intermediate_data)) {
                    $final_model_obj = new $final_model();
                    $final_model_obj = $final_model_obj->___clearWhere()->without($this->circularWiths($final_model_obj))->whereIn($final_model_foreign_key, array_column($intermediate_data, $intermediate_model_foreign_key));

                    if (!empty($this->where_has && !empty($this->where_has[$with])))
                        $final_model_obj = call_user_func_array($this->where_has[$with], [$final_model_obj]);

                    $final_data = $final_model_obj->get();

                    if (!empty($final_data)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                $entry->$with = null;
                                foreach ($intermediate_data as $intermediate) {
                                    if ($intermediate[$intermediate_model_foreign_key] == $entry->{$local_key}) {
                                        $loop = true;
                                        foreach ($final_data as $final) {
                                            if ($loop && $final->{$final_model_foreign_key} == $intermediate[$intermediate_model_local_key]) {
                                                $entry->$with = $final;
                                                $loop = false;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $final_data[0];
                        }
                    } else {
                        $result = $this->setDroplets($result, $with, null);
                    }

                }

            }

        } else if ($relational_props['type'] == 'hasManyVia') {

            $final_model = $relational_props['final_model'];
            $intermediate_model = $relational_props['intermediate_model'];
            $intermediate_model_foreign_key = $relational_props['intermediate_model_foreign_key'];
            $final_model_foreign_key = $relational_props['final_model_foreign_key'];
            $local_key = $relational_props['local_key'];
            $intermediate_model_local_key = $relational_props['intermediate_model_local_key'];

            if (is_array($entries)) {
                $intermediate_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $intermediate_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($intermediate_values_to_map)) {
                $intermediate_obj = new $intermediate_model();
                $intermediate_data = $intermediate_obj->___clearWhere()->without($this->circularWiths($intermediate_obj))->select($intermediate_model_local_key, $intermediate_model_foreign_key)->whereIn($intermediate_model_foreign_key, array_unique($intermediate_values_to_map))->___clearLimit()->getAsArray();
                
                if (!empty($intermediate_data)) {
                    $final_model_obj = new $final_model();
                    $final_model_obj = $final_model_obj->___clearWhere()->without($this->circularWiths($final_model_obj))->whereIn($final_model_foreign_key, array_column($intermediate_data, array_unique($intermediate_model_foreign_key)));

                    if (!empty($this->where_has && !empty($this->where_has[$with])))
                        $final_model_obj = call_user_func_array($this->where_has[$with], [$final_model_obj]);

                    $final_data = $final_model_obj->get();

                    if (!empty($final_data)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                $entry->$with = [];
                                foreach ($intermediate_data as $intermediate) {
                                    if ($intermediate[$intermediate_model_foreign_key] == $entry->{$local_key}) {
                                        $loop = true;
                                        foreach ($final_data as $final) {
                                            if ($loop && $final->{$final_model_foreign_key} == $intermediate[$intermediate_model_local_key]) {
                                                $entry->$with[] = $final;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $final_data;
                        }
                    } else {
                        $result = $this->setDroplets($result, $with, []);
                    }

                }

            }

        } else if ($relational_props['type'] == 'belongsToMany') {

            $final_model = $relational_props['final_model'];
            $intermediate_table = $relational_props['intermediate_table'];
            $intermediate_to_primary_foreign_key = $relational_props['intermediate_to_primary_foreign_key'];
            $intermediate_to_secondary_foreign_key = $relational_props['intermediate_to_secondary_foreign_key'];
            $local_key = $relational_props['local_key'];
            $final_model_local_key = $relational_props['final_model_local_key'];

            if (is_array($entries)) {
                $forein_key_values_to_map = array_map(function($item) use ($local_key) {
                    return $item->{$local_key};
                }, $entries);
            } else {
                $forein_key_values_to_map = [$entries->{$local_key}];
            }

            if (!empty($forein_key_values_to_map)) {
                
                $intermediate_data = Database::getInstance()->__clearWhere();
                $intermediate_data = $intermediate_data->whereIn($intermediate_to_primary_foreign_key, array_unique($forein_key_values_to_map))->pluck([$intermediate_to_primary_foreign_key, $intermediate_to_secondary_foreign_key], $intermediate_table);
                
                if (!empty($intermediate_data)) {
                    $final_model_obj = new $final_model();
                    $final_model_obj = $final_model_obj->___clearWhere()->without($this->circularWiths($final_model_obj))->whereIn($final_model_local_key, array_unique(array_column($intermediate_data, $intermediate_to_secondary_foreign_key)));

                    if (!empty($this->where_has && !empty($this->where_has[$with])))
                        $final_model_obj = call_user_func_array($this->where_has[$with], [$final_model_obj]);

                    $final_data = $final_model_obj->get();

                    if (!empty($final_data)) {
                        if (is_array($entries)) {
                            foreach ($result as &$entry) {
                                $entry->$with = [];
                                foreach ($intermediate_data as $intermediate) {
                                    if ($intermediate->$intermediate_to_primary_foreign_key == $entry->{$local_key}) {
                                        $loop = true;
                                        foreach ($final_data as $final) {
                                            if ($loop && $final->{$final_model_local_key} == $intermediate->$intermediate_to_secondary_foreign_key) {
                                                $entry->$with[] = $final;
                                            }
                                        }
                                    }
                                }    
                            }
                        } else {
                            $result->$with = $final_data;
                        }
                    } else {
                        $result = $this->setDroplets($result, $with, []);
                    }
                    
                }
            }

        }

        return $result;
    }

    public function ___setDroplets($set, $to, $droplet)
    {
        if (!$this->skip_relationships) {
            if ($set instanceof Model) 
                $set->$to = $droplet;
            else
                foreach ($set as &$entry) {
                    if (gettype($entry) == 'object') {
                        $entry->$to = $droplet;
                    }
                }
        }

        return $set;
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