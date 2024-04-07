<?php

use App\Controller\Admin\Admin;
use App\Controller\File\File;
use App\Controller\Login\Login;
use App\Controller\User\User;

return [
    'users/list' => ['GET' => ['controller' => User::class, 'method' => 'list', 'auth' => true, 'rep' => 'user']],
    'users/get/{id}' => ['GET' => ['controller' => User::class, 'method' => 'getId', 'auth' => true, 'rep' => 'user']],
    'users/search/{email}' => ['GET' => ['controller' => User::class, 'method' => 'searchEmail', 'auth' => true, 'rep' => 'user']],
    'users/update' => ['PUT' => ['controller' => User::class, 'method' => 'update', 'auth' => true, 'rep' => 'user']],

    'admin/users/list' => ['GET' => ['controller' => Admin::class, 'method' => 'list', 'auth' => true, 'rep' => 'admin']],
    'admin/users/get/{id}' => ['GET' => ['controller' => Admin::class, 'method' => 'getId', 'auth' => true, 'rep' => 'admin']],
    'admin/users/delete' => ['DELETE' => ['controller' => Admin::class, 'method' => 'delete', 'auth' => true, 'rep' => 'admin']],
    'admin/users/update' => ['PUT' => ['controller' => Admin::class, 'method' => 'update', 'auth' => true, 'rep' => 'admin']],

    'files/list' => ['GET' => ['controller' => File::class, 'method' => 'list', 'auth' => true, 'rep' => 'file']],
    'files/add' => ['POST' => ['controller' => File::class, 'method' => 'addFiles', 'auth' => true, 'rep' => 'file']],
    'files/get/{id}' => ['GET' => ['controller' => File::class, 'method' => 'getFiles', 'auth' => true, 'rep' => 'file']],
    'files/rename' => ['PUT' => ['controller' => File::class, 'method' => 'renameFiles', 'auth' => true, 'rep' => 'file']],
    'files/remove/{id}' => ['DELETE' => ['controller' => File::class, 'method' => 'removeFiles', 'auth' => true, 'rep' => 'file']],
   
    'files/share/{id}/{user_id}' => ['PUT' => ['controller' => File::class, 'method' => 'fileAccess', 'auth' => true, 'rep' => 'file']],
    'files/remove_share/{id}/{user_id}' => ['DELETE' => ['controller' => File::class, 'method' => 'removeFileAccess', 'auth' => true, 'rep' => 'file']],
    'files/share/{id}' => ['GET' => ['controller' => File::class, 'method' => 'getUserFile', 'auth' => true, 'rep' => 'file']],
    'files/move' => ['POST' => ['controller' => File::class, 'method' => 'updateDirectFiles', 'auth' => true, 'rep' => 'file']],
    'files/submove' => ['POST' => ['controller' => File::class, 'method' => 'updateSubDirectFiles', 'auth' => true, 'rep' => 'file']],
    'files/download/{id}' => ['GET' => ['controller' => File::class, 'method' => 'downloadFiles', 'auth' => true, 'rep' => 'file']],

    'directories/add' => ['POST' => ['controller' => File::class, 'method' => 'addDerectories', 'auth' => true, 'rep' => 'file']],
    'directories/rename' => ['PUT' => ['controller' => File::class, 'method' => 'renameDerectories', 'auth' => true, 'rep' => 'file']],
    'directories/get/{id}' => ['GET' => ['controller' => File::class, 'method' => 'getDerectories', 'auth' => true, 'rep' => 'file']],
    'directories/delete/{id}' => ['DELETE' => ['controller' => File::class, 'method' => 'removeDerectories', 'auth' => true, 'rep' => 'file']],
    'directories/addsub' => ['POST' => ['controller' => File::class, 'method' => 'addSubdirectory', 'auth' => true, 'rep' => 'file']],

    'users/login' => ['POST' => ['controller' => Login::class, 'method' => 'login', 'rep' => 'login']],
    'users/register' => ['POST' => ['controller' => Login::class, 'method' => 'registerUser', 'rep' => 'login']],
    'users/logout' => ['POST' => ['controller' => Login::class, 'method' => 'logout', 'rep' => 'login']],
    'users/reset_password' => ['POST' => ['controller' => Login::class, 'method' => 'reset_password', 'rep' => 'login']],
];
