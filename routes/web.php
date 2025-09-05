<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $rows = DB::table('users')
        ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
        ->select(
            'users.id','users.name','users.email','users.city','users.state',
            'specs.job','specs.salary','specs.description'
        )
        ->orderBy('users.id')
        ->get();

    $html = "
    <!doctype html>
    <html lang='en'>
    <head>
      <meta charset='utf-8'>
      <title>Users</title>
    </head>
    <body>
    <table border='1'>
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Email</th><th>City</th><th>State</th>
          <th>Job</th><th>Salary</th><th>Description</th>
        </tr>
      </thead>
      <tbody>";

    foreach ($rows as $r) {
        $html .= "<tr>
          <td>{$r->id}</td>
          <td>{$r->name}</td>
          <td>{$r->email}</td>
          <td>{$r->city}</td>
          <td>{$r->state}</td>
          <td>{$r->job}</td>
          <td>{$r->salary}</td>
          <td>{$r->description}</td>
        </tr>";
    }

    $html .= "
      </tbody>
    </table>
    </body>
    </html>";

    return $html;
});
