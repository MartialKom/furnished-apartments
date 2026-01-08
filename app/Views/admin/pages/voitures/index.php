<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-truck me-2"></i>
                Parc Automobile
            </h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createVoitureModal">
                <i class="feather-plus me-2"></i>Ajouter une voiture
            </button>
        </div>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="voitureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="disponible-tab" data-bs-toggle="tab" data-bs-target="#disponible" type="button" role="tab">
                    <i class="feather-check-circle me-2"></i>Disponibles
                    <span class="badge bg-success ms-2"><?= count($voitures['disponible']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="louee-tab" data-bs-toggle="tab" data-bs-target="#louee" type="button" role="tab">
                    <i class="feather-key me-2"></i>Louées
                    <span class="badge bg-warning text-dark ms-2"><?= count($voitures['louee']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#maintenance" type="button" role="tab">
                    <i class="feather-tool me-2"></i>En maintenance
                    <span class="badge bg-info ms-2"><?= count($voitures['maintenance']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="indisponible-tab" data-bs-toggle="tab" data-bs-target="#indisponible" type="button" role="tab">
                    <i class="feather-x-circle me-2"></i>Indisponibles
                    <span class="badge bg-danger ms-2"><?= count($voitures['indisponible']) ?></span>
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="voitureTabsContent">

            <!-- Onglet Disponibles -->
            <div class="tab-pane fade show active" id="disponible" role="tabpanel">
                <div class="row">
                    <?php if (!empty($voitures['disponible'])): ?>
                        <?php foreach ($voitures['disponible'] as $voiture): ?>
                            <?php
                            // Récupérer la première photo
                            $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                            $premierePhoto = !empty($photos) ? trim($photos[0]) : '';
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                                    <?php if ($premierePhoto): ?>
                                        <div style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                            <img src="<?= base_url($premierePhoto) ?>"
                                                 alt="<?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="feather-truck" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?></h5>
                                            <span class="badge bg-success">Disponible</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Immatriculation:</strong> <?= esc($voiture['immatriculation']) ?></p>
                                        <p class="text-muted mb-2"><strong>Année:</strong> <?= esc($voiture['annee']) ?></p>
                                        <p class="text-muted mb-2"><strong>Places:</strong> <?= esc($voiture['nombre_places']) ?></p>
                                        <p class="text-muted mb-2"><strong>Transmission:</strong> <?= ucfirst($voiture['transmission']) ?></p>
                                        <p class="text-muted mb-2"><strong>Carburant:</strong> <?= ucfirst($voiture['type_carburant']) ?></p>
                                        <p class="text-muted mb-3"><strong>Kilométrage:</strong> <?= number_format($voiture['kilometrage'], 0, ',', ' ') ?> km</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="text-primary mb-0"><?= number_format($voiture['tarif_journalier'], 0, ',', ' ') ?> FCFA</h4>
                                                <small class="text-muted">/ jour</small>
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-primary" onclick="voirVoiture(<?= $voiture['id'] ?>)" title="Voir">
                                                    <i class="feather-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" onclick="modifierVoiture(<?= $voiture['id'] ?>)" title="Modifier">
                                                    <i class="feather-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="supprimerVoiture(<?= $voiture['id'] ?>)" title="Supprimer">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-truck" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune voiture disponible</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onglet Louées -->
            <div class="tab-pane fade" id="louee" role="tabpanel">
                <div class="row">
                    <?php if (!empty($voitures['louee'])): ?>
                        <?php foreach ($voitures['louee'] as $voiture): ?>
                            <?php
                            // Récupérer la première photo
                            $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                            $premierePhoto = !empty($photos) ? trim($photos[0]) : '';
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                                    <?php if ($premierePhoto): ?>
                                        <div style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                            <img src="<?= base_url($premierePhoto) ?>"
                                                 alt="<?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="feather-truck" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?></h5>
                                            <span class="badge bg-warning text-dark">Louée</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Immatriculation:</strong> <?= esc($voiture['immatriculation']) ?></p>
                                        <p class="text-muted mb-2"><strong>Année:</strong> <?= esc($voiture['annee']) ?></p>
                                        <p class="text-muted mb-3"><strong>Kilométrage:</strong> <?= number_format($voiture['kilometrage'], 0, ',', ' ') ?> km</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="text-primary mb-0"><?= number_format($voiture['tarif_journalier'], 0, ',', ' ') ?> FCFA</h4>
                                                <small class="text-muted">/ jour</small>
                                            </div>
                                            <button class="btn btn-sm btn-primary" onclick="voirVoiture(<?= $voiture['id'] ?>)">
                                                <i class="feather-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-key" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune voiture louée actuellement</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onglet Maintenance -->
            <div class="tab-pane fade" id="maintenance" role="tabpanel">
                <div class="row">
                    <?php if (!empty($voitures['maintenance'])): ?>
                        <?php foreach ($voitures['maintenance'] as $voiture): ?>
                            <?php
                            // Récupérer la première photo
                            $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                            $premierePhoto = !empty($photos) ? trim($photos[0]) : '';
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                                    <?php if ($premierePhoto): ?>
                                        <div style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                            <img src="<?= base_url($premierePhoto) ?>"
                                                 alt="<?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="feather-truck" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?></h5>
                                            <span class="badge bg-info">Maintenance</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Immatriculation:</strong> <?= esc($voiture['immatriculation']) ?></p>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button class="btn btn-sm btn-success me-2" onclick="changerStatut(<?= $voiture['id'] ?>, 'disponible')">
                                                Marquer disponible
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-tool" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune voiture en maintenance</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onglet Indisponibles -->
            <div class="tab-pane fade" id="indisponible" role="tabpanel">
                <div class="row">
                    <?php if (!empty($voitures['indisponible'])): ?>
                        <?php foreach ($voitures['indisponible'] as $voiture): ?>
                            <?php
                            // Récupérer la première photo
                            $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                            $premierePhoto = !empty($photos) ? trim($photos[0]) : '';
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                                    <?php if ($premierePhoto): ?>
                                        <div style="height: 200px; overflow: hidden; background: #f8f9fa; position: relative;">
                                            <img src="<?= base_url($premierePhoto) ?>"
                                                 alt="<?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(50%);">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 200px; background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="feather-truck" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?></h5>
                                            <span class="badge bg-danger">Indisponible</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Immatriculation:</strong> <?= esc($voiture['immatriculation']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-x-circle" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune voiture indisponible</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modals -->
<?= $this->include('admin/pages/voitures/modals/create') ?>
<?= $this->include('admin/pages/voitures/modals/view') ?>
<?= $this->include('admin/pages/voitures/modals/edit') ?>

<script>
function supprimerVoiture(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')) {
        fetch(`<?= base_url('admin/voitures/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

function changerStatut(id, nouveauStatut) {
    fetch(`<?= base_url('admin/voitures/changer-statut') ?>/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ statut: nouveauStatut })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du changement de statut');
    });
}
</script>

<?= $this->endSection() ?>
