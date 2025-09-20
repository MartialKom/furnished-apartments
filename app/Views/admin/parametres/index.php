<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Paramètres</li>
                    </ol>
                </div>
                <h4 class="page-title">Paramètres de la Structure</h4>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="feather-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="feather-alert-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/parametres/update') ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Informations de la Structure -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Informations de la Structure</h4>
                        <p class="text-muted mb-0">Configurez les informations qui apparaîtront sur les contrats et documents</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_name" class="form-label">Nom de la Structure *</label>
                                    <input type="text" class="form-control" id="structure_name" name="structure_name" 
                                           value="<?= esc($structureParams['structure_name']) ?>" required>
                                    <small class="form-text text-muted">Nom qui apparaîtra sur les contrats</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_legal_form" class="form-label">Forme Juridique</label>
                                    <select class="form-control" id="structure_legal_form" name="structure_legal_form">
                                        <option value="SARL" <?= $structureParams['structure_legal_form'] === 'SARL' ? 'selected' : '' ?>>SARL</option>
                                        <option value="SA" <?= $structureParams['structure_legal_form'] === 'SA' ? 'selected' : '' ?>>SA</option>
                                        <option value="EURL" <?= $structureParams['structure_legal_form'] === 'EURL' ? 'selected' : '' ?>>EURL</option>
                                        <option value="SAS" <?= $structureParams['structure_legal_form'] === 'SAS' ? 'selected' : '' ?>>SAS</option>
                                        <option value="AUTRE" <?= $structureParams['structure_legal_form'] === 'AUTRE' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_address" class="form-label">Adresse Complète *</label>
                                    <textarea class="form-control" id="structure_address" name="structure_address" rows="3" required><?= esc($structureParams['structure_address']) ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_phone" class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" id="structure_phone" name="structure_phone" 
                                           value="<?= esc($structureParams['structure_phone']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="structure_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="structure_email" name="structure_email" 
                                           value="<?= esc($structureParams['structure_email']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_website" class="form-label">Site Web</label>
                                    <input type="url" class="form-control" id="structure_website" name="structure_website" 
                                           value="<?= esc($structureParams['structure_website']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_logo" class="form-label">Logo</label>
                                    <input type="text" class="form-control" id="structure_logo" name="structure_logo" 
                                           value="<?= esc($structureParams['structure_logo']) ?>" 
                                           placeholder="Chemin vers le logo (ex: assets/images/logo.png)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Légales -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Informations Légales</h4>
                        <p class="text-muted mb-0">Numéros d'enregistrement et informations fiscales</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="structure_rc" class="form-label">Registre du Commerce</label>
                                    <input type="text" class="form-control" id="structure_rc" name="structure_rc" 
                                           value="<?= esc($structureParams['structure_rc']) ?>" 
                                           placeholder="RC-ABJ-2024-A-12345">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="structure_nif" class="form-label">NIF</label>
                                    <input type="text" class="form-control" id="structure_nif" name="structure_nif" 
                                           value="<?= esc($structureParams['structure_nif']) ?>" 
                                           placeholder="NIF-ABJ-2024-67890">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres par Défaut -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Paramètres par Défaut</h4>
                        <p class="text-muted mb-0">Valeurs par défaut pour les nouveaux locataires</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_nationality" class="form-label">Nationalité par Défaut</label>
                                    <input type="text" class="form-control" id="default_nationality" name="default_nationality" 
                                           value="<?= esc($structureParams['default_nationality'] ?? 'Ivoirienne') ?>" 
                                           placeholder="Ivoirienne">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_id_type" class="form-label">Type de Pièce par Défaut</label>
                                    <select class="form-control" id="default_id_type" name="default_id_type">
                                        <option value="CNI" <?= ($structureParams['default_id_type'] ?? 'CNI') === 'CNI' ? 'selected' : '' ?>>CNI</option>
                                        <option value="PASSPORT" <?= ($structureParams['default_id_type'] ?? '') === 'PASSPORT' ? 'selected' : '' ?>>Passeport</option>
                                        <option value="CARTE_SEJOUR" <?= ($structureParams['default_id_type'] ?? '') === 'CARTE_SEJOUR' ? 'selected' : '' ?>>Carte de Séjour</option>
                                        <option value="AUTRE" <?= ($structureParams['default_id_type'] ?? '') === 'AUTRE' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de Sauvegarde -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="feather-save me-2"></i>Enregistrer les Paramètres
                        </button>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-lg ms-2">
                            <i class="feather-arrow-left me-2"></i>Retour au Tableau de Bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.required {
    color: #dc3545;
}
</style>
<?= $this->endSection() ?>

