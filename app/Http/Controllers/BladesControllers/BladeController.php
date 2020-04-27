<?php

namespace App\Http\Controllers\BladesControllers;

use App\Http\Controllers\Controller;
use App\Modules\Customers\Models\Suspect;
use App\Modules\Customers\Models\SuspiciousCustomers;
use App\Modules\Customers\Repository\CustomerRepository;
use Illuminate\Http\Request;


class BladeController extends Controller
{
    public function index(Request $request)
    {
        $suspects = SuspiciousCustomers::orderBy($request->session()->get('column'),
            $request->session()->get('order'))->paginate(10);

        $request->session()->put('suspects', $suspects);
        $request->session()->put('list_nav', 'active');
        $request->session()->put('search_nav', '');
        $request->session()->put('org_search_nav', '');
        $request->session()->put('show_list', 'show active');
        $request->session()->put('show_search', '');
        $request->session()->put('show_organizations_search', '');

        return view('suspects.index', compact('suspects'));
    }

    public function column(Request $request)
    {
        $this->sorting($request);
        if ($request->column === 'numb') {
            $request->session()->put('column', 'id');
        } else {
            if ($request->column === 'first_name') {
                $request->session()->put('column', 'first_name');
            } else {
                if ($request->column === 'second_name') {
                    $request->session()->put('column', 'second_name');
                } else {
                    if ($request->column === 'third_name') {
                        $request->session()->put('column', 'third_name');
                    } else {
                        if ($request->column === 'fourth_name') {
                            $request->session()->put('column', 'fourth_name');
                        }
                    }
                }
            }
        }

        return redirect('/');
    }

    public function sorting(Request $request)
    {
        $asc = $request->session()->get('order');

        if ($asc === 'asc') {
            $request->session()->put('order', 'desc');
        } else {
            $request->session()->put('order', 'asc');
        }
    }

    public function search(Request $request)
    {
        $attributes = [];
        $attributes['api_token'] = '';
        $attributes['operation_type'] = '';
        $attributes['initials'] = $request->initials;

        $customerRepository = new CustomerRepository($attributes);

        $collection = $customerRepository->examineCustomerByInitials();

        $request->session()->put('collection', $collection);
        $request->session()->put('list_nav', '');
        $request->session()->put('search_nav', 'active');
        $request->session()->put('org_search_nav', '');
        $request->session()->put('show_list', '');
        $request->session()->put('show_search', 'show active');
        $request->session()->put('show_organizations_search', '');

        return view('suspects.index');
    }

    public function searchOrganization(Request $request)
    {
        $attributes = [];
        $attributes['api_token'] = '';
        $attributes['operation_type'] = '';
        $attributes['initials'] = $request->initials;

        $customerRepository = new CustomerRepository($attributes);

        $organizations_collection = $customerRepository->examineAllyOrganization();

        $request->session()->put('organizations_collection', $organizations_collection);
        $request->session()->put('list_nav', '');
        $request->session()->put('search_nav', '');
        $request->session()->put('org_search_nav', 'active');
        $request->session()->put('show_list', '');
        $request->session()->put('show_search', '');
        $request->session()->put('show_organizations_search', 'show active');

        return view('suspects.index');
    }

    public function deleteInterpolList()
    {
        Suspect::where('organization', 'IP')->delete();

        return back();
    }

    public function deleteUnitedNationsList()
    {
        Suspect::where('organization', 'UN')->delete();

        return back();
    }

    public function deleteMinistryOfInternalAffairsList()
    {
        Suspect::where('organization', 'MIA')->delete();

        return back();
    }

    public function scanner()
    {
        return view('suspects.scanner');
    }

}
