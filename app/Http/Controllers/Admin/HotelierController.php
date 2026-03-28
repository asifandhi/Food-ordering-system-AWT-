<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
class HotelierController extends Controller
{
    public function index() { return view('hotelier.dashboard'); }
}