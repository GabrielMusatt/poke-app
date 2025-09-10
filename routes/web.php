<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// --------------------------
// Route for GET "/"
// --------------------------
Route::get('/', function () {
    // Query the "users" table, join with "specs" table (by user_id = users.id)
    // Select specific columns from both tables and order by user ID
    $rows = DB::table('users')
        ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
        ->select('users.id','users.name','users.email','users.city','users.state',
                 'specs.job','specs.salary','specs.description')
        ->orderBy('users.id')
        ->get();

    // Generate CSRF token for the form (security against CSRF attacks)
    $token = csrf_token();

    // Get validation errors from session (if any)
    $errorsBag = session('errors');         
    // and turn into array
    $errors    = $errorsBag ? $errorsBag->all() : [];
    $success   = session('success');    
    
    $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES);

    // Start building HTML output
    $html  = '<!doctype html>';
    $html .= '<html lang="en">';
    $html .= '<head>';
    $html .= '  <meta charset="utf-8">';
    $html .= '  <title>Users</title>';
    $html .= '  <link rel="stylesheet" href="/style.css">'; // ✅ external CSS
    $html .= '</head>';
    $html .= '<body>';
    $html .= '<h1>Add User</h1>';
    $html .= '<div>';

    // If there are validation errors, show them in a styled box
    if (!empty($errors)) {
        $html .= '<div class="alert-error"><ul>';
        foreach ($errors as $e) { 
            $html .= '<li>'.htmlspecialchars($e, ENT_QUOTES).'</li>'; 
        }
        $html .= '</ul></div>';
    }

    // If there is a success message, show it
    if ($success) {
        $html .= '<div class="alert-success">'
              .  htmlspecialchars($success, ENT_QUOTES)
              . '</div>';
    }


    // form
    $html .= '<form method="POST" action="/add" class="form-card">';
    $html .= '<input type="hidden" name="_token" value="'.$token.'">';
    $html .= '<div class="form-grid">';

    $html .= '<label class="field span-3"><span>Name</span><input class="input" name="name" required value="'.$h(old('name')).'"></label>';
    $html .= '<label class="field span-4"><span>Email</span><input class="input" type="email" name="email" required value="'.$h(old('email')).'"></label>';
    $html .= '<label class="field span-3"><span>Password</span><input class="input" type="password" name="password" required></label>';

    $html .= '<label class="field span-2"><span>City</span><input class="input" name="city" value="'.$h(old('city')).'"></label>';
    $html .= '<label class="field span-2"><span>State</span><input class="input" name="state" value="'.$h(old('state')).'"></label>';
    $html .= '<label class="field span-3"><span>Job</span><input class="input" name="job" value="'.$h(old('job')).'"></label>';
    $html .= '<label class="field span-2"><span>Salary</span><input class="input" type="number" step="1" name="salary" value="'.$h(old('salary')).'"></label>';
    $html .= '<label class="field span-12"><span>Description</span><input class="input" name="description" value="'.$h(old('description')).'"></label>';

    $html .= '<div class="form-actions"><button class="btn" type="submit">Save</button></div>';
    $html .= '</div></form>';

    // table
    $html .= '<table><thead><tr>';
    $html .= '<th>ID</th><th>Name</th><th>Email</th><th>City</th><th>State</th>';
    $html .= '<th>Job</th><th>Salary</th><th>Description</th><th>Actions</th></tr></thead><tbody>';

    foreach ($rows as $r) {
        $html .= '<tr>';
        $html .= '<td>'.$r->id.'</td>';
        $html .= '<td>'.$r->name.'</td>';
        $html .= '<td>'.$r->email.'</td>';
        $html .= '<td>'.$r->city.'</td>';
        $html .= '<td>'.$r->state.'</td>';
        $html .= '<td>'.$r->job.'</td>';
        $html .= '<td>'.$r->salary.'</td>';
        $html .= '<td>'.$r->description.'</td>';
        $html .= '<td>xxx</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';   
    $html .= '</body></html>';
    return $html;
});

Route::post('/add', function (Request $request) {
    $data = $request->validate([
        'name'        => 'required|string|max:100',
        'email'       => 'required|email|unique:users,email',
        'password'    => 'required|string|min:4',
        'city'        => 'nullable|string|max:255',
        'state'       => 'nullable|string|max:255',
        'job'         => 'nullable|string|max:255',
        'salary'      => 'nullable|numeric|min:0',
        'description' => 'nullable|string|max:2000',
    ]);

    // Insert user (city/state can be null or empty → store as null)
    $id = DB::table('users')->insertGetId([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => Hash::make($data['password']),
        'city'     => $data['city']  !== '' ? $data['city']  : null,
        'state'    => $data['state'] !== '' ? $data['state'] : null,
    ]);

    // Insert specs (always create a row; defaults if missing)
    DB::table('specs')->insert([
        'user_id'     => $id,
        'job'         => $data['job']         ?? '',
        'salary'      => isset($data['salary']) ? (float)$data['salary'] : 0,
        'description' => $data['description'] ?? '',
    ]);

    return redirect('/')->with('success', "User #{$id} added.");
});


// Check if DB is connected
// http://127.0.0.1:8000/_db
Route::get('/_db', function () {
    try {
        DB::connection()->getPdo();
        return 'DB OK';
    } catch (\Exception $e) {
        return 'DB ERROR: '.$e->getMessage();
    }
});
