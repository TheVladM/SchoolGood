const getAuthToken = () => localStorage.getItem('authToken') || '';

const debounce = (fn, delay = 300) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = window.setTimeout(() => fn(...args), delay);
    };
};

const fetchJson = async (url) => {
    const response = await fetch(url, {
        headers: {
            Authorization: `Bearer ${getAuthToken()}`,
            Accept: 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur réseau lors de la requête.');
    }

    return response.json();
};

const renderDropdown = (container, items, formatter) => {
    container.innerHTML = items
        .map((item) => `<button type="button" class="autocomplete-item" data-value='${JSON.stringify(item)}'>${formatter(item)}</button>`)
        .join('');
};

const setupParentAutocomplete = ({ inputId, hiddenId, cardContainerId, searchUrl }) => {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const cardContainer = document.getElementById(cardContainerId);
    const dropdown = document.createElement('div');
    dropdown.className = 'autocomplete-list';
    let selected = null;

    if (!input || !hidden || !cardContainer) {
        return null;
    }

    input.parentNode.appendChild(dropdown);

    const updateCard = (data) => {
        selected = data;
        hidden.value = data.idPers;
        cardContainer.innerHTML = `
            <div class="card">
                <strong>${data.nom} ${data.prenom}</strong><br>
                <span>${data.mobile || 'Pas de mobile'}</span><br>
                <small>ID Alanya : ${data.alanyaID || '—'}</small>
            </div>
        `;
    };

    input.addEventListener('input', debounce(async () => {
        const query = input.value.trim();

        if (!query) {
            dropdown.innerHTML = '';
            hidden.value = '';
            cardContainer.innerHTML = '';
            selected = null;
            return;
        }

        try {
            const results = await fetchJson(`${searchUrl}?q=${encodeURIComponent(query)}`);
            if (!Array.isArray(results) || results.length === 0) {
                dropdown.innerHTML = '<p class="autocomplete-empty">Aucun résultat</p>';
                return;
            }

            renderDropdown(dropdown, results, (person) => `${person.nom} ${person.prenom} — ${person.mobile || 'sans mobile'}`);
            dropdown.querySelectorAll('button').forEach((button) => {
                button.addEventListener('click', () => {
                    const data = JSON.parse(button.dataset.value);
                    updateCard(data);
                    dropdown.innerHTML = '';
                    input.value = `${data.nom} ${data.prenom}`;
                });
            });
        } catch (error) {
            console.error(error);
            dropdown.innerHTML = '<p class="autocomplete-empty">Erreur de recherche</p>';
        }
    }));

    return {
        getSelected: () => selected,
    };
};

const setupEleveAutocomplete = ({ inputId, hiddenId, cardContainerId, searchUrl }) => {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const cardContainer = document.getElementById(cardContainerId);
    const dropdown = document.createElement('div');
    dropdown.className = 'autocomplete-list';
    let selected = null;

    if (!input || !hidden || !cardContainer) {
        return null;
    }

    input.parentNode.appendChild(dropdown);

    const updateCard = (data) => {
        selected = data;
        hidden.value = data.matricule;
        cardContainer.innerHTML = `
            <div class="card">
                <strong>${data.matricule} — ${data.nom} ${data.prenom}</strong><br>
                <span>Classe : ${data.classe || 'Non assignée'}</span>
            </div>
        `;
    };

    input.addEventListener('input', debounce(async () => {
        const query = input.value.trim();
        if (!query) {
            dropdown.innerHTML = '';
            hidden.value = '';
            cardContainer.innerHTML = '';
            selected = null;
            return;
        }

        try {
            const results = await fetchJson(`${searchUrl}?q=${encodeURIComponent(query)}`);
            if (!Array.isArray(results) || results.length === 0) {
                dropdown.innerHTML = '<p class="autocomplete-empty">Aucun résultat</p>';
                return;
            }

            renderDropdown(dropdown, results, (eleve) => `${eleve.matricule} — ${eleve.nom} ${eleve.prenom} (${eleve.classe || '—'})`);
            dropdown.querySelectorAll('button').forEach((button) => {
                button.addEventListener('click', () => {
                    const data = JSON.parse(button.dataset.value);
                    updateCard(data);
                    dropdown.innerHTML = '';
                    input.value = `${data.matricule} — ${data.nom}`;
                });
            });
        } catch (error) {
            console.error(error);
            dropdown.innerHTML = '<p class="autocomplete-empty">Erreur de recherche</p>';
        }
    }));

    return {
        getSelected: () => selected,
    };
};

const setupLinkParentEleve = ({ formId, tableBodyId, parentAutocomplete, eleveAutocomplete, submitButtonId }) => {
    const form = document.getElementById(formId);
    const tableBody = document.getElementById(tableBodyId);
    const submitButton = document.getElementById(submitButtonId);

    const refreshButtonState = () => {
        if (!form || !submitButton) {
            return;
        }

        const hasParent = !!document.getElementById('parentId')?.value;
        const hasEleve = !!document.getElementById('matricule')?.value;
        submitButton.disabled = !(hasParent && hasEleve);
    };

    const renderLinks = async () => {
        if (!tableBody) {
            return;
        }

        try {
            const payload = await fetchJson('/api/parents');
            tableBody.innerHTML = payload.data.map((link) => `
                <tr>
                    <td>${link.personne.nom} ${link.personne.prenom}</td>
                    <td>${link.eleve.nom} ${link.eleve.prenom}</td>
                    <td>${link.eleve.frequences?.[0]?.salle?.classe?.libelle || '—'}</td>
                    <td><button type="button" class="btn-delete" data-id="${link.idParent}">Délier</button></td>
                </tr>
            `).join('');

            tableBody.querySelectorAll('.btn-delete').forEach((button) => {
                button.addEventListener('click', async () => {
                    if (!confirm('Confirmer la suppression du lien parent/élève ?')) {
                        return;
                    }

                    const id = button.dataset.id;
                    try {
                        await fetch(`/api/parents/${encodeURIComponent(id)}`, {
                            method: 'DELETE',
                            headers: {
                                Authorization: `Bearer ${getAuthToken()}`,
                                Accept: 'application/json',
                            },
                        });
                        await renderLinks();
                    } catch (error) {
                        console.error(error);
                        alert('Impossible de supprimer le lien.');
                    }
                });
            });
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="4">Impossible de charger les liens.</td></tr>';
        }
    };

    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const data = {
                idPers: document.getElementById('parentId')?.value,
                matricule: document.getElementById('matricule')?.value,
            };

            try {
                const response = await fetch('/api/parents', {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${getAuthToken()}`,
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                if (!response.ok) {
                    const payload = await response.json().catch(() => null);
                    const message = payload?.message || (payload?.errors ? Object.values(payload.errors).flat().join('\n') : 'Erreur');
                    throw new Error(message);
                }

                form.reset();
                document.getElementById('parentCard').innerHTML = '';
                document.getElementById('eleveCard').innerHTML = '';
                await renderLinks();
                refreshButtonState();
            } catch (error) {
                console.error(error);
                alert(error.message || 'Impossible de créer le lien.');
            }
        });
    }

    document.querySelectorAll('input').forEach((input) => {
        input.addEventListener('input', refreshButtonState);
    });

    renderLinks();
    refreshButtonState();
};

export { setupParentAutocomplete, setupEleveAutocomplete, setupLinkParentEleve };
