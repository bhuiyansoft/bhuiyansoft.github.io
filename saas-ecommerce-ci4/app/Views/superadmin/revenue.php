<?= $this->extend('layouts/superadmin') ?>
<?= $this->section('content') ?>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Revenue Overview</h4>
            <p class="text-muted mb-0">Platform-wide subscription revenue</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 fw-semibold pt-3">Monthly Revenue</div>
        <div class="card-body">
            <canvas id="revenueChart" height="280"></canvas>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 fw-semibold pt-3">Revenue Breakdown</div>
        <div class="card-body p-0">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">Month</th>
                        <th>Subscriptions</th>
                        <th>Revenue</th>
                        <th>Trend</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($monthly_revenue)): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No subscription revenue yet.</td></tr>
                    <?php else: ?>
                    <?php
                        $maxRev = max(array_column($monthly_revenue, 'revenue')) ?: 1;
                    ?>
                    <?php foreach ($monthly_revenue as $row): ?>
                    <tr>
                        <td class="px-3 fw-semibold"><?= date('M Y', strtotime($row['month'] . '-01')) ?></td>
                        <td><span class="badge bg-primary-subtle text-primary"><?= $row['subscriptions'] ?></span></td>
                        <td class="fw-semibold text-success">৳<?= number_format($row['revenue'], 2) ?></td>
                        <td style="min-width:180px">
                            <div class="progress" style="height:8px">
                                <div class="progress-bar bg-success" style="width:<?= ($row['revenue'] / $maxRev) * 100 ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td class="px-3 fw-bold">Total</td>
                        <td class="fw-bold"><?= array_sum(array_column($monthly_revenue, 'subscriptions')) ?></td>
                        <td class="fw-bold text-success">৳<?= number_format(array_sum(array_column($monthly_revenue, 'revenue')), 2) ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
const data = <?= json_encode(array_reverse($monthly_revenue ?? [])) ?>;
new Chart(document.getElementById('revenueChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: data.map(r => r.month),
        datasets: [{
            label: 'Monthly Revenue (৳)',
            data: data.map(r => parseFloat(r.revenue || 0)),
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            tension: 0.4, fill: true, pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { ticks: { callback: v => '৳' + v.toLocaleString() } }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
<?= $this->endSection() ?>
