<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Trainer Dashboard</title>
    <link rel="stylesheet" href="/css/PersonalTrainer.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center py-2" id="currentDate" style="font-size: 24px; color: white; font-weight: bold;"></div>
        <h4 class="text-center py-3">TRAINER</h4>
        <a href="#" data-section="profile-section" class="sidebar-link"><i class="fa-solid fa-user-tie"></i> Profile</a>
        <a href="#" data-section="jadwal-section" class="sidebar-link" onclick="loadTrainerSchedule('<?= $trainer['ID_PT']; ?>', '<?= $trainer['Nama_PT']; ?>')"><i class="fa-regular fa-calendar-days"></i> Jadwal</a>
        <a href="#" data-section="booking-section" class="sidebar-link"><i class="fa-solid fa-list-check"></i> Booking</a>
        <a href="#" onclick="handleLogout()" class="sidebar-link" style="color: red;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="main-content">

        <!-- Profile Section -->
        <div id="profile-section" class="content-section active-section">
            <div class="profile-card">
                <img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']); ?>" alt="Foto PT" class="profile-img">
                <!-- Rating -->
                <div class="rating">
                    <?php 
                    $rating = $trainer['Rating']; // Pastikan Rating tersedia di data
                    $fullStars = floor($rating);
                    $halfStar = $rating - $fullStars >= 0.5 ? true : false;
                    for ($i = 1; $i <= 5; $i++): 
                        if ($i <= $fullStars): ?>
                            <i class="fa fa-star full-star"></i>
                        <?php elseif ($halfStar && $i == $fullStars + 1): ?>
                            <i class="fa fa-star-half-alt half-star"></i>
                        <?php else: ?>
                            <i class="fa fa-star empty-star"></i>
                        <?php endif;
                    endfor; ?>
                    <span class="rating-text">(<?= number_format($rating, 1); ?>)</span>
                </div>
                <h2><?= $trainer['Nama_PT'] ?></h2>
                <p><strong>Spesialisasi:</strong> <?= $trainer['Spesialisasi'] ?></p>
                <p><strong>Prestasi:</strong> <?= $trainer['Prestasi'] ?></p>
                <p><strong>Harga Sesi:</strong> Rp<?= number_format($trainer['Harga_Sesi'], 0, ',', '.'); ?></p>

                <!-- Edit Button -->
                <div class="edit-profile">
                    <button class="btn-edit" onclick="openEditModal('<?= $trainer['ID_PT']; ?>', '<?= $trainer['Password']; ?>', '<?= $trainer['Nama_PT']; ?>', 
                    '<?= $trainer['Foto_PT']; ?>', '<?= $trainer['Prestasi']; ?>', '<?= $trainer['Spesialisasi']; ?>', '<?= $trainer['Harga_Sesi']; ?>')">Edit Profile</button>
                </div>
            </div>

            <!-- Card Jadwal Hari Ini -->
            <div class="schedule-card">
                <h4>JADWAL HARI INI</h4>
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Nama Member</th>
                            <th>Jam Sesi</th>
                            <th>Latihan</th>
                            <th>Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        // Ambil hari ini
                        $today = date('Y-m-d');

                        // Filter hanya data dengan tanggal hari ini
                        $todayBookings = array_filter($training, function($booking) use ($today) {
                            return $booking['date'] == $today;
                        });

                        // Urutkan berdasarkan session_time
                        usort($todayBookings, function($a, $b) {
                            // Ambil jam awal dari session_time (format: "HH:mm - HH:mm")
                            $timeA = strtotime(explode(' - ', $a['session_time'])[0]);
                            $timeB = strtotime(explode(' - ', $b['session_time'])[0]);
                            return $timeA <=> $timeB; // Urutan menaik
                        });

                        // Tampilkan data yang sudah diurutkan
                        foreach ($todayBookings as $booking): 
                        ?>
                        <tr>
                            <td><?= $booking['Nama_Member'] ?></td>
                            <td><?= $booking['session_time'] ?></td>
                            <td><?= $booking['Latihan'] ?></td>
                            <td>
                                <?php if ($booking['Confirm'] === 'request_reschedule'): ?>
                                    <span class="confirm-statusR">Meminta Reschedule</span>
                                <?php elseif ($booking['Confirm'] !== 'done'): ?>
                                    <button 
                                        type="button" 
                                        class="btn-confirm" 
                                        onclick="confirmSessionDone('<?= $booking['ID_Sesi'] ?>')">
                                        Selesai
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn-reschedule" 
                                        onclick="openRescheduleModal('<?= $booking['ID_Sesi'] ?>')">
                                        Reschedule
                                    </button>
                                <?php else: ?>
                                    <span class="confirm-status">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
                    
            <!-- modal request reschedule -->
            <div id="rescheduleModal" class="modal" style="display: none; padding-top: 100px;">
                <div class="modal-content" style="width:fit-content">
                    <h2 class="modal-title" style="color: #FFD700">Request Reschedule Sesi</h2>
                    <form id="rescheduleForm" action="/PT/requestReschedule" method="POST">
                        <input type="hidden" id="rescheduleID_Sesi" name="ID_Sesi">
                        <div class="form-row">
                            <textarea id="pesan" name="pesan" rows="4" placeholder="isi pesan anda" required></textarea>
                        </div>
                        <div class="form-row buttons">
                            <button type="submit" id="saveButtonReschedule" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn-cancel" onclick="closeRescheduleModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal untuk Edit Personal Trainer -->
                <div id="editModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <h2 class="modal-title" style="color: #FFD700">Edit Personal Trainer</h2>
                        <form id="editform" action="/PT/update" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="editID_PT" name="ID_PT">
                            <div class="form-row">
                                <label>Nama PT : </label>
                                <input type="text" id="editNama_PT" name="Nama_PT" required>
                            </div>
                            <div class="form-row">
                                <input type="hidden" id="editPassword" name="Password" required>
                            </div>
                            <div class="form-row">
                                <label>Foto PT : </label>
                                <div class="input-group">
                                    <div class="image-preview">
                                        <img id="imagePreview" src="" alt="Foto PT Saat Ini">
                                    </div>
                                    <div class="file-input-container">
                                        <input type="hidden" id="currentFoto" name="currentFoto">
                                        <input type="file" id="Foto_PT" name="Foto_PT" onchange="displayFileName()" accept="image/*">
                                        <span id="file-name"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Prestasi : </label>
                                <input type="text" id="editPrestasi" name="Prestasi" required>
                            </div>
                            <div class="form-row">
                                <label>Spesialisasi : </label>
                                <input type="text" id="editSpesialisasi" name="Spesialisasi" required>
                            </div>
                            <div class="form-row">
                                <label>Harga per Sesi : </label>
                                <input type="number" id="editHarga_Sesi" name="Harga_Sesi" required>
                            </div>
                            <div class="form-row buttons">
                                <button type="submit" id="saveButton" class="btn btn-primary" >Save Changes</button>
                                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>

        <!-- Jadwal Section -->
        <div id="jadwal-section" class="content-section">
            <!-- Content of Jadwal section goes here -->
             <!-- Modal Kalender -->
                <div id="modalKalender">
                        <h2 id="kalenderTitle">Jadwal PT - <span id="trainerName"></span></h2>
                        <!-- Tombol Navigasi Bulan -->
                        <div class="calendar-navigation">
                            <button id="prevMonth" class="btn">Bulan Sebelumnya</button>
                            <div id="calendarMonthYear" class="NamaBulan"></div>
                            <button id="nextMonth" class="btn">Bulan Berikutnya</button>
                        </div>
                        <table class="calendar-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Min</th>
                                    <th style="width: 80px;">Sen</th>
                                    <th style="width: 80px;">Sel</th>
                                    <th style="width: 80px;">Rab</th>
                                    <th style="width: 80px;">Kam</th>
                                    <th style="width: 80px;">Jum</th>
                                    <th style="width: 80px;">Sab</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody" class="calendar-container">
                                <!-- Tanggal akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                        <!-- Keterangan Warna -->
                        <div class="color-legend">
                            <div><span class="legend available-day"></span> Tersedia</div>
                            <div><span class="legend unavailable-day"></span> Tidak Tersedia</div>
                            <div><span class="legend today"></span> Hari Ini</div>
                        </div>
                </div>

                 <!-- Modal Jadwal Detail -->
                    <div id="modalJadwal" class="jadwalpt-modal" style="display: none;">
                        <div class="jadwalpt-modal-content">
                            <span class="close" onclick="closeJadwalModal()" style="color: red; font-size: 60px">&times;</span>
                            <h2 id="jadwalPTTitle">Jadwal PT</h2>
                            <div id="slotContainer">
                            </div>
                            <button id="saveJadwalBtn" onclick="saveAllJadwal(<?= $trainer['ID_PT']; ?>, selectedDate, selectedTrainerName)" class="btn btn-primary">Save</button>
                        </div>
                    </div>

                    <div class="notice-container">
                        <h3 class="notice-title">Ketentuan</h3>
                        <ul class="notice-list">
                            <li>1. Jadwal yang sudah diisi tidak dapat ditarik kembali.</li>
                            <li>2. Jika terjadi halangan namun jadwal Anda pada hari itu sudah ada yang booking, maka Anda bisa meminta reschedule.</li>
                            <li>3. Harap klik selesai di jadwal hari ini setiap kali Anda sudah menyelesaikan sesi pelatihan.</li>
                        </ul>
                    </div>

        </div>

        <!-- Booking Section -->
        <div id="booking-section" class="content-section">
            <h2>Daftar Member yang Booking Sesi</h2>

                <!-- Filter Dropdown -->
                <div class="filter-container">
                    <label for="filterMember">Filter by Member:</label>
                    <select id="filterMember" onchange="filterByMember()">
                        <option value="">All Members</option>
                        <?php foreach (array_unique(array_column($training, 'Nama_Member')) as $member): ?>
                            <option value="<?= $member ?>"><?= $member ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="searchmember">
                        <input type="search" class="search" id="search-member" placeholder="Cari Nama member..." style="width: 200px;" />
                    </div>
                    <label for="filter-tglmulai" style="margin-bottom: 0; margin-left: 15px;">Filter by Bulan:</label>
                    <input type="month" id="filter-tglmulai" class="filterbulan"  style="width: 180px; margin-right: 10px">
                </div>
                <table id="bookingTable" class="booking-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Member</th>
                            <th>Tanggal</th>
                            <th>Jam Sesi</th>
                            <th>Latihan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($training as $train): ?>
                            <tr data-id="<?= $train['ID_Sesi'] ?>" data-member="<?= $train['Nama_Member'] ?>">
                                <td><?= $no++ ?></td>
                                <td><?= $train['Nama_Member'] ?></td>
                                <td><?= date('d-m-Y', strtotime($train['date'])) ?></td>
                                <td><?= $train['session_time'] ?></td>
                                <td>
                                    <?php if (empty($train['Latihan'])): ?>
                                        <button class="buttonlatihan" onclick="editLatihan(<?= $train['ID_Sesi'] ?>, '')">Isi Latihan</button>
                                    <?php else: ?>
                                        <span>
                                            <?= htmlspecialchars($train['Latihan']) ?>
                                            <button class="buttoneditlatihan" onclick="editLatihan(<?= $train['ID_Sesi'] ?>, '<?= htmlspecialchars($train['Latihan']) ?>')" >
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($train['Confirm'] === 'request_reschedule'): ?>
                                        <span class="confirm-statusR">Meminta Reschedule</span>
                                    <?php elseif ($train['Confirm'] === 'done'): ?>
                                        <span class="confirm-status">Selesai</span>
                                    <?php else: ?>
                                        <button 
                                            type="button" 
                                            class="btn-reschedule1" 
                                            onclick="openRescheduleModal1('<?= $train['ID_Sesi'] ?>')">
                                            Reschedule
                                        </button>
                                    <?php endif; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                            <!-- modal untuk latihan -->
                            <div id="latihanModal" class="custom-modal" style="display: none;">
                                <div class="custommodal-content">
                                    <h3 class="modal-title" style="color: #FFD700">Isi Latihan</h3>
                                    <form id="latihanForm">
                                        <input type="hidden" id="ID_Sesi" name="ID_Sesi">
                                        <div class="form-group">
                                            <label for="Latihan"></label>
                                            <textarea class="latihan" id="Latihan" name="Latihan" rows="4" placeholder="isi latihan..." required></textarea>
                                        </div>
                                        <div class="form-row buttons">
                                            <button type="button" class="btn-save" onclick="saveLatihan()">Save</button>
                                            <button type="button" class="btn-cancel" onclick="closeLatihanModal()">Cancel</button>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>

                            <!-- modal request reschedule -->
                            <div id="rescheduleModal1" class="modal" style="display: none; padding-top: 100px;">
                                <div class="modal-content" style="width:fit-content">
                                    <h2 class="modal-title" style="color: #FFD700">Request Reschedule Sesi</h2>
                                    <form id="rescheduleForm1" action="/PT/requestReschedule1" method="POST">
                                        <input type="hidden" id="rescheduleID_Sesi1" name="ID_Sesi1">
                                        <div class="form-row">
                                            <textarea id="pesan1" name="pesan1" rows="4" placeholder="isi pesan anda" required></textarea>
                                        </div>
                                        <div class="form-row buttons">
                                            <button type="submit" id="saveButtonReschedule1" class="btn btn-primary">Submit</button>
                                            <button type="button" class="btn-cancel" onclick="closeRescheduleModal1()">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
        </div>
    </div>
    <script>
    const successMessage = "<?= session()->getFlashdata('sukses') ?>";
    const successMessageReschedule = "<?= session()->getFlashdata('sukses_reschedule') ?>";
    const successMessageS = "<?= session()->getFlashdata('sukses_selesai') ?>";

</script>
    <script src="/Javascript/PersonalTrainer.js">
    </script>
</body>
</html>
