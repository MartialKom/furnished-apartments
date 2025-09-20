<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouveau Contrat de Locataire</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/contrats') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="createContratForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="locataire_id">Locataire <span class="text-danger">*</span></label>
                                    <select class="form-control" id="locataire_id" name="locataire_id" required>
                                        <option value="">Sélectionner un locataire</option>
                                        <?php foreach ($locataires as $locataire): ?>
                                            <option value="<?= $locataire['id'] ?>">
                                                <?= $locataire['nom'] ?> (<?= $locataire['email'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="appartement_id">Appartement <span class="text-danger">*</span></label>
                                    <select class="form-control" id="appartement_id" name="appartement_id" required>
                                        <option value="">Sélectionner un appartement</option>
                                        <?php foreach ($appartements as $appartement): ?>
                                            <option value="<?= $appartement['id'] ?>" 
                                                    data-loyer="<?= $appartement['tarifs']['mensuel'] ?? 0 ?>">
                                                <?= $appartement['adresse'] ?> - 
                                                <?= number_format($appartement['tarifs']['mensuel'] ?? 0, 0, ',', ' ') ?> FCFA/mois
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_debut">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                           value="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_fin">Date de fin (optionnel)</label>
                                    <input type="date" class="form-control" id="date_fin" name="date_fin">
                                    <small class="form-text text-muted">Laisser vide pour un contrat indéterminé</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jour_paiement">Jour de paiement <span class="text-danger">*</span></label>
                                    <select class="form-control" id="jour_paiement" name="jour_paiement" required>
                                        <?php for ($i = 1; $i <= 31; $i++): ?>
                                            <option value="<?= $i ?>" <?= $i == 1 ? 'selected' : '' ?>>
                                                <?= $i ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loyer_mensuel">Loyer mensuel (FCFA) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="loyer_mensuel" name="loyer_mensuel" 
                                           step="100" min="0" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="caution">Caution (FCFA)</label>
                                    <input type="number" class="form-control" id="caution" name="caution" 
                                           step="100" min="0" value="0">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="conditions_particulieres">Conditions particulières</label>
                            <textarea class="form-control" id="conditions_particulieres" name="conditions_particulieres" 
                                      rows="4" placeholder="Toute condition particulière du contrat..."></textarea>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Créer le Contrat
                            </button>
                            <a href="<?= base_url('admin/contrats') ?>" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-remplir le loyer quand un appartement est sélectionné
    $('#appartement_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        const loyer = selectedOption.data('loyer');
        if (loyer && loyer > 0) {
            $('#loyer_mensuel').val(loyer);
        }
    });

    // Validation et soumission du formulaire
    $('#createContratForm').submit(function(e) {
        e.preventDefault();
        
        // Validation côté client
        if (!validateForm()) {
            return;
        }

        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.html();
        
        // Désactiver le bouton et afficher un indicateur de chargement
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url('admin/contrats/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, 'Succès', {
                        timeOut: 3000,
                        onHidden: function() {
                            window.location.href = '<?= base_url('admin/contrats') ?>';
                        }
                    });
                } else {
                    if (response.errors) {
                        displayValidationErrors(response.errors);
                    } else {
                        toastr.error(response.message, 'Erreur');
                    }
                    // Réactiver le bouton en cas d'erreur
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', error);
                toastr.error('Erreur lors de la communication avec le serveur', 'Erreur');
                // Réactiver le bouton en cas d'erreur
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    function validateForm() {
        let isValid = true;
        
        // Réinitialiser les erreurs
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Validation des champs requis
        const requiredFields = ['locataire_id', 'appartement_id', 'date_debut', 'jour_paiement', 'loyer_mensuel'];
        
        requiredFields.forEach(field => {
            const value = $(`#${field}`).val();
            if (!value || value.trim() === '') {
                $(`#${field}`).addClass('is-invalid');
                $(`#${field}`).siblings('.invalid-feedback').text('Ce champ est requis');
                isValid = false;
            }
        });

        // Validation du loyer
        const loyer = parseFloat($('#loyer_mensuel').val());
        if (loyer <= 0) {
            $('#loyer_mensuel').addClass('is-invalid');
            $('#loyer_mensuel').siblings('.invalid-feedback').text('Le loyer doit être supérieur à 0');
            isValid = false;
        }

        // Validation des dates
        const dateDebut = new Date($('#date_debut').val());
        const dateFin = $('#date_fin').val();
        
        if (dateFin) {
            const dateFinObj = new Date(dateFin);
            if (dateFinObj <= dateDebut) {
                $('#date_fin').addClass('is-invalid');
                $('#date_fin').siblings('.invalid-feedback').text('La date de fin doit être après la date de début');
                isValid = false;
            }
        }

        return isValid;
    }

    function displayValidationErrors(errors) {
        for (const field in errors) {
            const input = $(`[name="${field}"]`);
            input.addClass('is-invalid');
            input.siblings('.invalid-feedback').text(errors[field]);
        }
    }
});
</script>
<?= $this->endSection() ?>
