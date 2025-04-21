<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpdeskSchedule;
use App\Models\User;

class ScheduleController extends Controller
{
    public function index(){
      $schedules = HelpdeskSchedule::all();
      $users = User::all();
      return view("content.schedule.index", compact("schedules", "users"));
    }
}
