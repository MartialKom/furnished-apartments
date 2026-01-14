<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="feather-bar-chart-2 me-2"></i>Analytics Globale</h4>
                <p class="text-muted">Vue d'ensemble des performances financières de la plateforme</p>
            </div>
            <div>
                <select class="form-select" id="periode-select" onchange="window.location.href='<?= base_url('admin/analytics') ?>?periode=' + this.value">
                    <option value="6" <?= ($periode_mois == 6) ? 'selected' : '' ?>>6 derniers mois</option>
                    <option value="12" <?= ($periode_mois == 12) ? 'selected' : '' ?>>12 derniers mois</option>
                    <option value="24" <?= ($periode_mois == 24) ? 'selected' : '' ?>>24 derniers mois</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- KPIs Cash Flow -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1);">
                            <i class="feather-arrow-down-circle" style="color: #28a745; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Cash-In</h6>
                        <h3 class="mb-0 text-success"><?= number_format($totaux_periode['total_cash_in'], 0, ',', ' ') ?></h3>
                        <small class="text-muted">FCFA (<?= $periode_mois ?> mois)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1);">
                            <i class="feather-arrow-up-circle" style="color: #dc3545; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Cash-Out</h6>
                        <h3 class="mb-0 text-danger"><?= number_format($totaux_periode['total_cash_out'], 0, ',', ' ') ?></h3>
                        <small class="text-muted">FCFA (<?= $periode_mois ?> mois)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(23, 162, 184, 0.1);">
                            <i class="feather-trending-up" style="color: #17a2b8; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Balance Totale</h6>
                        <h3 class="mb-0 <?= $totaux_periode['balance_totale'] >= 0 ? 'text-info' : 'text-danger' ?>">
                            <?= number_format($totaux_periode['balance_totale'], 0, ',', ' ') ?>
                        </h3>
                        <small class="text-muted">FCFA (<?= $periode_mois ?> mois)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1);">
                            <i class="feather-alert-circle" style="color: #dc3545; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Paiements en Retard</h6>
                        <h3 class="mb-0"><?= $paiements_retard ?></h3>
                        <small class="text-danger"><?= number_format($montant_retard, 0, ',', ' ') ?> FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques Cash Flow -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-bar-chart me-2"></i>Cash-In vs Cash-Out Mensuel</h5>
                <p class="text-muted mb-0 small">Comparaison des entrées et sorties de trésorerie</p>
            </div>
            <div class="card-body">
                <canvas id="cashFlowChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Détails Cash-In -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-dollar-sign me-2"></i>Composition du Cash-In</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Réservations</span>
                        <strong><?= number_format($totaux_periode['total_reservations'], 0, ',', ' ') ?> FCFA</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <?php
                        $percentReservations = $totaux_periode['total_cash_in'] > 0
                            ? ($totaux_periode['total_reservations'] / $totaux_periode['total_cash_in']) * 100
                            : 0;
                        ?>
                        <div class="progress-bar bg-info" style="width: <?= $percentReservations ?>%"></div>
                    </div>
                    <small class="text-muted"><?= number_format($percentReservations, 1) ?>%</small>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Loyers Mensuels</span>
                        <strong><?= number_format($totaux_periode['total_loyers'], 0, ',', ' ') ?> FCFA</strong>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <?php
                        $percentLoyers = $totaux_periode['total_cash_in'] > 0
                            ? ($totaux_periode['total_loyers'] / $totaux_periode['total_cash_in']) * 100
                            : 0;
                        ?>
                        <div class="progress-bar" style="background: #d29751; width: <?= $percentLoyers ?>%"></div>
                    </div>
                    <small class="text-muted"><?= number_format($percentLoyers, 1) ?>%</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-pie-chart me-2"></i>Répartition Cash-In</h5>
            </div>
            <div class="card-body">
                <canvas id="cashInPieChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Balance Mensuelle -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-activity me-2"></i>Balance Mensuelle</h5>
                <p class="text-muted mb-0 small">Évolution de la balance (Cash-In - Cash-Out)</p>
            </div>
            <div class="card-body">
                <canvas id="balanceChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- KPIs Principaux -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(210, 151, 81, 0.1);">
                            <i class="feather-home" style="color: #d29751; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Taux d'Occupation</h6>
                        <h3 class="mb-0"><?= number_format($taux_occupation, 1) ?>%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1);">
                            <i class="feather-trending-up" style="color: #28a745; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Taux de Conversion</h6>
                        <h3 class="mb-0"><?= number_format($taux_conversion, 1) ?>%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(23, 162, 184, 0.1);">
                            <i class="feather-dollar-sign" style="color: #17a2b8; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Revenu Moyen/Résa</h6>
                        <h3 class="mb-0"><?= number_format($revenu_moyen_reservation, 0, ',', ' ') ?></h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(23, 162, 184, 0.1);">
                            <i class="feather-users" style="color: #17a2b8; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Revenu Moyen/Contrat</h6>
                        <h3 class="mb-0"><?= number_format($revenu_moyen_contrat, 0, ',', ' ') ?></h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Appartements -->
<div class="row">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-award me-2"></i>Top 5 Appartements Les Plus Rentables</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_appartements)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Adresse</th>
                                    <th class="text-center">Réservations</th>
                                    <th class="text-end">Revenu Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_appartements as $index => $appart): ?>
                                    <tr>
                                        <td>
                                            <span class="badge" style="background: #d29751;"><?= $index + 1 ?></span>
                                        </td>
                                        <td><?= esc($appart['adresse']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $appart['nb_reservations'] ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?= number_format($appart['revenu_total'], 0, ',', ' ') ?> FCFA</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Préparer les données pour les graphiques
    const cashFlowData = <?= json_encode($cash_flow) ?>;

    const labels = cashFlowData.map(item => {
        const [year, month] = item.mois.split('-');
        const date = new Date(year, month - 1);
        return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
    });

    const cashInData = cashFlowData.map(item => item.cash_in);
    const cashOutData = cashFlowData.map(item => item.cash_out);
    const balanceData = cashFlowData.map(item => item.balance);
    const reservationsData = cashFlowData.map(item => item.cash_in_reservations);
    const loyersData = cashFlowData.map(item => item.cash_in_loyers);

    // Graphique Cash-In vs Cash-Out
    const ctx1 = document.getElementById('cashFlowChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Cash-In (Entrées)',
                    data: cashInData,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Cash-Out (Sorties)',
                    data: cashOutData,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Graphique Balance Mensuelle
    const ctx2 = document.getElementById('balanceChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Balance (Cash-In - Cash-Out)',
                data: balanceData,
                backgroundColor: function(context) {
                    const value = context.parsed.y;
                    return value >= 0 ? 'rgba(23, 162, 184, 0.2)' : 'rgba(220, 53, 69, 0.2)';
                },
                borderColor: function(context) {
                    const value = context.parsed.y;
                    return value >= 0 ? 'rgba(23, 162, 184, 1)' : 'rgba(220, 53, 69, 1)';
                },
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Graphique Pie pour la composition du Cash-In
    const ctx3 = document.getElementById('cashInPieChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Réservations', 'Loyers Mensuels'],
            datasets: [{
                data: [
                    <?= $totaux_periode['total_reservations'] ?>,
                    <?= $totaux_periode['total_loyers'] ?>
                ],
                backgroundColor: [
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(210, 151, 81, 0.8)'
                ],
                borderColor: [
                    'rgba(23, 162, 184, 1)',
                    'rgba(210, 151, 81, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            label += new Intl.NumberFormat('fr-FR').format(value) + ' FCFA (' + percentage + '%)';
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
