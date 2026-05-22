<div class="mb-3">
    <strong>Invoice:</strong> {{ $purchase->inv_no }}<br>
    <strong>Date:</strong> {{ date('d M Y', strtotime($purchase->date)) }}<br>
    <strong>Description:</strong> {{ $purchase->description }}
</div>

@if ($purchase->expenseProfits->isEmpty())
    <div class="alert alert-info">
        No expense or profit entries found for this purchase.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->expenseProfits as $key => $expense)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ucfirst($expense->type) }}</td>
                        <td>{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ date('d M Y', strtotime($expense->date)) }}</td>
                        <td>{{ $expense->description }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" type="button" data-bs-toggle="collapse"
                                data-bs-target="#editExpense{{ $expense->id }}" aria-expanded="false"
                                aria-controls="editExpense{{ $expense->id }}">
                                Edit
                            </button>
                            <form action="{{ route('part_purchase.expenseProfit.delete', $expense->id) }}" method="post"
                                class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this expense/profit entry?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr class="collapse" id="editExpense{{ $expense->id }}">
                        <td colspan="6">
                            <form action="{{ route('part_purchase.expenseProfit.update', $expense->id) }}" method="post">
                                @csrf
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-control form-control-sm">
                                            <option value="expense" {{ $expense->type === 'expense' ? 'selected' : '' }}>Expense</option>
                                            <option value="profit" {{ $expense->type === 'profit' ? 'selected' : '' }}>Profit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control form-control-sm"
                                            value="{{ $expense->amount }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Date</label>
                                        <input type="date" name="date" class="form-control form-control-sm"
                                            value="{{ $expense->date }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Description</label>
                                        <input type="text" name="description" class="form-control form-control-sm"
                                            value="{{ $expense->description }}">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="collapse"
                                            data-bs-target="#editExpense{{ $expense->id }}">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
