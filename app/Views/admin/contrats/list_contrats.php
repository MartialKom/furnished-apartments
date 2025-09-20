<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Contrats de Location</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Contrats de Location</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Liste des Contrats</h4>
                    <p class="text-muted mb-0">Gérez et imprimez les contrats de location</p>
                </div>
                <div class="card-body">
                    <?php if (empty($contrats)): ?>
                        <div class="alert alert-info">
                            <i class="feather-info me-2"></i>
                            Aucun contrat de location trouvé.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>N° Contrat</th>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Loyer Mensuel</th>
                                        <th>Date Début</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contrats as $contrat): ?>
                                        <tr>
                                            <td>
                                                <strong>CONTRAT-<?= date('Ymd', strtotime($contrat['created_at'])) ?>-<?= $contrat['id'] ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= esc($contrat['locataire_nom']) ?></strong>
                                                </div>
                                                <small class="text-muted"><?= esc($contrat['locataire_email']) ?></small>
                                            </td>
                                            <td>
                                                <?= esc($contrat['appartement_adresse']) ?>
                                            </td>
                                            <td>
                                                <strong><?= number_format($contrat['loyer_mensuel'], 0, ',', ' ') ?> FCFA</strong>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y', strtotime($contrat['date_debut'])) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $badgeClass = '';
                                                $statusText = '';
                                                switch ($contrat['statut']) {
                                                    case 'actif':
                                                        $badgeClass = 'bg-success';
                                                        $statusText = 'Actif';
                                                        break;
                                                    case 'suspendu':
                                                        $badgeClass = 'bg-warning';
                                                        $statusText = 'Suspendu';
                                                        break;
                                                    case 'termine':
                                                        $badgeClass = 'bg-danger';
                                                        $statusText = 'Terminé';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-secondary';
                                                        $statusText = ucfirst($contrat['statut']);
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= $statusText ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url('admin/contrats-location/generate/' . $contrat['id']) ?>" 
                                                       class="btn btn-primary btn-sm" 
                                                       target="_blank"
                                                       title="Imprimer le contrat">
                                                        <i class="feather-printer"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/contrats/show/' . $contrat['id']) ?>" 
                                                       class="btn btn-info btn-sm" 
                                                       title="Voir les détails">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    background-color: #2c5aa0;
    color: white;
    border: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
}
</style>
<?= $this->endSection() ?>
