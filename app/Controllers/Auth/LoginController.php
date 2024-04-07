<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Enums\RoleEnum;
use App\Repositories\LoginRepository;
use App\Validation\Auth\LoginRequest;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Class LoginController
 *
 * This controller handles user login functionality.
 */
class LoginController extends BaseController
{
    protected $loginRepository;

    /**
     * LoginController constructor.
     *
     * Initializes the LoginRepository instance.
     */
    public function __construct()
    {
        $this->loginRepository = \Config\Services::LoginRepository();
    }

    /**
     * Displays the login form.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        return view("auth/login");
    }


    /**
     * Handles the user login process.
     *
     * This function validates the user input for email and password using the rules defined
     * in the LoginRequest class. If validation fails, it returns the login view with validation errors.
     * If validation succeeds, it attempts to retrieve the user data from the database based on the provided email.
     * If a user with the provided email exists, it verifies the password. If the password is correct,
     * it creates a session for the user and redirects them to their respective dashboard based on their role (admin or user).
     * If the email does not exist or the password is incorrect, appropriate error messages are flashed
     * and the user is redirected back to the login page.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirects the user to the appropriate dashboard after successful login,
     * or redirects back to the login page with error messages if login fails.
     */
    public function login()
    {
        try {


            $session = session();
            $validation = \Config\Services::validation()->setRules(LoginRequest::rules());

            if (!$validation->withRequest($this->request)->run()) {
                // If validation fails, return validation errors to the view
                return view('auth/login', [
                    'validation' => $validation,
                ]);
            }

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            $userData = $this->loginRepository->findByEmail($email);
            if ($userData) {
                $pass = $userData['password'];
                $authenticatePassword = password_verify($password, $pass);

                if ($authenticatePassword) {
                    $ses_data = [
                        'id' => $userData['id'],
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'isLoggedIn' => TRUE,
                        'role' => $userData['role'] == RoleEnum::ADMIN->value ?
                            RoleEnum::ADMIN_ROLE->value : RoleEnum::USER_ROLE->value
                    ];
                    $session->set($ses_data);
                    if ($userData['role'] == RoleEnum::ADMIN->value) {
                        return redirect()->to('/admin/admin-dashboard');
                    } elseif ($userData['role'] == RoleEnum::USER->value) {
                        return redirect()->to('/user/dashboard');
                    }

                } else {
                    $session->setFlashdata('msg', 'Password is incorrect.');
                    return redirect()->to('/');
                }
            } else {
                $session->setFlashdata('msg', 'Email does not exist.');
                return redirect()->to('/');
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error in login: ' . $e->getMessage());

        }
    }


    /**
     * Logs out the current user.
     *
     * This function terminates the current user session by destroying all session data
     * and redirects the user to the home page or login page, ensuring they are logged out.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirects the user to the home page or login page after logout.
     */
    public function logout()
    {
        try {


            // Start session
            $session = session();

            // Destroy session data
            $session->destroy();

            // Redirect to home page or login page
            return redirect()->to('/');
        }
        catch(\Exception $e) {
            log_message('error', 'Error in logout: ' . $e->getMessage());

        }
    }

}
