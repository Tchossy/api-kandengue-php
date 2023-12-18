<?php

namespace app\routes;

class Routes
{
  public static function get()
  {
    return [
      'get' => [
        '/' => 'BaseController@index',
        '/candidacy/getall' => 'CandidacyController@getAll',

        // Admin
        '/admin/getall' => 'AdminController@getAll',
        '/admin/search/all/[A-Za-z0-9]+' => 'AdminController@searchByTerm',

        // User
        '/user/getall' => 'UserController@getAll',
        '/user/search/all/[A-Za-z0-9]+' => 'UserController@searchByTerm',

        // Message
        '/message/getall' => 'MessageController@getAll',
        '/message/search/all/[A-Za-z0-9]+' => 'MessageController@searchByTerm',

        // Candidacy
        '/candidacy/search/all/[A-Za-z0-9]+' => 'CandidacyController@searchByTerm',
      ],
      'post' => [
        // '/search/.*' => 'Controller@getById',
        // Candidacy
        '/candidacy/search/[0-9]+' => 'CandidacyController@getById',
        '/candidacy/create' => 'CandidacyController@create',

        // Admin
        '/admin/search/one/[0-9]+' => 'AdminController@getById',
        '/admin/create' => 'AdminController@create',
        '/admin/update/[0-9]+' => 'AdminController@update',
        '/admin/delete/[0-9]+' => 'AdminController@delete',
        '/admin/login' => 'AdminController@login',

        // User
        '/user/search/one/[0-9]+' => 'UserController@getById',
        '/user/create' => 'UserController@create',
        '/user/update/[0-9]+' => 'UserController@update',
        '/user/delete/[0-9]+' => 'UserController@delete',
        '/user/login' => 'UserController@login',

        // Message
        '/message/send' => 'MessageController@create',

        // Upload
        '/upload/image/admin' => 'UploadController@imageAdmin',
        '/upload/image/user' => 'UploadController@imageUser',
        '/upload/image/candidacy' => 'UploadController@imageCandidacy',
        '/upload/doc/identityCard' => 'UploadController@docIdentityCard',
        '/upload/doc/curriculum' => 'UploadController@docCurriculum',
        '/upload/image/vehicle' => 'UploadController@imagesVehicle',
        '/upload/doc/docVehicle' => 'UploadController@docVehicle',
      ],
      'delete' => [
        // Candidacy
        '/candidacy/delete/[0-9]+' => 'CandidacyController@delete',

        // Message
        '/message/delete/[0-9]+' => 'MessageController@delete',
      ],
      'put' => [
        '/candidacy/update/[0-9]+' => 'CandidacyController@update',
      ],
    ];
  }
};