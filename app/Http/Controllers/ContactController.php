<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        return view($this->currentRoleView() . '.contact.index');
    }
}
