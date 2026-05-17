<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Slip - <?= esc($payroll['full_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; }
        .payslip { max-width: 800px; margin: 30px auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .payslip-header { background: linear-gradient(135deg, #1e1b4b, #4f46e5); color: white; padding: 24px; border-radius: 8px; margin-bottom: 24px; }
        .amount-box { background: #f0f9ff; border: 2px solid #0ea5e9; border-radius: 12px; padding: 20px; text-align: center; }
        .net-amount { font-size: 2rem; font-weight: 800; color: #0c4a6e; }
        @media print {
            body { background: white; }
            .payslip { box-shadow: none; margin: 0; border-radius: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="payslip">
    <!-- Header -->
    <div class="payslip-header">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="fw-800 mb-0">BhuiyanSoft HRM System</h4>
                <p class="mb-0 opacity-75">Pay Slip for <?= date('F Y', mktime(0,0,0,$payroll['month'],1,$payroll['year'])) ?></p>
            </div>
            <div class="col-auto text-end">
                <div class="opacity-75 small">Generated on</div>
                <div><?= date('M d, Y') ?></div>
            </div>
        </div>
    </div>

    <!-- Employee Info -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted fw-500">Employee Name</td><td class="fw-600"><?= esc($payroll['full_name']) ?></td></tr>
                <tr><td class="text-muted">Employee ID</td><td><?= esc($payroll['emp_code']) ?></td></tr>
                <tr><td class="text-muted">Department</td><td><?= esc($payroll['department_name'] ?? '-') ?></td></tr>
                <tr><td class="text-muted">Designation</td><td><?= esc($payroll['designation_title'] ?? '-') ?></td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless">
                <tr><td class="text-muted">Pay Period</td><td class="fw-600"><?= date('F Y', mktime(0,0,0,$payroll['month'],1,$payroll['year'])) ?></td></tr>
                <tr><td class="text-muted">Working Days</td><td><?= $payroll['attendance_days'] ?></td></tr>
                <tr><td class="text-muted">Absent Days</td><td><?= $payroll['absent_days'] ?></td></tr>
                <tr><td class="text-muted">Payment Status</td><td><span class="badge bg-<?= $payroll['status'] === 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($payroll['status']) ?></span></td></tr>
            </table>
        </div>
    </div>

    <!-- Earnings & Deductions -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <h6 class="fw-700 text-success mb-3"><i class="bi bi-plus-circle me-2"></i>Earnings</h6>
            <table class="table table-sm">
                <tbody>
                    <tr><td>Basic Salary</td><td class="text-end">৳<?= number_format($payroll['basic_salary']) ?></td></tr>
                    <tr><td>House Rent Allowance</td><td class="text-end">৳<?= number_format($payroll['house_rent']) ?></td></tr>
                    <tr><td>Medical Allowance</td><td class="text-end">৳<?= number_format($payroll['medical_allowance']) ?></td></tr>
                    <tr><td>Transport Allowance</td><td class="text-end">৳<?= number_format($payroll['transport_allowance']) ?></td></tr>
                    <?php if ($payroll['overtime_pay'] > 0): ?>
                    <tr><td>Overtime Pay</td><td class="text-end">৳<?= number_format($payroll['overtime_pay']) ?></td></tr>
                    <?php endif; ?>
                    <?php if ($payroll['other_earnings'] > 0): ?>
                    <tr><td>Other Earnings</td><td class="text-end">৳<?= number_format($payroll['other_earnings']) ?></td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-success"><td class="fw-700">Gross Salary</td><td class="text-end fw-700">৳<?= number_format($payroll['gross_salary']) ?></td></tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="fw-700 text-danger mb-3"><i class="bi bi-dash-circle me-2"></i>Deductions</h6>
            <table class="table table-sm">
                <tbody>
                    <tr><td>Income Tax</td><td class="text-end text-danger">৳<?= number_format($payroll['tax_deduction']) ?></td></tr>
                    <tr><td>Provident Fund</td><td class="text-end text-danger">৳<?= number_format($payroll['provident_fund']) ?></td></tr>
                    <?php if ($payroll['other_deductions'] > 0): ?>
                    <tr><td>Other Deductions</td><td class="text-end text-danger">৳<?= number_format($payroll['other_deductions']) ?></td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-danger"><td class="fw-700">Total Deductions</td><td class="text-end fw-700 text-danger">৳<?= number_format($payroll['total_deductions']) ?></td></tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Net Pay -->
    <div class="amount-box mb-4">
        <div class="text-muted small mb-1">NET PAY FOR <?= strtoupper(date('F Y', mktime(0,0,0,$payroll['month'],1,$payroll['year']))) ?></div>
        <div class="net-amount">৳<?= number_format($payroll['net_salary']) ?></div>
    </div>

    <!-- Footer -->
    <div class="row text-center border-top pt-3">
        <div class="col"><div class="fw-600">_________________________</div><div class="small text-muted">Employee Signature</div></div>
        <div class="col"><div class="fw-600">_________________________</div><div class="small text-muted">HR Manager</div></div>
        <div class="col"><div class="fw-600">_________________________</div><div class="small text-muted">Authorized Signatory</div></div>
    </div>

    <!-- Print Button -->
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary me-2"><i class="bi bi-printer me-1"></i>Print</button>
        <a href="<?= base_url('admin/payroll') ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>
</body>
</html>
