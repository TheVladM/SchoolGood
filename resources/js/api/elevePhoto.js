const getAuthToken = () => localStorage.getItem('authToken') || '';

const createInitialsAvatar = (name) => {
    const initials = name
        .split(' ')
        .map((part) => part.trim().charAt(0).toUpperCase())
        .join('')
        .slice(0, 2);

    const background = '#6b7280';
    const color = '#ffffff';

    return `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:${background};color:${color};border-radius:50%;font-weight:700;font-size:1rem;">${initials}</div>`;
};

const setupPhotoPreview = ({ inputId, previewContainerId, removeButtonId }) => {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewContainerId);
    const removeButton = document.getElementById(removeButtonId);

    if (!input || !previewContainer) {
        return;
    }

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();

        reader.onload = (event) => {
            previewContainer.innerHTML = `<img src="${event.target.result}" alt="Prévisualisation" style="width:120px;height:120px;object-fit:cover;border-radius:12px;">`;
        };

        reader.readAsDataURL(file);
    });

    if (removeButton) {
        removeButton.addEventListener('click', () => {
            if (!confirm('Supprimer définitivement la photo de cet enregistrement ?')) {
                return;
            }

            const matricule = input.dataset.matricule || input.dataset.id;
            if (!matricule) {
                return;
            }

            fetch(`/api/eleves/${encodeURIComponent(matricule)}/photo`, {
                method: 'DELETE',
                headers: {
                    Authorization: `Bearer ${getAuthToken()}`,
                    'Accept': 'application/json',
                },
            })
                .then((res) => {
                    if (!res.ok) {
                        throw new Error('Impossible de supprimer la photo.');
                    }
                    return res.json();
                })
                .then(() => {
                    previewContainer.innerHTML = '<div class="avatar-placeholder">Aucun visuel</div>';
                    input.value = '';
                })
                .catch((error) => {
                    console.error(error);
                    alert(error.message || 'Erreur lors de la suppression.');
                });
        });
    }
};

const submitPhotoForm = async ({ formId, apiUrl, successCallback, errorCallback }) => {
    const form = document.getElementById(formId);

    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const token = getAuthToken();
        const formData = new FormData(form);
        const method = form.dataset.method || 'POST';

        try {
            const response = await fetch(apiUrl, {
                method,
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                body: formData,
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => null);
                const message = payload?.message || (payload?.errors ? Object.values(payload.errors).flat().join('\n') : 'Erreur de validation');
                throw new Error(message);
            }

            const data = await response.json();

            if (typeof successCallback === 'function') {
                successCallback(data);
            }
        } catch (error) {
            console.error(error);
            if (typeof errorCallback === 'function') {
                errorCallback(error);
            } else {
                alert(error.message || 'Une erreur est survenue.');
            }
        }
    });
};

export { setupPhotoPreview, submitPhotoForm, createInitialsAvatar };
