// Fungsi untuk membuka modal
document.getElementById('openPTModal').addEventListener('click', function () {
    document.getElementById('ptModal').style.display = 'flex';
  });
  
  // Fungsi untuk menutup modal
  function closeForm() {
    document.getElementById('ptModal').style.display = 'none';
  }

// Fungsi untuk membuka modal edit dan mengisi data
function openEditModal(id, nama, foto, prestasi, spesialisasi, harga) {
  document.getElementById('editModal').style.display = 'block';


  // Isi data ke dalam form modal
  document.getElementById('editID_PT').value = id;
  document.getElementById('editNama_PT').value = nama;
  document.getElementById('editPrestasi').value = prestasi;
  document.getElementById('editSpesialisasi').value = spesialisasi;
  document.getElementById('editHarga_Sesi').value = harga;
  document.getElementById('editFoto_PT').src = '/path/to/uploads/' + foto;
}
function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
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

    document.getElementById('modalKalender').style.display = 'block';
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

function closeKalenderModal() {
    document.getElementById('modalKalender').style.display = 'none';
}


// Fungsi untuk menutup modal kalender
function closeKalenderModal() {
  document.getElementById('modalKalender').style.display = 'none';
}

// Fungsi untuk membuka modal jadwal
function openJadwalModal(trainerId, date, viewOnly = false) {
    selectedDate = date;
    console.log("Membuka modal jadwal untuk tanggal:", date, "dan trainerId:", trainerId, "Mode View:", viewOnly);

    // Memuat slot dengan parameter view-only
    loadSlots(trainerId, date, viewOnly);

    const modal = document.getElementById('modalJadwal');
    modal.style.display = 'block';

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
  document.getElementById('modalJadwal').style.display = 'none';
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

        if (isAvailable) {
            dateCell.classList.add(isPastDate ? 'past-available-day' : 'available-day');
        } else if (isPastDate) {
            dateCell.classList.add('past-unavailable-day');
        } else {
            dateCell.classList.add('unavailable-day');
        }

        if (currentDate === today.toISOString().split('T')[0]) {
            dateCell.classList.add('today');
        }

        dateCell.onclick = () => {
            if (isPastDate) {
                openJadwalModal(trainerId, currentDate, true); // Mode view-only
            } else {
                openJadwalModal(trainerId, currentDate, false); // Mode edit
            }
        };

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




// Fungsi untuk memuat jadwal (dengan memisahkan antara jadwal yang sudah ada dan yang kosong)
function loadSlots(trainerId, date, viewOnly = false) {
    console.log(`Memuat slot untuk Trainer ID: ${trainerId} pada tanggal: ${date} (ViewOnly: ${viewOnly})`);

    const slotContainer = document.getElementById('slotContainer');
    slotContainer.innerHTML = '';

    fetch(`/getJadwal/${trainerId}/${date}`)
        .then(response => response.json())
        .then(jadwal => {
            const slotTimes = ["07:00 - 09:00", "09:00 - 11:00", "11:00 - 13:00", "15:00 - 17:00", "19:00 - 21:00"];
            slotTimes.forEach((slot, index) => {
                const slotDiv = document.createElement('div');
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `sesi${index + 1}`;
                checkbox.value = `Sesi${index + 1}`;
                checkbox.disabled = viewOnly;

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
function saveAllJadwal(trainerId, date, trainerName) {
    const checkboxes = document.querySelectorAll('#slotContainer input[type="checkbox"]');
    const jadwalData = [];

    checkboxes.forEach((checkbox, index) => {
        if (checkbox.checked) {
            jadwalData.push(`Sesi${index + 1}`);
        }
    });

    fetch('/saveJadwal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ trainer_id: trainerId, date: date, nama_pt: trainerName, slots: jadwalData })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Jadwal berhasil disimpan!");
                updateCalendar(trainerId, activeMonth, activeYear);
                closeJadwalModal();
            } else {
                alert("Gagal menyimpan jadwal: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error saat menyimpan jadwal:", error);
            alert("Terjadi kesalahan saat menyimpan jadwal.");
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

