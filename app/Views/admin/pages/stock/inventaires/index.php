<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="feather-clipboard me-2"></i>Inventaires</h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createInventaireModal">
                <i class="feather-plus me-2"></i>Nouvel inventaire
            </button>
        </div>

        <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6 class="text-muted">Total</h6>
                        <h4><?= $statistiques['total'] ?></h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">En cours</h6>
                        <h4 class="text-warning"><?= $statistiques['en_cours'] ?></h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Terminés</h6>
                        <h4 class="text-info"><?= $statistiques['termines'] ?></h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Validés</h6>
                        <h4 class="text-success"><?= $statistiques['valides'] ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">N° Inventaire</th>
                                <th class="border-0">Date</th>
                                <th class="border-0">Responsable</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventaires as $inv): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($inv['numero_inventaire']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($inv['date_inventaire'])) ?></td>
                                    <td><?= esc($inv['utilisateur_nom']) ?></td>
                                    <td>
                                        <?php
                                        $badge = match($inv['statut']) {
                                            'en_cours' => 'bg-warning',
                                            'termine' => 'bg-info',
                                            'valide' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badge ?>"><?= ucfirst($inv['statut']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/stock/inventaires/show/'.$inv['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="feather-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($pager): ?>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        <?= $pager->links() ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div class="modal fade" id="createInventaireModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel Inventaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createInventaireForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date de l'inventaire <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date_inventaire" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observations</label>
                        <textarea class="form-control" name="observations" rows="3" placeholder="Notes générales sur cet inventaire..."></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="feather-info me-2"></i>
                        Tous les produits actifs seront automatiquement ajoutés à cet inventaire.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#createInventaireForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stock/inventaires/create') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    window.location.href = '<?= base_url('admin/stock/inventaires/show') ?>/' + response.inventaire_id;
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
