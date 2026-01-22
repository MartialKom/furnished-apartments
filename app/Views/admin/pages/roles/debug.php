<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <i class="feather-alert-triangle"></i>
            <strong>Page de Debug</strong> - Cette page affiche les informations de diagnostic pour les roles et permissions.
            <strong>Supprimez cette page en production !</strong>
        </div>
    </div>
</div>

<!-- Current User Session Debug -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="feather-user"></i> Session Utilisateur Actuel</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>User ID:</th>
                                <td><?= $currentUserDebug['user_id'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Nom:</th>
                                <td><?= $currentUserDebug['user_nom'] ?? 'N/A' ?></td>
                            </tr>
                            <tr>
                                <th>Role (ancien champ):</th>
                                <td><code><?= $currentUserDebug['user_role'] ?? 'N/A' ?></code></td>
                            </tr>
                            <tr>
                                <th>Role ID:</th>
                                <td><code><?= $currentUserDebug['user_role_id'] ?? 'N/A' ?></code></td>
                            </tr>
                            <tr>
                                <th>Role Name:</th>
                                <td><?= $currentUserDebug['user_role_name'] ?? 'N/A' ?></td>
                            </tr>
                            <tr class="<?= $currentUserDebug['is_super_admin'] ? 'table-danger' : 'table-success' ?>">
                                <th>Is Super Admin:</th>
                                <td>
                                    <strong><?= $currentUserDebug['is_super_admin'] ? 'OUI - ACCES TOTAL' : 'NON' ?></strong>
                                    <?php if ($currentUserDebug['is_super_admin']): ?>
                                        <span class="badge bg-danger">VOIR TOUT</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Permissions en Session (<?= count($currentUserDebug['user_permissions'] ?? []) ?>):</h6>
                        <?php if (!empty($currentUserDebug['user_permissions'])): ?>
                            <div style="max-height: 200px; overflow-y: auto;">
                                <?php foreach ($currentUserDebug['user_permissions'] as $perm): ?>
                                    <span class="badge bg-info me-1 mb-1"><?= esc($perm) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Aucune permission</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Permissions Available -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="feather-package"></i> Permissions Stock Disponibles (<?= count($stockPermissions) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($stockPermissions)): ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Nom</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stockPermissions as $perm): ?>
                                <tr>
                                    <td><?= $perm['id'] ?></td>
                                    <td><code><?= esc($perm['code']) ?></code></td>
                                    <td><?= esc($perm['nom']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">Aucune permission stock trouvee !</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Roles List with Details -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="feather-shield"></i> Tous les Roles (<?= count($roles) ?>) - Total Permissions: <?= $totalPermissions ?></h5>
            </div>
            <div class="card-body">
                <?php foreach ($roles as $role): ?>
                    <div class="card mb-3 <?= $role['is_super_admin'] ? 'border-danger' : '' ?>">
                        <div class="card-header <?= $role['is_super_admin'] ? 'bg-danger text-white' : 'bg-light' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= esc($role['nom']) ?></strong>
                                    <code class="ms-2"><?= esc($role['code']) ?></code>
                                    <?php if ($role['is_super_admin']): ?>
                                        <span class="badge bg-warning text-dark ms-2">SUPER ADMIN</span>
                                    <?php endif; ?>
                                    <?php if ($role['is_system']): ?>
                                        <span class="badge bg-secondary ms-2">Systeme</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="badge bg-primary"><?= $role['permissions_count'] ?> permissions</span>
                                    <span class="badge bg-success"><?= $role['users_count'] ?> utilisateur(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Permissions assignees (<?= count($role['permission_codes']) ?>):</h6>
                                    <?php if (!empty($role['permission_codes'])): ?>
                                        <div style="max-height: 150px; overflow-y: auto;">
                                            <?php foreach ($role['permission_codes'] as $code): ?>
                                                <span class="badge bg-secondary me-1 mb-1"><?= esc($code) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Aucune permission assignee</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h6>Utilisateurs assignes (<?= count($role['users']) ?>):</h6>
                                    <?php if (!empty($role['users'])): ?>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom</th>
                                                    <th>Username</th>
                                                    <th>Role (ancien)</th>
                                                    <th>role_id</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($role['users'] as $user): ?>
                                                    <tr>
                                                        <td><?= $user['id'] ?></td>
                                                        <td><?= esc($user['nom'] . ' ' . $user['prenom']) ?></td>
                                                        <td><?= esc($user['nomUtilisateur']) ?></td>
                                                        <td><code><?= esc($user['role']) ?></code></td>
                                                        <td><code><?= $user['role_id'] ?></code></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <span class="text-muted">Aucun utilisateur assigne</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Users without role_id -->
<?php if (!empty($usersWithoutRoleId)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="feather-alert-triangle"></i> Utilisateurs sans role_id (<?= count($usersWithoutRoleId) ?>)</h5>
            </div>
            <div class="card-body">
                <p class="text-danger"><strong>Ces utilisateurs n'ont pas de role_id assigne. Ils doivent etre migres vers le nouveau systeme de roles.</strong></p>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Username</th>
                            <th>Role (ancien champ texte)</th>
                            <th>role_id</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usersWithoutRoleId as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= esc($user['nom'] . ' ' . $user['prenom']) ?></td>
                                <td><?= esc($user['nomUtilisateur']) ?></td>
                                <td><code><?= esc($user['role']) ?></code></td>
                                <td><code><?= $user['role_id'] ?? 'NULL' ?></code></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Diagnostic Summary -->
<div class="row">
    <div class="col-12">
        <div class="card border-dark">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="feather-check-circle"></i> Resume du Diagnostic</h5>
            </div>
            <div class="card-body">
                <h6>Problemes potentiels detectes:</h6>
                <ul>
                    <?php
                    $problems = [];

                    // Check if current user has is_super_admin but shouldn't
                    if ($currentUserDebug['is_super_admin'] && $currentUserDebug['user_role_name'] !== 'Super Admin') {
                        $problems[] = "L'utilisateur actuel a is_super_admin=true mais son role n'est pas 'Super Admin'";
                    }

                    // Check for roles with is_super_admin that shouldn't have it
                    foreach ($roles as $role) {
                        if ($role['is_super_admin'] && strtolower($role['code']) !== 'admin' && strtolower($role['code']) !== 'super_admin') {
                            $problems[] = "Le role '{$role['nom']}' a is_super_admin=1 mais ne devrait probablement pas";
                        }
                    }

                    // Check for users without role_id
                    if (!empty($usersWithoutRoleId)) {
                        $problems[] = count($usersWithoutRoleId) . " utilisateur(s) n'ont pas de role_id assigne";
                    }

                    if (empty($problems)) {
                        echo '<li class="text-success">Aucun probleme majeur detecte</li>';
                    } else {
                        foreach ($problems as $problem) {
                            echo '<li class="text-danger"><strong>' . esc($problem) . '</strong></li>';
                        }
                    }
                    ?>
                </ul>

                <h6 class="mt-4">Actions recommandees:</h6>
                <ol>
                    <li>Verifiez que seul le role "Super Admin" a <code>is_super_admin = 1</code></li>
                    <li>Assurez-vous que les utilisateurs ont le bon <code>role_id</code></li>
                    <li>Verifiez que le role "Magazinier" a uniquement les permissions stock</li>
                    <li>Deconnectez et reconnectez l'utilisateur magazinier pour rafraichir sa session</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
