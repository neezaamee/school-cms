<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeService
{
    /**
     * Generate a 20-digit unique PSID.
     * Format: 11 (Prefix) + SchoolID(5) + InvoiceID(11) + Checksum(2)
     */
    public function generatePSID(Invoice $invoice): string
    {
        $prefix = "11";
        $schoolId = str_pad($invoice->school_id, 5, "0", STR_PAD_LEFT);
        $invoiceId = str_pad($invoice->id, 11, "0", STR_PAD_LEFT);
        
        $base = $prefix . $schoolId . $invoiceId;
        
        // Simple Modulo 97 Checksum (Bank Standard)
        $checksum = str_pad(98 - (intbcmod($base . "00", "97")), 2, "0", STR_PAD_LEFT);
        
        return $base . $checksum;
    }

    /**
     * Bulk generate monthly invoices for a class.
     */
    public function generateMonthlyInvoices($schoolId, $campusId, $className, $month, $year)
    {
        $students = Student::where('school_id', $schoolId)
            ->where('campus_id', $campusId)
            ->where('status', 'Active')
            ->whereHas('enrollments', function($q) use ($className) {
                $q->where('class_name', $className)->where('is_active', true);
            })->get();

        $structures = FeeStructure::where('school_id', $schoolId)
            ->where('campus_id', $campusId)
            ->where('class_name', $className)
            ->get();

        if ($structures->isEmpty()) return 0;

        $count = 0;
        foreach ($students as $student) {
            // Check if already generated
            $exists = Invoice::where('student_id', $student->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();
            
            if ($exists) continue;

            DB::transaction(function() use ($student, $structures, $month, $year, &$count) {
                $subtotal = $structures->sum('amount');
                $dueDay = $structures->first()->due_day ?? 10;
                $dueDate = Carbon::parse("{$year}-{$month}-{$dueDay}");

                $invoice = Invoice::create([
                    'school_id' => $student->school_id,
                    'campus_id' => $student->campus_id,
                    'student_id' => $student->id,
                    'psid' => 'PENDING', // Placeholder
                    'month' => $month,
                    'year' => $year,
                    'due_date' => $dueDate,
                    'subtotal' => $subtotal,
                    'total_amount' => $subtotal,
                    'status' => 'Unpaid',
                ]);

                // Add Items
                foreach ($structures as $st) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'fee_category_id' => $st->fee_category_id,
                        'description' => $st->category->name,
                        'amount' => $st->amount,
                    ]);
                }

                // Generate and update PSID
                $invoice->update(['psid' => $this->generatePSID($invoice)]);
                $count++;
            });
        }

        return $count;
    }

    /**
     * Process a cash payment for an invoice.
     */
    public function processCashPayment(Invoice $invoice, $amount, $receivedBy, $transactionId = null)
    {
        return DB::transaction(function() use ($invoice, $amount, $receivedBy, $transactionId) {
            $payment = Payment::create([
                'school_id' => $invoice->school_id,
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'payment_method' => 'Cash',
                'transaction_id' => $transactionId ?? 'REC-' . time(),
                'paid_at' => now(),
                'received_by' => $receivedBy,
            ]);

            $newPaidAmount = $invoice->paid_amount + $amount;
            $status = 'Paid';
            if ($newPaidAmount < $invoice->total_amount) {
                $status = 'Partial';
            }

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status
            ]);

            return $payment;
        });
    }

    /**
     * Calculate and apply fines to overdue invoices.
     */
    public function applyOverdueFines()
    {
        $overdueInvoices = Invoice::where('status', '!=', 'Paid')
            ->where('due_date', '<', now()->toDateString())
            ->where('fine_amount', 0) // Only apply once for now
            ->get();

        foreach ($overdueInvoices as $invoice) {
            // Find relevant fine rule from structures
            $student = $invoice->student;
            $enrollment = $student->enrollments()->where('is_active', true)->first();
            if (!$enrollment) continue;

            $fineStructure = FeeStructure::where('campus_id', $invoice->campus_id)
                ->where('class_name', $enrollment->class_name)
                ->where('fine_amount', '>', 0)
                ->first();

            if ($fineStructure) {
                $fine = $fineStructure->fine_amount;
                if ($fineStructure->fine_type === 'Percentage') {
                    $fine = ($invoice->subtotal * $fineStructure->fine_amount) / 100;
                }

                $invoice->update([
                    'fine_amount' => $fine,
                    'total_amount' => $invoice->subtotal + $fine,
                    'status' => 'Overdue'
                ]);
            }
        }
    }
}

/**
 * Helper for large number modulo
 */
function intbcmod($x, $y) {
    if (function_exists('bcmod')) {
        return bcmod($x, $y);
    }
    $take = 5;
    $mod = '';
    do {
        $a = (int)$mod.substr($x, 0, $take);
        $x = substr($x, $take);
        $mod = $a % $y;
    } while (strlen($x));
    return (int)$mod;
}
