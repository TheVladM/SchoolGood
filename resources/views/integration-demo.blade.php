<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des élèves et administrateurs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; color: #222; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1, h2 { margin-bottom: 16px; }
        .tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid #ddd; }
        .tab-button { padding: 12px 16px; background: none; border: none; cursor: pointer; font-size: 14px; border-bottom: 3px solid transparent; }
        .tab-button.active { color: #0066cc; border-bottom-color: #0066cc; }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* Photo Upload */
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; }
        input[type="file"], input[type="text"], select, textarea { display: block; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .photo-preview { width: 120px; height: 120px; border-radius: 12px; background: #eee; display: flex; align-items: center; justify-content: center; margin: 12px 0; color: #999; }
        .photo-preview img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
        button { padding: 10px 16px; background: #0066cc; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0052a3; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        button.secondary { background: #666; }
        button.secondary:hover { background: #555; }

        /* Autocomplete */
        input { position: relative; }
        .autocomplete-list { position: absolute; background: white; border: 1px solid #ddd; border-top: none; max-width: 100%; max-height: 200px; overflow-y: auto; z-index: 10; width: 100%; }
        .autocomplete-item { display: block; width: 100%; padding: 12px; text-align: left; background: white; border: none; cursor: pointer; border-bottom: 1px solid #f0f0f0; }
        .autocomplete-item:hover { background: #f5f5f5; }
        .autocomplete-empty { padding: 12px; color: #999; }

        /* Card Display */
        .card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin: 12px 0; }
        .card strong { display: block; margin-bottom: 4px; }
        .card span { display: block; font-size: 12px; color: #666; }

        /* Table */
        table { width: 100%; border-collapse: collapse; background: white; margin: 12px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: 600; }
        tr:hover { background: #fafafa; }
        .btn-delete { padding: 6px 12px; background: #d9534f; font-size: 12px; }
        .btn-delete:hover { background: #c9302c; }

        /* Print Controls */
        .filters { background: white; padding: 16px; border-radius: 8px; margin-bottom: 20px; }
        .filter-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 12px; }
        .filter-row label { margin-bottom: 4px; }
        .preview-info { padding: 12px; background: #e8f4f8; border-radius: 4px; border-left: 3px solid #0066cc; margin: 12px 0; }
        .actions { display: flex; gap: 12px; margin-top: 16px; }

        .form-container { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>SchoolGood — Gestion intégrée</h1>

        <div class="tabs">
            <button class="tab-button active" data-tab="eleves">Élèves &amp; Photos</button>
            <button class="tab-button" data-tab="parents">Lien Parent-Élève</button>
            <button class="tab-button" data-tab="print">Impression &amp; Export</button>
        </div>

        <!-- === ONGLET ÉLÈVES === -->
        <div id="eleves" class="tab-pane active">
            <div class="form-container">
                <h2>Ajouter/Modifier un élève</h2>
                <form id="eleveForm" method="POST">
                    <div class="form-group">
                        <label for="matricule">Matricule *</label>
                        <input type="text" id="matricule" name="matricule" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="photoInput">Photo (JPEG, PNG, WebP — max 2 Mo)</label>
                        <input type="file" id="photoInput" name="photo" accept="image/*">
                        <div id="photoPreview" class="photo-preview"></div>
                        <button type="button" id="removePhotoBtn" class="secondary">Supprimer la photo</button>
                    </div>
                    <button type="submit">Enregistrer</button>
                </form>
            </div>

            <div>
                <h2>Liste des élèves</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody id="elevesTableBody">
                        <tr><td colspan="3">Chargement...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- === ONGLET PARENT-ÉLÈVE === -->
        <div id="parents" class="tab-pane">
            <div class="form-container">
                <h2>Lier un parent à un élève</h2>
                <form id="parentForm" method="POST">
                    <div class="form-group">
                        <label for="parentSearch">Rechercher un parent</label>
                        <input type="text" id="parentSearch" placeholder="Nom ou prénom du parent">
                        <input type="hidden" id="parentId">
                    </div>
                    <div id="parentCard" style="margin-bottom: 16px;"></div>

                    <div class="form-group">
                        <label for="eleveSearch">Rechercher un élève</label>
                        <input type="text" id="eleveSearch" placeholder="Matricule, nom ou prénom">
                        <input type="hidden" id="matricule">
                    </div>
                    <div id="eleveCard" style="margin-bottom: 16px;"></div>

                    <button type="submit" id="linkButton" disabled>Lier parent-élève</button>
                </form>
            </div>

            <div>
                <h2>Liens existants</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Parent</th>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="parentsTableBody">
                        <tr><td colspan="4">Chargement...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- === ONGLET IMPRESSION === -->
        <div id="print" class="tab-pane">
            <div class="filters">
                <h2>Listes — Impression &amp; Export</h2>

                <div class="filter-row">
                    <div>
                        <label for="typeSelect">Type de liste *</label>
                        <select id="typeSelect">
                            <option value="admins">Administrateurs</option>
                            <option value="eleves">Élèves</option>
                        </select>
                    </div>
                    <div>
                        <label for="statusSelect">Statut</label>
                        <select id="statusSelect">
                            <option value="">Tous</option>
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="filter-row" id="eleveFiltersRow" style="display:none;">
                    <div>
                        <label for="classeSelect">Classe</label>
                        <select id="classeSelect">
                            <option value="">Toutes</option>
                        </select>
                    </div>
                    <div>
                        <label for="anneeSelect">Année académique</label>
                        <select id="anneeSelect">
                            <option value="">Toutes</option>
                        </select>
                    </div>
                </div>

                <div class="filter-row" id="adminFiltersRow">
                    <div>
                        <label for="typeAdminSelect">Rôle administrateur</label>
                        <select id="typeAdminSelect">
                            <option value="">Tous les rôles</option>
                        </select>
                    </div>
                </div>

                <button id="filterBtn">Mettre à jour l'aperçu</button>
                <div id="previewInfo" class="preview-info">Aperçu des enregistrements</div>

                <div class="actions">
                    <button id="pdfBtn" class="secondary">📄 Imprimer (PDF)</button>
                    <button id="excelBtn" class="secondary">📊 Exporter (Excel)</button>
                    <button id="directPrintBtn" class="secondary">🖨️ Impression directe</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Afficher/masquer les filtres selon le type
        document.getElementById('typeSelect').addEventListener('change', (event) => {
            const eleveFilters = document.getElementById('eleveFiltersRow');
            const adminFilters = document.getElementById('adminFiltersRow');
            if (event.target.value === 'eleves') {
                eleveFilters.style.display = 'grid';
                adminFilters.style.display = 'none';
            } else {
                eleveFilters.style.display = 'none';
                adminFilters.style.display = 'grid';
            }
        });

        // Gestion des onglets
        document.querySelectorAll('.tab-button').forEach((btn) => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;
                document.querySelectorAll('.tab-button').forEach((b) => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach((p) => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });
    </script>

    <!-- Imports des modules front-end -->
    @vite(['resources/js/app.js'])
    
    <script>
        // Attendre le chargement des modules API depuis app.js
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                // === Élève Photo ===
                if (window.setupPhotoPreview && window.submitPhotoForm) {
                    window.setupPhotoPreview({
                        inputId: 'photoInput',
                        previewContainerId: 'photoPreview',
                        removeButtonId: 'removePhotoBtn',
                    });

                    window.submitPhotoForm({
                        formId: 'eleveForm',
                        apiUrl: '/api/eleves',
                        successCallback: (data) => {
                            alert('Élève créé avec succès !');
                            document.getElementById('eleveForm').reset();
                        },
                    });
                }

                // === Parent Link ===
                if (window.setupParentAutocomplete && window.setupEleveAutocomplete && window.setupLinkParentEleve) {
                    window.setupParentAutocomplete({
                        inputId: 'parentSearch',
                        hiddenId: 'parentId',
                        cardContainerId: 'parentCard',
                        searchUrl: '/api/personnes/search',
                    });

                    window.setupEleveAutocomplete({
                        inputId: 'eleveSearch',
                        hiddenId: 'matricule',
                        cardContainerId: 'eleveCard',
                        searchUrl: '/api/eleves/search',
                    });

                    window.setupLinkParentEleve({
                        formId: 'parentForm',
                        tableBodyId: 'parentsTableBody',
                        submitButtonId: 'linkButton',
                    });
                }

                // === Print Lists ===
                if (window.setupPrintControls) {
                    window.setupPrintControls({
                        typeSelectId: 'typeSelect',
                        classeSelectId: 'classeSelect',
                        anneeSelectId: 'anneeSelect',
                        typeAdminSelectId: 'typeAdminSelect',
                        statusSelectId: 'statusSelect',
                        filterButtonId: 'filterBtn',
                        previewCountId: 'previewInfo',
                        pdfButtonId: 'pdfBtn',
                        excelButtonId: 'excelBtn',
                        directPrintButtonId: 'directPrintBtn',
                    });
                }
            }, 500);
        });
    </script>
</body>
</html>
