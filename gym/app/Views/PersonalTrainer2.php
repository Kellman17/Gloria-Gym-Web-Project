<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Personal Trainer</title>
    <link rel="stylesheet" href="/css/PersonalTrainer.css">
</head>
<body>

    <!-- Tombol untuk membuka modal -->
     <div class="tambah">
         <button id="openPTModal" class="btn btn__primary">Tambah Personal Trainer</button>
     </div>

    <!-- Modal Form Tambah PT -->
    <div id="ptModal" class="popup__form">
    <div class="popup__content">
        <span class="close" onclick="closeForm()">&times;</span>
        <h2>Tambah Personal Trainer</h2>
        <form action="/PT/store" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <label for="Nama_PT">Nama PT:</label>
                <input type="text" id="Nama_PT" name="Nama_PT" required />
            </div>
            <div class="form-row">
                <label for="Foto_PT">Foto PT:</label>
                <input type="file" id="Foto_PT" name="Foto_PT" required />
            </div>
            <div class="form-row">
                <label for="Prestasi">Prestasi:</label>
                <input type="text" id="Prestasi" name="Prestasi" required />
            </div>
            <div class="form-row">
                <label for="Spesialisasi">Spesialisasi:</label>
                <input type="text" id="Spesialisasi" name="Spesialisasi" required />
            </div>
            <div class="form-row">
                <label for="Harga_Sesi">Harga per Sesi:</label>
                <input type="number" id="Harga_Sesi" name="Harga_Sesi" required />
            </div>
            <button type="submit" class="btn btn__primary">Simpan</button>
        </form>
    </div>
    </div>

    <!-- Modal untuk Edit Personal Trainer -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Personal Trainer</h2>
            <form action="/PT/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="editID_PT" name="ID_PT">
                <div class="form-row">
                    <label>Nama PT:</label>
                    <input type="text" id="editNama_PT" name="Nama_PT" required>
                </div>
                <div class="form-row">
                    <label>Foto PT:</label>
                    <input type="file" id="Foto_PT" name="Foto_PT" required>
                </div>
                <div class="form-row">
                    <label>Prestasi:</label>
                    <input type="text" id="editPrestasi" name="Prestasi" required>
                </div>
                <div class="form-row">
                    <label>Spesialisasi:</label>
                    <input type="text" id="editSpesialisasi" name="Spesialisasi" required>
                </div>
                <div class="form-row">
                    <label>Harga per Sesi:</label>
                    <input type="number" id="editHarga_Sesi" name="Harga_Sesi" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <!-- Daftar PT -->
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Foto</th>
                <th>Prestasi</th>
                <th>Spesialisasi</th>
                <th>Harga per Sesi</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trainers as $trainer): ?>
            <tr>
                <td><?= $trainer['Nama_PT']; ?></td>
                <td><img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']); ?>" alt="Foto PT" width="100"></td>
                <td><?= $trainer['Prestasi']; ?></td>
                <td><?= $trainer['Spesialisasi']; ?></td>
                <td>Rp<?= number_format($trainer['Harga_Sesi'], 0, ',', '.'); ?></td>
                <td><?= $trainer['Rating']; ?></td>
                <td>
                    <!-- Tambahkan tombol Jadwal -->
                    <a href="javascript:void(0)" onclick="loadTrainerSchedule('<?= $trainer['ID_PT']; ?>', '<?= $trainer['Nama_PT']; ?>')" class="btn btn-schedule">Jadwal</a>
                    <!-- Tambahkan tombol Update -->
                    <a href="javascript:void(0)" class="btn btn-update" onclick="openEditModal('<?= $trainer['ID_PT']; ?>', '<?= $trainer['Nama_PT']; ?>', '<?= $trainer['Foto_PT']; ?>', '<?= $trainer['Prestasi']; ?>', '<?= $trainer['Spesialisasi']; ?>', '<?= $trainer['Harga_Sesi']; ?>')">Update</a>
                    <!-- Tambahkan tombol Delete -->
                    <a href="/PT/delete/<?= $trainer['ID_PT']; ?>" onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')" class="btn btn-delete">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal Kalender -->
    <!-- Modal Kalender -->
    <!-- Modal Kalender -->
    <div id="modalKalender" class="jadwalpt-modal" style="display: none;">
        <div class="jadwalpt-modal-content">
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
                        <th>Min</th>
                        <th>Sen</th>
                        <th>Sel</th>
                        <th>Rab</th>
                        <th>Kam</th>
                        <th>Jum</th>
                        <th>Sab</th>
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
            <button class="btn btn-primary" onclick="closeKalenderModal()">Kembali</button>
        </div>
    </div>



    <!-- Modal Jadwal Detail -->
    <div id="modalJadwal" class="jadwalpt-modal" style="display: none;">
    <div class="jadwalpt-modal-content">
        <span class="close" onclick="closeJadwalModal()">&times;</span>
        <h2 id="jadwalPTTitle">Jadwal PT</h2>
        <div id="slotContainer">
        </div>
        <button id="saveJadwalBtn" onclick="saveAllJadwal(selectedTrainerId, selectedDate, selectedTrainerName)" class="btn btn-primary">Simpan Jadwal</button>
    </div>
    </div>






    <script src="/Javascript/PersonalTrainer.js"></script>
</body>
</html>
