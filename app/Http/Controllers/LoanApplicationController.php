<?php
namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class LoanApplicationController extends Controller
{
    // Applicant
    public function create()
    {
        return Inertia::render('Loans/Create');
    }
    public function store(Request $r)
    {
        $data = $r->validate([
            'applicant_full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()],
            'vehicle_type' => ['required', Rule::in(['car', 'ute', 'truck', 'van', 'suv'])],
            'vehicle_make' => ['required', 'string', 'max:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0', 'lte:purchase_price'],
            'term_months' => ['required', 'integer', 'between:6,96'],
            'consent_to_credit_check' => ['accepted'],
        ]);
        $data['status'] = 'submitted';
        $data['submitted_at'] = now();
        LoanApplication::create($data);
        return redirect()->route('loans.thanks');
    }
    public function thanks()
    {
        return Inertia::render('Loans/Thanks');
    }

    // Consultant
    public function index(Request $r)
    {
        $this->authorize('viewAny', LoanApplication::class);
        $term = $r->string('q');
        $type = $r->string('vehicle_type');
        $status = $r->string('status');
        $loans = LoanApplication::query()
            ->search($term)->vehicleType($type)->status($status)
            ->orderByDesc('submitted_at')->paginate(10)->withQueryString();
        return Inertia::render('Consultant/Applications', [
            'filters' => ['q' => $term, 'vehicle_type' => $type, 'status' => $status],
            'loans' => $loans,
        ]);
    }
    public function show(LoanApplication $loan)
    {
        $this->authorize('view', $loan);
        return Inertia::render('Loans/Show', [
            'loan' => $loan->only([
                'id',
                'applicant_full_name',
                'email',
                'phone',
                'date_of_birth',
                'vehicle_type',
                'vehicle_make',
                'vehicle_model',
                'purchase_price',
                'deposit_amount',
                'term_months',
                'status',
                'consent_to_credit_check',
                'submitted_at',
                'created_at',
                'updated_at'
            ]) + ['loan_amount' => $loan->loan_amount],
        ]);
    }
    public function update(Request $r, LoanApplication $loan)
    {
        $this->authorize('update', $loan);
        $v = $r->validate(['status' => ['required', Rule::in(['submitted', 'in_review', 'approved', 'declined'])]]);
        $loan->update($v);
        return back()->with('success', 'Status updated.');
    }
    public function destroy(LoanApplication $loan)
    {
        $this->authorize('delete', $loan);
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Application deleted.');
    }
}