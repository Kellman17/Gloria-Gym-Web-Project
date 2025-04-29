let activeMonth = new Date().getMonth(); // Bulan aktif (0-11)
let activeYear = new Date().getFullYear(); // Tahun aktif
let trainerId = null;
let membershipStartDate = null; // Tanggal mulai membership
let membershipEndDate = null; // Tanggal berakhir membership
let usedSessions = 0; // Variabel untuk melacak jumlah sesi yang sudah digunakan
let selectedSessions = []; // Menyimpan sesi yang dipilih
let selectedDate = null; // Variabel global untuk menyimpan tanggal yang dipilih
const maxSessions = 8;
const formatDate = (date) => date.toISOString().split('T')[0];


// Fungsi untuk membuka form pembelian membership
function openForm(membershipType, duration, price, idMembership) {
    console.log("Opening form with data:", { membershipType, duration, price, idMembership });

    document.getElementById('popupForm').style.display = 'flex';
    document.getElementById('membershipType').value = membershipType;
    document.getElementById('membershipDuration').value = duration;
    document.getElementById('membershipPrice').dataset.basePrice = price; // Base price tracking
    updateTotalPrice(parseInt(price, 10)); // Format harga awal
    document.querySelector('input[name="ID_Membership"]').value = idMembership;

    console.log("Base price set:", price); // Tambahkan log di sini

    // Reset checkbox PT dan harga PT saat form dibuka
    document.getElementById('use_pt').checked = false;
    document.getElementById('Harga_PT').value = '0'; // Reset harga PT
    document.getElementById('Pakai_PT').value = 'tidak'; // Reset status pakai PT

    trainerId = null;
    trainerName = null;

    const usePtRow = document.getElementById('use_pt_row');
    if (membershipType === 'Bulanan_Gym') {
        usePtRow.style.display = 'block';
    } else {
        usePtRow.style.display = 'none';
    }

    // Calculate membership start and end dates
    const startDate = new Date();
    membershipStartDate = new Date(startDate);
    membershipStartDate.setDate(membershipStartDate.getDate()); // Tanggal mulai 1 hari sebelum

    const endDate = new Date(startDate);
    endDate.setDate(endDate.getDate() + parseInt(duration, 10));
    membershipEndDate = new Date(endDate);

    // Format dates
    document.getElementById('tgl_berlaku').value = formatDate(membershipStartDate); // Update tanggal mulai
    document.getElementById('tgl_berakhir').value = formatDate(membershipEndDate); // Update tanggal berakhir
}


function closeForm() {
    // Mengecek apakah jenis membership yang dibatalkan adalah "Bulanan_Gym"
    const membershipType = document.getElementById('membershipType').value;

    if (membershipType === 'Bulanan_Gym') {
        // Hanya reset sesi jika membership adalah Bulanan Gym
        resetSessions();
    }
    document.getElementById('popupForm').style.display = 'none';
}

function resetForm() {
    document.getElementById('popupForm').reset();
    document.getElementById("use_pt").checked = false;
    trainerId = null;
    trainerName = null;
    selectedSessions = [];
    updateSavedSessionsCount();
    console.log("Form berhasil direset.");
}

function updateTotalPrice(basePrice, trainerPrice = 0) {
    // Pastikan input adalah angka
    const totalPrice = parseInt(basePrice, 10) + parseInt(trainerPrice, 10);

    console.log("Updating total price:", { basePrice, trainerPrice, totalPrice }); // Tambahkan log di sini

    // Format harga sesuai format rupiah
    const formattedPrice = totalPrice.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
    });

    // Update harga ke UI
    document.getElementById('membershipPrice').value = formattedPrice;
    console.log("Harga total diperbarui:", formattedPrice);
    // Update hidden input untuk submit
    document.querySelector('input[name="Total_Harga"]').value = totalPrice; 
    console.log("Harga total diperbarui:", totalPrice);
    document.getElementById('paymentAmount').innerText = formattedPrice;
}


// Event listener untuk tombol pembelian
document.querySelectorAll('.btn-buy').forEach(button => {
    button.addEventListener('click', function () {
        const membershipType = this.dataset.membershipType;
        const duration = this.dataset.duration;
        const price = this.dataset.price;
        const idMembership = this.dataset.id;
        openForm(membershipType, duration, price, idMembership);
    });
});


// Fungsi untuk menangani proses submit form
// Event listener untuk tombol submit form (secara langsung ke backend)
document.querySelector("form").addEventListener("submit", function (event) {
    const usePtCheckbox = document.getElementById('use_pt');
    const basePrice = parseInt(document.getElementById('membershipPrice').dataset.basePrice, 10); // Harga dasar
    let finalPrice = basePrice; // Harga dasar

    if (usePtCheckbox.checked) {
        const ptPrice = parseInt(document.getElementById('Harga_PT').value, 10); // Harga PT
        if (isNaN(ptPrice)) {
            console.error("Harga PT tidak valid");
            event.preventDefault(); // Hentikan submit form jika ada kesalahan
            return;
        }
        finalPrice += ptPrice; // Tambahkan harga PT ke harga dasar
    }

    // Update harga total di form (tanpa format mata uang)
    document.getElementById('membershipPrice').value = finalPrice; // Hanya nilai angka, tanpa format

    // Kirim status 'use_pt' dan harga ke form
    document.querySelector('input[name="Pakai_PT"]').value = usePtCheckbox.checked ? 'ya' : 'tidak';

    // Kirim harga total sebagai angka
    document.querySelector('input[name="Total_Harga"]').value = finalPrice;  // Pastikan harga total dikirim sebagai integer
    console.log("Total Harga yg dikirim:", finalPrice);
});

// Menangani munculnya modal pemilihan PT
document.getElementById('use_pt').addEventListener('change', function () {
    console.log("use_pt checkbox status:", this.checked); // Tambahkan log di sini
    console.log("Harga_PT:", document.getElementById('Harga_PT').value);

    if (this.checked) {
        // Jika checkbox dicentang, buka modal untuk memilih PT
        document.getElementById('selectPTModal').style.display = 'flex';
    } else {
        // Jika checkbox di-uncheck, reset trainer dan sesi
        trainerId = null;
        trainerName = null;
        // Reset harga PT
        document.getElementById('Harga_PT').value = '0'; // Set harga PT ke 0
        document.getElementById('Pakai_PT').value = 'tidak'; // Set status pakai PT
        // Reset sesi yang sudah disimpan
        resetSessions();

        // Perbarui harga ke harga dasar (tanpa harga PT)
        const basePrice = document.getElementById('membershipPrice').dataset.basePrice;
        updateTotalPrice(basePrice);

        console.log("Personal Trainer dan sesi berhasil direset.");
    }
});


function closePTModal() {
    console.log("closePTModal dipanggil");
    document.getElementById("selectPTModal").style.display = "none";
}

// Fungsi untuk memilih PT dan mengatur `trainerId` yang dipilih
function selectTrainer(id, name, pricePer8Sessions) {
    trainerId = id;
    trainerName = name;
    console.log("Trainer dipilih:", trainerId, trainerName, pricePer8Sessions);

    alert(`Anda memilih ${trainerName} sebagai personal trainer Anda.`);

    // Update harga PT di hidden input
    document.getElementById('Harga_PT').value = pricePer8Sessions; // Ganti dengan harga PT yang sesuai
    document.getElementById('Pakai_PT').value = 'ya'; // Set status pakai PT

    // Log harga PT yang dipilih
    console.log("Harga PT yang dipilih:", pricePer8Sessions);

    // Update harga total dengan harga trainer
    const basePrice = parseInt(document.getElementById('membershipPrice').dataset.basePrice, 10);
    updateTotalPrice(basePrice, pricePer8Sessions); // Update harga dengan harga PT

    // Tutup modal pemilihan PT
    closePTModal();

    // Muat jadwal trainer untuk bulan dan tahun aktif
    loadTrainerSchedule(trainerId, trainerName, activeMonth, activeYear);
}



// Memuat jadwal PT dari server
function loadTrainerSchedule(id, name, month = activeMonth, year = activeYear) {
    const url = `/getTrainerScheduleMonthly/${id}/${month + 1}/${year}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(schedule => {
            console.log("Jadwal yang diterima dari server:", schedule);
            if (!Array.isArray(schedule) || schedule.length === 0) {
                console.warn("Jadwal kosong diterima dari server.");
                alert("Tidak ada jadwal yang dapat ditampilkan untuk bulan ini.");
                return;
            }

            // Panggil generateCalendar
            openKalenderModal(trainerId, trainerName, schedule);
        })
        .catch(error => {
            console.error("Error saat memuat jadwal:", error.message);
            alert("Terjadi kesalahan saat memuat jadwal: " + error.message);
        });
}




// Fungsi untuk membuka modal kalender dan mengatur nama PT
function openKalenderModal(trainerId, trainerName, schedule) {
    console.log("Memanggil openKalenderModal dengan:", { trainerId, trainerName, schedule });

    if (!schedule || schedule.length === 0) {
        console.error("Schedule kosong atau tidak sesuai format:", schedule);
        alert("Tidak ada jadwal yang dapat ditampilkan untuk bulan ini.");
        return;
    }

    const trainerNameElement = document.getElementById('trainerName');
    if (trainerNameElement) {
        trainerNameElement.textContent = trainerName; // Tetapkan nama trainer
    } else {
        console.error("Element dengan ID 'trainerName' tidak ditemukan.");
    }

    const monthYearLabel = `${new Date(activeYear, activeMonth).toLocaleString('id-ID', { month: 'long' })} ${activeYear}`;
    const calendarMonthYearElement = document.getElementById('calendarMonthYear');
    if (calendarMonthYearElement) {
        calendarMonthYearElement.textContent = monthYearLabel; // Tetapkan bulan dan tahun
    } else {
        console.error("Element dengan ID 'calendarMonthYear' tidak ditemukan.");
    }

    const modalKalenderElement = document.getElementById('modalKalender');
    if (modalKalenderElement) {
        modalKalenderElement.style.display = 'block'; // Tampilkan modal
    } else {
        console.error("Element dengan ID 'modalKalender' tidak ditemukan.");
    }

    // Panggil generateCalendar
    generateCalendar(trainerId, trainerName, activeMonth, activeYear, schedule);

    // Tutup semua modal sebelumnya
    closeJadwalModal();
}

function closeKalenderModal() {
    const modalKalender = document.getElementById('modalKalender');
    if (modalKalender) {
        modalKalender.style.display = 'none'; // Sembunyikan modal
        modalKalender.setAttribute('aria-hidden', 'true'); // Aksesibilitas: setel sebagai tersembunyi
        console.log("Modal kalender berhasil ditutup.");
    } else {
        console.error("Modal kalender tidak ditemukan.");
    }
}


function navigateMonth(direction) {
    activeMonth += direction;

    if (activeMonth > 11) {
        activeMonth = 0;
        activeYear++;
    } else if (activeMonth < 0) {
        activeMonth = 11;
        activeYear--;
    }

    console.log(`Navigasi ke bulan baru: ${activeMonth + 1}, Tahun: ${activeYear}`);

    if (!trainerId || !trainerName) {
        alert("Trainer belum dipilih. Pastikan trainer dipilih terlebih dahulu.");
        return;
    }

    loadTrainerSchedule(trainerId, trainerName, activeMonth, activeYear);
}

// Event listener untuk navigasi bulan
let navigating = false; // Menambahkan flag untuk mencegah duplikasi


// Fungsi untuk membuat kalender dengan ketersediaan sesi
function generateCalendar(trainerId, trainerName, month, year, schedule) {
    console.log("Generating calendar for:", { trainerId, trainerName, month, year });

    const calendarBody = document.getElementById('calendarBody');
    if (!calendarBody) {
        console.error("calendarBody element not found");
        return;
    }else {
        console.log("calendarBody element ditemukan.");
    }

        console.log("Schedule diterima:", schedule);


    calendarBody.innerHTML = ''; // Clear previous content

    const daysInMonth = new Date(year, month + 1, 0).getDate(); // Total days in the month
    const firstDay = new Date(year, month, 1).getDay(); // Weekday of the first day
    const today = new Date();

    const adjustedMembershipStartDate = membershipStartDate
        ? new Date(membershipStartDate.getTime())
        : null;
    if (adjustedMembershipStartDate) {
        adjustedMembershipStartDate.setDate(adjustedMembershipStartDate.getDate());
    }

    let row = document.createElement('tr');

    // Fill empty cells before the first day
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('td');
        row.appendChild(emptyCell);
    }

    // Iterate over all days in the month
    for (let date = 1; date <= daysInMonth; date++) {
        const dateCell = document.createElement('td');
        dateCell.textContent = date;

        // Format date as YYYY-MM-DD
        const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
        const dateObject = new Date(currentDate);

        const isToday = today.toDateString() === dateObject.toDateString();
        const isPast = dateObject < isToday;

        const inMembershipPeriod =
            adjustedMembershipStartDate && membershipEndDate &&
            dateObject >= adjustedMembershipStartDate && dateObject <= membershipEndDate;

        const scheduleForDate = schedule.find(item => item.Tanggal === currentDate);

        console.log({ currentDate, scheduleForDate, inMembershipPeriod, isPast });

        // Reset classes
        dateCell.classList.remove('available-day', 'unavailable-day', 'today', 'selected-day');

        // Apply conditions to set class
        if (scheduleForDate) {
            // Semua slot pada hari tersebut sudah penuh atau tidak tersedia
            const allSlotsFull =
                Array.isArray(scheduleForDate.sessions) &&
                scheduleForDate.sessions.every(slot => slot.status !== "tersedia");

            if (scheduleForDate.success === false || allSlotsFull) {
                // Warnai merah jika semua slot penuh atau tidak tersedia
                dateCell.classList.add("unavailable-day");
            } else if (scheduleForDate.Status === "tersedia" && inMembershipPeriod && !isPast) {
                // Warnai hijau jika ada slot yang tersedia
                dateCell.classList.add("available-day");
                dateCell.onclick = () => selectDate(currentDate);
            } else {
                // Default ke warna merah jika tidak ada kondisi yang terpenuhi
                dateCell.classList.add("unavailable-day");
            }
        } else {
            // Jika tidak ada data jadwal untuk tanggal ini
            dateCell.classList.add("unavailable-day");
        }



        // Highlight today's date
        if (isToday) {
            dateCell.classList.add('today'); // Blue
        }

        row.appendChild(dateCell);

        // Append row if the week is complete or it's the last date
        if ((firstDay + date) % 7 === 0 || date === daysInMonth) {
            calendarBody.appendChild(row);
            row = document.createElement('tr');
        }
    }
}


// Fungsi untuk memilih tanggal
function selectDate(date) {
    console.log("Tanggal yang dipilih:", date);

    // Validasi jika sesi sudah penuh
    if (usedSessions + selectedSessions.length >= maxSessions) {
        alert(`Anda telah mencapai batas maksimum ${maxSessions} sesi.`);
        return;
    }

    // Validasi ID trainer
    if (!trainerId) {
        alert("Trainer belum dipilih. Silakan pilih personal trainer terlebih dahulu.");
        return;
    }

    const calendarCells = document.querySelectorAll('#calendarBody td');

    // Hapus kelas 'selected-day' dari semua sel kalender
    calendarCells.forEach(cell => {
        cell.classList.remove('selected-day');
    });

    const selectedCell = Array.from(calendarCells).find(cell => {
        const cellDate = `${activeYear}-${String(activeMonth + 1).padStart(2, '0')}-${String(cell.textContent).padStart(2, '0')}`;
        console.log(`Memeriksa cellDate: ${cellDate} dengan date: ${date}`);
        return cellDate === date && cell.classList.contains('available-day');
    });

    if (!selectedCell) {
        console.error("Elemen sel untuk tanggal yang dipilih tidak ditemukan atau tidak tersedia. Tanggal:", date);
        alert("Tanggal ini tidak tersedia.");
        return;
    }

    selectedCell.classList.add('selected-day');
    console.log("Elemen yang dipilih:", selectedCell);

    selectedDate = date; // Simpan tanggal yang dipilih

    openJadwalModal(date); // Panggil modal jadwal
}

// Fungsi untuk menutup modal kalender
// Atur selectedDate dengan nilai tanggal dari elemen teks, bukan elemen DOM
function openJadwalModal(date) {
    console.log("[DEBUG] Membuka modal jadwal untuk tanggal:", date);
    const selectedDateElement = document.getElementById('selectedDate');
    selectedDateElement.textContent = selectedDate;

    if (!trainerId) {
        alert("Trainer belum dipilih. Silakan pilih personal trainer terlebih dahulu.");
        return;
    }

    selectedDate = date; // Simpan tanggal yang dipilih

    const memberId = document.querySelector('input[name="ID_Member"]').value;

    fetchTotalSessions(memberId).then((totalSessions) => {
        usedSessions = totalSessions;
        console.log("[DEBUG] Total sesi yang telah tersimpan:", usedSessions);

        const url = `/getTrainerScheduleDaily/${trainerId}/${date}`;
        console.log("[DEBUG] Request URL:", url);

        fetch(url)
            .then(response => {
                console.log("[DEBUG] Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP Error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("[DEBUG] Data dari server:", data);

                if (data.success && Array.isArray(data.sessions)) {
                    renderSlots(data.sessions);
                    document.getElementById("modalJadwal").style.display = "block";
                } else {
                    alert(data.message || "Slot sesi sudah full booked untuk tanggal ini.");
                }
            })
            .catch(err => {
                console.error("[DEBUG] Error fetching slots:", err);
                alert("Terjadi kesalahan saat memuat slot. Coba lagi nanti.");
            });
    }).catch(err => {
        console.error("[DEBUG] Error fetching total sessions:", err);
        alert("Terjadi kesalahan saat memuat data sesi total. Coba lagi nanti.");
    });
}


function closeJadwalModal() {
    const modalJadwal = document.getElementById('modalJadwal');
    if (modalJadwal) {
        modalJadwal.style.display = 'none'; // Sembunyikan modal
        modalJadwal.setAttribute('aria-hidden', 'true'); // Aksesibilitas: setel sebagai tersembunyi
    }
    console.log("[DEBUG] Modal jadwal berhasil ditutup.");
}



// Memuat slot waktu untuk tanggal tertentu
function loadSlots(trainerId, date) {
    fetch(`/getTrainerScheduleDaily/${trainerId}/${date}`)
        .then(response => {
            console.log("[DEBUG] Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("[DEBUG] Response data:", data);
            if (data.success) {
                renderSlots(data.sessions);
            } else {
                alert(data.message || "Tidak ada slot tersedia.");
            }
        })
        .catch(err => {
            console.error("[DEBUG] Error fetching slots:", err);
            alert("Terjadi kesalahan saat memuat slot.");
        });
}
function renderSlots(slots) {
    const slotContainer = document.getElementById("slotContainer");
    if (!slotContainer) {
        console.error("[DEBUG] slotContainer tidak ditemukan.");
        return;
    }

    slotContainer.innerHTML = ""; // Bersihkan kontainer

    slots.forEach(slot => {
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.disabled = slot.status !== "tersedia"; // Disabled jika tidak tersedia
        checkbox.id = slot.time;
        checkbox.value = slot.time;

        if (slot.status === "booked" || slot.status === "paid") {
            checkbox.disabled = true; // Disable checkbox untuk slot booked dan paid
        }

        checkbox.addEventListener("change", function () {
            toggleSession(selectedDate, slot.time); // Kirim waktu slot
        });

        const label = document.createElement("label");
        label.htmlFor = slot.time;
        label.textContent = `${slot.time} (${slot.status === "booked" ? "Sudah dipesan" : "Tersedia"})`;

        const container = document.createElement("div");
        container.appendChild(checkbox);
        container.appendChild(label);

        slotContainer.appendChild(container);
    });
}



// Fungsi untuk toggle sesi (tambahkan alert jumlah sesi)
function toggleSession(date, time) {
    if (!date) {
        console.error("[DEBUG] Date tidak tersedia!");
        return;
    }

    console.log("[DEBUG] Toggle session for:", { date, time });

    const sessionExists = selectedSessions.some(
        session => session.date === date && session.time === time
    );

    // Tambah atau hapus sesi dari daftar yang dipilih
    if (sessionExists) {
        selectedSessions = selectedSessions.filter(
            session => !(session.date === date && session.time === time)
        );
    } else {
        if (selectedSessions.length + usedSessions >= maxSessions) {
            alert(`Anda hanya dapat memilih hingga ${maxSessions} sesi.`);
            return;
        }
        selectedSessions.push({ date, time });
    }

    console.log("[DEBUG] Updated selected sessions:", selectedSessions);
    updateSavedSessionsCount(); // Update tampilan
}


// Fungsi untuk menyimpan sesi
// function saveSessions() {
//     const memberId = document.querySelector("input[name='ID_Member']").value;
//     const memberName = document.querySelector("input[name='Nama_Member']").value;

//     if (!trainerId || !trainerName) {
//         alert("Trainer belum dipilih. Silakan pilih personal trainer.");
//         return;
//     }
//     if (!memberId || !memberName) {
//         alert("ID atau Nama Member tidak valid. Silakan login ulang.");
//         return;
//     }

//     // Filter sesi valid
//     const validSessions = selectedSessions.map((session) => ({
//         date: session.date,
//         time: session.time, // Kirim format waktu (misal "09:00 - 11:00")
//         Nama_PT: trainerName,
//         Nama_Member: memberName,
//     }));

//     console.log("[DEBUG] Data sesi yang dikirim ke server:", {
//         member_id: memberId,
//         trainer_id: trainerId,
//         Nama_PT: trainerName,
//         Nama_Member: memberName,
//         sessions: validSessions,
//     });

//     if (validSessions.length === 0) {
//         alert("Tidak ada sesi valid untuk disimpan.");
//         return;
//     }

//     fetch("/saveSessions", {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({
//             member_id: memberId,
//             trainer_id: trainerId,
//             Nama_PT: trainerName,
//             Nama_Member: memberName,
//             sessions: validSessions,
//         }),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 throw new Error(`HTTP Error ${response.status}`);
//             }
//             return response.json();
//         })
//         .then((data) => {
//             if (data.success) {
//                 alert("Sesi berhasil disimpan.");
//                 selectedSessions = [];
//                 updateSavedSessionsCount();
//                 closeJadwalModal();
//             } else {
//                 alert(`Gagal menyimpan sesi: ${data.message}`);
//             }
//         })
//         .catch((error) => {
//             console.error("[DEBUG] Error saat menyimpan sesi:", error);
//             alert("Terjadi kesalahan saat menyimpan sesi.");
//         });
// }
function saveSessions() {
    const memberId = document.querySelector("input[name='ID_Member']").value;
    const memberName = document.querySelector("input[name='Nama_Member']").value;

    if (!trainerId || !trainerName) {
        Swal.fire({
            icon: "error",
            title: "Trainer Belum Dipilih",
            text: "Silakan pilih personal trainer sebelum melanjutkan.",
            confirmButtonText: "OK",
        });
        return;
    }

    if (!memberId || !memberName) {
        Swal.fire({
            icon: "error",
            title: "Data Tidak Valid",
            text: "ID atau Nama Member tidak valid. Silakan login ulang.",
            confirmButtonText: "OK",
        });
        return;
    }

    // Filter sesi valid
    const validSessions = selectedSessions.map((session) => ({
        date: session.date,
        time: session.time, // Kirim format waktu (misal "09:00 - 11:00")
        Nama_PT: trainerName,
        Nama_Member: memberName,
    }));

    console.log("[DEBUG] Data sesi yang dikirim ke server:", {
        member_id: memberId,
        trainer_id: trainerId,
        Nama_PT: trainerName,
        Nama_Member: memberName,
        sessions: validSessions,
    });

    if (validSessions.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Tidak Ada Sesi Valid",
            text: "Silakan pilih sesi yang valid sebelum melanjutkan.",
            confirmButtonText: "OK",
        });
        return;
    }

    fetch("/saveSessions", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            member_id: memberId,
            trainer_id: trainerId,
            Nama_PT: trainerName,
            Nama_Member: memberName,
            sessions: validSessions,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Disimpan",
                    text: "Sesi Anda berhasil disimpan.",
                    confirmButtonText: "OK",
                }).then(() => {
                    selectedSessions = [];
                    updateSavedSessionsCount();
                    closeJadwalModal();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Menyimpan Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch((error) => {
            console.error("[DEBUG] Error saat menyimpan sesi:", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat menyimpan sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}


// Fungsi untuk reset sesi yang dipilih
document.getElementById('resetSessionButton').addEventListener('click', resetSessions);


// Fungsi untuk reset seluruh sesi (frontend)
// function resetSessions() {
//     const memberId = document.querySelector('input[name="ID_Member"]').value;

//     if (!memberId) {
//         alert("ID Member tidak valid.");
//         return;
//     }

//     fetch('/resetSessions', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({ member_id: memberId }),
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 selectedSessions = []; // Clear the selected sessions
//                 updateSavedSessionsCount(); // Update the UI
//                 closeSavedSessionsModal();
//             } else {
//                 alert("Gagal mereset sesi: " + data.message);
//             }
//         })
//         .catch(error => {
//             console.error("Error saat mereset sesi:", error.message);
//         });
// }

function resetSessions() {
    const memberId = document.querySelector('input[name="ID_Member"]').value;

    if (!memberId) {
        Swal.fire({
            icon: "error",
            title: "ID Member Tidak Valid",
            text: "Silakan coba lagi.",
            confirmButtonText: "OK",
        });
        return;
    }

    fetch('/resetSessions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ member_id: memberId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedSessions = []; // Clear the selected sessions
                updateSavedSessionsCount(); // Update the UI
                closeSavedSessionsModal();
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Direset",
                    text: "Semua sesi Anda berhasil dihapus.",
                    confirmButtonText: "OK",
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Mereset Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch(error => {
            console.error("Error saat mereset sesi:", error.message);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat mereset sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}



function updateSavedSessionsCount() {
    const memberId = document.querySelector('input[name="ID_Member"]').value;
    fetch(`/getTotalSessions/${memberId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('remainingSessions').textContent = `Saved sessions: ${data.total_sessions}`;
        });
}

function fetchTotalSessions(memberId) {
    return fetch(`/getTotalSessions/${memberId}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                usedSessions = data.total_sessions; // Perbarui usedSessions
                console.log("[DEBUG] Total saved sessions:", usedSessions);
                return usedSessions;
            } else {
                console.warn("[DEBUG] Failed to fetch total sessions:", data.message);
                usedSessions = 0; // Default to 0 jika gagal
                return usedSessions;
            }
        })
        .catch((error) => {
            console.error("[DEBUG] Error fetching total sessions:", error);
            usedSessions = 0; // Default to 0 jika terjadi error
            return usedSessions;
        });
}

// Fungsi untuk membuka modal dan menampilkan sesi yang sudah dipesan
function showSavedSessions() {
    // Ambil nilai trainerId dan memberId (misalnya bisa diambil dari data elemen atau variabel global)
    const trainerID = trainerId;
    const memberId = document.querySelector('input[name="ID_Member"]').value; // Contoh mengambil dari elemen input

    // Periksa apakah trainerId dan memberId valid
    if (!trainerID || !memberId) {
        alert("Trainer ID atau Member ID tidak valid.");
        return;
    }

    // Lakukan fetch ke server untuk mengambil data sesi yang sudah dibooking
    fetch(`getSavedSessions/${trainerID}/${memberId}`)  // Gantilah dengan URL API yang sesuai
        .then(response => response.json())
        .then(data => {
            const savedSessionsContainer = document.getElementById('savedSessionsList');
            savedSessionsContainer.innerHTML = '';  // Bersihkan container sebelum menampilkan

            // Loop untuk menampilkan sesi yang sudah dipesan
            if (data.sessions && data.sessions.length > 0) {
                data.sessions.forEach(session => {
                    const row = document.createElement('tr');

                    // Kolom Tanggal
                    const dateCell = document.createElement('td');
                    const date = new Date(session.date);
                    const formattedDate = `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    dateCell.textContent = formattedDate;

                    // Kolom Sesi
                    const sessionCell = document.createElement('td');
                    sessionCell.textContent = session.time;

                    // Kolom Action (Button Batal)
                    const actionCell = document.createElement('td');
                    const cancelButton = document.createElement('button');
                    cancelButton.textContent = 'Batal';
                    cancelButton.classList.add('delete-session');
                    cancelButton.onclick = () => cancelSession(session); // Fungsi untuk membatalkan sesi

                    actionCell.appendChild(cancelButton);

                    // Tambahkan kolom ke baris
                    row.appendChild(dateCell);
                    row.appendChild(sessionCell);
                    row.appendChild(actionCell);

                    // Tambahkan baris ke tabel
                    savedSessionsContainer.appendChild(row);
                });
            } else {
                savedSessionsContainer.innerHTML = '<tr><td colspan="3">Tidak ada sesi yang sudah dipesan.</td></tr>';
            }

            // Tampilkan modal
            document.getElementById('modalSavedSessions').style.display = 'block';
        })
        .catch(error => console.error('Error fetching saved sessions:', error));
}

// document.getElementById("submitBtn").addEventListener("click", function (event) {
//     const membershipType = document.getElementById("membershipType").value;
//     const usePT = document.getElementById("use_pt").checked;
//     const paymentProof = document.querySelector("input[name='Bukti_Pembayaran']"); // Input file

//     // Validasi apakah file bukti pembayaran sudah diunggah
//     if (!paymentProof || !paymentProof.files || paymentProof.files.length === 0) {
//         event.preventDefault(); // Prevent form submission
//         alert("Harap unggah bukti pembayaran sebelum melanjutkan.");
//         return;
//     }

//     // Hanya lakukan validasi sesi jika membership type adalah "Bulanan_Gym" dan "Pakai PT"
//     if (membershipType === "Bulanan_Gym" && usePT) {
//         const memberId = document.querySelector("input[name='ID_Member']").value;

//         // Fetch jumlah sesi yang telah disimpan
//         fetch(`/getTotalSessions/${memberId}`)
//             .then((response) => {
//                 if (!response.ok) {
//                     throw new Error(`HTTP Error ${response.status}`);
//                 }
//                 return response.json();
//             })
//             .then((data) => {
//                 if (data.success) {
//                     const totalSessions = data.total_sessions;
//                     console.log("[DEBUG] Total sessions saved:", totalSessions);

//                     // Jika sesi kurang dari 8, prevent form submission
//                     if (totalSessions < 8) {
//                         event.preventDefault();
//                         alert("Anda harus memilih minimal 8 sesi sebelum melanjutkan pembelian.");
//                         location.reload();
//                         closeForm();
//                     } else {
//                         // Lanjutkan dengan submit form
//                         document.querySelector("form[action='/BuyMembership']").submit();
//                     }
//                 } else {
//                     event.preventDefault();
//                     alert("Gagal mendapatkan data sesi. Silakan coba lagi.");
//                 }
//             })
//             .catch((error) => {
//                 console.error("[DEBUG] Error fetching total sessions:", error);
//                 event.preventDefault();
//                 alert("Terjadi kesalahan saat memvalidasi jumlah sesi. Silakan coba lagi.");
//             });
//     } else {
//         // Jika bukan "Bulanan_Gym" atau tidak menggunakan PT, langsung submit form
//         document.querySelector("form[action='/BuyMembership']").submit();
//     }
// });

document.getElementById("submitBtn").addEventListener("click", async function (event) {
    const membershipType = document.getElementById("membershipType").value;
    const usePT = document.getElementById("use_pt").checked;
    const paymentProof = document.querySelector("input[name='Bukti_Pembayaran']"); // Input file

    // Prevent default form submission
    event.preventDefault();

    // Validasi apakah file bukti pembayaran sudah diunggah
    if (!paymentProof || !paymentProof.files || paymentProof.files.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Bukti Pembayaran Belum Diupload",
            text: "Harap unggah bukti pembayaran sebelum melanjutkan.",
            confirmButtonText: "OK",
        });
        return;
    }

    // Jika jenis membership adalah "Bulanan_Gym" dan menggunakan PT
    if (membershipType === "Bulanan_Gym" && usePT) {
        try {
            const memberId = document.querySelector("input[name='ID_Member']").value;

            // Fetch jumlah sesi yang telah disimpan
            const response = await fetch(`/getTotalSessions/${memberId}`);
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }

            const data = await response.json();
            if (data.success) {
                const totalSessions = data.total_sessions;
                console.log("[DEBUG] Total sessions saved:", totalSessions);

                // Jika sesi kurang dari 8, prevent form submission
                if (totalSessions < 8) {
                    Swal.fire({
                        icon: "warning",
                        title: "Sesi Tidak Mencukupi",
                        text: "Anda harus memilih minimal 8 sesi sebelum melanjutkan pembelian.",
                        confirmButtonText: "OK",
                    }).then(() => {
                        location.reload(); // Reset ulang halaman
                        closeForm(); // Tutup modal form
                    });
                    return;
                }
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Mendapatkan Data Sesi",
                    text: "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
                return;
            }
        } catch (error) {
            console.error("[DEBUG] Error fetching total sessions:", error);
            Swal.fire({
                icon: "error",
                title: "Kesalahan",
                text: "Terjadi kesalahan saat memvalidasi jumlah sesi. Silakan coba lagi.",
                confirmButtonText: "OK",
            });
            return;
        }
    }

    // Jika semua validasi berhasil, submit form
    document.querySelector("form[action='/BuyMembership']").submit();
});




// Fungsi untuk membatalkan sesi
// function cancelSession(session) {
//     const trainerID = trainerId;
//     const memberId = document.querySelector('input[name="ID_Member"]').value; // Ambil memberId

//     // Kirim request ke server untuk menghapus sesi
//     fetch(`cancelSession/${trainerID}/${memberId}/${session.date}/${session.time}`, {
//         method: 'DELETE',
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert('Sesi berhasil dibatalkan.');
//                 // Refresh daftar sesi yang sudah dibooking
//                 updateSavedSessionsCount();
//                 showSavedSessions();
//             } else {
//                 alert('Gagal membatalkan sesi.');
//                 updateSavedSessionsCount();
//             }
//         })
//         .catch(error => console.error('Error canceling session:', error));
// }

function cancelSession(session) {
    const trainerID = trainerId;
    const memberId = document.querySelector('input[name="ID_Member"]').value; // Ambil memberId

    if (!memberId || !trainerID || !session) {
        Swal.fire({
            icon: "error",
            title: "Data Tidak Valid",
            text: "Silakan coba lagi.",
            confirmButtonText: "OK",
        });
        return;
    }

    // Kirim request ke server untuk menghapus sesi
    fetch(`cancelSession/${trainerID}/${memberId}/${session.date}/${session.time}`, {
        method: 'DELETE',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Dibatalkan",
                    text: "Sesi Anda berhasil dihapus.",
                    confirmButtonText: "OK",
                }).then(() => {
                    // Refresh daftar sesi yang sudah dibooking
                    updateSavedSessionsCount();
                    showSavedSessions();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Membatalkan Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
                updateSavedSessionsCount();
            }
        })
        .catch(error => {
            console.error('Error canceling session:', error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat membatalkan sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}


// Fungsi untuk menampilkan detail sesi
function viewSessionDetails(session) {
    // Bisa ditambahkan untuk menampilkan detail lebih lanjut jika diperlukan
    alert(`Detail Sesi: ${session.time}`);
}

// Fungsi untuk menutup modal
function closeSavedSessionsModal() {
    document.getElementById('modalSavedSessions').style.display = 'none';
}

// Fungsi untuk melanjutkan ke halaman/form berikutnya
function continueBooking() {
    // Arahkan kembali ke form atau halaman awal
    closeSavedSessionsModal();
    closeKalenderModal();
}


// Fungsi untuk membuka modal
function openReviewModal(sessionId, idPT, namaPT, idMember, namaMember, date, sessionTime, status, rating, review) {
    // Menampilkan modal
    let modal = document.getElementById('reviewForm');
    modal.style.display = 'block';

   // Debugging: Menampilkan data yang masuk
   console.log("[DEBUG] Data yang diterima:");
   console.log("Session ID:", sessionId);
   console.log("ID PT:", idPT);
   console.log("Nama PT:", namaPT);
   console.log("ID Member:", idMember);
   console.log("Nama Member:", namaMember);
   console.log("Date:", date);
   console.log("Session Time:", sessionTime);
   console.log("Status:", status);
   console.log("Rating:", rating);
   console.log("Review:", review);
    
    // Mengisi field di modal dengan data yang diterima
    document.getElementById('reviewSessionId').value = sessionId;
    document.getElementById('reviewIdPT').value = idPT;
    document.getElementById('reviewNamaPT').value = namaPT;
    document.getElementById('reviewIdMember').value = idMember;
    document.getElementById('reviewNamaMember').value = namaMember;
    document.getElementById('reviewDate').value = date;
    document.getElementById('reviewSessionTime').value = sessionTime;
    document.getElementById('reviewStatus').value = status;
    document.getElementById('reviewRating').value = rating;
    document.getElementById('reviewReview').value = review;
}

// Fungsi untuk menutup modal
function closeReviewModal() {
    document.getElementById('reviewForm').style.display = 'none';
}

//fumgsi booking class
// function bookClass(classId, memberId) {
//     console.log("Booking class initiated");
//     console.log("Class ID:", classId);
//     console.log("Member ID:", memberId);

//     // Misalnya, kita akan melakukan permintaan POST untuk booking kelas
//     fetch('/bookClass', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//         },
//         body: JSON.stringify({
//             class_id: classId,
//             member_id: memberId
//         })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             alert("Booking Class berhasil!");
//             location.reload();
//         } else {
//             alert("Booking Class gagal: " + data.message);
//         }
//     })
//     .catch(error => {
//         console.error("Error during booking:", error);
//         alert("Terjadi kesalahan. Silakan coba lagi.");
//     });
// }
function bookClass(classId, memberId) {
    console.log("Booking class initiated");
    console.log("Class ID:", classId);
    console.log("Member ID:", memberId);

    // Tampilkan SweetAlert untuk konfirmasi
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Anda akan melakukan booking untuk kelas ini.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#40ce20",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Booking!",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengkonfirmasi, lanjutkan proses booking
            fetch('/bookClass', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    class_id: classId,
                    member_id: memberId,
                }),
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Booking Berhasil!",
                        text: "Anda telah berhasil memesan kelas.",
                        confirmButtonText: "OK",
                    }).then(() => {
                        location.reload(); // Memuat ulang halaman
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Booking Gagal",
                        text: data.message || "Silakan coba lagi.",
                        confirmButtonText: "OK",
                    });
                }
            })
            .catch((error) => {
                console.error("Error during booking:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    text: "Terjadi kesalahan saat memproses booking. Silakan coba lagi nanti.",
                    confirmButtonText: "OK",
                });
            });
        }
    });
}


// Fungsi untuk membuka modal dan menampilkan data booking
function openCouponModal(classId, className, instructorName, date, time) {
    // Menampilkan data di dalam modal
    document.getElementById('couponClassId').value = classId;
    document.getElementById('couponClassName').value = className;
    document.getElementById('couponInstructorName').value = instructorName;
    document.getElementById('couponDate').value = date;
    document.getElementById('couponTime').value = time;
    
    // Menampilkan modal
    document.getElementById('couponModal').style.display = 'block';
}

// Fungsi untuk menutup modal
function closeCouponModal() {
    document.getElementById('couponModal').style.display = 'none';
}

// Fungsi untuk membatalkan booking
// function cancelBooking(currentClassId, memberId) {
//         console.log("Canceling booking for Class ID:", currentClassId);

//         // Kirim request untuk membatalkan booking
//         fetch(`/cancelBooking/${currentClassId}`, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({
//                 class_id: currentClassId,
//                 member_id: memberId // ID Member dari session
//             })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert("Booking berhasil dibatalkan!");
//                 closeCouponModal(); // Menutup modal
//                 // Update tampilan setelah pembatalan
//                 location.reload(); // Memuat ulang halaman untuk memperbarui tampilan
//             } else {
//                 alert("Gagal membatalkan booking: " + data.message);
//             }
//         })
//         .catch(error => {
//             console.error("Error during cancel booking:", error);
//             alert("Terjadi kesalahan. Silakan coba lagi.");
//         });
// }
function cancelBooking(memberId) {
    // Get the class ID from the hidden input
    var classId = document.getElementById('couponClassId').value;
      // Get member ID from session

    console.log("Canceling booking for Class ID:", classId);

    // Show SweetAlert for confirmation
    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Booking yang sudah dibatalkan tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#40ce20",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, batalkan!",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            // If user confirms, proceed with canceling the booking
            fetch(`/cancelBooking/${classId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    class_id: classId,
                    member_id: memberId // ID Member from session
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Booking Dibatalkan",
                        text: "Booking Anda berhasil dibatalkan.",
                        confirmButtonText: "OK",
                    }).then(() => {
                        closeCouponModal(); // Close the modal
                        location.reload(); // Reload the page to update the view
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Membatalkan Booking",
                        text: data.message || "Silakan coba lagi.",
                        confirmButtonText: "OK",
                    });
                }
            })
            .catch(error => {
                console.error("Error during cancel booking:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    text: "Terjadi kesalahan saat memproses pembatalan booking. Silakan coba lagi nanti.",
                    confirmButtonText: "OK",
                });
            });
        }
    });
}


// Fungsi untuk memperbarui harga PT sesuai dengan PT yang dipilih
document.getElementById("ID_PT").addEventListener("change", function() {
    const selectedOption = this.options[this.selectedIndex];
    const hargaPT = selectedOption ? selectedOption.text.split(' - ')[1] : '';
  
    document.getElementById("Harga_PT").value = hargaPT;
});



// Fungsi untuk membuka modal penambahan PT
function openAddPTModal(ID_Record,tgl) {
    document.getElementById("addPTModal").style.display = "block";

    membershipStartDate = document.getElementById("tgl_berlaku").value;
    document.getElementById("tgl_berakhir1").value = tgl
    membershipEndDate = document.getElementById("tgl_berakhir1").value;
    document.getElementById("ID_Record").value = ID_Record;

    console.log("ID Record:", ID_Record);

    console.log("tgl start date:", membershipStartDate);

    console.log("tgl end date:", membershipEndDate);


}

// Fungsi untuk menutup modal penambahan PT
function closeAddPTModal() {
    resetSessions1();
    updateSavedSessionsCount1();
    console.log(document.getElementById('ID_PT1').value);
    document.getElementById("addPTModal").style.display = "none";
}

// Fungsi untuk membuka modal pilih PT
function openSelectPTModal() {
    document.getElementById("selectPT").style.display = "block";
}

function selectPT(id, name, pricePer8Sessions) {
    trainerId = id;
    trainerName = name;
    console.log("Trainer dipilih:", trainerId, trainerName, pricePer8Sessions);

    alert(`Anda memilih ${trainerName} sebagai personal trainer Anda.`);

    // Update harga PT di hidden input
    document.getElementById('ID_PT1').value = trainerId;
    document.getElementById('Harga_PT').value = pricePer8Sessions; // Ganti dengan harga PT yang sesuai
    document.getElementById('Pakai_PT').value = 'ya'; // Set status pakai PT

    // Log harga PT yang dipilih
    console.log("Harga PT yang dipilih:", pricePer8Sessions);
    const formattedPrice = pricePer8Sessions.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
    });
    document.getElementById('paymentAmount1').innerText = formattedPrice;

    // Tutup modal pemilihan PT
    closePTModal();

    // Muat jadwal trainer untuk bulan dan tahun aktif
    loadTrainerSchedule1(trainerId, trainerName, activeMonth, activeYear);
}

function loadTrainerSchedule1(id, name, month = activeMonth, year = activeYear) {
    const url = `/getTrainerScheduleMonthly/${id}/${month + 1}/${year}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(schedule => {
            console.log("Jadwal yang diterima dari server:", schedule);
            if (!Array.isArray(schedule) || schedule.length === 0) {
                console.warn("Jadwal kosong diterima dari server.");
                alert("Tidak ada jadwal yang dapat ditampilkan untuk bulan ini.");
                return;
            }

            // Panggil generateCalendar
            openKalenderModal1(trainerId, trainerName, schedule);
        })
        .catch(error => {
            console.error("Error saat memuat jadwal:", error.message);
            alert("Terjadi kesalahan saat memuat jadwal: " + error.message);
        });
}

function navigateMonth1(direction) {
    activeMonth += direction;

    if (activeMonth > 11) {
        activeMonth = 0;
        activeYear++;
    } else if (activeMonth < 0) {
        activeMonth = 11;
        activeYear--;
    }

    console.log(`Navigasi ke bulan baru: ${activeMonth + 1}, Tahun: ${activeYear}`);

    if (!trainerId || !trainerName) {
        alert("Trainer belum dipilih. Pastikan trainer dipilih terlebih dahulu.");
        return;
    }

    loadTrainerSchedule1(trainerId, trainerName, activeMonth, activeYear);
}


// Fungsi untuk membuka modal kalender dan mengatur nama PT
function openKalenderModal1(trainerId, trainerName, schedule) {
    console.log("Memanggil openKalenderModal dengan:", { trainerId, trainerName, schedule });

    if (!schedule || schedule.length === 0) {
        console.error("Schedule kosong atau tidak sesuai format:", schedule);
        alert("Tidak ada jadwal yang dapat ditampilkan untuk bulan ini.");
        return;
    }

    const trainerNameElement1 = document.getElementById('trainerName1');
    if (trainerNameElement1) {
        trainerNameElement1.textContent = trainerName; // Tetapkan nama trainer
    } else {
        console.error("Element dengan ID 'trainerName' tidak ditemukan.");
    }

    const monthYearLabel = `${new Date(activeYear, activeMonth).toLocaleString('id-ID', { month: 'long' })} ${activeYear}`;
    const calendarMonthYearElement1 = document.getElementById('calendarMonthYear1');
    if (calendarMonthYearElement1) {
        calendarMonthYearElement1.textContent = monthYearLabel; // Tetapkan bulan dan tahun
    } else {
        console.error("Element dengan ID 'calendarMonthYear' tidak ditemukan.");
    }

    const modalKalenderElement1 = document.getElementById('modalKalender1');
    if (modalKalenderElement1) {
        modalKalenderElement1.style.display = 'block'; // Tampilkan modal
    } else {
        console.error("Element dengan ID 'modalKalender' tidak ditemukan.");
    }

    // Panggil generateCalendar
    generateCalendar1(trainerId, trainerName, activeMonth, activeYear, schedule);

    // Tutup semua modal sebelumnya
    closeJadwalModal();
}

function closeKalenderModal1() {
    const modalKalender1 = document.getElementById('modalKalender1');
    if (modalKalender1) {
        modalKalender1.style.display = 'none'; // Sembunyikan modal
        modalKalender1.setAttribute('aria-hidden', 'true'); // Aksesibilitas: setel sebagai tersembunyi
        console.log("Modal kalender1 berhasil ditutup.");
    } else {
        console.error("Modal kalender1 tidak ditemukan.");
    }
}

// Fungsi untuk membuat kalender dengan ketersediaan sesi
function generateCalendar1(trainerId, trainerName, month, year, schedule) {
    closeSelectPTModal();
    console.log("Generating calendar for:", { trainerId, trainerName, month, year });

    const calendarBody1 = document.getElementById('calendarBody1');
    if (!calendarBody1) {
        console.error("calendarBody element not found");
        return;
    }

    calendarBody1.innerHTML = ''; // Clear previous content

    const daysInMonth = new Date(year, month + 1, 0).getDate(); // Total days in the month
    const firstDay = new Date(year, month, 1).getDay(); // Weekday of the first day
    const today = new Date();
    const normalizeDate = date => new Date(date.getFullYear(), date.getMonth(), date.getDate());
    today.setDate(today.getDate() + 1);

    const adjustedMembershipStartDate = membershipStartDate ? new Date(membershipStartDate) : null;
    if (adjustedMembershipStartDate) {
        adjustedMembershipStartDate.setDate(adjustedMembershipStartDate.getDate() - 1);
        adjustedMembershipStartDate.setHours(0, 0, 0, 0);
    }

    const normalizedToday = normalizeDate(today);
    const normalizedMembershipStartDate = adjustedMembershipStartDate ? normalizeDate(adjustedMembershipStartDate) : null;
    const normalizedMembershipEndDate = membershipEndDate ? normalizeDate(new Date(membershipEndDate)) : null;

    let row = document.createElement('tr');

    // Fill empty cells before the first day
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('td');
        row.appendChild(emptyCell);
    }

    // Iterate over all days in the month
    for (let date = 1; date <= daysInMonth; date++) {
        const dateCell = document.createElement('td');
        dateCell.textContent = date;

        // Format date as YYYY-MM-DD
        const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
        const dateObject = new Date(currentDate);
        const normalizedDateObject = normalizeDate(dateObject);


        const isToday = normalizedToday.toDateString() === normalizedDateObject.toDateString();
        const isPast = normalizedDateObject < normalizedToday;
    
        const inMembershipPeriod = normalizedMembershipStartDate &&
        normalizedMembershipEndDate &&
        normalizedDateObject >= normalizedMembershipStartDate &&
        normalizedDateObject <= normalizedMembershipEndDate;


        const scheduleForDate = schedule.find(item => item.Tanggal === currentDate);
        // Logging untuk debugging
        console.log({
            currentDate,
            dateObject,
            normalizedDateObject,
            normalizedToday,
            normalizedMembershipStartDate,
            normalizedMembershipEndDate,
            scheduleForDate,
            inMembershipPeriod,
            isPast,
        });

        console.log({ currentDate, scheduleForDate, inMembershipPeriod, isPast });

        // Reset classes
        dateCell.classList.remove('available-day', 'unavailable-day', 'today', 'selected-day');

        // Apply conditions to set class
        if (scheduleForDate) {
            // Semua slot pada hari tersebut sudah penuh atau tidak tersedia
            const allSlotsFull =
                Array.isArray(scheduleForDate.sessions) &&
                scheduleForDate.sessions.every(slot => slot.status !== "tersedia");

            if (scheduleForDate.success === false || allSlotsFull) {
                // Warnai merah jika semua slot penuh atau tidak tersedia
                dateCell.classList.add("unavailable-day");
            } else if (scheduleForDate.Status === "tersedia" && inMembershipPeriod && !isPast) {
                // Warnai hijau jika ada slot yang tersedia
                dateCell.classList.add("available-day");
                dateCell.onclick = () => selectDate1(currentDate);
            } else {
                // Default ke warna merah jika tidak ada kondisi yang terpenuhi
                dateCell.classList.add("unavailable-day");
            }
        } else {
            // Jika tidak ada data jadwal untuk tanggal ini
            dateCell.classList.add("unavailable-day");
        }



        // Highlight today's date
        if (isToday) {
            dateCell.classList.add('today'); // Blue
        }

        row.appendChild(dateCell);

        // Append row if the week is complete or it's the last date
        if ((firstDay + date) % 7 === 0 || date === daysInMonth) {
            calendarBody1.appendChild(row);
            row = document.createElement('tr');
        }
    }
}

// Fungsi untuk menutup modal pilih PT
function closeSelectPTModal() {
    document.getElementById("selectPT").style.display = "none";
}

function selectDate1(date) {
    console.log("Tanggal yang dipilih:", date);

    // Validasi jika sesi sudah penuh
    if (usedSessions + selectedSessions.length >= maxSessions) {
        alert(`Anda telah mencapai batas maksimum ${maxSessions} sesi.`);
        return;
    }

    // Validasi ID trainer
    if (!trainerId) {
        alert("Trainer belum dipilih. Silakan pilih personal trainer terlebih dahulu.");
        return;
    }

    const calendarCells = document.querySelectorAll('#calendarBody1 td');

    // Hapus kelas 'selected-day' dari semua sel kalender
    calendarCells.forEach(cell => {
        cell.classList.remove('selected-day');
    });

    const selectedCell = Array.from(calendarCells).find(cell => {
        const cellDate = `${activeYear}-${String(activeMonth + 1).padStart(2, '0')}-${String(cell.textContent).padStart(2, '0')}`;
        console.log(`Memeriksa cellDate: ${cellDate} dengan date: ${date}`);
        return cellDate === date && cell.classList.contains('available-day');
    });

    if (!selectedCell) {
        console.error("Elemen sel untuk tanggal yang dipilih tidak ditemukan atau tidak tersedia. Tanggal:", date);
        alert("Tanggal ini tidak tersedia.");
        return;
    }

    selectedCell.classList.add('selected-day');
    console.log("Elemen yang dipilih:", selectedCell);

    selectedDate = date; // Simpan tanggal yang dipilih

    openJadwalModal1(date); // Panggil modal jadwal
}


function openJadwalModal1(date) {
    console.log("[DEBUG] Membuka modal jadwal untuk tanggal:", date);
    const selectedDateElement1 = document.getElementById('selectedDate1');
    selectedDateElement1.textContent = selectedDate;

    if (!trainerId) {
        alert("Trainer belum dipilih. Silakan pilih personal trainer terlebih dahulu.");
        return;
    }

    selectedDate = date; // Simpan tanggal yang dipilih

    const memberId = document.querySelector('input[name="ID_Member1"]').value;

    fetchTotalSessions(memberId).then((totalSessions) => {
        usedSessions = totalSessions;
        console.log("[DEBUG] Total sesi yang telah tersimpan:", usedSessions);

        const url = `/getTrainerScheduleDaily/${trainerId}/${date}`;
        console.log("[DEBUG] Request URL:", url);

        fetch(url)
            .then(response => {
                console.log("[DEBUG] Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP Error ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("[DEBUG] Data dari server:", data);

                if (data.success && Array.isArray(data.sessions)) {
                    renderSlots1(data.sessions);
                    document.getElementById("modalJadwal1").style.display = "block";
                } else {
                    alert(data.message || "Slot sesi sudah full booked untuk tanggal ini.");
                }
            })
            .catch(err => {
                console.error("[DEBUG] Error fetching slots:", err);
                alert("Terjadi kesalahan saat memuat slot. Coba lagi nanti.");
            });
    }).catch(err => {
        console.error("[DEBUG] Error fetching total sessions:", err);
        alert("Terjadi kesalahan saat memuat data sesi total. Coba lagi nanti.");
    });
}

function closeJadwalModal1() {
    const modalJadwal = document.getElementById('modalJadwal1');
    if (modalJadwal) {
        modalJadwal.style.display = 'none'; // Sembunyikan modal
        modalJadwal.setAttribute('aria-hidden', 'true'); // Aksesibilitas: setel sebagai tersembunyi
    }
    console.log("[DEBUG] Modal jadwal berhasil ditutup.");
}


// Memuat slot waktu untuk tanggal tertentu
function loadSlots1(trainerId, date) {
    fetch(`/getTrainerScheduleDaily/${trainerId}/${date}`)
        .then(response => {
            console.log("[DEBUG] Response status:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("[DEBUG] Response data:", data);
            if (data.success) {
                renderSlots(data.sessions);
            } else {
                alert(data.message || "Tidak ada slot tersedia.");
            }
        })
        .catch(err => {
            console.error("[DEBUG] Error fetching slots:", err);
            alert("Terjadi kesalahan saat memuat slot.");
        });
}
function renderSlots1(slots) {
    const slotContainer = document.getElementById("slotContainer1");
    if (!slotContainer) {
        console.error("[DEBUG] slotContainer tidak ditemukan.");
        return;
    }

    slotContainer.innerHTML = ""; // Bersihkan kontainer

    slots.forEach(slot => {
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.disabled = slot.status !== "tersedia"; // Disabled jika tidak tersedia
        checkbox.id = slot.time;
        checkbox.value = slot.time;

        if (slot.status === "booked" || slot.status === "paid") {
            checkbox.disabled = true; // Disable checkbox untuk slot booked dan paid
        }

        checkbox.addEventListener("change", function () {
            toggleSession(selectedDate, slot.time); // Kirim waktu slot
        });

        const label = document.createElement("label");
        label.htmlFor = slot.time;
        label.textContent = `${slot.time} (${slot.status === "booked" ? "Sudah dipesan" : "Tersedia"})`;

        const container = document.createElement("div");
        container.appendChild(checkbox);
        container.appendChild(label);

        slotContainer.appendChild(container);
    });
}

// Fungsi untuk menyimpan sesi
// function saveSessions1() {
//     const memberId = document.querySelector("input[name='ID_Member1']").value;
//     const memberName = document.querySelector("input[name='Nama_Member1']").value;

//     if (!trainerId || !trainerName) {
//         alert("Trainer belum dipilih. Silakan pilih personal trainer.");
//         return;
//     }
//     if (!memberId || !memberName) {
//         alert("ID atau Nama Member tidak valid. Silakan login ulang.");
//         return;
//     }

//     // Filter sesi valid
//     const validSessions = selectedSessions.map((session) => ({
//         date: session.date,
//         time: session.time, // Kirim format waktu (misal "09:00 - 11:00")
//         Nama_PT: trainerName,
//         Nama_Member: memberName,
//     }));

//     console.log("[DEBUG] Data sesi yang dikirim ke server:", {
//         member_id: memberId,
//         trainer_id: trainerId,
//         Nama_PT: trainerName,
//         Nama_Member: memberName,
//         sessions: validSessions,
//     });

//     if (validSessions.length === 0) {
//         alert("Tidak ada sesi valid untuk disimpan.");
//         return;
//     }

//     fetch("/saveSessions", {
//         method: "POST",
//         headers: { "Content-Type": "application/json" },
//         body: JSON.stringify({
//             member_id: memberId,
//             trainer_id: trainerId,
//             Nama_PT: trainerName,
//             Nama_Member: memberName,
//             sessions: validSessions,
//         }),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 throw new Error(`HTTP Error ${response.status}`);
//             }
//             return response.json();
//         })
//         .then((data) => {
//             if (data.success) {
//                 alert("Sesi berhasil disimpan.");
//                 selectedSessions = [];
//                 updateSavedSessionsCount1();
//                 closeJadwalModal1();
//             } else {
//                 alert(`Gagal menyimpan sesi: ${data.message}`);
//             }
//         })
//         .catch((error) => {
//             console.error("[DEBUG] Error saat menyimpan sesi:", error);
//             alert("Terjadi kesalahan saat menyimpan sesi.");
//         });
// }

function saveSessions1() {
    const memberId = document.querySelector("input[name='ID_Member1']").value;
    const memberName = document.querySelector("input[name='Nama_Member1']").value;

    if (!trainerId || !trainerName) {
        Swal.fire({
            icon: "error",
            title: "Trainer Belum Dipilih",
            text: "Silakan pilih personal trainer sebelum melanjutkan.",
            confirmButtonText: "OK",
        });
        return;
    }

    if (!memberId || !memberName) {
        Swal.fire({
            icon: "error",
            title: "Data Tidak Valid",
            text: "ID atau Nama Member tidak valid. Silakan login ulang.",
            confirmButtonText: "OK",
        });
        return;
    }

    // Filter sesi valid
    const validSessions = selectedSessions.map((session) => ({
        date: session.date,
        time: session.time, // Kirim format waktu (misal "09:00 - 11:00")
        Nama_PT: trainerName,
        Nama_Member: memberName,
    }));

    console.log("[DEBUG] Data sesi yang dikirim ke server:", {
        member_id: memberId,
        trainer_id: trainerId,
        Nama_PT: trainerName,
        Nama_Member: memberName,
        sessions: validSessions,
    });

    if (validSessions.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Tidak Ada Sesi Valid",
            text: "Silakan pilih sesi yang valid sebelum melanjutkan.",
            confirmButtonText: "OK",
        });
        return;
    }

    fetch("/saveSessions", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            member_id: memberId,
            trainer_id: trainerId,
            Nama_PT: trainerName,
            Nama_Member: memberName,
            sessions: validSessions,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Disimpan",
                    text: "Sesi Anda berhasil disimpan.",
                    confirmButtonText: "OK",
                }).then(() => {
                    selectedSessions = [];
                    updateSavedSessionsCount1();
                    closeJadwalModal1();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Menyimpan Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch((error) => {
            console.error("[DEBUG] Error saat menyimpan sesi:", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat menyimpan sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}


function updateSavedSessionsCount1() {
    const memberId = document.querySelector('input[name="ID_Member1"]').value;
    fetch(`/getTotalSessions/${memberId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('remainingSessions1').textContent = `Saved sessions: ${data.total_sessions}`;
        });
}

// Fungsi untuk membuka modal dan menampilkan sesi yang sudah dipesan
function showSavedSessions1() {
    // Ambil nilai trainerId dan memberId (misalnya bisa diambil dari data elemen atau variabel global)
    const trainerID = trainerId;
    const memberId = document.querySelector('input[name="ID_Member1"]').value; // Contoh mengambil dari elemen input

    // Periksa apakah trainerId dan memberId valid
    if (!trainerID || !memberId) {
        alert("Trainer ID atau Member ID tidak valid.");
        return;
    }

    // Lakukan fetch ke server untuk mengambil data sesi yang sudah dibooking
    fetch(`getSavedSessions/${trainerID}/${memberId}`)  // Gantilah dengan URL API yang sesuai
        .then(response => response.json())
        .then(data => {
            const savedSessionsContainer = document.getElementById('savedSessionsList1');
            savedSessionsContainer.innerHTML = '';  // Bersihkan container sebelum menampilkan

            // Loop untuk menampilkan sesi yang sudah dipesan
            if (data.sessions && data.sessions.length > 0) {
                data.sessions.forEach(session => {
                    const row = document.createElement('tr');

                    // Kolom Tanggal
                    const dateCell = document.createElement('td');
                    const date = new Date(session.date);
                    const formattedDate = `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    dateCell.textContent = formattedDate;

                    // Kolom Sesi
                    const sessionCell = document.createElement('td');
                    sessionCell.textContent = session.time;

                    // Kolom Action (Button Batal)
                    const actionCell = document.createElement('td');
                    const cancelButton = document.createElement('button');
                    cancelButton.textContent = 'Batal';
                    cancelButton.classList.add('delete-session');
                    cancelButton.onclick = () => cancelSession1(session); // Fungsi untuk membatalkan sesi

                    actionCell.appendChild(cancelButton);

                    // Tambahkan kolom ke baris
                    row.appendChild(dateCell);
                    row.appendChild(sessionCell);
                    row.appendChild(actionCell);

                    // Tambahkan baris ke tabel
                    savedSessionsContainer.appendChild(row);
                });
            } else {
                savedSessionsContainer.innerHTML = '<tr><td colspan="3">Tidak ada sesi yang sudah dipesan.</td></tr>';
            }

            // Tampilkan modal
            document.getElementById('modalSavedSessions1').style.display = 'block';
        })
        .catch(error => console.error('Error fetching saved sessions:', error));
}

// Fungsi untuk menutup modal
function closeSavedSessionsModal1() {
    document.getElementById('modalSavedSessions1').style.display = 'none';
}

// Fungsi untuk membatalkan sesi
// function cancelSession1(session) {
//     const trainerID = trainerId;
//     const memberId = document.querySelector('input[name="ID_Member1"]').value; // Ambil memberId

//     // Kirim request ke server untuk menghapus sesi
//     fetch(`cancelSession/${trainerID}/${memberId}/${session.date}/${session.time}`, {
//         method: 'DELETE',
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert('Sesi berhasil dibatalkan.');
//                 // Refresh daftar sesi yang sudah dibooking
//                 updateSavedSessionsCount1();
//                 showSavedSessions1();
//             } else {
//                 alert('Gagal membatalkan sesi.');
//                 updateSavedSessionsCount1();
//             }
//         })
//         .catch(error => console.error('Error canceling session:', error));
// }

function cancelSession1(session) {
    const trainerID = trainerId;
    const memberId = document.querySelector('input[name="ID_Member1"]').value; // Ambil memberId

    if (!memberId || !trainerID || !session) {
        Swal.fire({
            icon: "error",
            title: "Data Tidak Valid",
            text: "Silakan coba lagi.",
            confirmButtonText: "OK",
        });
        return;
    }

    // Kirim request ke server untuk menghapus sesi
    fetch(`cancelSession/${trainerID}/${memberId}/${session.date}/${session.time}`, {
        method: 'DELETE',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Dibatalkan",
                    text: "Sesi Anda berhasil dihapus.",
                    confirmButtonText: "OK",
                }).then(() => {
                    // Refresh daftar sesi yang sudah dibooking
                    updateSavedSessionsCount1();
                    showSavedSessions1();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Membatalkan Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
                updateSavedSessionsCount1();
            }
        })
        .catch(error => {
            console.error('Error canceling session:', error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat membatalkan sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}


// Fungsi untuk reset seluruh sesi (frontend)
// function resetSessions1() {
//     const memberId = document.querySelector('input[name="ID_Member1"]').value;

//     if (!memberId) {
//         alert("ID Member tidak valid.");
//         return;
//     }

//     fetch('/resetSessions', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify({ member_id: memberId }),
//     })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 selectedSessions = []; // Clear the selected sessions
//                 updateSavedSessionsCount1(); // Update the UI
//                 closeSavedSessionsModal1();
//             } else {
//                 alert("Gagal mereset sesi: " + data.message);
//             }
//         })
//         .catch(error => {
//             console.error("Error saat mereset sesi:", error.message);
//             alert("Terjadi kesalahan saat mereset sesi.");
//         });
// }
function resetSessions1() {
    const memberId = document.querySelector('input[name="ID_Member1"]').value;

    if (!memberId) {
        Swal.fire({
            icon: "error",
            title: "ID Member Tidak Valid",
            text: "Silakan coba lagi.",
            confirmButtonText: "OK",
        });
        return;
    }

    fetch('/resetSessions', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ member_id: memberId }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedSessions = []; // Clear the selected sessions
                updateSavedSessionsCount1(); // Update the UI
                closeSavedSessionsModal1();
                Swal.fire({
                    icon: "success",
                    title: "Sesi Berhasil Direset",
                    text: "Semua sesi Anda berhasil dihapus.",
                    confirmButtonText: "OK",
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Gagal Mereset Sesi",
                    text: data.message || "Silakan coba lagi.",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch(error => {
            console.error("Error saat mereset sesi:", error.message);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan saat mereset sesi. Silakan coba lagi nanti.",
                confirmButtonText: "OK",
            });
        });
}

document.getElementById("submitBtnAddPT").addEventListener("click", function (event) {
    const memberId = document.querySelector("input[name='ID_Member1']").value;
    const buktiPembayaranInput = document.querySelector("input[name='Bukti_TambahPT']");
    // Cek apakah bukti pembayaran sudah diunggah
    if (!buktiPembayaranInput.files.length) {
        // Jika belum ada file yang dipilih, tampilkan SweetAlert
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Anda harus mengunggah bukti pembayaran sebelum melanjutkan.',
            confirmButtonColor: '#40ce20',
        });
        return; // Stop form submission
    }
    // Fetch jumlah sesi yang telah disimpan
    fetch(`/getTotalSessions/${memberId}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP Error ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                const totalSessions = data.total_sessions;
                console.log("[DEBUG] Total sessions saved:", totalSessions);

                // Jika sesi kurang dari 8, prevent form submission
                if (totalSessions < 8) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian!',
                        text: 'Anda harus memilih minimal 8 sesi sebelum melanjutkan.',
                        confirmButtonColor: '#40ce20',
                    }).then(() => {
                        closeAddPTModal();
                        location.reload();
                    });
                } else {
                    // Lanjutkan dengan submit form
                    document.querySelector("form[action='/addPTToMembership']").submit();
                }
            } else {
                event.preventDefault();
                alert("Gagal mendapatkan data sesi. Silakan coba lagi.");
            }
        })
        .catch((error) => {
            console.error("[DEBUG] Error fetching total sessions:", error);
            event.preventDefault();
            alert("Terjadi kesalahan saat memvalidasi jumlah sesi. Silakan coba lagi.");
        });
});

// Fungsi untuk melanjutkan ke halaman/form berikutnya
function continueBooking1() {
    // Arahkan kembali ke form atau halaman awal
    closeSelectPTModal();
    closeSavedSessionsModal1();
    closeKalenderModal1();
}


//rating bintang
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating');

    stars.forEach(star => {
        star.addEventListener('click', function () {
            // Dapatkan nilai dari data-value
            const ratingValue = this.getAttribute('data-value');
            ratingInput.value = ratingValue; // Set nilai ke input hidden

            // Reset semua bintang (hapus class 'selected')
            stars.forEach(s => s.classList.remove('selected'));

            // Tambahkan class 'selected' ke bintang yang dipilih dan sebelumnya
            this.classList.add('selected');
            let prevStar = this.previousElementSibling;
            while (prevStar) {
                prevStar.classList.add('selected');
                prevStar = prevStar.previousElementSibling;
            }
        });

        // Hover efek untuk feedback
        star.addEventListener('mouseover', function () {
            stars.forEach(s => s.classList.remove('hover'));
            this.classList.add('hover');
            let prevStar = this.previousElementSibling;
            while (prevStar) {
                prevStar.classList.add('hover');
                prevStar = prevStar.previousElementSibling;
            }
        });

        // Menghapus hover efek ketika keluar
        star.addEventListener('mouseleave', function () {
            stars.forEach(s => s.classList.remove('hover'));
        });
    });
});

function openRescheduleModal(idSesi, idPT, namaPT, idMember) {
    console.log("ID Sesi:", idSesi);
    console.log("ID PT:", idPT);
    console.log("Nama PT:", namaPT);
    console.log("ID Member:", idMember);

    // Set nilai input hidden
    document.getElementById('reschedule_ID_Sesi').value = idSesi;
    document.getElementById('Unique_ID_PT').value = idPT;
    document.getElementById('Unique_ID_Member').value = idMember;

    console.log("Nilai Hidden ID Member:", document.getElementById('Unique_ID_Member').value);

    // Tampilkan modal reschedule
    document.getElementById("unique_reschedule_form").style.display = "block";
    initializeFlatpickr();
}

function closeRescheduleModal() {
    document.getElementById("unique_reschedule_form").style.display = "none";
}


function initializeFlatpickr() {
    const idMember = document.getElementById('Unique_ID_Member').value;

    console.log("Fetching available dates for ID Member:", idMember);

    fetch(`/getAvailableDates?idMember=${idMember}`)
        .then(response => response.json())
        .then(data => {
            console.log("Response dari Server:", data);
            const availableDates = data.availableDates || [];

            if (availableDates.length === 0) {
                alert("Tidak ada sesi tersedia dalam rentang membership aktif.");
            }

            flatpickr("#unique_tanggal_reschedule", {
                dateFormat: "Y-m-d",
                enable: availableDates,
                onChange: function (selectedDates, dateStr) {
                    console.log("Tanggal dipilih:", dateStr);
                    loadAvailableSessions(dateStr);
                }
            });
        })
        .catch(error => {
            console.error("Error fetching available dates:", error);
            alert("Gagal memuat tanggal. Silakan coba lagi.");
        });
}

function loadAvailableSessions(selectedDate) {
    const idPT = document.getElementById("Unique_ID_PT").value;
    const sessionContainer = document.getElementById("unique_session_container");

    console.log("Fetching sessions for date:", selectedDate, "and ID PT:", idPT);

    fetch(`/getAvailableSessions?date=${selectedDate}&idPT=${idPT}`)
        .then(response => response.json())
        .then(data => {
            console.log("Sessions Response:", data);
            sessionContainer.innerHTML = "";

            if (data.success && data.sessions.length > 0) {
                data.sessions.forEach(session => {
                    const radio = `
                        <div class="session-option">
                            <input type="radio" name="sesi" value="${session.time}" id="session_${session.time}" required>
                            <label for="session_${session.time}" style="font-size: 20px">${session.time}</label>
                        </div>`;
                    sessionContainer.innerHTML += radio;
                });
            } else {
                sessionContainer.innerHTML = "<p>Tidak ada sesi tersedia untuk tanggal ini.</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching sessions:", error);
            sessionContainer.innerHTML = "<p>Error memuat sesi. Coba lagi nanti.</p>";
        });
}
document.addEventListener("DOMContentLoaded", function () {
    const rescheduleForm = document.querySelector("#unique_reschedule_form form");

    rescheduleForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Hentikan submit default

        // Ambil nilai input
        const idSesiLama = document.querySelector('#reschedule_ID_Sesi').value;
        const tanggal = document.querySelector('input[name="tanggal"]').value;
        const sesi = document.querySelector('input[name="sesi"]:checked')?.value;

        // Debug log
        console.log("ID Sesi Lama:", idSesiLama);
        console.log("Tanggal yang dipilih:", tanggal);
        console.log("Sesi yang dipilih:", sesi);

        // Validasi
        if (!idSesiLama || !tanggal || !sesi) {
            Swal.fire({
                icon: "warning",
                title: "Data Tidak Lengkap",
                text: "Pastikan Anda memilih tanggal dan sesi yang tersedia.",
                confirmButtonText: "OK"
            });
            return;
        }

        const formData = new FormData(rescheduleForm);

        fetch("/rescheduleSession", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Reschedule Berhasil",
                    text: data.message,
                    confirmButtonText: "OK"
                }).then(() => {
                    location.reload(); // Reload halaman setelah konfirmasi
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Reschedule Gagal",
                    text: data.message,
                    confirmButtonText: "OK"
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Terjadi kesalahan. Silakan coba lagi nanti.",
                confirmButtonText: "OK"
            });
        });
    });
});


// // Submit Form Reschedule
// document.addEventListener("DOMContentLoaded", function () {
//     const rescheduleForm = document.querySelector("#unique_reschedule_form form");

//     rescheduleForm.addEventListener("submit", function (event) {
//         event.preventDefault(); // Hentikan submit default

//         // Ambil nilai input
//         const idSesiLama = document.querySelector('#reschedule_ID_Sesi').value;
//         const tanggal = document.querySelector('input[name="tanggal"]').value;
//         const sesi = document.querySelector('input[name="sesi"]:checked')?.value;

//         // Debug log
//         console.log("ID Sesi Lama:", idSesiLama);
//         console.log("Tanggal yang dipilih:", tanggal);
//         console.log("Sesi yang dipilih:", sesi);

//         // Validasi
//         if (!idSesiLama || !tanggal || !sesi) {
//             alert("Pastikan Anda memilih tanggal, sesi, dan data sudah lengkap!");
//             return;
//         }

//         const formData = new FormData(rescheduleForm);

//         fetch("/rescheduleSession", {
//             method: "POST",
//             body: formData
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 alert(data.message); // Alert berhasil
//                 location.reload();   // Reload halaman
//             } else {
//                 alert(`Error: ${data.message}`); // Alert jika error
//             }
//         })
//         .catch(error => {
//             console.error("Error:", error);
//             alert("Terjadi kesalahan. Coba lagi nanti.");
//         });
//     });
// });

// Ambil semua link navigasi
// Ambil semua link navigasi
const navLinks = document.querySelectorAll('.nav__links a');

// Tambahkan event listener untuk setiap link
navLinks.forEach((link) => {
  link.addEventListener('click', function (e) {
    e.preventDefault(); // Mencegah reload halaman

    // Ambil ID section yang dituju
    const sectionId = this.getAttribute('data-section');

    // Simpan section aktif ke localStorage
    localStorage.setItem('activeSection', sectionId);

    // Sembunyikan semua section
    document.querySelectorAll('section').forEach((section) => {
      section.style.display = 'none';
    });

    // Tampilkan section yang dipilih
    document.getElementById(sectionId).style.display = 'block';

    // Hapus kelas 'active' dari semua link
    navLinks.forEach((navLink) => {
      navLink.classList.remove('active');
    });

    // Tambahkan kelas 'active' pada link yang diklik
    this.classList.add('active');
  });
});

// Saat halaman dimuat, periksa localStorage untuk menentukan section aktif
document.addEventListener('DOMContentLoaded', function () {
  // Ambil section aktif dari localStorage
  const activeSection = localStorage.getItem('activeSection');

  // Sembunyikan semua section terlebih dahulu
  document.querySelectorAll('section').forEach((section) => {
    section.style.display = 'none';
  });

  if (activeSection) {
    // Jika ada, tampilkan section yang tersimpan
    const sectionToShow = document.getElementById(activeSection);
    if (sectionToShow) {
      sectionToShow.style.display = 'block';
    }

    // Tambahkan kelas 'active' pada link yang sesuai
    navLinks.forEach((navLink) => {
      const sectionId = navLink.getAttribute('data-section');
      if (sectionId === activeSection) {
        navLink.classList.add('active');
      } else {
        navLink.classList.remove('active');
      }
    });
  } else {
    // Jika localStorage kosong, tampilkan section Profile sebagai default
    const defaultSection = 'profile-section'; // ID section default
    const defaultSectionElement = document.getElementById(defaultSection);

    if (defaultSectionElement) {
      defaultSectionElement.style.display = 'block';
    }

    // Tambahkan kelas 'active' pada link default
    navLinks.forEach((navLink) => {
      const sectionId = navLink.getAttribute('data-section');
      if (sectionId === defaultSection) {
        navLink.classList.add('active');
      } else {
        navLink.classList.remove('active');
      }
    });

    // Simpan section default ke localStorage
    localStorage.setItem('activeSection', defaultSection);
  }
});

  
  function handleLogout() {
    // Optional: Tampilkan SweetAlert untuk konfirmasi logout
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
            // Redirect ke halaman login PT
            window.location.href = '/';
        }
    });
}

// Fungsi untuk membuka modal edit profile
function openEditProfileModal() {
    const modal = document.getElementById("editProfileModal");
    modal.style.display = "block"; // Tampilkan modal
}

// Fungsi untuk menutup modal edit profile
function closeEditProfileModal() {
    const modal = document.getElementById("editProfileModal");
    modal.style.display = "none"; // Sembunyikan modal
}

// Fungsi untuk validasi data sebelum mengirim
function validateAndSubmitEditProfile(event) {
    event.preventDefault(); // Mencegah form submit default

    // Ambil nilai input dari form khusus ini
    const nama = document.getElementById("EditNama_Member").value.trim();
    const email = document.getElementById("EditEmail").value.trim();
    const noHP = document.getElementById("EditNoHP").value.trim();
    const foto = document.getElementById("EditFoto_Member").value;

    // Validasi nama
    if (!nama) {
        Swal.fire("Error", "Nama tidak boleh kosong!", "error");
        return;
    }

    // Validasi email
    if (!email || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
        Swal.fire("Error", "Email tidak valid!", "error");
        return;
    }

    // Validasi No HP
    if (!noHP || !/^[\d]{10,15}$/.test(noHP)) {
        Swal.fire("Error", "No HP harus berupa angka dan panjang antara 10-15 karakter!", "error");
        return;
    }

    // Validasi Foto (opsional)
    if (foto) {
        const allowedExtensions = ["jpg", "jpeg", "png"];
        const fileExtension = foto.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            Swal.fire("Error", "Format foto harus jpg, jpeg, atau png!", "error");
            return;
        }
    }

    // SweetAlert konfirmasi sebelum submit
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menyimpan perubahan?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#40ce20",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, simpan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector("#editProfileForm").submit(); // Submit form jika disetujui
        }
    });
}

// Tambahkan event listener hanya untuk form edit profile
document.querySelector("#editProfileForm").addEventListener("submit", validateAndSubmitEditProfile);

// Function to display success message
function displaySuccessMessage() {
    Swal.fire({
        title: 'Berhasil!',
        text: 'Data Member berhasil diupdate!',
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

$(document).ready(function () {
    // Set default date ke hari ini
    var today = new Date();
    var formattedToday = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
    $('#filter-date1').val(formattedToday); // Set tanggal default

    // Terapkan filter tanggal default ke DataTable
    var table = $('#class-schedule-table').DataTable({
        pageLength: 5,
        lengthChange: false,
        dom: '<"d-none"lf>rt<"pagination-container"p>', // Hilangkan 'i'
        language: {
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Terapkan filter otomatis untuk tanggal default
    var formattedDefaultDate = formatDateToDMY(formattedToday); // DD-MM-YYYY
    table.column(2).search('^' + formattedDefaultDate + '$', true, false).draw();

    // Fungsi format ulang tanggal
    function formatDateToDMY(dateString) {
        const dateParts = dateString.split('-');
        return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
    }

    // Filter Nama Class
    $('#filter-class').on('change', function () {
        var selectedClass = $(this).val();
        table.column(0).search(selectedClass ? `^${selectedClass}$` : '', true, false).draw();
    });

    // Filter Tanggal
    $('#filter-date1').on('change', function () {
        var selectedDate = $(this).val(); // Format YYYY-MM-DD
        table.column(2).search(selectedDate ? `^${formatDateToDMY(selectedDate)}$` : '', true, false).draw();
    });

    // Filter Jam
    $('#filter-time').on('change', function () {
        table.column(3).search(this.value || '').draw();
    });

    // Filter Nama Instruktur
    $('#filter-instructor').on('keyup', function () {
        table.column(1).search(this.value).draw();
    });
});


$(document).ready(function () {
    // Set default date ke hari ini
    var today = new Date();
    var formattedToday = today.toISOString().split('T')[0];  // Format ke YYYY-MM-DD
    $('#filter-date-active').val(formattedToday);  // Set default date

    // Ambil tanggal minimal dan maksimal dari atribut input
    var minDate = $('#filter-date-active').attr('min');
    var maxDate = $('#filter-date-active').attr('max');

    // Inisialisasi DataTable
    var tableActive = $('#class-schedule-table-active').DataTable({
        pageLength: 5,
        lengthChange: false,
        dom: '<"d-none"lf>rt<"pagination-container"p>',
        language: {
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Format tanggal ke DD-MM-YYYY untuk DataTables
    function formatDateToDMY(dateString) {
        const dateParts = dateString.split('-');
        return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
    }

    // Filter otomatis untuk tanggal default
    var formattedDefaultDate = formatDateToDMY(formattedToday); // DD-MM-YYYY
    tableActive.column(2).search('^' + formattedDefaultDate + '$', true, false).draw();

    // Validasi perubahan tanggal
    $('#filter-date-active').on('change', function () {
        var selectedDate = $(this).val();
        
        if (selectedDate < minDate || selectedDate > maxDate) {
            alert(`Tanggal harus berada dalam rentang ${minDate} sampai ${maxDate}`);
            $(this).val(minDate); // Set kembali ke tanggal minimal jika invalid
        } else {
            // Filter DataTable berdasarkan tanggal yang valid
            tableActive.column(2).search(selectedDate ? `^${formatDateToDMY(selectedDate)}$` : '', true, false).draw();
        }
    });

    // Filter Nama Class
    $('#filter-class-active').on('change', function () {
        var selectedClass = $(this).val();
        tableActive.column(0).search(selectedClass ? `^${selectedClass}$` : '', true, false).draw();
    });

    // Filter Jam
    $('#filter-time-active').on('change', function () {
        tableActive.column(3).search(this.value || '').draw();
    });

    // Filter Nama Instruktur
    $('#filter-instructor-active').on('keyup', function () {
        tableActive.column(1).search(this.value).draw();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Dapatkan waktu saat ini
    var currentDate = new Date();
    
    // Ambil semua baris tabel yang berisi kelas
    var rows = document.querySelectorAll("#class-schedule-table-active tbody tr");
    
    rows.forEach(function(row) {
        // Ambil kolom "Tanggal" dan "Jam" di dalam setiap baris
        var dateCell = row.cells[2]; // Kolom Tanggal
        var timeCell = row.cells[3]; // Kolom Jam
        var dateText = dateCell.textContent.trim(); // Ambil teks tanggal
        var timeText = timeCell.textContent.trim(); // Ambil teks waktu

        // Pisahkan waktu mulai dan waktu berakhir berdasarkan tanda '-'
        var timeRange = timeText.split(" - ");
        var startTime = timeRange[0].trim(); // Waktu mulai
        var endTime = timeRange[1].trim();   // Waktu berakhir

        // Pisahkan tanggal berdasarkan '-'
        var dateParts = dateText.split("-");
        var day = parseInt(dateParts[0], 10);
        var month = parseInt(dateParts[1], 10) - 1; // Bulan di JavaScript dimulai dari 0 (Januari = 0)
        var year = parseInt(dateParts[2], 10);
        
        // Parsing waktu mulai (misal "08:00" menjadi objek Date)
        var startTimeParts = startTime.split(":");
        var startHour = parseInt(startTimeParts[0], 10); // Jam mulai
        var startMinute = parseInt(startTimeParts[1], 10); // Menit mulai

        // Buat objek Date untuk waktu mulai kelas
        var classStartTime = new Date(year, month, day, startHour, startMinute);

        // Bandingkan tanggal dan waktu mulai kelas dengan waktu saat ini
        if (classStartTime < currentDate) {
            // Jika waktu sudah lewat, sembunyikan tombol "Action"
            row.cells[5].querySelectorAll("button").forEach(function(button) {
                button.style.display = 'none'; // Menyembunyikan tombol
            });
        }
    });
});



// JavaScript untuk menangani tab dan menampilkan data berdasarkan kategori yang dipilih
document.getElementById('tab-transaksi').addEventListener('click', function() {
    showHistory('transaksi');
});
document.getElementById('tab-pt').addEventListener('click', function() {
    showHistory('pt');
});
document.getElementById('tab-class').addEventListener('click', function() {
    showHistory('class');
});

function showHistory(type) {
    // Sembunyikan semua kategori
    document.querySelectorAll('.history-category').forEach(function(category) {
        category.style.display = 'none';
    });

    // Tampilkan kategori yang sesuai dengan tab yang dipilih
    if (type === 'transaksi') {
        document.getElementById('transaksi-history').style.display = 'block';
    } else if (type === 'pt') {
        document.getElementById('pt-history').style.display = 'block';
    } else if (type === 'class') {
        document.getElementById('class-history').style.display = 'block';
    }

    // Update aktif pada tab
    document.querySelectorAll('.history-tab').forEach(function(tab) {
        tab.classList.remove('active');
    });
    document.getElementById('tab-' + type).classList.add('active');
}

// Inisialisasi default tab
showHistory('transaksi');

//pagination history personal training 
$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel PT History
    var ptTable = $('#pt-history-table').DataTable({
        pageLength: 8, // Jumlah baris per halaman
        lengthChange: false, // Menonaktifkan dropdown jumlah baris
        dom: '<"d-none"lf>rt<"d-flex justify-content-between"ip>', // Mengatur tampilan DataTable
        language: {
            search: "", // Menghapus label pencarian default
            searchPlaceholder: "Cari Nama PT...", // Placeholder pencarian
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya",
            }
        }
    });

    // Menampilkan History Personal Training ketika tombol "History Personal Training" diklik
    $('#tab-pt').on('click', function() {
        $('.history-category').hide();
        $('#pt-history').show();
    });
});

//pagination history booking class
$(document).ready(function () {
    var table = $('#class-history-table').DataTable({
        pageLength: 5, // Adjust the number of rows per page
        lengthChange: false, // Disable the length change option
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
});
