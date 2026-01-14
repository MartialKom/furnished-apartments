<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestion des Rôles et Permissions</h5>
                <a href="<?= base_url('/admin/roles/create') ?>" class="btn btn-primary">
                    <i class="feather-plus"></i> Créer un Rôle
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover" id="rolesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Permissions</th>
                                <th>Utilisateurs</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td><?= $role['id'] ?></td>
                                        <td>
                                            <?= esc($role['nom']) ?>
                                            <?php if ($role['is_system'] == 1): ?>
                                                <span class="badge bg-purple ms-1">Système</span>
                                            <?php endif; ?>
                                            <?php if ($role['is_super_admin'] == 1): ?>
                                                <span class="badge bg-danger ms-1">Super Admin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?= esc($role['code']) ?></code></td>
                                        <td>
                                            <?php if (!empty($role['description'])): ?>
                                                <?= esc(substr($role['description'], 0, 50)) ?>
                                                <?= strlen($role['description']) > 50 ? '...' : '' ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($role['is_super_admin'] == 1): ?>
                                                <span class="badge bg-success">Toutes</span>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-outline-info view-permissions"
                                                        data-id="<?= $role['id'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        title="Voir les permissions">
                                                    <i class="feather-eye"></i> Voir
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= $role['users_count'] ?> utilisateur(s)
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $role['statut'] === 'actif' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($role['statut']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($role['is_super_admin'] != 1): ?>
                                                <a href="<?= base_url('/admin/roles/edit/' . $role['id']) ?>"
                                                   class="btn btn-sm btn-info"
                                                   data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="feather-edit"></i>
                                                </a>

                                                <?php if ($role['is_system'] != 1): ?>
                                                    <button class="btn btn-sm btn-warning toggle-status"
                                                            data-id="<?= $role['id'] ?>"
                                                            data-statut="<?= $role['statut'] ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="<?= $role['statut'] === 'actif' ? 'Désactiver' : 'Activer' ?>">
                                                        <i class="feather-<?= $role['statut'] === 'actif' ? 'pause' : 'play' ?>"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-danger delete-role"
                                                            data-id="<?= $role['id'] ?>"
                                                            data-name="<?= esc($role['nom']) ?>"
                                                            data-users-count="<?= $role['users_count'] ?>"
                                                            data-bs-toggle="tooltip" title="Supprimer"
                                                            <?= $role['users_count'] > 0 ? 'disabled' : '' ?>>
                                                        <i class="feather-trash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled data-bs-toggle="tooltip" title="Rôle système protégé">
                                                        <i class="feather-lock"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled data-bs-toggle="tooltip" title="Super Admin protégé">
                                                    <i class="feather-shield"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Aucun rôle trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Voir Permissions -->
<div class="modal fade" id="viewPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permissions du Rôle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="permissionsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#rolesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[0, 'desc']]
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // View permissions
    $('.view-permissions').on('click', function() {
        const roleId = $(this).data('id');

        $.ajax({
            url: `/admin/roles/get/${roleId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const role = response.role;
                    let html = '<h6>' + role.nom + '</h6>';
                    html += '<p class="text-muted">' + (role.description || '') + '</p>';
                    html += '<hr>';

                    if (role.permissions && role.permissions.length > 0) {
                        // Group permissions by groupe
                        const grouped = {};
                        role.permissions.forEach(perm => {
                            const groupe = perm.groupe || 'Autres';
                            if (!grouped[groupe]) {
                                grouped[groupe] = [];
                            }
                            grouped[groupe].push(perm);
                        });

                        // Display grouped permissions
                        for (const [groupe, perms] of Object.entries(grouped)) {
                            html += '<h6 class="mt-3 mb-2">' + groupe + '</h6>';
                            html += '<ul class="list-unstyled ms-3">';
                            perms.forEach(perm => {
                                html += '<li><i class="feather-check text-success"></i> ' + perm.nom + '</li>';
                            });
                            html += '</ul>';
                        }
                    } else {
                        html += '<div class="alert alert-warning">Aucune permission assignée à ce rôle.</div>';
                    }

                    $('#permissionsContent').html(html);
                    $('#viewPermissionsModal').modal('show');
                } else {
                    alert('Erreur lors du chargement des permissions.');
                }
            },
            error: function() {
                alert('Erreur lors du chargement des permissions.');
            }
        });
    });

    // Toggle status
    $('.toggle-status').on('click', function() {
        const roleId = $(this).data('id');
        const currentStatus = $(this).data('statut');
        const newStatus = currentStatus === 'actif' ? 'inactif' : 'actif';

        if (confirm(`Voulez-vous vraiment ${newStatus === 'actif' ? 'activer' : 'désactiver'} ce rôle ?`)) {
            $.ajax({
                url: `/admin/roles/toggle-status/${roleId}`,
                type: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Erreur lors de la modification du statut.');
                    }
                },
                error: function() {
                    alert('Erreur lors de la modification du statut.');
                }
            });
        }
    });

    // Delete role
    $('.delete-role').on('click', function() {
        const roleId = $(this).data('id');
        const roleName = $(this).data('name');
        const usersCount = $(this).data('users-count');

        if (usersCount > 0) {
            alert(`Ce rôle ne peut pas être supprimé car ${usersCount} utilisateur(s) y sont assignés.`);
            return;
        }

        if (confirm(`Voulez-vous vraiment supprimer le rôle "${roleName}" ?`)) {
            $.ajax({
                url: `/admin/roles/delete/${roleId}`,
                type: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Erreur lors de la suppression.');
                    }
                },
                error: function() {
                    alert('Erreur lors de la suppression.');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
