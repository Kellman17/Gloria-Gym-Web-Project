document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.sidebar a');
    const sections = document.querySelectorAll('.dashboard-section');

    // Ambil flashdata dari PHP yang disisipkan di halaman
    const activeSectionId = flashdataSection || localStorage.getItem('activeSection');

    // Fungsi untuk menyembunyikan semua section
    const hideAllSections = () => {
        sections.forEach(section => section.classList.remove('active'));
    };

    // Tampilkan section yang sesuai
    if (activeSectionId) {
        hideAllSections();
        const targetSection = document.getElementById(activeSectionId);
        if (targetSection) {
            targetSection.classList.add('active');
            localStorage.setItem('activeSection', activeSectionId); // Simpan ke localStorage
        }
    } else if (sections.length > 0) {
        sections[0].classList.add('active'); // Default ke section pertama
    }

    // Tambahkan event listener untuk navigasi sidebar
    navLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            if (link.getAttribute('href') === '/') return; // Abaikan logout link

            event.preventDefault();
            const sectionId = link.getAttribute('data-section');

            hideAllSections();
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
                localStorage.setItem('activeSection', sectionId); // Update localStorage
            }
        });
    });
});

//pagination tabel member
$(document).ready(function () {
    var table = $('#members-table').DataTable({
        pageLength: 5, // Menentukan jumlah row per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah row
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "", // Hapus label default search
            searchPlaceholder: "Cari Nama Member...", // Tambahkan placeholder di input search bawaan
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });
    // Search berdasarkan nama member
    $('#search-member').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
});

//pagination tabel bulanan gym
$(document).ready(function () {
    var table = $('#bulanangym-table').DataTable({
        pageLength: 5, // Menentukan jumlah row per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah row
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "", // Hapus label default search
            searchPlaceholder: "Cari Nama Member...", // Tambahkan placeholder di input search bawaan
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // **Fungsi untuk menghitung total pendapatan berdasarkan semua filter yang diterapkan**
    function calculateTotalRevenue() {
        var totalRevenue = 0;

        // Ambil data dari DataTable yang sudah difilter
        var filteredData = table.rows({ search: 'applied' }).data(); // Ambil semua baris yang sesuai filter

        filteredData.each(function (rowData) {
            var price = rowData[5]; // Ambil harga dari kolom ke-5 (Harga)

            // Bersihkan format harga (hapus 'Rp' dan titik), lalu ubah ke angka
            totalRevenue += parseInt(price.replace('Rp', '').replace(/\./g, '').trim());
        });

        // **Menampilkan total pendapatan**
        $('#total-revenuegym').text('Total Pendapatan: Rp' + totalRevenue.toLocaleString());
    }

    // **Trigger perhitungan revenue setelah setiap perubahan tabel**
    table.on('draw', function () {
        calculateTotalRevenue(); // Pastikan revenue diperbarui saat tabel berubah
    });

    // **Filter Berdasarkan Pakai PT**
    $('#filter-pakai-pt').on('change', function () {
        var selectedValue = $(this).val();
        table.column(6).search(selectedValue ? '^' + selectedValue + '$' : '', true, false).draw();
    });

    // **Validasi agar bulan mulai <= bulan berakhir**
    function validateDateFilters() {
        var startMonth = $('#filter-tglmulai').val();
        var endMonth = $('#filter-tglberakhir').val();

        if (startMonth && endMonth) {
            var startDate = new Date(startMonth + "-01");
            var endDate = new Date(endMonth + "-01");

            if (startDate > endDate) {
                alert("Bulan Mulai tidak boleh lebih dari Bulan Berakhir!");
                return false;
            }
        }
        return true;
    }

    // **Filter berdasarkan Bulan Mulai**
    $('#filter-tglmulai').on('change', function () {
        if (!validateDateFilters()) {
            $('#filter-tglmulai').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(3).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Filter berdasarkan Bulan Berakhir**
    $('#filter-tglberakhir').on('change', function () {
        if (!validateDateFilters()) {
            $('#filter-tglberakhir').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(4).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Filter berdasarkan Status**
    var statusSet = new Set();
    table.rows().every(function () {
        var status = this.data()[8]; // Assuming the status is in the 9th column (index 8)
        if (status) {
            statusSet.add(status.trim()); // Add unique statuses to the set
        }
    });

    // Populate the dropdown with unique status values
    statusSet.forEach(function (status) {
        $('#filter-status').append(
            $('<option></option>').val(status).text(status)
        );
    });

    // **Filter Berdasarkan Status**
    $('#filter-status').on('change', function () {
        var selectedValue = $(this).val();
        table.column(8).search(selectedValue ? '^' + selectedValue + '$' : '', true, false).draw();
    });

    // **Filter berdasarkan Nama Member**
    $('#search-bulanangym').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

});


//pagination tabel bulanan class
$(document).ready(function () {
    var table = $('#bulananclass-table').DataTable({
        pageLength: 5, // Menentukan jumlah row per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah row
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "", // Hapus label default search
            searchPlaceholder: "Cari Nama Member...", // Tambahkan placeholder di input search bawaan
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // **🔹 Fungsi untuk menghitung total pendapatan dari data yang sudah difilter**
    function calculateTotalRevenue() {
        var totalRevenue = 0;

        // Ambil semua data yang sudah difilter (bukan hanya yang terlihat)
        var filteredData = table.rows({ search: 'applied' }).data();

        filteredData.each(function (rowData) {
            var price = rowData[5]; // Ambil harga dari kolom ke-5 (Harga)

            // Bersihkan format harga (hapus 'Rp' dan titik), lalu ubah ke angka
            totalRevenue += parseInt(price.replace('Rp', '').replace(/\./g, '').trim());
        });

        // **Menampilkan total pendapatan**
        $('#total-revenuebulanan').text('Total Pendapatan: Rp' + totalRevenue.toLocaleString());
    }

    // **Trigger perhitungan revenue setelah setiap perubahan tabel**
    table.on('draw', function () {
        calculateTotalRevenue(); // Update total pendapatan
    });

    // **Filter Berdasarkan Status**
    var statusSet = new Set();
    table.rows().every(function () {
        var status = this.data()[7]; // Assuming the status is in the 8th column (index 7)
        if (status) {
            statusSet.add(status.trim()); // Add unique statuses to the set
        }
    });

    // Populate the dropdown with unique status values
    statusSet.forEach(function (status) {
        $('#filter-statusclass').append(
            $('<option></option>').val(status).text(status)
        );
    });

    // **Filter Berdasarkan Status**
    $('#filter-statusclass').on('change', function () {
        var selectedValue = $(this).val();
        table.column(7).search(selectedValue ? '^' + selectedValue + '$' : '', true, false).draw();
    });

    // **Validasi agar bulan mulai <= bulan berakhir**
    function validateDateFiltersclass() {
        var startMonth = $('#filter-tglmulaiclass').val();
        var endMonth = $('#filter-tglberakhirclass').val();

        if (startMonth && endMonth) {
            var startDate = new Date(startMonth + "-01");
            var endDate = new Date(endMonth + "-01");

            if (startDate > endDate) {
                alert("Bulan Mulai tidak boleh lebih dari Bulan Berakhir!");
                return false;
            }
        }
        return true;
    }

    // **Filter berdasarkan Bulan Mulai**
    $('#filter-tglmulaiclass').on('change', function () {
        if (!validateDateFiltersclass()) {
            $('#filter-tglmulaiclass').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(3).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Filter berdasarkan Bulan Berakhir**
    $('#filter-tglberakhirclass').on('change', function () {
        if (!validateDateFiltersclass()) {
            $('#filter-tglberakhirclass').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(4).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Search berdasarkan nama member**
    $('#search-bulananclass').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

});

//pagination tabel harian gym
$(document).ready(function () {
    var table = $('#harian-table').DataTable({
        pageLength: 5, // Menentukan jumlah row per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah row
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "", // Hapus label default search
            searchPlaceholder: "Cari Nama Member...", // Tambahkan placeholder di input search bawaan
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // **🔹 Fungsi untuk menghitung total pendapatan dari data yang sudah difilter**
    function calculateTotalRevenue() {
        var totalRevenue = 0;

        // Ambil semua data yang sudah difilter (bukan hanya yang terlihat)
        var filteredData = table.rows({ search: 'applied' }).data();

        filteredData.each(function (rowData) {
            var price = rowData[5]; // Ambil harga dari kolom ke-5 (Harga)

            // Bersihkan format harga (hapus 'Rp' dan titik), lalu ubah ke angka
            totalRevenue += parseInt(price.replace('Rp', '').replace(/\./g, '').trim());
        });

        // **Menampilkan total pendapatan**
        $('#total-revenue').text('Total Pendapatan: Rp' + totalRevenue.toLocaleString());
    }

    // **Trigger perhitungan revenue setelah setiap perubahan tabel**
    table.on('draw', function () {
        calculateTotalRevenue(); // Update total pendapatan
    });

    var statusSet = new Set();
    table.rows().every(function () {
        var status = this.data()[7]; // Assuming the status is in the 8th column (index 7)
        if (status) {
            statusSet.add(status.trim()); // Add unique statuses to the set
        }
    });

    // Populate the dropdown with unique status values
    statusSet.forEach(function (status) {
        $('#filter-statusharian').append(
            $('<option></option>').val(status).text(status)
        );
    });

    // **Filter Berdasarkan Status**
    $('#filter-statusharian').on('change', function () {
        var selectedValue = $(this).val();
        table.column(7).search(selectedValue ? '^' + selectedValue + '$' : '', true, false).draw();
    });

    // **Validasi agar bulan mulai <= bulan berakhir**
    function validateDateFiltersharian() {
        var startMonth = $('#filter-tglmulaiharian').val();
        var endMonth = $('#filter-tglberakhirharian').val();

        if (startMonth && endMonth) {
            var startDate = new Date(startMonth + "-01");
            var endDate = new Date(endMonth + "-01");

            if (startDate > endDate) {
                alert("Bulan Mulai tidak boleh lebih dari Bulan Berakhir!");
                return false;
            }
        }
        return true;
    }

    // **Filter berdasarkan Bulan Mulai**
    $('#filter-tglmulaiharian').on('change', function () {
        if (!validateDateFiltersharian()) {
            $('#filter-tglmulaiharian').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(3).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Filter berdasarkan Bulan Berakhir**
    $('#filter-tglberakhirharian').on('change', function () {
        if (!validateDateFiltersharian()) {
            $('#filter-tglberakhirharian').val("");
            return;
        }

        var selectedMonth = $(this).val();
        table.column(4).search(selectedMonth ? '^' + selectedMonth : '', true, false).draw();
    });

    // **Search berdasarkan nama member**
    $('#search-harian').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

});


//pagination tabel pt
$(document).ready(function () {
    var table = $('#pt-table').DataTable({
        pageLength: 5,
        lengthChange: false,
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "",
            searchPlaceholder: "Cari Nama PT...",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Ambil nilai unik dari kolom Spesialisasi
    var spesialisasiSet = new Set();
    table.column(5).data().each(function (value) {
        spesialisasiSet.add(value.trim());
    });

    // Tambahkan nilai unik ke dropdown
    spesialisasiSet.forEach(function (spesialisasi) {
        $('#filter-spesialisasi').append(
            $('<option></option>').val(spesialisasi).text(spesialisasi)
        );
    });

    // Filter berdasarkan spesialisasi
    $('#filter-spesialisasi').on('change', function () {
        var selectedValue = $(this).val();
        if (selectedValue) {
            table.column(5).search('^' + selectedValue + '$', true, false).draw();
        } else {
            table.column(5).search('').draw();
        }
    });

    // Filter berdasarkan Harga Minimal
    $('#filter-harga-min').on('change', function () {
        var minHarga = parseFloat($(this).val().replace(/[^0-9]/g, "")); // Hapus Rp dan format pemisah ribuan
        table.rows().every(function () {
            var data = this.data();
            var harga = parseFloat(data[6].replace(/[^0-9]/g, "")); // Kolom Harga per 8 sesi
            if (harga >= minHarga || isNaN(minHarga)) {
                $(this.node()).show();
            } else {
                $(this.node()).hide();
            }
        });
        table.draw(false);
    });

    // Filter berdasarkan Rating Minimal
    $('#filter-rating-min').on('change', function () {
        var minRating = parseFloat($(this).val());
        table.rows().every(function () {
            var rating = parseFloat($(this.node()).find('td[data-rating]').data('rating')); // Ambil nilai rating dari atribut data-rating
            if (rating >= minRating || isNaN(minRating)) {
                $(this.node()).show();
            } else {
                $(this.node()).hide();
            }
        });
        table.draw(false);
    });

    // Search berdasarkan nama PT
    $('#search-pt').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });
});

//pagination tabel instruktur
$(document).ready(function () {
    var table = $('#instruktur-table').DataTable({
        pageLength: 5,
        lengthChange: false,
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "",
            searchPlaceholder: "Cari Nama Instruktur...",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Ambil nilai unik dari kolom Spesialisasi
    var spesialisasiSet = new Set();
    table.column(3).data().each(function (value) {
        spesialisasiSet.add(value.trim());
    });

    // Tambahkan nilai unik ke dropdown
    spesialisasiSet.forEach(function (spesialisasi) {
        $('#filter-spesialisasiinstruktur').append(
            $('<option></option>').val(spesialisasi).text(spesialisasi)
        );
    });

    // Filter berdasarkan spesialisasi
    $('#filter-spesialisasiinstruktur').on('change', function () {
        var selectedValue = $(this).val();
        if (selectedValue) {
            table.column(3).search('^' + selectedValue + '$', true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Search berdasarkan nama PT
    $('#search-instruktur').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
    // Ambil nilai unik dari kolom Status
    var statusSet = new Set();
    table.column(4).data().each(function (value) {
        statusSet.add(value.trim());
    });

    // Tambahkan nilai unik ke dropdown Status
    statusSet.forEach(function (status) {
        $('#filter-statusinstruktur').append(
            $('<option></option>').val(status).text(status)
        );
    });

    // Filter berdasarkan status
    $('#filter-statusinstruktur').on('change', function () {
        var selectedValue = $(this).val();
        if (selectedValue) {
            table.column(4).search('^' + selectedValue + '$', true, false).draw();
        } else {
            table.column(4).search('').draw();
        }
    });

});

//pagination tabel jadwal kelas
$(document).ready(function () {
    var table = $('#class-table').DataTable({
        pageLength: 5, // Menentukan jumlah row per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah row
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "",
            searchPlaceholder: "Cari Nama Instruktur...",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Filter Nama Class
    $('#filter-nama-class').on('change', function () {
        var selectedClass = $(this).val();
        if (selectedClass) {
            table.column(1).search('^' + selectedClass + '$', true, false).draw();
        } else {
            table.column(1).search('').draw();
        }
    });
    
    // Format ulang tanggal dari YYYY-MM-DD ke DD-MM-YYYY
    function formatDateToDMY(dateString) {
        const dateParts = dateString.split('-');
        return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
    }

    // Filter Tanggal
    $('#filter-tanggal').on('change', function () {
        var selectedDate = $(this).val(); // Format YYYY-MM-DD
        if (selectedDate) {
            var formattedDate = formatDateToDMY(selectedDate); // Ubah ke DD-MM-YYYY
            table.column(3).search('^' + formattedDate + '$', true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Filter Jam
    $('#filter-jam').on('change', function () {
        var selectedTime = $(this).val();
        if (selectedTime) {
            table.column(4).search('^' + selectedTime + '$', true, false).draw();
        } else {
            table.column(4).search('').draw();
        }
    });

    // Search Nama Instruktur
    $('#search-instrukturkelas').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });
});

//pagination tabel addon pt
$(document).ready(function () {
    var table = $('#tambah-table').DataTable({
        pageLength: 5,
        lengthChange: false,
        dom: '<"d-none"lf>rt<""ip>',
        language: {
            search: "",
            searchPlaceholder: "Cari Nama Member...",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Fungsi untuk menghitung total pendapatan berdasarkan semua filter yang diterapkan
    function calculateTotalRevenue() {
        var totalRevenue = 0;
        // Ambil bulan yang dipilih di filter
        var selectedMonth = $('#filter-tglmulaitambah').val(); // Format: YYYY-MM
        var selectedDate = selectedMonth ? new Date(selectedMonth + "-01") : null;

        // Ambil semua data yang sudah difilter
        var filteredData = table.rows({ search: 'applied' }).data(); // Hanya data yang terfilter

        // Loop setiap baris dalam data yang sudah difilter
        filteredData.each(function (rowData) {
            var price = rowData[6]; // Ambil harga dari kolom Harga PT
            var startDate = new Date(rowData[4]); // Ambil Tgl_Berlaku (start date)

            // Filter hanya jika bulan dan tahun cocok dengan filter bulan yang dipilih
            if (!selectedDate || (startDate.getMonth() === selectedDate.getMonth() && startDate.getFullYear() === selectedDate.getFullYear())) {
                totalRevenue += parseInt(price.replace('Rp', '').replace('.', '').trim());
            }
        });

        // Menampilkan total pendapatan setelah filter diterapkan
        $('#total-revenuetambah').text('Total Pendapatan: Rp' + totalRevenue.toLocaleString());
    }

    // Panggil fungsi calculateTotalRevenue setiap kali DataTable digambar ulang atau difilter
    table.on('draw', function () {
        calculateTotalRevenue(); // Hitung total pendapatan setelah setiap perubahan tampilan
    });

    // Filter Nama Member
    $('#search-tambah').on('keyup', function () {
        table.column(2).search(this.value).draw();
    });

    // Filter Nama PT
    $('#filter-nama-pt').on('keyup', function () {
        table.column(3).search(this.value).draw();
    });

    // Filter ID Record
    $('#filter-id-record').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });

    // Ambil nilai unik dari kolom Status
    var statusSet = new Set();
    $('#tambah-table tbody tr').each(function () {
        var status = $(this).find('td[data-status]').data('status'); // Ambil nilai data-status
        if (status) {
            statusSet.add(status.trim());
        }
    });

    // Tambahkan nilai unik ke dropdown filter status
    statusSet.forEach(function (status) {
        $('#filter-statustambah').append(
            $('<option></option>').val(status).text(status)
        );
    });

    // Filter berdasarkan status
    $('#filter-statustambah').on('change', function () {
        var selectedValue = $(this).val();
        if (selectedValue) {
            table.column(8).search('^' + selectedValue + '$', true, false).draw(); // Hapus regex ketat
        } else {
            table.column(8).search('').draw();
        }
    });

    // Validasi untuk memastikan bulan mulai <= bulan berakhir
    function validateDateFilterstambah() {
        var startMonth = $('#filter-tglmulaitambah').val();
        var endMonth = $('#filter-tglberakhirtambah').val();

        if (startMonth && endMonth) {
            var startDate = new Date(startMonth + "-01"); // Konversi ke Date
            var endDate = new Date(endMonth + "-01"); // Konversi ke Date

            if (startDate > endDate) {
                alert("Bulan Mulai tidak boleh lebih dari Bulan Berakhir!");
                return false;
            }
        }
        return true;
    }

    // Filter berdasarkan Bulan Mulai
    $('#filter-tglmulaitambah').on('change', function () {
        if (!validateDateFilterstambah()) {
            $('#filter-tglmulaitambah').val(""); // Reset Bulan Mulai jika tidak valid
            return;
        }

        var selectedMonth = $(this).val(); // Format: YYYY-MM
        if (selectedMonth) {
            var filterDate = new Date(selectedMonth + "-01"); // Ubah menjadi objek Date

            table.rows().every(function () {
                var data = this.data();
                var startDate = new Date(data[4]); // Ambil Tgl_Berlaku dari database

                var startMonth = startDate.getMonth();
                var startYear = startDate.getFullYear();

                var filterMonth = filterDate.getMonth();
                var filterYear = filterDate.getFullYear();

                // Jika bulan dan tahun sesuai, tampilkan baris, jika tidak sembunyikan
                if (startMonth === filterMonth && startYear === filterYear) {
                    $(this.node()).show(); // Tampilkan baris
                } else {
                    $(this.node()).hide(); // Sembunyikan baris
                }
            });
            table.draw(false); // Perbarui tabel tanpa mereset pagination
        } else {
            table.rows().show().draw(); // Tampilkan semua jika filter kosong
        }
    });

    // Filter berdasarkan Bulan Berakhir
    $('#filter-tglberakhirtambah').on('change', function () {
        if (!validateDateFilterstambah()) {
            $('#filter-tglberakhirtambah').val(""); // Reset Bulan Berakhir jika invalid
            return;
        }

        var selectedMonth = $(this).val(); // Format: YYYY-MM
        if (selectedMonth) {
            table.rows().every(function () {
                var data = this.data();
                var endDate = new Date(data[5]); // Konversi ke format Date
                var filterDate = new Date(selectedMonth + "-01"); // Set ke tanggal pertama bulan
                filterDate.setMonth(filterDate.getMonth() + 1); // Tambahkan 1 bulan untuk batas akhir
                if (endDate < filterDate) {
                    $(this.node()).show(); // Tampilkan baris
                } else {
                    $(this.node()).hide(); // Sembunyikan baris
                }
            });
            table.draw(false); // Perbarui tabel tanpa reset pagination
        } else {
            table.rows().show().draw(); // Tampilkan semua jika filter kosong
        }
    });
});



// Fungsi untuk membuka modal Edit Member
function openEditMemberForm(id, name, phone, email) {
    // Isi form dengan data member yang dipilih
    document.getElementById('editId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editEmail').value = email;

    // Tampilkan modal dengan efek
    let modal = document.getElementById('editMemberModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); // Agar animasi tampak
}

// Fungsi untuk membuka modal Add Member
function openAddMemberForm() {
    let modal = document.getElementById('addMemberModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); // Agar animasi tampak
}

// Fungsi untuk menutup modal Edit Member
function closeEditForm() {
    let modal = document.getElementById('editMemberModal');
    modal.querySelector('.modal-content').style.transform = 'translateY(-50px)';
    modal.querySelector('.modal-content').style.opacity = '0';

    setTimeout(function() {
        modal.style.display = 'none';
    }, 300); // Waktu transisi
}

// Fungsi untuk menutup modal Add Member
function closeAddForm() {
    let modal = document.getElementById('addMemberModal');
    modal.querySelector('.modal-content').style.transform = 'translateY(-50px)';
    modal.querySelector('.modal-content').style.opacity = '0';

    setTimeout(function() {
        modal.style.display = 'none';
    }, 300); // Waktu transisi
}

// Function untuk menghapus member
function confirmMemberDelete(ID_Member) {
    if (confirm("Are you sure you want to delete this member?")) {
        // Buat objek data yang akan dikirimkan
        const data = new FormData();
        data.append('ID_Member', ID_Member); // Menambahkan ID_Member ke FormData

        // Menggunakan fetch untuk mengirim request POST
        fetch(`/dashboard/deleteMember/${ID_Member}`, {
            method: 'POST',  // POST method
            body: data  // FormData yang berisi data yang akan dikirim
        })
        .then(response => response.json())  // Menangani response dalam format JSON
        .then(data => {
            if (data.success) {
                // Jika berhasil, reload halaman atau tampilkan pesan sukses
                alert(data.message);  // Menampilkan pesan sukses
                location.reload();  // Reload halaman untuk memperbarui data
            } else {
                // Jika gagal, tampilkan pesan error
                alert(data.message);  // Menampilkan pesan error
            }
        })
        .catch(error => {
            // Menangani error jika terjadi masalah saat fetch
            alert('Error: ' + error);
        });
    }
}


// Open the Add Membership Form
function openAddMembershipForm() {
    let modal = document.getElementById('addMembershipModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Close the Add Membership Form
function closeAddMembershipForm() {
    let modal = document.getElementById('addMembershipModal');
    modal.style.display = 'none';
}

// Open the Edit Membership Form
function openEditMembershipForm(id,jenisMembership, durasi, harga) {
    // Fetch membership data via AJAX or pre-populate fields (example shown)
    let modal = document.getElementById('editMembershipModal');
    modal.style.display = 'flex';
    
    // Set membership ID in hidden input
    document.getElementById('editMembershipID').value = id;
    
    // Fill other form fields (you can fetch data from server or pass it as parameter)
    document.getElementById('editMembershipType').value = jenisMembership;  // Example
    document.getElementById('editDuration').value = durasi;  // Example
    document.getElementById('editPrice').value = harga;  // Example

    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Close the Edit Membership Form
function closeEditMembershipForm() {
    let modal = document.getElementById('editMembershipModal');
    modal.style.display = 'none';
}

// Confirm before delete
function confirmMembershipDelete(ID_Membership) {
    if (confirm("Are you sure you want to delete this membership?")) {
        // Create a form to send the delete request
        const id = ID_Membership;
        var form = document.createElement('form');
        form.method = 'POST';  // POST method
        form.action = `/dashboard/deleteMembership/${id}`; // Correct URL with ID parameter

        // Create a hidden input for the membership ID
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ID_Membership';  // This can be optional, depends on backend
        input.value = id;

        form.appendChild(input);

        // Append form to body and submit it
        document.body.appendChild(form);
        form.submit(); // Submit the form
    }
}

// Function to populate the filter dropdown
function populateFilterDropdown(data) {
    const filterDropdown = document.getElementById('filterMember');
    filterDropdown.innerHTML = '<option value="">All Members</option>'; // Clear existing options

    const uniqueMembers = [...new Set(data.map(item => item.Nama_Member))]; // Get unique member names

    uniqueMembers.forEach(member => {
        const option = document.createElement('option');
        option.value = member;
        option.textContent = member;
        filterDropdown.appendChild(option);
    });
}

// Function to filter the table based on the selected member
function filterTable() {
    const filterValue = document.getElementById('filterMember').value;
    const table = $('#trainerDetailsTable').DataTable();

    if (filterValue === "") {
        table.search('').draw(); // Reset filter
    } else {
        table.search(filterValue).draw(); // Filter by member name
    }
}

// Function to show trainer rating and review detail
function showTrainerDetails(trainerId) {
    // Fetch data from the server
    fetch(`/getTrainerDetails/${trainerId}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('trainerDetailsBody');
            tableBody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="custom-tabletr">${item.date}</td>
                    <td class="custom-tabletr">${item.Nama_Member}</td>
                    <td class="custom-tabletr">${item.rating}<i class="fa fa-star text-warning"></i></td>
                    <td class="custom-tabletr">${item.review || 'No review'}</td>
                `;
                tableBody.appendChild(row);
            });

            // Initialize DataTables with pagination and search
            var table = $('#trainerDetailsTable').DataTable({
                pageLength: 8, // Number of rows per page
                lengthChange: false, // Disable dropdown to change rows per page
                destroy: true, // Reinitialize if already initialized
                dom: '<"d-none"lf>rt<""ip>',
                language: {
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    },
                    search: "",
                    searchPlaceholder: "Cari Nama Member..."
                }
            });
            $('#search-ptdetail').on('keyup', function () {
                table.column(1).search(this.value).draw();
            });

            // Show the modal
            document.getElementById('trainerDetailsModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching trainer details:', error));
}

// Function to close the modal
function closeTrainerDetailsModal() {
    document.getElementById('trainerDetailsModal').style.display = 'none';
}


// Function to open Add Trainer form
function openAddTrainerForm() {
    let modal = document.getElementById('addTrainerModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Function to close Add Trainer form
function closeAddTrainerForm() {
    let modal = document.getElementById('addTrainerModal');
    modal.style.display = 'none';
}

// Function to open Edit Trainer form and populate fields with data
function openEditTrainerForm(id, email, password, name, photo, achievement, specialty, hourlyRate) {
    document.getElementById('editID_PT').value = id;
    document.getElementById('editPTName').value = name;
    document.getElementById('editPTEmail').value = email;
    document.getElementById('editAchievement').value = achievement;
    document.getElementById('editSpecialty').value = specialty;
    document.getElementById('editHourlyRate').value = hourlyRate;

    // Set hidden input untuk menyimpan nama file foto
    document.getElementById('currentFoto').value = photo;

    const modal = document.getElementById('editTrainerModal');
    modal.style.display = 'flex';

    setTimeout(function () {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10);
}



// Function to close Edit Trainer form
function closeEditTrainerForm() {
    let modal = document.getElementById('editTrainerModal');
    modal.style.display = 'none';
}


// Confirm before delete PT
function confirmTrainerDelete(ID_PT) {
    if (confirm("Are you sure you want to delete this trainer?")) {
        // Create a form to send the delete request
        const id = ID_PT;
        var form = document.createElement('form');
        form.method = 'POST';  // POST method
        form.action = `/dashboard/deleteTrainer/${id}`; // Correct URL with ID parameter

        // Create a hidden input for the membership ID
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ID_PT';  // This can be optional, depends on backend
        input.value = id;

        form.appendChild(input);

        // Append form to body and submit it
        document.body.appendChild(form);
        form.submit(); // Submit the form
    }
}

// Fungsi untuk membuka modal instruktur
function openAddInstrukturForm() {
    let modal = document.getElementById('addInstrukturModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Fungsi untuk menutup modal instruktur
function closeAddInstrukturForm() {
    let modal = document.getElementById('addInstrukturModal');
    modal.style.display = 'none';
}

// Menampilkan modal edit instruktur
function openEditInstrukturModal(id, name, photo, specialty, status) {
    // Isi nilai form dengan data instruktur yang sudah ada
    document.getElementById('editID_Instruktur').value = id;
    console.log("id ins set:", id);
    document.getElementById('editInstrukturName').value = name;
    console.log("name ins set:", name);
    document.getElementById('editInsSpecialty').value = specialty;
    console.log("special set:", specialty);
    document.getElementById('editInsStatus').value = status;
    console.log("status set:", status);
    document.getElementById('currentFotoIns').value = photo;
    console.log("foto set:", photo);

    let modal = document.getElementById('editInstrukturModal');
    modal.style.display = 'flex';

    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Menutup modal edit instruktur
function closeEditInstrukturForm() {
    document.getElementById('editInstrukturModal').style.display = 'none';
}
// Confirm before delete instruktur
function confirmInstrukturDelete(ID_Instruktur) {
    if (confirm("Are you sure you want to delete this instructor?")) {
        // Create a form to send the delete request
        const id = ID_Instruktur;
        var form = document.createElement('form');
        form.method = 'POST';  // POST method
        form.action = `/dashboard/deleteInstruktur/${id}`; // Correct URL with ID parameter

        // Create a hidden input for the membership ID
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ID_Instruktur';  // This can be optional, depends on backend
        input.value = id;

        form.appendChild(input);

        // Append form to body and submit it
        document.body.appendChild(form);
        form.submit(); // Submit the form
    }
}

// Fungsi untuk membuka modal tambah kelas
function openAddClassModal() {
    let modal = document.getElementById('addClassModal');
    modal.style.display = 'flex';
    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}
// Fungsi untuk membatasi tanggal yang dapat dipilih
function setMinDate() {
    const today = new Date().toISOString().split('T')[0]; // Ambil tanggal hari ini dalam format YYYY-MM-DD
    document.getElementById('newDate').setAttribute('min', today); // Set tanggal minimum
}

// Pastikan untuk memanggil fungsi setMinDate saat halaman dimuat
document.addEventListener('DOMContentLoaded', setMinDate);


document.getElementById('newClassName').addEventListener('change', function() {
    const selectedClass = this.value; // Ambil kelas yang dipilih
    const instructorDropdown = document.getElementById('newInstructor'); // Dropdown instruktur
    const instructorOptions = instructorDropdown.querySelectorAll('option'); // Semua opsi instruktur

    // Tampilkan opsi "Pilih Instruktur"
    instructorOptions.forEach(option => {
        if (option.value === "") {
            option.style.display = "block";
        } else {
            // Cek apakah spesialisasi instruktur sesuai dengan kelas yang dipilih
            if (option.dataset.spesialisasi === selectedClass || selectedClass === "") {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        }
    });

    // Reset pilihan instruktur
    instructorDropdown.value = "";
});


document.getElementById('newInstructor').addEventListener('change', filterTimeOptions);
document.getElementById('newDate').addEventListener('change', filterTimeOptions);

function filterTimeOptions() {
    const instructorId = document.getElementById('newInstructor').value;
    const date = document.getElementById('newDate').value;

    if (!instructorId || !date) {
        return; // Tidak ada filter jika instruktur atau tanggal belum dipilih
    }

    fetch(`/dashboard/getUnavailableTimes?ID_Instruktur=${instructorId}&Tanggal=${date}`)
        .then(response => response.json())
        .then(unavailableTimes => {
            const timeDropdown = document.getElementById('newTime');
            const allOptions = Array.from(timeDropdown.options);

            allOptions.forEach(option => {
                if (option.value && unavailableTimes.includes(option.value)) {
                    option.style.display = 'none'; // Sembunyikan opsi yang sudah digunakan
                } else {
                    option.style.display = 'block'; // Tampilkan opsi yang tersedia
                }
            });
        })
        .catch(error => console.error('Error fetching unavailable times:', error));
}



// Fungsi untuk menutup modal tambah kelas
function closeAddClassForm() {
    let modal = document.getElementById('addClassModal');
    modal.style.display = 'none';
}

// Fungsi untuk membuka modal edit kelas
function openEditClassModal(ID_Class, Nama_Class, ID_Instruktur, Tanggal, Jam, Kuota) {
    console.log('ID_Class:', ID_Class);
    console.log('Nama_Class:', Nama_Class);
    console.log('ID_Instruktur:', ID_Instruktur);
    console.log('Tanggal:', Tanggal);
    console.log('Jam:', Jam);
    console.log('Kuota:', Kuota);

    document.getElementById('editClassID').value = ID_Class;
    document.getElementById('editClassName').value = Nama_Class;
    document.getElementById('editInstructor').value = ID_Instruktur;
    document.getElementById('editDate').value = Tanggal;
    document.getElementById('editTime').value = Jam;
    document.getElementById('editQuota').value = Kuota;

    // Filter instruktur berdasarkan kelas yang dipilih
    filterInstructorsByClass(Nama_Class);

     // Fetch unavailable times for edit
     fetch(`/dashboard/getUnavailableTimesEdit?ID_Instruktur=${ID_Instruktur}&Tanggal=${Tanggal}&ID_Class=${ID_Class}`)
     .then(response => response.json())
     .then(unavailableTimes => {
         const timeDropdown = document.getElementById('editTime');
         const allOptions = Array.from(timeDropdown.options);

         allOptions.forEach(option => {
             if (option.value && unavailableTimes.includes(option.value)) {
                 option.style.display = 'none'; // Sembunyikan opsi yang sudah digunakan
             } else {
                 option.style.display = 'block'; // Tampilkan opsi yang tersedia
             }
         });
     })
     .catch(error => console.error('Error fetching unavailable times for edit:', error));


    // Menampilkan modal edit
    let modal = document.getElementById('editClassModal');
    modal.style.display = 'flex';

    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}

// Fungsi untuk memfilter instruktur berdasarkan spesialisasi kelas yang dipilih
function filterInstructorsByClass(className) {
    const instructorDropdown = document.getElementById('editInstructor');
    const instructorOptions = instructorDropdown.querySelectorAll('option');

    // Mengambil instruktur yang spesialisasinya cocok dengan kelas yang dipilih
    instructorOptions.forEach(option => {
        const spesialisasi = option.dataset.spesialisasi; // Asumsi data spesialisasi ada di atribut data-spesialisasi
        if (className === "Aerobik" && spesialisasi === "Aerobik" || 
            className === "Yoga" && spesialisasi === "Yoga" ||
            className === "Zumba" && spesialisasi === "Zumba" || 
            className === "") {
            option.style.display = "block";  // Tampilkan instruktur dengan spesialisasi yang sesuai
        } else {
            option.style.display = "none"; // Sembunyikan instruktur yang tidak sesuai
        }
    });
}

// Menutup modal edit class
function closeEditClassModal() {
    document.getElementById('editClassModal').style.display = 'none';
}

// Fungsi konfirmasi penghapusan kelas
function confirmClassDelete(ID_Class) {
    if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
        window.location.href = '/dashboard/deleteClass/' + ID_Class;
    }
}

//fungsi untuk liat daftar member yg booking class
function viewBookingMembers(classID) {
    fetch(`/getBookingMembers/${classID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.getElementById('bookingMembersTableBody');
                tableBody.innerHTML = '';

                data.members.forEach(member => {
                    const row = `<tr>
                        <td style="text-align: center">
                            <img src="${member.Foto_Member}" alt="Foto Member" style="width: 200x; height: 200px; border-radius: 10%;">
                        </td>
                        <td style="text-align: center; font-size: 20px">${member.Nama_Member}</td>
                        <td style="text-align: center">${member.Tanggal_Booking}</td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });

                document.getElementById('bookingMembersModal').style.display = 'flex';
            } else {
                alert('Tidak ada member yg booking kelas ini.');
            }
        })
        .catch(error => console.error('Error:', error));
}
function filterMembers() {
    // Ambil input dari pengguna
    const input = document.getElementById('memberSearchInput').value.toLowerCase();
    const tableBody = document.getElementById('bookingMembersTableBody');
    const rows = tableBody.getElementsByTagName('tr');

    // Loop melalui setiap baris dalam tabel
    for (let i = 0; i < rows.length; i++) {
        const nameCell = rows[i].getElementsByTagName('td')[1]; // Kolom Nama Member
        if (nameCell) {
            const name = nameCell.textContent || nameCell.innerText;
            // Tampilkan atau sembunyikan baris berdasarkan kecocokan teks
            rows[i].style.display = name.toLowerCase().indexOf(input) > -1 ? '' : 'none';
        }
    }
}


function closeBookingMembersModal() {
    document.getElementById('bookingMembersModal').style.display = 'none';
}

function openPaymentModal(id, imageUrl, status) {
    if (status === "Aktif" || status === "Non-Aktif" || status === "Selesai") {
        alert("Data sudah diverifikasi!");
        return; // Keluar dari fungsi jika status sudah diverifikasi
    }

    document.getElementById('recordID').value = id;
    document.getElementById('paymentImage').src = imageUrl;
    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function savePaymentStatus() {
    const recordID = document.getElementById('recordID').value;
    const status = document.querySelector('input[name="StatusGym"]:checked').value;
    const reason = document.getElementById('reason').value;

    fetch('/updateMembershipStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ID_Record: recordID, Status: status, Reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal memperbarui status.');
        }
    })
    .catch(error => console.error('Error:', error));
}
function setPendingStatusGym(idRecord) {
    if (!confirm("Apakah Anda yakin ingin membatalkan verifikasi dan mengatur ulang ke status Pending?")) {
        return;
    }

    fetch('/updateMembershipStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_Record: idRecord, // Tambahkan ID_Record
            Status: 'Pending',
            Reason: 'Dibatalkan oleh Admin',
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diubah menjadi Pending.');
            location.reload(); // Refresh halaman untuk memperbarui tabel
        } else {
            alert('Gagal mengubah status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function openPaymentModalHarian(id, imageUrl, status) {
    if (status === "Aktif" || status === "Non-Aktif" || status === "Selesai") {
        alert("Data sudah diverifikasi!");
        return; // Keluar dari fungsi jika status sudah diverifikasi
    }

    document.getElementById('recordID').value = id;
    document.getElementById('paymentImageHarian').src = imageUrl;
    document.getElementById('paymentModalHarian').style.display = 'flex';
}

function closePaymentModalHarian() {
    document.getElementById('paymentModalHarian').style.display = 'none';
}

function savePaymentStatusHarian() {
    const recordID = document.getElementById('recordID').value;
    const status = document.querySelector('input[name="Statusharian"]:checked').value;
    const reasonHarian = document.getElementById('reasonHarian').value;

    console.log("alasan:", document.getElementById('reasonHarian'));
    fetch('/updateMembershipStatusHarian', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ID_Record: recordID, Status: status, ReasonHarian: reasonHarian })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal memperbarui status.');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Fungsi untuk mengubah status menjadi Pending
function setPendingStatusHarian(idRecord) {
    if (!confirm("Apakah Anda yakin ingin membatalkan verifikasi dan mengatur ulang ke status Pending?")) {
        return;
    }

    fetch('/updateMembershipStatusHarian', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_Record: idRecord, // Tambahkan ID_Record
            Status: 'Pending',
            ReasonHarian: 'Dibatalkan oleh Admin',
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diubah menjadi Pending.');
            location.reload(); // Refresh halaman untuk memperbarui tabel
        } else {
            alert('Gagal mengubah status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function openPaymentModalClass(id, imageUrl, status) {
    if (status === "Aktif" || status === "Non-Aktif" || status === "Selesai") {
        alert("Data sudah diverifikasi!");
        return; // Keluar dari fungsi jika status sudah diverifikasi
    }

    document.getElementById('recordID').value = id;
    document.getElementById('paymentImageClass').src = imageUrl;
    document.getElementById('paymentModalClass').style.display = 'flex';
}

function closePaymentModalClass() {
    document.getElementById('paymentModalClass').style.display = 'none';
}

function savePaymentStatusClass() {
    const recordID = document.getElementById('recordID').value;
    const status = document.querySelector('input[name="Statusclass"]:checked').value;
    const reasonClass = document.getElementById('reasonClass').value;

    console.log("alasan:", document.getElementById('reasonClass'));
    fetch('/updateMembershipStatusClass', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ID_Record: recordID, Status: status, ReasonClass: reasonClass })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal memperbarui status.');
        }
    })
    .catch(error => console.error('Error:', error));
}
// Fungsi untuk mengubah status menjadi Pending
function setPendingStatusClass(idRecord) {
    if (!confirm("Apakah Anda yakin ingin membatalkan verifikasi dan mengatur ulang ke status Pending?")) {
        return;
    }

    fetch('/updateMembershipStatusClass', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_Record: idRecord, // Tambahkan ID_Record
            Status: 'Pending',
            ReasonClass: 'Dibatalkan oleh Admin',
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diubah menjadi Pending.');
            location.reload(); // Refresh halaman untuk memperbarui tabel
        } else {
            alert('Gagal mengubah status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


// Fungsi untuk membuka modal dan mengisi form dengan data yang dipilih
function openEditStatusModal(id, memberName, membershipType, startDate, endDate, currentStatus) {
    document.getElementById('editID_Record').value = id;
    document.getElementById('memberName').value = memberName;
    document.getElementById('membershipType').value = membershipType;
    document.getElementById('startDate').value = startDate;
    document.getElementById('endDate').value = endDate;
    document.getElementById('statusDropdown').value = currentStatus;

    let modal = document.getElementById('editStatusModal');
    modal.style.display = 'flex';

    // Update button status
    updateButtonStatus(currentStatus);


    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}


// Fungsi untuk menutup modal
function closeEditStatusForm() {
    let modal = document.getElementById('editStatusModal');
    modal.style.display = 'none';
}

// Fungsi untuk memperbarui tombol status berdasarkan status yang dipilih di modal
function updateButtonStatus(status) {
    const buttons = document.querySelectorAll('.status-button'); // Ambil semua tombol status

    buttons.forEach(button => {
        // Cek apakah teks tombol sama dengan status yang ada
        if (button.textContent === status) {
            // Ubah warna tombol sesuai status
            switch (status) {
                case 'Pending':
                    button.style.backgroundColor = 'yellow'; // Warna kuning untuk Pending
                    break;
                case 'Aktif':
                    button.style.backgroundColor = 'green'; // Warna hijau untuk Aktif
                    break;
                case 'Non-Aktif':
                    button.style.backgroundColor = 'gray'; // Warna abu-abu untuk Non-Aktif
                    break;
                case 'Selesai':
                    button.style.backgroundColor = 'blue'; // Warna biru untuk Selesai
                    break;
                default:
                    button.style.backgroundColor = ''; // Default, tidak ada warna
            }
        }
    });
}

// Fungsi untuk menangani perubahan status di dropdown modal
document.getElementById('statusDropdown').addEventListener('change', function() {
    const selectedStatus = this.value; // Ambil status yang dipilih
    updateButtonStatus(selectedStatus); // Perbarui tombol status berdasarkan pilihan
});

// Fungsi untuk membuka modal dan mengisi form dengan data yang dipilih
function openEditStatusTambahModal(idtambah, idrecord, idpt, hargapt, status) {
    document.getElementById('editID_Tambah_PT').value = idtambah;
    document.getElementById('ID_Record').value = idrecord;
    document.getElementById('ID_PT').value = idpt;
    document.getElementById('Harga_PT').value = hargapt;
    document.getElementById('Status').value = status;

    let modal = document.getElementById('editStatusTambahModal');
    modal.style.display = 'flex';

    // Update button status
    updateButtonStatusTambah(Status);


    setTimeout(function() {
        modal.querySelector('.modal-content').style.transform = 'translateY(0)';
        modal.querySelector('.modal-content').style.opacity = '1';
    }, 10); 
}


// Fungsi untuk menutup modal
function closeEditStatusTambahForm() {
    let modal = document.getElementById('editStatusTambahModal');
    modal.style.display = 'none';
}

// Fungsi untuk memperbarui tombol status berdasarkan status yang dipilih di modal
function updateButtonStatusTambah(status) {
    const buttons = document.querySelectorAll('.status-button'); // Ambil semua tombol status

    buttons.forEach(button => {
        // Cek apakah teks tombol sama dengan status yang ada
        if (button.textContent === status) {
            // Ubah warna tombol sesuai status
            switch (status) {
                case 'Pending':
                    button.style.backgroundColor = 'yellow'; // Warna kuning untuk Pending
                    break;
                case 'Aktif':
                    button.style.backgroundColor = 'green'; // Warna hijau untuk Aktif
                    break;
                default:
                    button.style.backgroundColor = ''; // Default, tidak ada warna
            }
        }
    });
}

// Fungsi untuk menangani perubahan status di dropdown modal
document.getElementById('status').addEventListener('change', function() {
    const selectedStatus = this.value; // Ambil status yang dipilih
    updateButtonStatusTambah(selectedStatus); // Perbarui tombol status berdasarkan pilihan
});



// Fungsi untuk membuka modal verifikasi pembayaran Add-on PT
function openAddonPaymentModal(status, id, recordID, imageUrl) {
    console.log("Checking addon modal elements...");
    console.log("addonID:", document.getElementById('addonID'));
    console.log("addonRecordID:", document.getElementById('addonRecordID'));
    console.log("addonPaymentImage:", document.getElementById('addonPaymentImage'));
    
    if (status === "Aktif" || status === "Non-Aktif" || status === "Selesai") {
        alert("Data sudah diverifikasi!");
        return; // Keluar dari fungsi jika status sudah diverifikasi
    }
    document.getElementById('addonID').value = id;
    document.getElementById('addonRecordID').value = recordID;
    document.getElementById('addonPaymentImage').src = imageUrl;
    document.getElementById('verifyAddOnModal').style.display = 'flex';
}

// Fungsi untuk menutup modal
function closeAddonPaymentModal() {
    document.getElementById('verifyAddOnModal').style.display = 'none';
}

// Fungsi untuk mengubah status menjadi Pending
function setPendingStatus(idTambahPT, idRecord) {
    if (!confirm("Apakah Anda yakin ingin membatalkan verifikasi dan mengatur ulang ke status Pending?")) {
        return;
    }

    fetch('/updateAddonStatus', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_Tambah_PT: idTambahPT,
            ID_Record: idRecord, // Tambahkan ID_Record
            Status: 'Pending',
            Reason: 'Dibatalkan oleh Admin',
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diubah menjadi Pending.');
            location.reload(); // Refresh halaman untuk memperbarui tabel
        } else {
            alert('Gagal mengubah status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Fungsi untuk menyimpan status pembayaran Add-on PT
function saveAddonPaymentStatus() {
    const addonID = document.getElementById('addonID').value;
    const recordID = document.getElementById('addonRecordID').value;
    const status = document.querySelector('input[name="addonStatus"]:checked').value;
    const reason = document.getElementById('addonReason').value;

    fetch('/updateAddonStatus', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ID_Tambah_PT: addonID, ID_Record: recordID, Status: status, Reason: reason }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status berhasil diperbarui!');
                location.reload();
            } else {
                alert('Gagal memperbarui status.');
            }
        })
        .catch(error => console.error('Error:', error));
}
