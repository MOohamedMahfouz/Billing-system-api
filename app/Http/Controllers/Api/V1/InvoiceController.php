<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Http\Request;
use App\Filters\V1\InvoicesFilter;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Requests\V1\RemoveInvoiceRequest;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new InvoicesFilter();
        $queryItems = $filter->transform($request);

        if (count($queryItems) === 0) {
            return new InvoiceCollection(Invoice::paginate());
        } else {
            $invoices = Invoice::where($queryItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }

    public function bulkStore(BulkStoreInvoiceRequest $request) {

        $bulk = collect($request->all())->map(function ($arr, $key) {
            return Arr::except($arr,['paidDate','billedDate','customerId']);
        });
        Invoice::insert($bulk->toArray());
    }
    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RemoveInvoiceRequest $request,Invoice $invoice)
    {
        $invoice->delete($request->all());
    }
}
