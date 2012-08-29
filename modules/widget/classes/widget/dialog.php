<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Dialog extends Widget {

    protected $_data = array(
        'data' => array(),
        'fields' => array(),
        'columns' => array(),
        'item_name' => '',
        'action' => '',
        'key_data' => array(),
        'extra_fields' => array(),
        // client data
        'client_name' => NULL,
        'client' => NULL,
        'is_translations' => FALSE,
    );

    public function set_data($data, $allow_actions = array())
    {
        // check action
        $this->action = Arr::get($data, 'action', '');
        if (empty($this->action) || !in_array($this->action, $allow_actions))
        {
            return FALSE;
        }

        // check key data
        $keys = Arr::get($data, 'key', array());
        $values = Arr::get($data, 'val', array());
        $this->key_data = (!empty($keys) && count($keys) == count($values))
            ? array_combine($keys, $values)
            : array();
        $this->extra_fields = Arr::get($data, 'extra', array());

        return TRUE;
    }

    public function init_client($data, $allow_actions = array(), $client_name = NULL,$translate = FALSE)
    {
        if (!$this->set_data($data, $allow_actions))
        {
            return FALSE;
        }
        if ($client_name === NULL)
        {
            $client_name = Arr::get($data, 'client');
        }
        $this->client_name = $client_name;
        if (empty($this->client_name))
        {
            return FALSE;
        }

        $client = Client::factory($this->client_name);
        if($translate)
            $client->get_fields_translations();
        if ( ! $client->get_module())
        {
            return FALSE;
        }
        foreach ($client->fields as $field => $data)
        {
            if (isset($data['type']) && ($data['type'] == 'image') && isset($data['path']))
            {
                $path = 'files/' .$data['path'] . strtolower($this->client_name);

                foreach ($this->key_data as $key)
                {
                    $path .= '_' . $key;
                }
                $path .= '_preview.png';
                if (file_exists($path))
                {
                    $this->data[$field] = $path;
                }
            }
            if (isset($data['type']) && ($data['type'] == 'html'))
            {
                $this->is_tinymce = TRUE;
            }
        }
        $this->bind('client', $client);
        $this->set(array(
            'fields' => $client->fields,
            'columns' => array_keys($this->client->fields_by_action($this->action)),
            'item_name' => $client->get_module(),
            'is_translations' => $client->is_translations,
        ));
        return TRUE;
    }

    public function init_client_ext($data, $allow_actions = array())
    {
        $action = Arr::get($data, 'action', '');
        if (isset($allow_actions[$action]))
        {
            $this->init_client($data, array_keys($allow_actions), $allow_actions[$action]);
        }
        return TRUE;
    }

    public function process_modify($data)
    {
        $errors = array();

        if (empty($this->key_data) && $this->action == 'edit')
        {
            $errors[] = __('Empty key data.');
        }
        else
        {
            $data = $this->key_data + $data;
        }
        //@TODO: check also primary key exists into data for edit
        //save user input data in temporary variable
        $user_data = $data;
        $data += Arr::parse($this->client->fields, NULL, 'default');
        //cycle for correctly save data not marked checkboxes.
        foreach ($data as $field => &$value)
        {
            if (!isset($user_data[$field]) && isset($this->client->fields[$field]))
            {
                if (isset($this->client->fields[$field]['type']) && ($this->client->fields[$field]['type'] == 'bool'))
                {
                    $value = FALSE;
                }
            }
        }
        //unset temporary variable
        unset($userdata);
        $typecasts = array_intersect_key(Arr::parse($this->client->fields, NULL, 'typecast'), $data);

        foreach ($typecasts as $field => $enabled)
        {
            if ($enabled && isset($this->client->fields[$field]['enum'], $this->client->fields[$field]['type']))
            {
                if ($this->client->fields[$field]['type'] == 'bool')
                {
                    $data[$field] = (bool)$data[$field];
                }
            }
        }

        $client_data = Input::filter($data, $this->columns);

        //check NULL in default for field
        foreach ($this->client->fields as $field => $field_data)
        {
            if (array_key_exists('default',$field_data) && ($field_data['default'] === NULL))
            {
                if ((!isset($client_data[$field])) || ($client_data[$field] == NULL))
                {
                    $client_data[$field] = NULL;
                }
            }
        }
        $this->client->set($client_data);

        if (!$this->client->validate())
        {
            $errors = array_merge($errors, $this->client->errors());
        }
        if (empty($errors) && !empty($client_data))
        {
            $result = FALSE;
            if ($this->action == 'add')
            {
                $result = $this->client->create();
                if ($result)
                {
                    $data['id'] = $result->id;
                    $this->created_id = $result->id; //to have access to the id of the new records without repeated requests
                }
            }
            else if ( ($this->action == 'edit') || ($this->action == 'edit_group') )
            {
                $result = $this->client->update();
            }
            if ($result)
            {
                if (!empty($data['translations']))
                {
                    $languages = Request::initial()->languages;
                    foreach ($data['translations'] as $language_id => $fields)
                    {
                        if (isset($languages[$language_id]))
                        {
                            foreach ($fields as $field => $translation)
                            {
                                if (empty($translation) || !isset($this->client->fields[$field]['translations']))
                                {
                                    continue;
                                }
                                $translations = &$this->client->fields[$field]['translations'];
                                if (!isset($data[$translations['field']]))
                                {
                                    continue;
                                }
                                $save_data = array(
                                    'parent_class' => $translations['class_name'],
                                    'subclass_name' => $translations['subclass_name'],
                                    'class_id' => $data[$translations['field']],
                                    'language_id' => $language_id,
                                );
                                $client = Client::factory('Translation');
                                $result = $client->find($save_data);

                                $client->clear();
                                if ($result)
                                {
                                    // update Translation
                                    if ($result['translation'] != $translation)
                                    {
                                        $result = $client->set(array(
                                            'id' => $result['id'],
                                            'translation' => $translation,
                                        ))->update();
                                    }
                                }
                                else
                                {
                                    $save_data += array(
                                        'translation' => $translation,
                                    ) + Arr::parse($client->fields, NULL, 'default');
                                    $result = $client->set($save_data)->create();
                                }
                            }
                        }
                    }
                }
            }
            if ( ! $result)
            {
                $errors = array_merge($errors, $this->client->errors());
            }
            foreach ($this->fields as $field => $dataset)
            {
                if ( isset($dataset['file']) && isset($client_data[$field])
                        && ($dataset['type'] == 'image') )
                {
                    $image = $client_data[$field];
                    if ($image['error'] == 4)
                    {
                        continue;
                    }

                    $type = explode('/',$image['type']);
                    if (!in_array(end($type),$dataset['file_type']) )
                    {
                        return array('Not valid image type.');
                    }

                    $img_module = Image::factory($image['tmp_name']);
                    if (!$img_module)
                    {
                        return false;
                    }
                    $path = 'files/'.$dataset['path'] . strtolower($this->client_name);
                    foreach ($this->key_data as $key)
                    {
                        $path .= '_' . $key;
                    }
                    if (empty($this->key_data))
                    {
                        $path .= '_' . $this->created_id;
                    }
                    $path = DOCROOT . $path;

                    $img_module->save($path . '.png');

                    if (array_key_exists('resize_to',$dataset) && (gettype($dataset['resize_to']) == 'array'))
                    {
                        $width = $dataset['resize_to']['width'];
                        $height = $dataset['resize_to']['height'];
                        $img_module->resize($width, $height, Image::NONE);
                        $img_module->save($path . '_preview.png');
                    }

                }
            }
        }
        return $errors;
    }

    public function process_delete()
    {
        $errors = array();
        //@TODO: check also primary key exists into data
        if (empty($this->key_data))
        {
            $errors[] = __('Empty key data.');
        }
        //@TODO: find & remove all isset translation for this item
        if (empty($errors))
        {
            $translations = Arr::parse($this->client->fields, NULL, 'translations');

            if (!empty($translations))
            {
                $removed = 0;
                foreach ($translations as $data)
                {
                    if (isset($data['class_name']))
                    {
                        $request['parent_class'] = $data['class_name'];
                    }
                    if (isset($data['subclass_name']))
                    {
                        $request['subclass_name'] = $data['subclass_name'];
                    }
                    if (isset($data['field']))
                    {
                        $request['class_id'] = $this->key_data[$data['field']];
                    }
                    else
                    {
                        $errors[] = __('Translation key not found in key_data.');
                        return $errors;
                    }

                    $dataset = Client::factory('Translation')->set(array(
                        'filter' => $request,
                    ))->find_all();

                    if (!$dataset->records)
                    {
                        continue;
                    }

                    $result = $dataset->records;
                    $translation = Arr::parse($result, NULL, 'id');
                    foreach ($translation as $id)
                    {
                        $remove = Client::factory('Translation')->set(array('id' => $id,))->remove();
                        if ($remove)
                        {
                            $removed++;
                        }
                    }
                    $removed = $removed - count($translation);
                }
                if ($removed != 0)
                {
                    $errors[] = __('Deleted not all finded translations for this item');
                }
            }
            if (empty($errors) && !$this->client->set($this->key_data)->remove())
            {
                $errors = array_merge($errors, $this->client->errors());
            }
        }
        if (empty($errors))
        {
            //find & remove all images for this item
            $files = Arr::parse($this->client->fields, NULL, 'file');
            if (!empty($files))
            {
                foreach ($this->client->fields as $field => $data)
                {
                    if (isset($data['file']) && $data['file'])
                    {
                        $path = 'files/'.$data['path'] . strtolower($this->client_name);
                        foreach ($this->key_data as $key)
                        {
                            $path .= '_' . $key;
                        }
                        $path = DOCROOT . $path;
                        if (is_file($path . '.png'))
                        {
                            unlink($path . '.png');
                        }
                        if (is_file($path . '_preview.png'))
                        {
                            unlink($path . '_preview.png');
                        }
                    }
                }
            }
        }
        return $errors;
    }

    public function default_process($data = array())
    {
        $errors = array();
        switch ($this->action)
        {
            case 'add':
            case 'edit_group':
            case 'edit':
                $errors = $this->process_modify($data);
                break;
            case 'delete':
                $errors = $this->process_delete();
                break;
        }
        return $errors;
    }

    public function get_response()
    {
        $dialog = array();
        switch ($this->action)
        {
            case 'add':
                // set view data
                //$this->data = array();
                $html = $this->render();
                // set dialog options
                $dialog = array(
                    'title' => __('Add {name}', array('{name}' => $this->item_name)),
                    'buttons' => array('add', 'cancel'),
                    'defaultButton' => 'add',
                    'html' => $html,
                );
                if (count($this->columns) > 8)
                {
                    $dialog['width'] = 700;
                }
                break;
            case 'edit_group':
            case 'edit':
                // set view data
                $this->data += $this->client->find($this->key_data);
                $html = $this->render();
                // set dialog options
                $dialog = array(
                    'title' => __('Edit {name}', array('{name}' => $this->item_name)),
                    'buttons' => array('save', 'cancel'),
                    'defaultButton' => 'save',
                    'html' => $html,
                );
                if (count($this->columns) > 8)
                {
                    $dialog['width'] = 700;
                }
                break;
            case 'delete':
                $dialog = array(
                    'title' => __('Delete {name}', array('{name}' => $this->item_name)),
                    'buttons' => array('yes', 'no'),
                    'defaultButton' => 'yes',
                    'message' => __('Are you sure you want to remove this record ?'),
                );
                break;
            default:
                $dialog = array(
                    'buttons' => array('close'),
                );
        }
        return array(
            'dialog' => $dialog + array(
                'submitUrl' => Request::initial()->uri().URL::query(Request::initial()->query()),
                'title' => __('Action with {name}', array('{name}' => $this->item_name)),
                'width' => 320,
            ),
        );
    }
}
