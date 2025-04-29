<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class | Gloria Gym</title>
    <link rel="stylesheet" href="/css/Homegym.css">
    <link rel="stylesheet" href="/css/homemembership.css">
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


</head>
<body>
    <nav>
        <div class="nav__bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="/"><img src="/assets/Logo1.png" alt="logo"></a>
                </div>
            </div>
            <ul class="nav__links">
                <li><a href="/">HOME</a></li>
                <li><a href="/about">ABOUT</a></li>
                <li><a href="/class">CLASS</a></li>
                <li><a href="/trainer" >TRAINER</a></li>
                <li><a href="/instructor">INSTRUCTOR</a></li>
                <li><a href="/membership" style="color: red">MEMBERSHIP</a></li>
                <li><a href="/contact">CONTACT US</a></li>
                <li><a href="/Portal">LOGIN</a></li>
            </ul>
        </div>
    </nav>

    <section class="membership" id="membership">
        <div class="section__container membership__container">
            <h3 class="section__header" style="font-size: 60px; font-weight: 500">MEMBERSHIP GYM</h3>
            
            <!-- Section Harian dan Bulanan Gym -->
            <div class="membership__group">
            <div class="membership__grid">
                <?php foreach ($memberships as $membership): ?>
                <!-- Kartu Membership Harian -->
                <?php if ($membership['ID_Membership'] == '1'): ?>
                    <div class="membership__card">
                        <h3>Harian Gym</h3>
                        <ul>
                            <li><i class="ri-check-line"></i> Akses penuh ke gym untuk satu hari.</li>
                            <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                            <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari</li>
                            <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>

                        </ul>
                        <a href="/Portal"><button class="btn-buy">BELI SEKARANG</button></a>
                    </div>
                    <?php endif; ?>

                    <!-- Kartu Membership Bulanan Gym -->
                    <?php if ($membership['ID_Membership'] == '2'): ?>
                    <div class="membership__card">
                        <h3>Bulanan Gym</h3>
                        <ul>
                            <li><i class="ri-check-line"></i> Akses penuh ke gym selama satu bulan.</li>
                            <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                            <li><i class="ri-check-line"></i> Gratis konsultasi singkat dengan trainer kami.</li>
                            <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari</li>
                            <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>

                        </ul>
                        <a href="/Portal"><button class="btn-buy">BELI SEKARANG</button></a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            </div>
            
            <h3 class="section__header" style="font-size: 60px; font-weight: 500; margin-top: 20px">MEMBERSHIP CLASS</h3>
            <!-- Section Bulanan Class -->
            <div class="membership__group">
            <div class="membership__grid">
                <?php foreach ($memberships as $membership): ?>
                <!-- Kartu Membership Bulanan Class -->
                <?php if ($membership['ID_Membership'] == '3'): ?>
                    <div class="membership__card">
                        <h3>Bulanan Class</h3>
                        <ul style="padding-left: 20%">
                            <li><i class="ri-check-line"></i> Akses ke semua kelas fitness: Aerobic, Yoga, Zumba.</li>
                            <li><i class="ri-check-line"></i> Akses penuh ke ruang loker dan shower.</li>
                            <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari</li>
                            <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>

                        </ul>
                        <!-- Tabel Jadwal -->
                        <div style="margin-bottom: 15px;">
                            <input type="text" id="filter-class" placeholder="Cari Class">
                            <input type="text" id="filter-instructor" placeholder="Cari Instruktur">
                            <input type="date" id="filter-date" > 
                            <select id="filter-time" class="form-select" style="width: 150px;">
                                <option value="">Pilih Waktu</option>
                                <option value="08:00 - 09:30">08:00 - 09:30</option>
                                <option value="11:00 - 12:30">11:00 - 12:30</option>
                                <option value="14:00 - 15:30">14:00 - 15:30</option>
                                <option value="17:00 - 18:30">17:00 - 18:30</option>
                                <option value="20:00 - 21:30">20:00 - 21:30</option>
                            </select>
                        </div>
                        <table id="class-schedule-table" class="class-schedule-table">
                            <thead>
                                <tr>
                                    <th>Nama Class</th>
                                    <th>Nama Instruktur</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($classes as $class): ?>
                                    <tr>
                                        <td><?= $class['Nama_Class']; ?></td>
                                        <td><?= $class['Nama_Instruktur']; ?></td>
                                        <td><?= date('d-m-Y', strtotime($class['Tanggal'])); ?></td>
                                        <td><?= $class['Jam']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <a href="/Portal"><button class="btn-buy">BELI SEKARANG</button></a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            </div>
        </div>
    </section>
    <script>
   $(document).ready(function () {
    // Set default date ke hari ini
    var today = new Date();
    var formattedToday = today.toISOString().split('T')[0]; // Format YYYY-MM-DD
    $('#filter-date').val(formattedToday); // Set tanggal default

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
    $('#filter-date').on('change', function () {
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


</script>



</body>
</html>
