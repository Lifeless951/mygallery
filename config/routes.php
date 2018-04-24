<?php

return [
    'image/(?:show/)?(\d+)/?' => 'image/show/$1',
    
    'account/login' => 'account/login',
    'account/register' => 'account/register',
    
    '(?:main)?' => 'main/index'
];