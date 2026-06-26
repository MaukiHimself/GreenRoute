@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Invoice Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('invoices.pdf', $invoice) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i>Download PDF
            </a>
            <a href="{{ route('invoices.edit', $invoice) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('invoices.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Invoice Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $invoice->invoice_number }}</h2>
                        <p class="text-gray-600">Invoice Date: {{ $invoice->invoice_date->format('M d, Y') }}</p>
                        <p class="text-gray-600">Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'sent' => 'bg-green-100 text-green-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'overdue' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                        @if($invoice->is_overdue && $invoice->status !== 'paid')
                            <p class="text-red-600 text-sm mt-1">Overdue</p>
                        @endif
                    </div>
                </div>

                <!-- Client Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Bill To:</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-medium text-gray-800">{{ $invoice->client->name }}</p>
                        <p class="text-gray-600">{{ $invoice->client->email }}</p>
                        <p class="text-gray-600">{{ $invoice->client->phone }}</p>
                        @if($invoice->client->address)
                            <p class="text-gray-600 mt-2">{{ $invoice->client->address }}</p>
                        @endif
                    </div>
                </div>

                <!-- Service Details -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Service Details:</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Service Type</p>
                                <p class="font-medium">{{ $invoice->service_type }}</p>
                            </div>
                            @if($invoice->schedule)
                                <div>
                                    <p class="text-sm text-gray-600">Related Schedule</p>
                                    <p class="font-medium">
                                        <a href="{{ route('schedules.show', $invoice->schedule) }}" class="text-green-600 hover:text-green-800">
                                            {{ $invoice->schedule->pickup_date->format('M d, Y') }} - {{ $invoice->schedule->pickup_time }}
                                        </a>
                                        @if($invoice->schedule->displayed_price !== null)
                                            <div class="text-sm text-gray-500">Schedule price: TZS {{ number_format($invoice->schedule->displayed_price, 2) }}</div>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        @if($invoice->description)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Description</p>
                                <p class="text-gray-800">{{ $invoice->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Financial Breakdown -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Details:</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">TZS {{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax ({{ $invoice->tax_rate }}%):</span>
                                <span class="font-medium">TZS {{ number_format($invoice->tax_amount, 2) }}</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between text-lg font-bold">
                                <span>Total Amount:</span>
                                <span>TZS {{ number_format($invoice->total_amount, 2) }}</span>
                            </div>
                            @if($invoice->amount_paid > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Amount Paid:</span>
                                    <span class="font-medium">TZS {{ number_format($invoice->amount_paid, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-red-600 font-medium">
                                    <span>Balance Due:</span>
                                    <span>TZS {{ number_format($invoice->balance_due, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($invoice->notes)
                    <!-- Notes -->
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Notes:</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-800">{{ $invoice->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Status</h3>
                @if($invoice->status === 'paid')
                    <div class="text-center">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                        <p class="text-green-600 font-medium">Paid in Full</p>
                        @if($invoice->paid_at)
                            <p class="text-sm text-gray-600">Paid on {{ $invoice->paid_at->format('M d, Y') }}</p>
                        @endif
                        @if($invoice->payment_method)
                            <p class="text-sm text-gray-600">via {{ $invoice->payment_method }}</p>
                        @endif
                    </div>
                @else
                    <div class="text-center">
                        <i class="fas fa-clock text-yellow-500 text-3xl mb-2"></i>
                        <p class="text-yellow-600 font-medium">Pending Payment</p>
                        <p class="text-sm text-gray-600 mb-4">Balance: TZS {{ number_format($invoice->balance_due, 2) }}</p>
                        
                        <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <input type="text" name="payment_method" placeholder="Payment method (optional)" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200" onclick="return confirm('Mark this invoice as paid?')">
                                <i class="fas fa-check mr-2"></i>Mark as Paid
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('invoices.pdf', $invoice) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 block text-center" target="_blank">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </a>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="w-full bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg transition duration-200 block text-center">
                        <i class="fas fa-edit mr-2"></i>Edit Invoice
                    </a>
                    @if($invoice->client)
                        <a href="{{ route('clients.show', $invoice->client) }}" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 block text-center">
                            <i class="fas fa-user mr-2"></i>View Client
                        </a>
                    @endif
                </div>
            </div>

            <!-- Invoice Metadata -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invoice Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Created:</span>
                        <span>{{ $invoice->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Updated:</span>
                        <span>{{ $invoice->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Contractor:</span>
                        <span>{{ $invoice->contractor->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection