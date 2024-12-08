<?php

use App\Enums\Roles;
use App\Models\UserRepository;
use Framework\Controllers\SessionManager;
use Framework\Controllers\SessionValues;

$user = UserRepository::find($_GET['id']);
$current = SessionManager::get(SessionValues::USER_INFO->value);

?>

<form action="/user/edit?id=<?php echo $_GET['id']?>" method="POST">
    <div class="field">
        <label for="username" class="label">Username</label>
        <div class="control">
            <input type="text" name="username" id="username" class="input" value="<?php echo $user->username; ?>" disabled>
        </div>
    </div>

    <div class="field">
        <label for="auth_level" class="label">Auth Level</label>
        <div class="select">
            <select name="auth_level" id="auth_level" required <?php if ($_GET['id'] == $current['id']) { echo "disabled"; } ?>>
                <option <?php if ($user->getAuthLevelValue() == Roles::EMPLOYEE->value) { echo "selected"; } ?> value="<?php echo Roles::EMPLOYEE->value; ?>">Employee</option>
                <option <?php if ($user->getAuthLevelValue() == Roles::MANAGER->value ) { echo "selected"; } ?> value="<?php echo Roles::MANAGER->value; ?>">Manager</option>
                <option <?php if ($user->getAuthLevelValue() == Roles::ADMIN->value) { echo "selected"; } ?> value="<?php echo Roles::ADMIN->value; ?>">Admin</option>
            </select>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <input type="submit" value="Update" class="button is-primary">
        </div>
    </div>
</form>

