<?php


// ------------------------------
// Laravel facades/classes
// ------------------------------
// Route     = defines HTTP endpoints (GET/POST/etc) and their handlers
// DB        = run database queries with the query builder
// Request   = represents the current HTTP request (params, files, headers...)
// Hash      = securely hash passwords (bcrypt/argon)

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// -------- helpers ----------
/**
 * render_rows()
 * Helper that builds the HTML rows for users table
 * - $rows: users joined with specs
 * - $token: CSRF token string for forms
 * - $editing: the user record that is edited (or null). If true, the function print an extra row with an inline edit form.
 */
function render_rows($rows, $token, $editing = null): string
{
    $html = "";
    // e() is global escape helper from laravel - converts special chars to HTML entities to prevent XSS.
    // e($value) HTML-escapes characters like < > " ' &, so the browser renders them as text instead of executing them as code.
    foreach ($rows as $r) {
        // row
        $html .= "<tr>";
        $html .= "<td>" . e($r->id) . "</td>";
        $html .= "<td>" . e($r->name) . "</td>";
        $html .= "<td>" . e($r->email) . "</td>";
        $html .= "<td>" . e($r->city) . "</td>";
        $html .= "<td>" . e($r->state) . "</td>";
        $html .= "<td>" . e($r->job) . "</td>";
        $html .= "<td>" . e($r->salary) . "</td>";
        $html .= "<td>" . e($r->description) . "</td>";

        // actions column inside the rowe
        // Edit is a button handled by JS (no navigation, stays on /)
        // Delete is an Ajax form (class="ajax-form"), using POST + _method=DELETE
        $html .= "<td class='actions'>
                    <button type='button' class='btn secondary edit-btn' data-id='" . $r->id . "'>Edit</button>
                    <form class='ajax-form' action='/delete/" . $r->id . "' method='POST' style='display:inline'>
                        <input type='hidden' name='_token' value='" . $token . "'>
                        <input type='hidden' name='_method' value='DELETE'>
                        <button class='btn danger' type='submit'>Delete</button>
                    </form>
                  </td>";
        $html .= "</tr>";

        // edit row: only render for the selected user
        if ($editing && $editing->id == $r->id) {
            $html .= "<tr id='u" . $r->id . "' class='edit-row'><td colspan='9'>";
            $html .= "<form class='ajax-form form-edit' method='POST' action='/update/" . $r->id . "'>";
            $html .= "<input type='hidden' name='_token' value='" . $token . "'>";

            // method spoofing for PUT: Laravel reads _method when the real HTTP method is POST
            $html .= "<input type='hidden' name='_method' value='PUT'>";
            $html .= "<div class='grid'>";

            // Editable fields; values are taken from the joined $editing record
            $html .= "<input type='text' name='name' value='" . e($editing->name) . "' placeholder='Name'>";
            $html .= "<input type='email' name='email' value='" . e($editing->email) . "' placeholder='Email'>";
            $html .= "<input type='text' name='city' value='" . e($editing->city) . "' placeholder='City'>";
            $html .= "<input type='text' name='state' value='" . e($editing->state) . "' placeholder='State'>";
            $html .= "<input type='text' name='job' value='" . e($editing->job) . "' placeholder='Job'>";
            $html .= "<input type='number' name='salary' value='" . e($editing->salary) . "' placeholder='Salary'>";
            $html .= "<textarea name='description' placeholder='Description'>" . e($editing->description) . "</textarea>";
            $html .= "</div>";

            $html .= "<div class='row'>";
            $html .= "<button class='btn primary' type='submit'>Update</button>";
            $html .= "<a class='btn secondary' href='/'>Cancel</a>";
            $html .= "</div>";
            $html .= "</form>";
            $html .= "</td></tr>";
        }
    }
    return $html;
}

// -------- page ----------
// $req = current Request
Route::get('/', function (Request $req) {

    // Build a SELECT with a LEFT JOIN to specs so users without specs still appear.
    $rows = DB::table('users')
        ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.city',
            'users.state',
            'specs.job',
            'specs.salary',
            'specs.description'
        )
        ->orderBy('users.id')
        ->get();
    // ->get() executes and returns a collection

    // If we previously chose a row to edit (via JS: don’t change URL, but still support ?edit=ID)
    $editingId = (int) $req->query('edit', 0);
    $editing = null;
    if ($editingId) {
        $editing = DB::table('users')
            ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
            ->select('users.*', 'specs.job', 'specs.salary', 'specs.description')
            ->where('users.id', $editingId)
            ->first();
        // ->first() returns a single row or null
    }

    // CSRF token: protects against cross-site request forgery; required by Laravel for POST forms
    $token   = csrf_token();

    // Optional flash success message (from session) to show once after redirect or action
    $success = session('success');

    $html = "";
    $html .= "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
    $html .= "<link rel='stylesheet' href='/style.css'>";
    $html .= "<title>Users</title></head><body>";

    $html .= "<div id='alerts'>";
    if ($success) $html .= "<div class='alert success'>" . e($success) . "</div>";
    $html .= "</div>";

    // Create form
    // class='ajax-form' marks this form to be intercepted by our JS and sent via fetch()
    $html .= "<h2>Add User</h2>";
    $html .= "<form class='ajax-form form-create' method='POST' action='/store'>";
    $html .= "<input type='hidden' name='_token' value='" . $token . "'>";
    $html .= "<div class='grid'>";
    $html .= "<input type='text' name='name' placeholder='Name'>";
    $html .= "<input type='email' name='email' placeholder='Email'>";
    $html .= "<input type='password' name='password' placeholder='Password'>";
    $html .= "<input type='text' name='city' placeholder='City'>";
    $html .= "<input type='text' name='state' placeholder='State'>";
    $html .= "<input type='text' name='job' placeholder='Job'>";
    $html .= "<input type='number' name='salary' placeholder='Salary'>";
    $html .= "<textarea name='description' placeholder='Description'></textarea>";
    $html .= "</div>";
    $html .= "<button class='btn primary' type='submit'>Save</button>";
    $html .= "</form>";

    // Table
    $html .= "<h2>Users</h2>";
    $html .= "<table class='table'>";
    $html .= "<thead><tr>
                <th>ID</th><th>Name</th><th>Email</th>
                <th>City</th><th>State</th>
                <th>Job</th><th>Salary</th><th>Description</th>
                <th>Actions</th>
              </tr></thead>";

    // tbody gets replaced dynamically after Ajax actions
    $html .= "<tbody id='users-tbody'>" . render_rows($rows, $token, $editing) . "</tbody>";
    $html .= "</table>";

    // Minimal JS to Ajaxify forms
    $html .= "<script>
    function showAlert(msg, type){
        const box = document.getElementById('alerts');
        const div = document.createElement('div');
        div.className = 'alert ' + (type || 'success');
        div.textContent = msg;
        box.innerHTML = '';
        box.appendChild(div);
    }

    async function handleAjaxSubmit(e){
        const form = e.target.closest('form.ajax-form');
        if(!form) return;
        e.preventDefault();

        const btn = form.querySelector('button[type=submit]');
        if(btn){ btn.disabled = true; }

        try{
            const res = await fetch(form.action, {
                method: form.method || 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if(res.status === 422){
                const data = await res.json();
                const messages = [];
                if (data.errors){
                    for (const k in data.errors){ messages.push(data.errors[k][0]); }
                } else if (data.message){ messages.push(data.message); }
                showAlert(messages.join('\\n'), 'error');
                return;
            }

            // Expect JSON: { ok:true, html:'<tbody>...</tbody>', message:'...' }
            const data = await res.json();
            if(data && data.html){
                document.getElementById('users-tbody').innerHTML = data.html;
            }
            if(data && data.message){
                showAlert(data.message, data.ok ? 'success' : 'error');
            }
        }catch(err){
            showAlert('Network error', 'error');
        }finally{
            if(btn){ btn.disabled = false; }
        }
    }

    document.addEventListener('submit', handleAjaxSubmit);


    async function openInlineEdit(id){
    const res = await fetch('/inline-edit/' + id, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await res.json();
    if (data && data.html) {
        document.getElementById('users-tbody').innerHTML = data.html;
    }
    }

    document.addEventListener('click', function(e){
    const btn = e.target.closest('.edit-btn');
    if (!btn) return;
    e.preventDefault();           // <- stops navigation
    const id = btn.getAttribute('data-id');
    openInlineEdit(id);
    });
    </script>";

    $html .= "</body></html>";
    return $html;
});

// ------------------------------------------
// GET "/inline-edit/{id}" — return rows HTML
// ------------------------------------------
// used by JS to open (or close with id=0) the inline edit without changing URL.
Route::get('/inline-edit/{id}', function ($id) {
    $rows = DB::table('users')
        ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.city',
            'users.state',
            'specs.job',
            'specs.salary',
            'specs.description'
        )
        ->orderBy('users.id')
        ->get();

    $editing = DB::table('users')
        ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
        ->select('users.*', 'specs.job', 'specs.salary', 'specs.description')
        ->where('users.id', $id)
        ->first();

    // sends a JSON body with appropriate headers
    return response()->json([
        'ok' => true,
        'html' => render_rows($rows, csrf_token(), $editing),
    ]);
});


// --------------------
// POST "/store" — create
// --------------------
Route::post('/store', function (Request $req) {
    $req->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:4',
    ]);

    // Insert into users table and get the new id
    $id = DB::table('users')->insertGetId([
        'name' => $req->name,
        'email' => $req->email,
        'password' => Hash::make($req->password),
        'city' => $req->city,
        'state' => $req->state,
    ]);

    // Insert the related specs row
    DB::table('specs')->insert([
        'user_id' => $id,
        'job' => $req->job,
        'salary' => $req->salary,
        'description' => $req->description,
    ]);

    if ($req->ajax()) {
        $rows = DB::table('users')
            ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.city',
                'users.state',
                'specs.job',
                'specs.salary',
                'specs.description'
            )
            ->orderBy('users.id')->get();
        return response()->json([
            'ok' => true,
            'message' => 'User created successfully.',
            'html' => render_rows($rows, csrf_token())
        ]);
    }

    return redirect('/')->with('success', 'User created successfully.');
});

// -----------------------------------------
// POST/PUT "/update/{id}" — update user+spec
// -----------------------------------------
// Route::match accepts both POST and PUT
Route::match(['POST', 'PUT'], '/update/{id}', function (Request $req, $id) {
    $req->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    // Update user base fields
    DB::table('users')->where('id', $id)->update([
        'name'  => $req->name,
        'email' => $req->email,
        'city'  => $req->city,
        'state' => $req->state,
    ]);

    // Update or create the specs row matched by user_id
    DB::table('specs')->updateOrInsert(
        ['user_id' => $id],
        // close edit row after save
        ['job' => $req->job, 'salary' => $req->salary, 'description' => $req->description]
    );

    if ($req->ajax()) {
        $rows = DB::table('users')
            ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.city',
                'users.state',
                'specs.job',
                'specs.salary',
                'specs.description'
            )
            ->orderBy('users.id')->get();
        return response()->json([
            'ok' => true,
            'message' => 'User updated successfully.',
            'html' => render_rows($rows, csrf_token())
        ]);
    }
    return redirect('/')->with('success', 'User updated successfully.');
});

// --------------------------------------
// POST/DELETE "/delete/{id}" — delete row
// --------------------------------------
// Accept POST and DELETE 
Route::match(['POST', 'DELETE'], '/delete/{id}', function (Request $req, $id) {
    DB::table('specs')->where('user_id', $id)->delete();
    DB::table('users')->where('id', $id)->delete();

    if ($req->ajax()) {
        $rows = DB::table('users')
            ->leftJoin('specs', 'specs.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.city',
                'users.state',
                'specs.job',
                'specs.salary',
                'specs.description'
            )
            ->orderBy('users.id')->get();
        return response()->json([
            'ok' => true,
            'message' => 'User deleted.',
            'html' => render_rows($rows, csrf_token())
        ]);
    }
    return redirect('/')->with('success', 'User deleted.');
});
