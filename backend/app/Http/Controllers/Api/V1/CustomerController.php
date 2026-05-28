<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $customers = $this->tenantQuery(Customer::query(), $request)
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = $request->string('search');
                $query->where(function (Builder $nested) use ($search) {
                    $nested->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('last_name')
            ->paginate(25);

        return CustomerResource::collection($customers);
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($this->tenantPayload($request, $request->validated()));

        return (new CustomerResource($customer))->response()->setStatusCode(201);
    }

    public function show(Request $request, Customer $customer): CustomerResource
    {
        $this->ensureTenantModel($request, $customer);

        return new CustomerResource($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        $this->ensureTenantModel($request, $customer);
        $customer->update($this->tenantPayload($request, $request->validated()));

        return new CustomerResource($customer);
    }

    public function destroy(Request $request, Customer $customer): Response
    {
        $this->ensureTenantModel($request, $customer);
        $customer->delete();

        return response()->noContent();
    }

    private function tenantQuery(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        return $user && ! $user->isSuperAdmin()
            ? $query->where('beach_club_id', $user->beach_club_id)
            : $query;
    }

    private function tenantPayload(Request $request, array $data): array
    {
        $user = $request->user();

        if ($user && ! $user->isSuperAdmin()) {
            $data['beach_club_id'] = $user->beach_club_id;
        }

        return $data;
    }

    private function ensureTenantModel(Request $request, Customer $customer): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $customer->beach_club_id), 403);
    }
}
