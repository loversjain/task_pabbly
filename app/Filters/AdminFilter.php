<?php namespace App\Filters;

use App\Enums\RoleEnum;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is authenticated and has admin role
        if ( session()->get('role') == RoleEnum::USER_ROLE->value) {
            // Redirect user to login or home page or show access denied message
            return redirect()->to('/user/dashboard'); // Change the URL as per your application's requirement
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
