<?php

return [

    /* Task */
    'needed_params' => ['title','description','done','deleted','category'],
    'messages' => [
        'title.required' => 'Title is required',
        'description.required' => 'Description is required',
        'done.boolean' => 'Done is not a boolean',
        'deleted' => "Deleted is not a boolean",
        'category.required' => "Category is required"
    ],

    // Rules Create
    'create_rules' => [
        'title' => 'required',
        'description' => 'required',
        'category' => 'required',
        'done' => 'boolean|nullable',
        'deleted' => 'boolean|nullable'
    ],

    // Rules Update
    'update_rules' => [
        'title' => '',
        'description' => '',
        'category' => '',
        'done' => 'boolean',
        'deleted' => 'boolean'
    ],

    // Order possibilities
    'order' => ['title','description','shared','category','done','undeleted','created_at','updated_at'],
    'directions' => ['ASC','DESC'],

];
