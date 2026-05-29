<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpsertCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate((int) $request->integer('per_page', 15))
            ->withQueryString();

        return CustomerResource::collection($customers);
    }

    public function store(UpsertCustomerRequest $request): CustomerResource
    {
        $customer = Customer::query()->create([
            ...$request->validated(),
            'beach_club_id' => $request->user()->beach_club_id,
        ]);

        return CustomerResource::make($customer);
    }

    public function show(Customer $customer): CustomerResource
    {
        return CustomerResource::make($customer);
    }

    public function update(UpsertCustomerRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->validated());

        return CustomerResource::make($customer->refresh());
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Cliente eliminado correctamente.',
        ]);
    }
}
