<?php

namespace App\Controllers;

use App\Core\Helpers;
use App\Models\UserModel;

class UserController extends Controller
{
    /** @var UserModel */
    protected $userModel;

    /**
     * Create new UsersController instance.
     */
    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->loginAction();
    }

    /**
     * @return void
     */
    public function loginAction()
    {
        if (Helpers::isAuth()) {
            Helpers::redirectTo('task/table');
        }
        $checkedData = $this->userModel->validateLoginForm($_POST);
        $parsedQueryString = Helpers::clean(Helpers::parseQueryString());

        // Check if submit and valid form
        if ($checkedData['isSubmitForm'] && $checkedData['isValidForm']) {
            $authUser = $this->userModel->getByCredentials($checkedData['formData']);
            if ($authUser->getId()) {
                Helpers::auth($authUser->getId());
                Helpers::redirectTo($checkedData['formData']['redirectTo'] ?? 'task/table');
            } else {
                $checkedData['isValidForm'] = false;
                $checkedData['errorMessage'][] = 'Invalid credentials. User not found.';
            }
        }

        $this->view('user/login', [
            'isValidForm'  => $checkedData['isValidForm'],
            'isSubmitForm' => $checkedData['isSubmitForm'],
            'errorMessage' => implode(' ', $checkedData['errorMessage']),
            'formData'     => $checkedData['formData'],
            'formErrors'   => $checkedData['formErrors'],
            'formAction'   => Helpers::path('user/login'),
            'submitAction' => 'login',
            'submitLabel'  => 'Login',
            'redirectTo'   => $parsedQueryString['redirect_to'] ?? 'task/table',
        ]);
    }

    /**
     * @return void
     */
    public function logoutAction() {
        Helpers::logout();
        Helpers::redirectTo('user/login');
    }
}
