document.addEventListener('DOMContentLoaded', function () {
    const baseUrl = "https://www.emsifa.com/api-wilayah-indonesia/api";

    const safeBind = (id, event, callback) => {
        const el = document.getElementById(id);
        if (el) el.addEventListener(event, callback);
    };

    const updateSelect = (id, data, label, currentValue = null) => {
        const select = document.getElementById(id);
        if (!select) return;

        select.innerHTML = `<option value="">Pilih ${label}</option>`;
        data.forEach(item => {
            const option = new Option(item.name, item.id);
            if (item.id == currentValue) option.selected = true;
            select.add(option);
        });
    };

    safeBind('provinsi', 'change', async function() {
        if(!this.value) {
            updateSelect('kabupaten', [], 'Kabupaten');
            return;
        }
        try {
            const res = await fetch(`${baseUrl}/regencies/${this.value}.json`);
            updateSelect('kabupaten', await res.json(), 'Kabupaten');
            updateSelect('kecamatan', [], 'Kecamatan');
            updateSelect('desa', [], 'Desa');
        } catch (e) { console.error(e); }
    });

    safeBind('kabupaten', 'change', async function() {
        if(!this.value) return;
        try {
            const res = await fetch(`${baseUrl}/districts/${this.value}.json`);
            updateSelect('kecamatan', await res.json(), 'Kecamatan');
            updateSelect('desa', [], 'Desa');
        } catch (e) { console.error(e); }
    });

    safeBind('kecamatan', 'change', async function() {
        if(!this.value) return;
        try {
            const res = await fetch(`${baseUrl}/villages/${this.value}.json`);
            updateSelect('desa', await res.json(), 'Desa');
        } catch (e) { console.error(e); }
    });

    window.initWilayah = async function() {
        const provSelect = document.getElementById('provinsi');
        const kabSelect = document.getElementById('kabupaten');
        const kecSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');

        if(!provSelect) return;

        try {
            const oldProvId = provSelect.getAttribute('data-old');
            const resProv = await fetch(`${baseUrl}/provinces.json`);
            updateSelect('provinsi', await resProv.json(), 'Provinsi', oldProvId);

            if (oldProvId) {
                const oldKabId = kabSelect.getAttribute('data-old');
                const resKab = await fetch(`${baseUrl}/regencies/${oldProvId}.json`);
                updateSelect('kabupaten', await resKab.json(), 'Kabupaten', oldKabId);

                if (oldKabId) {
                    const oldKecId = kecSelect.getAttribute('data-old');
                    const resKec = await fetch(`${baseUrl}/districts/${oldKabId}.json`);
                    updateSelect('kecamatan', await resKec.json(), 'Kecamatan', oldKecId);

                    if (oldKecId) {
                        const oldDesaId = desaSelect.getAttribute('data-old');
                        const resDesa = await fetch(`${baseUrl}/villages/${oldKecId}.json`);
                        updateSelect('desa', await resDesa.json(), 'Desa', oldDesaId);
                    }
                }
            }
        } catch (e) { console.error(e); }
    };
});
