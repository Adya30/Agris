function renderOptions(elementId, data, placeholder) {
    const select = document.getElementById(elementId);
    const currentValue = select.value;
    const currentText = select.options[0] ? select.options[0].text : placeholder;

    select.innerHTML = `<option value="${currentValue}">${currentText}</option>`;

    if (Array.isArray(data)) {
        data.forEach(item => {
            if (item.id != currentValue) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                select.appendChild(option);
            }
        });
    }
}

async function loadProvinsi() {
    try {
        const response = await fetch(`${baseUrl}/provinsi`);
        const data = await response.json();
        renderOptions('provinsi', data, 'Pilih Provinsi');
    } catch (e) { console.error("Error load provinsi", e); }
}

document.getElementById('provinsi').addEventListener('change', async function () {
    const id = this.value;
    if (!id) return;
    const res = await fetch(`${baseUrl}/kabupaten/${id}`);
    const selectKab = document.getElementById('kabupaten');
    selectKab.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
    (await res.json()).forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id; opt.textContent = item.name;
        selectKab.appendChild(opt);
    });
    document.getElementById('kecamatan').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    document.getElementById('desa').innerHTML = '<option value="">-- Pilih Desa --</option>';
});

document.getElementById('kabupaten').addEventListener('change', async function () {
    const id = this.value;
    if (!id) return;
    const res = await fetch(`${baseUrl}/kecamatan/${id}`);
    const selectKec = document.getElementById('kecamatan');
    selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    (await res.json()).forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id; opt.textContent = item.name;
        selectKec.appendChild(opt);
    });
    document.getElementById('desa').innerHTML = '<option value="">-- Pilih Desa --</option>';
});

document.getElementById('kecamatan').addEventListener('change', async function () {
    const id = this.value;
    if (!id) return;
    const res = await fetch(`${baseUrl}/desa/${id}`);
    const selectDesa = document.getElementById('desa');
    selectDesa.innerHTML = '<option value="">-- Pilih Desa --</option>';
    (await res.json()).forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id; opt.textContent = item.name;
        selectDesa.appendChild(opt);
    });
});
