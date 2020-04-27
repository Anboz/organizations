<?php

namespace App\Http\Controllers\API;


use App\Exceptions\LogData;
use App\Http\Controllers\Controller;
use App\Modules\Customers\Jobs\ClientsVsSuspectsScannerJob;
use App\Modules\Customers\Models\CrmClients;
use App\Modules\Customers\Models\SuspiciousCustomers;
use App\Modules\Customers\Repository\CustomerRepository;
use App\Modules\Customers\Requests\CrmClientStoreRequest;
use App\Modules\Customers\Requests\CrmClientUpdateRequest;
use App\Modules\Customers\Requests\CustomersRequest;
use App\Modules\Customers\UseCases\CustomersDiscoverer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class CustomerController extends Controller
{

    public function findSuspiciousCustomer(CustomersRequest $request)
    {
        $customerRepository = new CustomerRepository($request->all());
        $collection = $customerRepository->examineCustomerByInitials();

        return response()->json($collection, 200);
    }

    public function findSuspiciousAllyOrganization(CustomersRequest $request)
    {
        $customerRepository = new CustomerRepository($request->all());
        $collection = $customerRepository->examineAllyOrganization();

        return response()->json($collection, 200);
    }


    public function clientsVsSuspectsResearch()
    {
          Artisan::call('scan:suspects');
//        $customersDiscovererUseCase = new CustomersDiscoverer();
//        $customersDiscovererUseCase->compareClientVsSuspectsList();
//        ClientsVsSuspectsScannerJob::dispatch()->delay(now()->addSecond(5));

        return back();
    }

    public function suspectsVsCustomersResearch()
    {
          Artisan::call('scan:clients');
//        $customersDiscovererUseCase = new CustomersDiscoverer();
//        $customersDiscovererUseCase->compareSuspectsVsCustomers();
//        CustomersDiscoverer::rescanSuspectsVsCustomers();

        return back();
    }

    public function exemineMIASuspectsVsCustomers()
    {
        Artisan::call('scan:miasuspects');

        return back();
    }

    public function exemineUNSuspectsVsCustomers()
    {
        Artisan::call('scan:unsuspects');

        return back();
    }

    public function exemineIPSuspectsVsCustomers()
    {
        Artisan::call('scan:ipsuspects');

        return back();
    }

    /**
     * @return SuspiciousCustomers[]|Collection
     */
    public function getSuspiciousCustomers()
    {
        return SuspiciousCustomers::with('suspects')->orderBy('sim', 'DESC')->paginate(25);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function saveCrmClient(CrmClientStoreRequest $request)
    {
        try {
            $attributes = [];

            $attributes['crm_client_id'] = $request->clientId;
            $attributes['concatenated_names'] = concatinateInitials($request->surname, $request->name,
                $request->patronymic, '');
            $attributes['second_name'] = $request->surname;
            $attributes['first_name'] = $request->name;
            $attributes['third_name'] = $request->patronymic;
            $attributes['client_registration_date'] = $request->clientRegistrationDate;
            $crmClients = new CrmClients($attributes);

            $crmClients->save();

        } catch (Exception $e) {
            throw new LogData($e);
        }

        return 'ok';
    }

    public function updateCrmClient(CrmClientUpdateRequest $request)
    {
        try {
            $crmClient = CrmClients::where('crm_client_id', $request->client_id)->first();

            $attributes = [];

            $attributes['crm_client_id'] = $request->clientId;
            $attributes['concatenated_names'] = concatinateInitials($request->surname, $request->name,
                $request->patronymic, '');
            $attributes['second_name'] = $request->surname;
            $attributes['first_name'] = $request->name;
            $attributes['third_name'] = $request->patronymic;
            $attributes['birth_date'] = $request->birthDate;

            $crmClient->update($attributes);

        } catch (Exception $e) {
            throw new LogData($e);
        }

        return 'ok';
    }


}
