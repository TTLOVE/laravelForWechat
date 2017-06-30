<?php 

namespace Zyz\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class ContactController extends Controller
{
    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        //dd(Config::get("contact.message"));
        //dd(Config::get("app.debug"));
        return view('contact::contact');
    }
}
