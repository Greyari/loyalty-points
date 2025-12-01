document.addEventListener("DOMContentLoaded", () => {
    // Saat halaman selesai dimuat, langsung load tabel pertama
    loadTable();

    // Event untuk pencarian realtime (setiap user mengetik)
    document.getElementById("search").addEventListener("keyup", function () {
        loadTable(this.value); // Kirim nilai pencarian ke loadTable()
    });

    // Delegasi event untuk klik pagination
    document.addEventListener("click", function (e) {
        // Mengecek apakah yang diklik adalah link pagination
        if (e.target.closest(".pagination a")) {
            e.preventDefault(); // Mencegah default pindah halaman
            loadTable(null, e.target.getAttribute("href")); // load tabel berdasarkan URL pagination
        }
    });
});

function loadTable(search = "", url = "/api/customers") {

    // Fetch ke API customers, kirim parameter search
    fetch(url + "?search=" + search)
        .then(res => res.json())
        .then(res => {

            // Jika API sukses (success = true)
            if (res.success) {
                // Isi elemen #customerTable dengan hasil render tabel
                document.getElementById("customerTable").innerHTML =
                    renderTable(res.data);
            }
        });
}


function renderTable(paginationData) {
    return `
    <table class="table">
        <thead>
            <tr><th>Nama</th><th>No HP</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        ${paginationData.data
            .map(
                (c) => `
            <tr>
                <td>${c.name}</td>
                <td>${c.phone}</td>
                <td>
                    <button class="editBtn" data-id="${c.id}">Edit</button>
                    <button class="deleteBtn" data-id="${c.id}">Delete</button>
                </td>
            </tr>
        `
            )
            .join("")}
        </tbody>
    </table>

    ${paginationData.links}
    `;
}

document.addEventListener("submit", function (e) {
    // Jika form yang disubmit adalah form tambah customer
    if (e.target.id === "formAddCustomer") {
        e.preventDefault(); // Mencegah reload halaman

        let formData = new FormData(e.target); // Ambil data form

        // Kirim ke API
        fetch("/api/customers", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert(res.message);
                    loadTable(); // Refresh tabel
                    document.getElementById("modalAdd").style.display = "none"; // Tutup modal
                } else {
                    alert(res.message); // Pesan error
                }
            });
    }
});

document.addEventListener("click", function (e) {
    // Jika tombol edit diklik
    if (e.target.classList.contains("editBtn")) {
        let id = e.target.dataset.id; // Ambil ID customer

        // Fetch data customer by id
        fetch(`/api/customers/${id}`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    let c = res.data.customer;

                    // Isi modal edit
                    document.getElementById("modalEdit").innerHTML = `
                        <form id="formEditCustomer" data-id="${c.id}">
                            <input name="name" value="${c.name}">
                            <input name="phone" value="${c.phone}">
                            <button type="submit">Simpan</button>
                        </form>
                    `;
                }
            });
    }
});


document.addEventListener("submit", function (e) {
    if (e.target.id === "formEditCustomer") {
        e.preventDefault();

        let id = e.target.dataset.id;      // Ambil ID customer
        let formData = new FormData(e.target);

        fetch(`/api/customers/${id}`, {
            method: "POST", // Laravel butuh POST
            headers: { "X-HTTP-Method-Override": "PUT" }, // override jadi PUT
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert(res.message);
                    loadTable(); // Reload tabel
                } else {
                    alert(res.message);
                }
            });
    }
});


document.addEventListener("click", function (e) {
    if (e.target.classList.contains("deleteBtn")) {

        // Konfirmasi delete
        if (!confirm("Yakin hapus?")) return;

        let id = e.target.dataset.id;

        fetch(`/api/customers/${id}`, {
            method: "DELETE" // Request DELETE
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert(res.message);
                    loadTable(); // Refresh tabel
                } else {
                    alert(res.message);
                }
            });
    }
});
