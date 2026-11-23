document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = "/wilayah";
    const cache = new Map();

    async function fetchCached(url) {
        if (cache.has(url)) {
            return cache.get(url);
        }
        try {
            const res = await fetch(url);
            if (!res.ok) {
                console.warn(`Fetch ${url} failed with status ${res.status}`);
                return [];
            }
            const data = await res.json();
            if (Array.isArray(data)) {
                cache.set(url, data);
                return data;
            }
            return [];
        } catch (err) {
            console.error(`Fetch ${url} error:`, err);
            return [];
        }
    }

    const safeBind = (id, event, callback) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener(event, callback);
    };

    const updateSelect = (id, data, label, currentValue = null, loading = false) => {
        const select = document.getElementById(id);
        if (!select) return;

        if (loading) {
            select.innerHTML = `<option value="">Memuat data ${label}...</option>`;
            select.disabled = true;
            return;
        }

        select.disabled = false;
        select.innerHTML = `<option value="">Pilih ${label}</option>`;
        if (Array.isArray(data)) {
            data.forEach(item => {
                const option = new Option(item.name, item.id);
                if (currentValue !== null && String(item.id) === String(currentValue)) {
                    option.selected = true;
                }
                select.add(option);
            });
        }
    };

    // 1. Change event Provinsi: load Kabupaten bertahap
    safeBind('provinsi', 'change', async function() {
        if (!this.value) {
            updateSelect('kabupaten', [], 'Kabupaten');
            updateSelect('kecamatan', [], 'Kecamatan');
            updateSelect('desa', [], 'Desa');
            return;
        }
        updateSelect('kabupaten', [], 'Kabupaten', null, true);
        updateSelect('kecamatan', [], 'Kecamatan');
        updateSelect('desa', [], 'Desa');

        const data = await fetchCached(`${baseUrl}/kabupaten/${this.value}`);
        updateSelect('kabupaten', data, 'Kabupaten');
    });

    // 2. Change event Kabupaten: load Kecamatan bertahap
    safeBind('kabupaten', 'change', async function() {
        if (!this.value) {
            updateSelect('kecamatan', [], 'Kecamatan');
            updateSelect('desa', [], 'Desa');
            return;
        }
        updateSelect('kecamatan', [], 'Kecamatan', null, true);
        updateSelect('desa', [], 'Desa');

        const data = await fetchCached(`${baseUrl}/kecamatan/${this.value}`);
        updateSelect('kecamatan', data, 'Kecamatan');
    });

    // 3. Change event Kecamatan: load Desa bertahap
    safeBind('kecamatan', 'change', async function() {
        if (!this.value) {
            updateSelect('desa', [], 'Desa');
            return;
        }
        updateSelect('desa', [], 'Desa', null, true);

        const data = await fetchCached(`${baseUrl}/desa/${this.value}`);
        updateSelect('desa', data, 'Desa');
    });

    // Inisialisasi data wilayah bertahap saat mode edit aktif atau saat error validasi
    window.initWilayah = async function() {
        const provSelect = document.getElementById('provinsi');
        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');

        if (!provSelect) return;

        // Ambil nilai data-old (bisa dari session old() saat error submit atau dari database)
        const oldProvId = provSelect.getAttribute('data-old') || provSelect.value;
        const oldKabId = kabSelect ? (kabSelect.getAttribute('data-old') || kabSelect.value) : null;
        const oldKecId = kecSelect ? (kecSelect.getAttribute('data-old') || kecSelect.value) : null;
        const oldDesaId = desaSelect ? (desaSelect.getAttribute('data-old') || desaSelect.value) : null;

        // Tahap 1: Load Provinsi
        updateSelect('provinsi', [], 'Provinsi', null, true);
        const provinces = await fetchCached(`${baseUrl}/provinsi`);
        updateSelect('provinsi', provinces, 'Provinsi', oldProvId);

        // Tahap 2: Load Kabupaten jika provinsi tersedia
        if (oldProvId) {
            updateSelect('kabupaten', [], 'Kabupaten', null, true);
            const regencies = await fetchCached(`${baseUrl}/kabupaten/${oldProvId}`);
            updateSelect('kabupaten', regencies, 'Kabupaten', oldKabId);

            // Tahap 3: Load Kecamatan jika kabupaten tersedia
            if (oldKabId) {
                updateSelect('kecamatan', [], 'Kecamatan', null, true);
                const districts = await fetchCached(`${baseUrl}/kecamatan/${oldKabId}`);
                updateSelect('kecamatan', districts, 'Kecamatan', oldKecId);

                // Tahap 4: Load Desa jika kecamatan tersedia
                if (oldKecId) {
                    updateSelect('desa', [], 'Desa', null, true);
                    const villages = await fetchCached(`${baseUrl}/desa/${oldKecId}`);
                    updateSelect('desa', villages, 'Desa', oldDesaId);
                }
            }
        }
    };
});
