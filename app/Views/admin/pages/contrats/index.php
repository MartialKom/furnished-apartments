<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contrats de Locataires à Long Terme</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/contrats/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Contrat
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($contrats)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Appartement</th>
                                        <th>Loyer Mensuel</th>
                                        <th>Jour Paiement</th>
                                        <th>Date Début</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contrats as $contrat): ?>
                                        <tr>
                                            <td><?= $contrat['id'] ?></td>
                                            <td><?= $contrat['locataire_nom'] ?></td>
                                            <td><?= $contrat['locataire_email'] ?></td>
                                            <td><?= $contrat['locataire_telephone'] ?></td>
                                            <td><?= $contrat['appartement_adresse'] ?></td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    <?= number_format($contrat['loyer_mensuel'], 0, ',', ' ') ?> FCFA
                                                </span>
                                            </td>
                                            <td><?= $contrat['jour_paiement'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></td>
                                            <td>
                                                <?php
                                                $statutClass = match($contrat['statut']) {
                                                    'actif' => 'bg-success',
                                                    'suspendu' => 'bg-warning text-dark',
                                                    'termine' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                                $statutText = match($contrat['statut']) {
                                                    'actif' => 'Actif',
                                                    'suspendu' => 'Suspendu',
                                                    'termine' => 'Terminé',
                                                    default => ucfirst($contrat['statut'])
                                                };
                                                $statutIcon = match($contrat['statut']) {
                                                    'actif' => 'feather-check-circle',
                                                    'suspendu' => 'feather-pause-circle',
                                                    'termine' => 'feather-x-circle',
                                                    default => 'feather-circle'
                                                };
                                                ?>
                                                <span class="badge <?= $statutClass ?> d-flex align-items-center">
                                                    <i class="<?= $statutIcon ?> me-1" style="font-size: 12px;"></i>
                                                    <?= $statutText ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/contrats/show/' . $contrat['id']) ?>" 
                                                       class="btn btn-info btn-sm" title="Voir détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/contrats/edit/' . $contrat['id']) ?>" 
                                                       class="btn btn-warning btn-sm" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($contrat['statut'] === 'actif'): ?>
                                                        <button class="btn btn-danger btn-sm terminer-contrat" 
                                                                data-id="<?= $contrat['id'] ?>" title="Terminer">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucun contrat de locataire trouvé.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de fin de contrat -->
<div class="modal fade" id="modalTerminerContrat" tabindex="-1" role="dialog" aria-labelledby="modalTerminerContratLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTerminerContratLabel">Confirmer la fin du contrat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir terminer ce contrat ?</p>
                <p class="text-muted">Cette action libérera l'appartement et marquera le contrat comme terminé.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmTerminerContrat">
                    <i class="feather-check me-2"></i>Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let contratIdToTerminer = null;

    $('.terminer-contrat').click(function() {
        contratIdToTerminer = $(this).data('id');
        $('#modalTerminerContrat').modal('show');
    });

    $('#confirmTerminerContrat').click(function() {
        if (contratIdToTerminer) {
            const $btn = $(this);
            
            // Désactiver le bouton pendant le traitement
            $btn.prop('disabled', true).html('<i class="feather-loader me-2"></i>Traitement...');
            
            $.ajax({
                url: '<?= base_url('admin/contrats/terminer') ?>/' + contratIdToTerminer,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Contrat terminé');
                        $('#modalTerminerContrat').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Erreur');
                    }
                },
                error: function() {
                    toastr.error('Erreur lors de la communication avec le serveur', 'Erreur');
                },
                complete: function() {
                    // Réactiver le bouton
                    $btn.prop('disabled', false).html('<i class="feather-check me-2"></i>Confirmer');
                }
            });
        }
    });
    
    // Fermer la modal avec la touche Échap
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#modalTerminerContrat').hasClass('show')) {
            $('#modalTerminerContrat').modal('hide');
        }
    });
});
</script>
<?= $this->endSection() ?>
