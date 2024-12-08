<?php

use App\Enums\Roles;
use App\Helpers\AuthHelper;
use App\Models\UserRepository;
use Framework\Controllers\Authentication;
use Framework\Controllers\SessionManager;
use Framework\Controllers\SessionValues;
use Framework\Views\Layout;

$current_user = SessionManager::get(SessionValues::USER_INFO->value);

if (is_null($current_user)) { 
    header("HTTP/1.0 401 Unauthorized");
    Layout::contentRender('errors/401');
    die();
}

Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
    return AuthHelper::hasAccess($string, $criteria);
}, AuthHelper::forAdmin());

echo "<h2 class='title'> All Users </h2>";

$users = UserRepository::getAll();

echo "<table class='table is-fullwidth'>";
echo "<thead>
<tr> 

<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Auth Level</th>
<th>Delete</th>
<th>Edit</th>

</tr>
</thead>
";
echo "<tbody>";
foreach ($users as $user) {

    echo "<tr>";
    echo "<td>" . $user->getId() . "</td>";
    echo "<td>" . $user->getUsername() . "</td>";
    echo "<td>" . $user->getEmail() . "</td>";
    echo "<td>" . match($user->getAuthLevelValue()) {
        Roles::ADMIN->value => 'Admin',
        Roles::MANAGER->value => 'Manager',
        Roles::EMPLOYEE->value => 'Employee',
        default => 'Guest'
    } . "</td>";
    
    if ($user->getId() == $current_user['id']) {
        echo "<td>Can't delete yourself</td>";
        echo "<td>Can't Edit yourself</td>";
        continue;
    }
    else{
        echo "<td><a class='button is-danger' href='/user/delete?id=" . $user->getId() . "'>Delete</a></td>";
        echo "<td><a class='button is-info' href='/user/edit?id=" . $user->getId() . "'>Edit</a></td>";
    }
    echo "</tr>";

}

echo "</tbody>";
echo "</table>";