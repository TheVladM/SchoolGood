# Implémentation complète : 3 Fonctionnalités SchoolGood

## 📋 Vue d'ensemble

Ce document couvre l'implémentation de trois fonctionnalités majeures pour votre projet Laravel 10/11 + Vanilla JS :

1. **Upload et affichage de photos** — Gestion des photos de profil avec prévisualisation
2. **Liaison parent-élève par nom** — Autocomplétion et recherche de personnes et élèves
3. **Impression des listes** — Export PDF/Excel avec filtres et aperçu

---

## 🔧 Installation et configuration

### Prérequis

```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
php artisan storage:link
```

### Variables d'environnement (.env)

```env
SCHOOL_LOGO_PATH=/images/logo.png
FILESYSTEM_DISK=public
```

---

## 1️⃣ UPLOAD ET AFFICHAGE DE PHOTOS

### 1.1 Migrations

**Fichiers créés :**
- `database/migrations/2026_06_13_000001_add_photourl_to_personnes_and_parent_unique_index.php`
- `database/migrations/2026_06_13_000002_add_photourl_to_admins_table.php`

**Ce qui change :**
- Table `eleves` : `photoURL` est déjà présent et nullable ✅
- Table `personnes` : ajout de `photoURL` nullable
- Table `admins` : ajout de `photoURL` nullable
- Table `parents` : ajout d'index unique sur `(idPers, matricule)`

### 1.2 Trait partagé

**Fichier :** `app/Http/Traits/ReusablePhotoUpload.php`

Contient trois méthodes réutilisables dans tous les contrôleurs :

```php
uploadPhoto(Request $request, $inputName, Model $model, $attribute, $folder, $disk)
deleteStoredPhoto(Model $model, $attribute, $disk)
extractRelativeStoragePath($publicUrl, $disk)
```

**Utilisation :**
```php
use App\Http\Traits\ReusablePhotoUpload;

class EleveController extends Controller {
    use ReusablePhotoUpload;
    
    public function store(StoreEleveRequest $request) {
        $photoURL = $this->uploadPhoto($request, 'photo', new Eleve(), 'photoURL', 'photos/eleves');
        // Stockage à : storage/app/public/photos/eleves/{nom_unique}
        // URL publique : /storage/photos/eleves/{nom_unique}
    }
}
```

### 1.3 Validation des formulaires

**Fichiers :**
- `app/Http/Requests/StoreEleveRequest.php`
- `app/Http/Requests/UpdateEleveRequest.php`
- `app/Http/Requests/StoreAdminRequest.php`
- `app/Http/Requests/UpdateAdminRequest.php`

Validation requise pour les photos :
```php
'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
```

### 1.4 Contrôleurs mis à jour

**EleveController** (app/Http/Controllers/Pedagogie/EleveController.php)

```php
public function store(StoreEleveRequest $request) {
    $data = $request->validated();
    $photoURL = $this->uploadPhoto($request, 'photo', new Eleve(), 'photoURL', 'photos/eleves');
    
    // Ajouter l'URL au tableau de données
    if ($photoURL !== null) {
        $data['photoURL'] = $photoURL;
    }
    
    $eleve = Eleve::create($data);
    return response()->json($eleve, 201);
}

public function update(UpdateEleveRequest $request, Eleve $eleve) {
    // Même logique : supprimer l'ancienne avant d'uploader la nouvelle
    $photoURL = $this->uploadPhoto($request, 'photo', $eleve, 'photoURL', 'photos/eleves');
    // ...
}

public function deletePhoto(Eleve $eleve) {
    // Route : DELETE /api/eleves/{matricule}/photo
    $this->deleteStoredPhoto($eleve, 'photoURL', 'public');
    $eleve->update(['photoURL' => null]);
    return response()->json(['message' => 'Photo de l\'élève supprimée.']);
}

public function search(Request $request) {
    // Route : GET /api/eleves/search?q=matricule_ou_nom
    // Retourne liste des élèves avec photoURL et classe
}
```

**AdminController** : même logique pour `photos/admins`

**PersonneController** : même logique pour `photos/personnes`

### 1.5 Routes API

```php
Route::get('eleves/search', [EleveController::class, 'search'])->name('eleves.search');
Route::apiResource('eleves', EleveController::class);
Route::delete('eleves/{eleve}/photo', [EleveController::class, 'deletePhoto'])->name('eleves.photo.delete');

Route::get('personnes/search', [PersonneController::class, 'search'])->name('personnes.search');
Route::apiResource('personnes', PersonneController::class);
```

### 1.6 Module Front-end : elevePhoto.js

**Fichier :** `resources/js/api/elevePhoto.js`

**Fonctions principales :**

```javascript
setupPhotoPreview({ inputId, previewContainerId, removeButtonId })
// Configure la prévisualisation d'image + bouton suppression

submitPhotoForm({ formId, apiUrl, successCallback, errorCallback })
// Envoie FormData en multipart/form-data avec fetch + Bearer token
```

**Exemple d'utilisation :**

```javascript
import { setupPhotoPreview, submitPhotoForm } from '/resources/js/api/elevePhoto.js';

// Prévisualisation et suppression
setupPhotoPreview({
    inputId: 'photoInput',
    previewContainerId: 'photoPreview',
    removeButtonId: 'removePhotoBtn',
});

// Envoi du formulaire
submitPhotoForm({
    formId: 'eleveForm',
    apiUrl: '/api/eleves',
    successCallback: (data) => alert('Élève créé!'),
});
```

**HTML requis :**

```html
<form id="eleveForm" method="POST">
    <input type="file" id="photoInput" name="photo" accept="image/*">
    <div id="photoPreview"></div>
    <button type="button" id="removePhotoBtn">Supprimer la photo</button>
    <button type="submit">Enregistrer</button>
</form>
```

---

## 2️⃣ LIAISON PARENT-ÉLÈVE PAR NOM

### 2.1 Migrations & Validations

**Fichier :** `database/migrations/2026_06_13_000001_...` (déjà créée)

Crée un index unique `(idPers, matricule)` pour éviter les doublons.

**Form Requests :**
- `app/Http/Requests/StoreParentRequest.php`
- `app/Http/Requests/UpdateParentRequest.php`

Valide :
- `idPers` → existence dans `personnes`
- `matricule` → existence dans `eleves`
- Unicité du duo `(idPers, matricule)`

### 2.2 Modèles

**Personne.php** : ajout du scope

```php
public function scopeParents($query) {
    return $query->where('typePersonne', '2');
}

public function parents(): HasMany {
    return $this->hasMany(ParentEleve::class, 'idPers', 'idPers');
}
```

**ParentEleve.php** (inchangé, relations en place)

### 2.3 PersonneController

Nouvelle méthode `search()` :

```php
public function search(Request $request) {
    // GET /api/personnes/search?q=Dupont&type=parent
    $q = trim($request->query('q', ''));
    $type = $request->query('type', 'parent');
    
    if ($q === '') return response()->json([]);
    
    $typePersonne = $type === 'parent' ? '2' : $type;
    
    $personnes = Personne::where('typePersonne', $typePersonne)
        ->where(function ($query) use ($q) {
            $query->where('nom', 'like', "%{$q}%")
                  ->orWhere('prenom', 'like', "%{$q}%");
        })
        ->limit(10)
        ->get(['idPers', 'nom', 'prenom', 'mobile', 'alanyaID']);
    
    return response()->json($personnes);
}
```

### 2.4 EleveController

Nouvelle méthode `search()` :

```php
public function search(Request $request) {
    // GET /api/eleves/search?q=matricule_ou_nom
    $q = trim($request->query('q', ''));
    
    if ($q === '') return response()->json([]);
    
    $eleves = Eleve::with(['frequentes.salle.classe'])
        ->where(function ($query) use ($q) {
            $query->where('matricule', $q)
                  ->orWhere('nom', 'like', "%{$q}%")
                  ->orWhere('prenom', 'like', "%{$q}%");
        })
        ->limit(10)
        ->get()
        ->map(function (Eleve $eleve) {
            return [
                'matricule' => $eleve->matricule,
                'nom' => $eleve->nom,
                'prenom' => $eleve->prenom,
                'photoURL' => $eleve->photoURL,
                'classe' => optional($eleve->frequentes->first()?->salle?->classe)->libelle,
            ];
        });
    
    return response()->json($eleves);
}
```

### 2.5 ParentsController

Utilise les Form Requests pour validation :

```php
public function store(StoreParentRequest $request) {
    $data = $request->validated();
    $parent = ParentEleve::create($data);
    $parent->load(['personne', 'eleve', 'admin']);
    return response()->json($parent, 201);
}
```

### 2.6 Routes API

```php
Route::get('personnes/search', [PersonneController::class, 'search'])->name('personnes.search');
Route::get('eleves/search', [EleveController::class, 'search'])->name('eleves.search');
Route::apiResource('parents', ParentsController::class);
```

### 2.7 Module Front-end : parentLink.js

**Fichier :** `resources/js/api/parentLink.js`

**Fonctions principales :**

```javascript
setupParentAutocomplete({ inputId, hiddenId, cardContainerId, searchUrl })
// Autocomplétion parent avec debounce 300ms

setupEleveAutocomplete({ inputId, hiddenId, cardContainerId, searchUrl })
// Autocomplétion élève avec debounce 300ms

setupLinkParentEleve({ formId, tableBodyId, submitButtonId })
// Gère le formulaire de liaison et affichage du tableau
```

**Exemple d'utilisation :**

```javascript
import { setupParentAutocomplete, setupEleveAutocomplete, setupLinkParentEleve } from '/resources/js/api/parentLink.js';

// Autocomplétion parent
setupParentAutocomplete({
    inputId: 'parentSearch',
    hiddenId: 'parentId',
    cardContainerId: 'parentCard',
    searchUrl: '/api/personnes/search',
});

// Autocomplétion élève
setupEleveAutocomplete({
    inputId: 'eleveSearch',
    hiddenId: 'matricule',
    cardContainerId: 'eleveCard',
    searchUrl: '/api/eleves/search',
});

// Liaison
setupLinkParentEleve({
    formId: 'parentForm',
    tableBodyId: 'parentsTableBody',
    submitButtonId: 'linkButton',
});
```

**HTML requis :**

```html
<form id="parentForm">
    <input type="text" id="parentSearch" placeholder="Nom du parent">
    <input type="hidden" id="parentId">
    <div id="parentCard"></div>
    
    <input type="text" id="eleveSearch" placeholder="Matricule de l'élève">
    <input type="hidden" id="matricule">
    <div id="eleveCard"></div>
    
    <button type="submit" id="linkButton" disabled>Lier</button>
</form>

<table>
    <tbody id="parentsTableBody"></tbody>
</table>
```

---

## 3️⃣ IMPRESSION DES LISTES

### 3.1 Exports (Excel & PDF)

**Fichiers créés :**
- `app/Exports/AdminsExport.php` — utilise maatwebsite/excel
- `app/Exports/ElevesExport.php`

```php
class AdminsExport implements FromCollection, WithHeadings {
    public function __construct(?string $typeAdmin = null, ?string $actif = null) { }
    public function collection(): Collection { }
    public function headings(): array { }
}
```

### 3.2 Vues Blade (PDF)

**Fichiers créés :**
- `resources/views/admin/liste-admins.blade.php`
- `resources/views/admin/liste-eleves.blade.php`

**Caractéristiques :**
- En-tête avec logo et titre
- Tableau stylisé avec alternance de couleurs
- Photos 40px (admins) ou 30px (élèves) avec avatars CSS si manquante
- Pied de page avec numérotation et signature
- Formats : A4 portrait (admins) et paysage (élèves)

### 3.3 AdminController : nouvelles routes

```php
public function listePdf(Request $request) {
    // GET /api/admin/liste-pdf?type=admins|eleves&classe=...&annee=...&typeAdmin=...&actif=...
    // Retourne un PDF généré avec barryvdh/laravel-dompdf
}

public function listeExcel(Request $request) {
    // GET /api/admin/liste-excel?type=admins|eleves&classe=...&annee=...
    // Retourne un fichier .xlsx
}
```

### 3.4 Routes API

```php
Route::get('admin/liste-pdf', [AdminController::class, 'listePdf'])->name('admin.liste-pdf');
Route::get('admin/liste-excel', [AdminController::class, 'listeExcel'])->name('admin.liste-excel');
```

### 3.5 Module Front-end : printLists.js

**Fichier :** `resources/js/api/printLists.js`

**Fonction principale :**

```javascript
setupPrintControls({
    typeSelectId,           // Sélecteur type (admins/élèves)
    classeSelectId,         // Sélecteur classe (élèves uniquement)
    anneeSelectId,          // Sélecteur année (élèves uniquement)
    typeAdminSelectId,      // Sélecteur rôle (admins uniquement)
    statusSelectId,         // Sélecteur statut (actif/inactif)
    filterButtonId,         // Bouton "Mettre à jour l'aperçu"
    previewCountId,         // Zone affichage du compte
    pdfButtonId,            // Bouton PDF
    excelButtonId,          // Bouton Excel
    directPrintButtonId     // Bouton impression directe
})
```

**Exemple d'utilisation :**

```javascript
import { setupPrintControls } from '/resources/js/api/printLists.js';

setupPrintControls({
    typeSelectId: 'typeSelect',
    classeSelectId: 'classeSelect',
    anneeSelectId: 'anneeSelect',
    typeAdminSelectId: 'typeAdminSelect',
    statusSelectId: 'statusSelect',
    filterButtonId: 'filterBtn',
    previewCountId: 'previewInfo',
    pdfButtonId: 'pdfBtn',
    excelButtonId: 'excelBtn',
});
```

**HTML requis :**

```html
<div>
    <label>Type de liste</label>
    <select id="typeSelect">
        <option value="admins">Administrateurs</option>
        <option value="eleves">Élèves</option>
    </select>

    <label>Classe</label>
    <select id="classeSelect"></select>

    <label>Année</label>
    <select id="anneeSelect"></select>

    <label>Rôle administrateur</label>
    <select id="typeAdminSelect"></select>

    <label>Statut</label>
    <select id="statusSelect">
        <option value="">Tous</option>
        <option value="1">Actif</option>
        <option value="0">Inactif</option>
    </select>

    <button id="filterBtn">Aperçu</button>
    <div id="previewInfo">Aperçu...</div>

    <button id="pdfBtn">📄 PDF</button>
    <button id="excelBtn">📊 Excel</button>
    <button id="directPrintBtn">🖨️ Impression</button>
</div>
```

---

## 📄 Page de démonstration intégrée

**Fichier :** `resources/views/integration-demo.blade.php`

Page complète avec :
- 3 onglets (Élèves, Parents, Impression)
- Tous les formulaires et tableaux
- CSS vanilla complet
- Imports des 3 modules JS

**Route web :**
```php
Route::get('/integration-demo', function () {
    return view('integration-demo');
});
```

---

## 🚀 Étapes de déploiement

1. **Exécuter les migrations :**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

2. **Tester les endpoints API :**
   ```bash
   curl -H "Authorization: Bearer {TOKEN}" http://127.0.0.1:8000/api/eleves/search?q=test
   ```

3. **Afficher la démo intégrée :**
   ```
   http://127.0.0.1:8000/integration-demo
   ```

4. **Configurer le logo (optionnel) :**
   ```env
   SCHOOL_LOGO_PATH=/images/school-logo.png
   ```

---

## 📝 Résumé des fichiers créés

### Migrations
- `2026_06_13_000001_add_photourl_to_personnes_and_parent_unique_index.php`
- `2026_06_13_000002_add_photourl_to_admins_table.php`

### Backend
- `app/Http/Traits/ReusablePhotoUpload.php`
- `app/Http/Requests/StoreEleveRequest.php`, `UpdateEleveRequest.php`
- `app/Http/Requests/StoreAdminRequest.php`, `UpdateAdminRequest.php`
- `app/Http/Requests/StoreParentRequest.php`, `UpdateParentRequest.php`
- `app/Exports/AdminsExport.php`, `ElevesExport.php`

### Contrôleurs (modifiés)
- `app/Http/Controllers/Pedagogie/EleveController.php`
- `app/Http/Controllers/Pedagogie/PersonneController.php`
- `app/Http/Controllers/Administration/AdminController.php`
- `app/Http/Controllers/Administration/ParentsController.php`

### Vues
- `resources/views/admin/liste-admins.blade.php`
- `resources/views/admin/liste-eleves.blade.php`
- `resources/views/integration-demo.blade.php`

### Front-end JavaScript
- `resources/js/api/elevePhoto.js`
- `resources/js/api/parentLink.js`
- `resources/js/api/printLists.js`

### Routes API (ajoutées)
- `GET /api/eleves/search`
- `DELETE /api/eleves/{matricule}/photo`
- `GET /api/personnes/search`
- `GET /api/admin/liste-pdf`
- `GET /api/admin/liste-excel`

---

## ✅ Conformité aux contraintes

✅ Laravel 10/11, PHP 8.1+  
✅ Sanctum pour authentification (Bearer token)  
✅ Storage disk 'public' pour uploads  
✅ HTML/JS/CSS vanilla uniquement  
✅ JSON API responses  
✅ Gestion d'erreurs 422/404/500  
✅ Commentaires en français  
✅ Form Requests pour validation  
✅ Traits réutilisables  

---

## 📞 Support

Pour des questions sur l'implémentation, consultez :
- Documentation Laravel officielle : https://laravel.com
- barryvdh/laravel-dompdf : https://github.com/barryvdh/laravel-dompdf
- maatwebsite/excel : https://docs.laravel-excel.com
