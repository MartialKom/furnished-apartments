<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestion des Utilisateurs</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUtilisateurModal">
                    <i class="feather-plus"></i> Ajouter un Utilisateur
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="utilisateursTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($utilisateurs)): ?>
                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                    <tr>
                                        <td><?= $utilisateur['id'] ?></td>
                                        <td><?= esc($utilisateur['nom']) ?></td>
                                        <td><?= esc($utilisateur['prenom']) ?></td>
                                        <td><?= esc($utilisateur['nomUtilisateur']) ?></td>
                                        <td><?= esc($utilisateur['email']) ?></td>
                                        <td><?= esc($utilisateur['telephone']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'primary';
                                            $roleCode = $utilisateur['role_code'] ?? $utilisateur['role'] ?? '';
                                            if (in_array($roleCode, ['admin', 'super_admin'])) {
                                                $badgeClass = 'danger';
                                            } elseif ($roleCode === 'gestionnaire') {
                                                $badgeClass = 'info';
                                            }
                                            ?>
                                            <span class="badge bg-<?= $badgeClass ?>">
                                                <?= esc($utilisateur['role_nom'] ?? ucfirst($utilisateur['role'] ?? 'Non défini')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $utilisateur['statut'] === 'actif' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($utilisateur['statut']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-utilisateur" data-id="<?= $utilisateur['id'] ?>">
                                                <i class="feather-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning toggle-status" data-id="<?= $utilisateur['id'] ?>" data-statut="<?= $utilisateur['statut'] ?>">
                                                <i class="feather-<?= $utilisateur['statut'] === 'actif' ? 'pause' : 'play' ?>"></i>
                                            </button>
                                            <?php if (session()->get('user_id') != $utilisateur['id']): ?>
                                                <button class="btn btn-sm btn-danger delete-utilisateur" data-id="<?= $utilisateur['id'] ?>">
                                                    <i class="feather-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Aucun utilisateur trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Utilisateur -->
<div class="modal fade" id="addUtilisateurModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUtilisateurForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nomUtilisateur" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nomUtilisateur" name="nomUtilisateur" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-control" id="role_id" name="role_id" required>
                            <option value="">Sélectionner un rôle</option>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= esc($role['nom']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="motDePasse" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="motDePasse" name="motDePasse" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Utilisateur -->
<div class="modal fade" id="editUtilisateurModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUtilisateurForm">
                <input type="hidden" id="edit_utilisateur_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_nom" name="nom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_prenom" name="prenom" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nomUtilisateur" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nomUtilisateur" name="nomUtilisateur" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_telephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_id" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_role_id" name="role_id" required>
                            <option value="">Sélectionner un rôle</option>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>"><?= esc($role['nom']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_motDePasse" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" class="form-control" id="edit_motDePasse" name="motDePasse">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Ajouter un utilisateur
    $('#addUtilisateurForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('/admin/utilisateurs/create') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                alert('Erreur lors de l\'ajout de l\'utilisateur');
            }
        });
    });

    // Modifier un utilisateur
    $('.edit-utilisateur').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('/admin/utilisateurs/get/') ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const utilisateur = response.utilisateur;
                    $('#edit_utilisateur_id').val(utilisateur.id);
                    $('#edit_nom').val(utilisateur.nom);
                    $('#edit_prenom').val(utilisateur.prenom);
                    $('#edit_nomUtilisateur').val(utilisateur.nomUtilisateur);
                    $('#edit_telephone').val(utilisateur.telephone);
                    $('#edit_email').val(utilisateur.email);
                    $('#edit_role_id').val(utilisateur.role_id);
                    $('#editUtilisateurModal').modal('show');
                }
            }
        });
    });

    $('#editUtilisateurForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_utilisateur_id').val();
        
        $.ajax({
            url: '<?= base_url('/admin/utilisateurs/update/') ?>' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            }
        });
    });

    // Toggle statut
    $('.toggle-status').on('click', function() {
        const id = $(this).data('id');
        const statut = $(this).data('statut');
        const action = statut === 'actif' ? 'désactiver' : 'activer';
        
        if (confirm(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`)) {
            $.ajax({
                url: '<?= base_url('/admin/utilisateurs/toggle-status/') ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                }
            });
        }
    });

    // Supprimer un utilisateur
    $('.delete-utilisateur').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
            const id = $(this).data('id');
            
            $.ajax({
                url: '<?= base_url('/admin/utilisateurs/delete/') ?>' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>