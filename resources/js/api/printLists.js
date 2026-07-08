const getAuthToken = () => localStorage.getItem('authToken') || '';

const fetchJson = async (url) => {
    const response = await fetch(url, {
        headers: {
            Authorization: `Bearer ${getAuthToken()}`,
            Accept: 'application/json',
        },
    });

    if (!response.ok) {
        throw new Error('Erreur lors de la récupération des données.');
    }

    return response.json();
};

const buildQueryString = (params) => new URLSearchParams(Object.entries(params).filter(([, value]) => value !== '' && value !== undefined && value !== null)).toString();

const setupPrintControls = ({ typeSelectId, classeSelectId, anneeSelectId, typeAdminSelectId, statusSelectId, filterButtonId, previewCountId, pdfButtonId, excelButtonId, directPrintButtonId }) => {
    const typeSelect = document.getElementById(typeSelectId);
    const classeSelect = document.getElementById(classeSelectId);
    const anneeSelect = document.getElementById(anneeSelectId);
    const typeAdminSelect = document.getElementById(typeAdminSelectId);
    const statusSelect = document.getElementById(statusSelectId);
    const filterButton = document.getElementById(filterButtonId);
    const previewCount = document.getElementById(previewCountId);
    const pdfButton = document.getElementById(pdfButtonId);
    const excelButton = document.getElementById(excelButtonId);
    const directPrintButton = document.getElementById(directPrintButtonId);

    if (!typeSelect || !previewCount || !pdfButton || !excelButton || !filterButton) {
        return;
    }

    const getFilters = () => ({
        type: typeSelect.value,
        classe: classeSelect?.value || '',
        annee: anneeSelect?.value || '',
        typeAdmin: typeAdminSelect?.value || '',
        statut: statusSelect?.value || '',
    });

    const updatePreview = async () => {
        const filters = getFilters();
        const url = filters.type === 'eleves'
            ? `/api/eleves?${buildQueryString({ classe: filters.classe, annee: filters.annee, actif: filters.statut, per_page: 1 })}`
            : `/api/admins?${buildQueryString({ typeAdmin: filters.typeAdmin, actif: filters.statut, per_page: 1 })}`;

        try {
            const payload = await fetchJson(url);
            const total = payload?.meta?.total ?? payload?.total ?? 0;
            previewCount.textContent = `${total} ${filters.type === 'eleves' ? 'élèves' : 'administrateurs'} seront imprimés.`;
        } catch (error) {
            console.error(error);
            previewCount.textContent = 'Impossible de calculer le nombre d’enregistrements.';
        }
    };

    const openPdf = () => {
        const filters = getFilters();
        const query = buildQueryString({ type: filters.type, classe: filters.classe, annee: filters.annee, typeAdmin: filters.typeAdmin, actif: filters.statut });
        window.open(`/api/admin/liste-pdf?${query}`, '_blank');
    };

    const downloadExcel = () => {
        const filters = getFilters();
        const query = buildQueryString({ type: filters.type, classe: filters.classe, annee: filters.annee, typeAdmin: filters.typeAdmin, actif: filters.statut });
        window.location.href = `/api/admin/liste-excel?${query}`;
    };

    const printDirectly = () => {
        const filters = getFilters();
        const query = buildQueryString({ type: filters.type, classe: filters.classe, annee: filters.annee });
        window.open(`/admin/print-preview?${query}`, '_blank');
    };

    filterButton.addEventListener('click', (event) => {
        event.preventDefault();
        updatePreview();
    });

    pdfButton.addEventListener('click', (event) => {
        event.preventDefault();
        openPdf();
    });

    excelButton.addEventListener('click', (event) => {
        event.preventDefault();
        downloadExcel();
    });

    if (directPrintButton) {
        directPrintButton.addEventListener('click', (event) => {
            event.preventDefault();
            printDirectly();
        });
    }

    updatePreview();
};

export { setupPrintControls };
