document.addEventListener('DOMContentLoaded', function () {
    // Mendapatkan semua link sidebar
    const sidebarLinks = document.querySelectorAll('.sidebar-link');

    // Mengecek section terakhir yang dipilih dari localStorage
    const lastSection = localStorage.getItem('activeSection') || 'profile-section';

    // Menampilkan section terakhir yang dipilih
    showSection(lastSection);

    // Menambahkan event listener ke setiap link sidebar
    sidebarLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            if (link.getAttribute('href') === '/') {
                // Tidak menghalangi default behavior untuk logout
                return;
            }
            event.preventDefault();

            // Mendapatkan nama section yang dipilih dari atribut data-section
            const section = link.getAttribute('data-section');

            // Menyimpan section ke localStorage
            localStorage.setItem('activeSection', section);

            // Menampilkan section yang sesuai
            showSection(section);
        });
    });

    // Fungsi untuk menampilkan section dan menyembunyikan yang lain
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.content-section');

        sections.forEach(function (sec) {
            if (sec.id === sectionId) {
                sec.style.display = 'block'; // Menampilkan section yang dipilih
            } else {
                sec.style.display = 'none'; // Menyembunyikan section lain
            }
        });
    }
});



// Mendapatkan tanggal hari ini
function displayCurrentDate() {
    const dateElement = document.getElementById('currentDate');
    const today = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = today.toLocaleDateString('id-ID', options); // Format lokal Indonesia
    dateElement.textContent = formattedDate;
}

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', displayCurrentDate);

// Fungsi untuk membuka modal edit dan mengisi data
function openEditModal(id, password, nama, foto, prestasi, spesialisasi, harga) {
    const modal = document.getElementById('editModal');
    // Isi data ke dalam form modal
    document.getElementById('editID_PT').value = id;
    document.getElementById('editPassword').value = password;
    document.getElementById('editNama_PT').value = nama;
    document.getElementById('editPrestasi').value = prestasi;
    document.getElementById('editSpesialisasi').value = spesialisasi;
    document.getElementById('editHarga_Sesi').value = harga;
    document.getElementById('currentFoto').value = foto;
    
    // Tampilkan foto yang sudah disimpan
    const imagePreview = document.getElementById('imagePreview');
    if (foto) {
        imagePreview.src = `/uploads/pt_photos/${foto}`;
    } else {
        imagePreview.src = ''; // Default jika tidak ada foto
    }

     // Tampilkan modal dengan efek
    // Tampilkan modal dengan efek keyframes
    modal.style.display = 'block';
    modal.classList.remove('hide'); // Pastikan animasi keluar dihapus
    modal.classList.add('show'); // Tambahkan animasi masuk
 
}
// Fungsi untuk menutup modal
function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('show'); // Hapus animasi masuk
    modal.classList.add('hide'); // Tambahkan animasi keluar
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500); // Tunggu transisi selesai
}

// SweetAlert confirmation before submitting
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editform');
    const saveButton = document.getElementById('saveButton');

    // Add event listener to the submit button
    saveButton.addEventListener('click', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Display SweetAlert confirmation
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin merubah data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#40ce20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form after confirmation
                form.submit();
            }
        });
    });
});


// Function to display success message
function displaySuccessMessage() {
    Swal.fire({
        title: 'Berhasil!',
        text: 'Data PT berhasil diupdate!',
        icon: 'success',
        confirmButtonColor: '#40ce20'
    });
}

// Call success message if success flashdata is set
document.addEventListener('DOMContentLoaded', function () {
    if (successMessage) {
        displaySuccessMessage();
    }
});
// Function to display success message
function displaySuccessMessageR() {
    Swal.fire({
        title: 'Berhasil!',
        text: 'Berhasil Request Reschedule!',
        icon: 'success',
        confirmButtonColor: '#40ce20'
    });
}

// Call success message if success flashdata is set
document.addEventListener('DOMContentLoaded', function () {
    if (successMessageReschedule) {
        displaySuccessMessageR();
    }
});


// Fungsi untuk menampilkan nama file yang dipilih
function displayFileName() {
    const fileInput = document.getElementById('Foto_PT');
    const fileName = fileInput.files[0] ? fileInput.files[0].name : ''; // Ambil nama file yang dipilih
    document.getElementById('file-name').textContent = fileName; // Tampilkan nama file
}
// Open Reschedule Modal
function openRescheduleModal(idSesi) {
    const modal = document.getElementById('rescheduleModal');
    console.log("Memuat reschedule untuk ID:", idSesi);
    // Tampilkan modal dengan efek keyframes
    modal.style.display = 'block';
    modal.classList.remove('hide'); // Pastikan animasi keluar dihapus
    modal.classList.add('show'); // Tambahkan animasi masuk
 
    document.getElementById('rescheduleID_Sesi').value = idSesi;
}

// Open Reschedule Modal
function openRescheduleModal1(idSesi) {
    const modal = document.getElementById('rescheduleModal1');
    console.log("Memuat reschedule untuk ID:", idSesi);
    // Tampilkan modal dengan efek keyframes
    modal.style.display = 'block';
    modal.classList.remove('hide'); // Pastikan animasi keluar dihapus
    modal.classList.add('show'); // Tambahkan animasi masuk
 
    document.getElementById('rescheduleID_Sesi1').value = idSesi;
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('rescheduleForm');
    const saveButton = document.getElementById('saveButtonReschedule');

    // Add event listener to the submit button
    saveButton.addEventListener('click', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Display SweetAlert confirmation
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin reschedule sesi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#40ce20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form after confirmation
                form.submit();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('rescheduleForm1');
    const saveButton = document.getElementById('saveButtonReschedule1');

    // Add event listener to the submit button
    saveButton.addEventListener('click', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Display SweetAlert confirmation
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin reschedule sesi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#40ce20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form after confirmation
                form.submit();
            }
        });
    });
});



// Close Reschedule Modal
function closeRescheduleModal() {
    const modal = document.getElementById('rescheduleModal');
    modal.classList.remove('show'); // Hapus animasi masuk
    modal.classList.add('hide'); // Tambahkan animasi keluar
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500); // Tunggu transisi selesai
}

function closeRescheduleModal1() {
    const modal = document.getElementById('rescheduleModal1');
    modal.classList.remove('show'); // Hapus animasi masuk
    modal.classList.add('hide'); // Tambahkan animasi keluar
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500); // Tunggu transisi selesai
}

// Fungsi untuk membuka modal kalender dengan nama PT
let selectedTrainerId;
let selectedTrainerName;
let selectedDate;


// Tambahkan variabel global untuk menyimpan bulan dan tahun aktif
let activeMonth = null;
let activeYear = null;

// Modifikasi fungsi openKalenderModal untuk menyimpan bulan & tahun
function openKalenderModal(trainerId, trainerName, schedule) {
    console.log("Memanggil openKalenderModal dengan:", { trainerId, trainerName, schedule });

    selectedTrainerId = trainerId;
    selectedTrainerName = trainerName;

    document.getElementById('trainerName').textContent = trainerName;

    // Atur bulan dan tahun aktif
    const today = new Date();
    activeMonth = activeMonth !== null ? activeMonth : today.getMonth();
    activeYear = activeYear !== null ? activeYear : today.getFullYear();

    generateCalendar(trainerId, trainerName, activeMonth, activeYear, schedule);

}

// Fungsi untuk memuat jadwal dari server dan memanggil openKalenderModal
function loadTrainerSchedule(trainerId, trainerName) {
    console.log("Memuat jadwal untuk trainer ID:", trainerId, "Nama:", trainerName);
    fetch(`/getTrainerSchedule/${trainerId}`)
        .then(response => response.json())
        .then(schedule => {
            console.log("Jadwal yang diperoleh:", schedule);
            openKalenderModal(trainerId, trainerName, schedule);
        })
        .catch(error => console.error("Error loading schedule:", error));
}

// Tombol navigasi bulan sebelumnya
document.getElementById('prevMonth').addEventListener('click', () => {
    navigateMonth(-1);
});

// Tombol navigasi bulan berikutnya
document.getElementById('nextMonth').addEventListener('click', () => {
    navigateMonth(1);
});

function navigateMonth(direction) {
    // Hitung bulan dan tahun baru berdasarkan arah navigasi
    activeMonth += direction;

    if (activeMonth > 11) {
        activeMonth = 0;
        activeYear++;
    } else if (activeMonth < 0) {
        activeMonth = 11;
        activeYear--;
    }

    console.log(`Navigasi ke bulan baru: ${activeMonth + 1}, Tahun: ${activeYear}`);

    // Perbarui kalender dengan data yang sesuai
    fetch(`/getTrainerSchedule/${selectedTrainerId}`)
        .then(response => response.json())
        .then(schedule => {
            generateCalendar(selectedTrainerId, selectedTrainerName, activeMonth, activeYear, schedule);
        })
        .catch(error => console.error("Error saat navigasi bulan:", error));
}


// Fungsi untuk menghasilkan kalender berdasarkan bulan dan tahun
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let selectedTrainerSchedule = []; // Simpan jadwal trainer saat ini

function generateCalendar(trainerId, trainerName, month, year, schedule) {
    console.log("Generating calendar for:", { trainerId, trainerName, month, year });

    const calendarBody = document.getElementById('calendarBody');
    const monthYearLabel = `${new Date(year, month).toLocaleString('id-ID', { month: 'long' })} ${year}`;
    document.getElementById('calendarMonthYear').textContent = monthYearLabel;
    calendarBody.innerHTML = '';

    const today = new Date();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    // Pastikan `schedule` berbentuk array
    if (!Array.isArray(schedule)) {
        console.error("Schedule is not an array. Defaulting to empty array.");
        schedule = [];
    }

    let row = document.createElement('tr');
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('td');
        row.appendChild(emptyCell);
    }

    for (let date = 1; date <= daysInMonth; date++) {
        const dateCell = document.createElement('td');
        dateCell.textContent = date;

        const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
        const isAvailable = schedule.some(item => item.Tanggal === currentDate && Object.values(item).includes('tersedia'));
        const isPastDate = new Date(currentDate) < today;
        const isFilled = schedule.some(item => item.Tanggal === currentDate && Object.values(item).includes('tersedia'));

        if (isAvailable) {
            dateCell.classList.add(isPastDate ? 'past-available-day' : 'available-day');
            dateCell.onclick = () => openJadwalModal(trainerId, currentDate, true);
        } else if (isPastDate) {
            dateCell.classList.add('past-unavailable-day');
        } else if (isFilled) {
            dateCell.classList.add('available-day');
        }
        else {
            dateCell.classList.add('unavailable-day');
            dateCell.onclick = () => openJadwalModal(trainerId, currentDate, true);
        }

        if (currentDate === today.toISOString().split('T')[0]) {
            dateCell.classList.add('today');
            dateCell.onclick = () => openJadwalModal(trainerId, currentDate, true);
        }



        row.appendChild(dateCell);

        if ((firstDay + date) % 7 === 0 || date === daysInMonth) {
            calendarBody.appendChild(row);
            row = document.createElement('tr');
        }
    }

    if (row.children.length > 0) {
        calendarBody.appendChild(row);
    }
}

// Fungsi ini dijalankan ketika tanggal dengan sesi tersedia diklik
function selectDate(currentDate) {
    console.log("Tanggal yang dipilih:", currentDate);

    if (!selectedTrainerId) {
        alert("Trainer belum dipilih. Silakan pilih personal trainer terlebih dahulu.");
        return;
    }

    const calendarCells = document.querySelectorAll('#calendarBody td');
    calendarCells.forEach(cell => {
        cell.classList.remove('selected-day');
    });

    const selectedCell = Array.from(calendarCells).find(cell => parseInt(cell.textContent, 10) === new Date(currentDate).getDate());
    if (selectedCell) {
        selectedCell.classList.add('selected-day');
    }

    selectedDate = currentDate;
    openJadwalModal(selectedTrainerId, selectedDate);
}


// Fungsi untuk membuka modal jadwal
function openJadwalModal(trainerId, date, viewOnly) {
    selectedDate = date;
    console.log("Membuka modal jadwal untuk tanggal:", date, "dan trainerId:", trainerId, "Mode View:", viewOnly);

    const today = new Date().setHours(0, 0, 0, 0); // Set jam ke awal hari
    const selectedDateTimestamp = new Date(date).setHours(0, 0, 0, 0);

    // Jika tanggal telah lewat, tetapkan viewOnly ke true
    if (selectedDateTimestamp < today) {
        viewOnly = true;
    }

    // Memuat slot dengan parameter view-only
    loadSlots(trainerId, date, viewOnly);

    const modal = document.getElementById('modalJadwal');
    modal.style.display = 'block';
    modal.classList.remove('hide'); // Pastikan animasi keluar dihapus
    modal.classList.add('show'); // Tambahkan animasi masuk
    // Sembunyikan atau tampilkan tombol simpan jadwal berdasarkan viewOnly
    const saveButton = document.getElementById('saveJadwalBtn');
    if (viewOnly) {
        saveButton.style.display = 'none'; // Sembunyikan tombol simpan jika view-only
    } else {
        saveButton.style.display = 'block'; // Tampilkan tombol simpan jika tidak view-only
    }
}

// Fungsi untuk menutup modal jadwal
function closeJadwalModal() {
    const modal = document.getElementById('modalJadwal');
    modal.classList.remove('show'); // Hapus animasi masuk
    modal.classList.add('hide'); // Tambahkan animasi keluar
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500); // Tunggu transisi selesai
  }

// Fungsi untuk memuat jadwal (dengan memisahkan antara jadwal yang sudah ada dan yang kosong)
function loadSlots(trainerId, date) {
    console.log(`Memuat slot untuk Trainer ID: ${trainerId} pada tanggal: ${date}`);

    const slotContainer = document.getElementById('slotContainer');
    slotContainer.innerHTML = '';


    fetch(`/getJadwal/${trainerId}/${date}`)
        .then(response => response.json())
        .then(jadwal => {
            const slotTimes = ["07:00 - 09:00", "09:00 - 11:00", "11:00 - 13:00", "15:00 - 17:00", "19:00 - 21:00"];
            let isEditable = true;

            // Determine if all slots are already saved (i.e., view-only mode)
            if (jadwal && Object.values(jadwal).some(val => val === 'tersedia')) {
                isEditable = false;
            } 

            slotTimes.forEach((slot, index) => {
                const slotDiv = document.createElement('div');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `sesi${index + 1}`;
                checkbox.value = `Sesi${index + 1}`;
                checkbox.disabled = !isEditable; // Disable checkbox if in view-only mode

                // Check the slot if it is marked as available
                if (jadwal && jadwal[`Sesi${index + 1}`] === 'tersedia') {
                    checkbox.checked = true;
                }

                const label = document.createElement('label');
                label.htmlFor = `sesi${index + 1}`;
                label.textContent = slot;

                slotDiv.appendChild(checkbox);
                slotDiv.appendChild(label);
                slotContainer.appendChild(slotDiv);
            });

            // Show or hide the save button based on the editable status
            const saveButton = document.getElementById('saveJadwalBtn');
            saveButton.style.display = isEditable ? 'block' : 'none';
        })
        .catch(error => {
            console.error("Error saat memuat jadwal:", error);
            slotContainer.innerHTML = '<p>Tidak ada jadwal untuk tanggal ini.</p>';
        });
}


// Fungsi untuk memuat slot yang sudah ada dalam jadwal
function loadExistingSlots(jadwal) {
    const slotContainer = document.getElementById('slotContainer');

    // Definisikan waktu slot yang tersedia
    const slotTimes = ["07:00 - 09:00", "09:00 - 11:00", "11:00 - 13:00", "15:00 - 17:00", "19:00 - 21:00"];

    // Loop untuk membuat checkbox untuk setiap slot
    slotTimes.forEach((slot, index) => {
        const slotDiv = document.createElement('div');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `sesi${index + 1}`;
        checkbox.value = `Sesi${index + 1}`;

        // Cek apakah slot ini ada di jadwal dari database
        if (jadwal[`Sesi${index + 1}`] === 'tersedia') {
            checkbox.checked = true;
        }

        const label = document.createElement('label');
        label.htmlFor = `sesi${index + 1}`;
        label.textContent = slot;

        slotDiv.appendChild(checkbox);
        slotDiv.appendChild(label);
        slotContainer.appendChild(slotDiv);
    });
}

// Fungsi untuk memuat slot kosong jika jadwal tidak ditemukan
function loadEmptySlots() {
    const slotContainer = document.getElementById('slotContainer');

    // Definisikan waktu slot yang tersedia
    const slotTimes = ["07:00 - 09:00", "09:00 - 11:00", "11:00 - 13:00", "15:00 - 17:00", "19:00 - 21:00"];

    // Loop untuk membuat checkbox untuk setiap slot
    slotTimes.forEach((slot, index) => {
        const slotDiv = document.createElement('div');
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `sesi${index + 1}`;
        checkbox.value = `Sesi${index + 1}`;

        // Checkbox dibiarkan tidak tercentang karena tidak ada data yang tersedia
        const label = document.createElement('label');
        label.htmlFor = `sesi${index + 1}`;
        label.textContent = slot;

        slotDiv.appendChild(checkbox);
        slotDiv.appendChild(label);
        slotContainer.appendChild(slotDiv);
    });
}


//fungsi ubtuk menyimpan jadwal
// Fungsi untuk menyimpan jadwal dengan validasi SweetAlert
function saveAllJadwal(trainerId, date, trainerName) {
    const checkboxes = document.querySelectorAll('#slotContainer input[type="checkbox"]');
    const jadwalData = [];

    // Mengumpulkan slot yang dipilih
    checkboxes.forEach((checkbox, index) => {
        if (checkbox.checked) {
            jadwalData.push(`Sesi${index + 1}`);
        }
    });

    // Validasi menggunakan SweetAlert
    if (jadwalData.length === 0) {
        // Jika tidak ada slot yang dipilih, tampilkan peringatan
        Swal.fire({
            icon: 'warning',
            title: 'Tidak ada slot yang dipilih',
            text: 'Silakan pilih minimal satu slot sebelum menyimpan jadwal!',
        });
        return;
    }

    // Menampilkan konfirmasi SweetAlert sebelum menyimpan
    Swal.fire({
        title: 'Konfirmasi Penyimpanan',
        text: `Anda yakin ingin menyimpan jadwal untuk tanggal ${date}? karena anda tidak dapat membatalkan setelah diisi`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#40ce20',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi, kirim data ke server
            fetch('/saveJadwal', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ trainer_id: trainerId, date: date, nama_pt: trainerName, slots: jadwalData }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Tampilkan pesan sukses menggunakan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Jadwal Disimpan',
                            text: 'Jadwal berhasil disimpan!',
                        });

                        // Nonaktifkan semua checkbox
                        checkboxes.forEach((checkbox) => {
                            checkbox.disabled = true;
                        });

                        // Sembunyikan tombol "Simpan Jadwal"
                        const saveButton = document.getElementById('saveJadwalBtn');
                        saveButton.style.display = 'none';

                        // Perbarui kalender untuk mencerminkan perubahan
                        updateCalendar(trainerId, activeMonth, activeYear);

                        // Tutup modal
                        closeJadwalModal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan Jadwal',
                            text: data.message,
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error saat menyimpan jadwal:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan saat menyimpan jadwal.',
                    });
                });
        }
    });
}


// Tambahkan logika untuk mengosongkan sesi
function clearJadwal(trainerId, date) {
    console.log("Menghapus jadwal untuk Trainer ID:", trainerId, "Tanggal:", date);

    fetch('/clearJadwal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ trainer_id: trainerId, date: date })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Jadwal berhasil dikosongkan!");
                updateCalendar(trainerId, activeMonth, activeYear);
            } else {
                alert("Gagal menghapus jadwal: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menghapus jadwal.");
        });
}

// Fungsi untuk memperbarui kalender berdasarkan bulan dan tahun
function updateCalendar(trainerId, month, year) {
    fetch(`/getTrainerSchedule/${trainerId}`)
        .then(response => response.json())
        .then(schedule => {
            console.log("Data jadwal terbaru setelah simpan atau hapus:", schedule);
            generateCalendar(trainerId, selectedTrainerName, month, year, schedule);
        })
        .catch(error => console.error("Gagal memperbarui kalender:", error));
}
// Modifikasi fungsi updateCalendarAfterSave
function updateCalendarAfterSave(trainerId) {
    fetch(`/getTrainerSchedule/${trainerId}`)
        .then(response => response.json())
        .then(schedule => {
            console.log("Data jadwal terbaru setelah simpan:", schedule);

            // Tetap pada bulan & tahun aktif
            generateCalendar(trainerId, selectedTrainerName, activeMonth, activeYear, schedule);
        })
        .catch(error => {
            console.error("Gagal memperbarui kalender setelah simpan:", error);
        });
}

function editLatihan(idSesi, latihan) {
    document.getElementById('ID_Sesi').value = idSesi;
    document.getElementById('Latihan').value = latihan || '';
    const modal = document.getElementById('latihanModal');
    modal.style.display = 'block';
    modal.classList.remove('hide'); // Pastikan animasi keluar dihapus
    modal.classList.add('show'); // Tambahkan animasi masuk
    console.log('Data dikirim ke server:', { ID_Sesi: idSesi, Latihan: latihan });

}

function closeLatihanModal() {
    const modal = document.getElementById('latihanModal');
    modal.classList.remove('show'); // Hapus animasi masuk
    modal.classList.add('hide'); // Tambahkan animasi keluar
    setTimeout(() => {
        modal.style.display = 'none';
    }, 500); // Tunggu transisi selesai
}

function saveLatihan() {
    const idSesi = document.getElementById('ID_Sesi').value;
    const latihan = document.getElementById('Latihan').value.trim();

    // Jika belum diisi, tampilkan alert SweetAlert
    if (!idSesi || !latihan) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap isi data latihan dengan benar sebelum menyimpan!',
        });
        return;
    }

    // Tampilkan SweetAlert konfirmasi sebelum menyimpan
    Swal.fire({
        title: 'Konfirmasi Pengisian',
        text: `Apakah Anda yakin ingin menyimpan latihan ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#40ce20',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, lakukan fetch untuk menyimpan data
            console.log('Mengirim data:', { ID_Sesi: idSesi, Latihan: latihan });

            fetch(`/updateLatihan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ID_Sesi: idSesi, Latihan: latihan }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Latihan berhasil diperbarui!',
                        });

                        // Perbarui isi kolom latihan di tabel
                        location.reload();
                        // Tutup modal
                        closeLatihanModal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal memperbarui latihan!',
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan latihan.',
                    });
                });
        }
    });
}

function filterByMember() {
    const selectedMember = document.getElementById('filterMember').value.toLowerCase();
    const rows = document.querySelectorAll('.booking-table tbody tr');

    rows.forEach(row => {
        const memberName = row.getAttribute('data-member').toLowerCase();
        if (selectedMember === "" || memberName === selectedMember) {
            row.style.display = ""; // Tampilkan baris
        } else {
            row.style.display = "none"; // Sembunyikan baris
        }
    });
}

// function handleLogout() {
//         // Optional: Tampilkan SweetAlert untuk konfirmasi logout
//         Swal.fire({
//             title: 'Logout',
//             text: 'Are you sure you want to logout?',
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonText: 'Yes, Logout',
//             confirmButtonColor: '#40ce20',
//             cancelButtonText: 'Cancel',
//             cancelButtonColor: '#d33'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 // Redirect ke halaman login PT
//                 window.location.href = '/loginpt';
//             }
//         });
//     }

function handleLogout() {
    // Tampilkan SweetAlert untuk konfirmasi logout
    Swal.fire({
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Logout',
        confirmButtonColor: '#40ce20',
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            // Panggil AJAX untuk logout di backend
            $.ajax({
                url: '/logoutpt', // Ganti dengan URL endpoint logout Anda
                method: 'POST',
                success: function(response) {
                    if(response.success) {
                        // Jika logout sukses, redirect ke halaman login
                        window.location.href = '/loginpt'; // Ganti dengan URL halaman login
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Logout gagal!',
                            icon: 'error',
                            confirmButtonText: 'Okay'
                        });
                    }
                }
            });
        }
    });
}


 // Fungsi konfirmasi SweetAlert sebelum submit form
 function confirmSessionDone(idSesi) {
    Swal.fire({
        title: 'Konfirmasi Sesi',
        text: "Apakah Anda yakin sesi ini sudah selesai?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#40ce20',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Selesai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user mengonfirmasi, kirimkan form dengan ID Sesi
            const form = document.createElement('form');
            form.action = `/PT/confirmSession/${idSesi}`;
            form.method = 'POST';
            document.body.appendChild(form);
            form.submit();
        }
    });
}
// Function to display success message
function displaySuccessMessageSelesai() {
    Swal.fire({
        title: 'Berhasil!',
        text: 'Sesi telah selesai!',
        icon: 'success',
        confirmButtonColor: '#40ce20'
    });
}

// Call success message if success flashdata is set
document.addEventListener('DOMContentLoaded', function () {
    if (successMessageS) {
        displaySuccessMessageSelesai();
    }
});

$(document).ready(function () {
    var table = $('#bookingTable').DataTable({
        pageLength: 8,
        lengthChange: false,
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

    // Filter berdasarkan Bulan
    $('#filter-tglmulai').on('change', function () {
        var selectedMonth = $(this).val(); // Format: YYYY-MM
        if (selectedMonth) {
            table.rows().every(function () {
                var rowData = this.data();
                var rowDate = rowData[2]; // Ambil data kolom tanggal
                var rowDateObj = new Date(rowDate.split('-').reverse().join('-')); // Konversi ke Date (dd-mm-yyyy ke yyyy-mm-dd)
                var filterMonth = new Date(selectedMonth + '-01'); // Bulan pertama di filter

                // Periksa apakah tanggal ada di bulan yang dipilih
                if (
                    rowDateObj.getFullYear() === filterMonth.getFullYear() &&
                    rowDateObj.getMonth() === filterMonth.getMonth()
                ) {
                    $(this.node()).show(); // Tampilkan baris
                } else {
                    $(this.node()).hide(); // Sembunyikan baris
                }
            });
        } else {
            table.rows().show(); // Reset jika filter kosong
        }
        table.draw(false); // Perbarui tabel tanpa reset pagination
    });
});
