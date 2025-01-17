<?php

namespace App\Http\Controllers;

// use App\Models\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewspaperEmail;
use App\Models\Email;

class MailController extends Controller
{
    public function sendemail(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $msg = $request->message;
        $subject = $request->subject;


        $email = new Email();
        $email->from = $from;
        $email->to = $to;
        $email->subject = $subject;
        $email->message = $msg;
        $email->save();

        Mail::to($to)->send(new NewspaperEmail($msg, $subject, $from));

        return redirect()->route('faqs')->with('success', 'Mail Sent successfully.');
    }
    public function index()
    {
        $emails = Email::all();
        return view('dashboard.admin.smtp.manage', compact('emails'));
    }
}
