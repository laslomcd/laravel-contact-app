<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(): Factory|View|Application
    {
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('first_name', 'asc')->where(function($query) {
            if($companyId = \request('company_id')) {
                $query->where('company_id', $companyId);
            }

        })->paginate(10);
        return view('contacts.index', compact('contacts', 'companies'));
    }

    public function create(): Factory|View|Application
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');
        return view('contacts.create', compact('companies'));
    }

    public function show($id): Factory|View|Application
    {
        $contact = Contact::find($id);
        return view('contacts.show', compact('contact'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);
    }

}
