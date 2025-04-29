<section class="membership" id="membership" class="section" style="display: none;">
    <div class="section__container membership__container">
        <h2 class="section__header">MEMBERSHIP</h2>

        <div class="membership__grid gym-membership">
            <!-- Cek jika ada memberships -->
            <?php if (!empty($memberships)): ?>
                <?php foreach ($memberships as $membership): ?>
                    <?php 
                        // Cek apakah ada membership Bulanan Gym atau Harian yang Pending/Aktif
                        $hasActiveBulananGym = false;
                        $hasActiveHarian = false;

                        if (!empty($membershipStatuses)) {
                            foreach ($membershipStatuses as $status) {
                                if ($status['ID_Membership'] == '1' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                                    $hasActiveHarian = true; // Harian sedang Pending/Aktif
                                }
                                if ($status['ID_Membership'] == '2' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                                    $hasActiveBulananGym = true; // Bulanan Gym sedang Pending/Aktif
                                }
                            }
                        }
                    ?>
                        <!-- Kartu Membership Harian -->
                    <?php if (!$hasActiveBulananGym): ?> <!-- Tidak tampil jika Bulanan Gym aktif -->
                        <?php if ($membership['ID_Membership'] == '1'): ?>
                            <div class="membership__card">
                                <h3><?= $membership['Jenis_Membership'] ?></h3>

                                <!-- Cek status membership untuk Harian -->
                                <?php 
                                    // Cek status pending atau aktif
                                    $isPending = false;
                                    $isActive = false;

                                    if (!empty($membershipStatuses)) {
                                        foreach ($membershipStatuses as $status) {
                                            if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                                if ($status['Status'] == 'Pending') {
                                                    $isPending = true;
                                                }
                                                if ($status['Status'] == 'Aktif') {
                                                    $isActive = true;
                                                }
                                                break; // Keluar loop setelah ditemukan
                                            }
                                        }
                                    }
                                ?>

                                <!-- Jika membership sedang dalam status pending -->
                                <?php if ($isPending): ?>
                                    <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                                <!-- Jika membership aktif -->
                                <?php elseif ($isActive): ?>
                                    <p class="text-success" >Your membership is active!</p>
                                    <div class="membership-info-wrapper">
                                        <table class="membership-info-table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tipe Membership:</strong></td>
                                                    <td><?= $membership['Jenis_Membership'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berlaku:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berakhir:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Harga:</strong></td>
                                                    <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <!-- Jika membership tidak aktif -->
                                    <ul>
                                        <li><i class="ri-check-line"></i> Akses penuh ke gym dan semua fasilitas untuk satu hari.</li>
                                        <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                        <li><i class="ri-check-line"></i> Konsultasi fitness singkat dengan pelatih kami.</li>
                                        <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari</li>
                                        <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?>/hari</li>
                                    </ul>
                                    <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                        <!-- Kartu Membership Bulanan Gym -->
                    <?php if (!$hasActiveHarian): ?> <!-- Tidak tampil jika Harian aktif -->
                        <?php if ($membership['ID_Membership'] == '2'): ?>
                            <div class="membership__card">
                                <h3><?= $membership['Jenis_Membership'] ?></h3>

                                <!-- Cek status membership untuk Bulanan Gym -->
                                <?php 
                                    // Cek status pending atau aktif
                                    $isPending = false;
                                    $isActive = false;

                                    if (!empty($membershipStatuses)) {
                                        foreach ($membershipStatuses as $status) {
                                            if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                                if ($status['Status'] == 'Pending') {
                                                    $isPending = true;
                                                }
                                                if ($status['Status'] == 'Aktif') {
                                                    $isActive = true;
                                                }
                                                break; // Keluar loop setelah ditemukan
                                            }
                                        }
                                    }
                                ?>

                                <!-- Jika membership sedang dalam status pending -->
                                <?php if ($isPending): ?>
                                    <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                                <!-- Jika membership aktif -->
                                <?php elseif ($isActive): ?>
                                    <p class="text-success">Your membership is active!</p>
                                    <div class="membership-info-wrapper">
                                        <table class="membership-info-table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tipe Membership:</strong></td>
                                                    <td><?= $membership['Jenis_Membership'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berlaku:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berakhir:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Harga:</strong></td>
                                                    <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Jika Member Menggunakan PT, tampilkan Jadwal PT -->
                                    <div class="personal-training-wrapper">
                                    <?php if (!empty($personalTraining) && $status['Jenis_Membership'] === 'Bulanan_Gym' && $status['Pakai_PT'] === 'ya'): ?>            
                                        <table class="personal-training-table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" >JADWAL PERSONAL TRAINING</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jam Sesi</th>
                                                        <th>Nama PT</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($personalTraining as $schedule): ?>
                                                        <tr>
                                                            <td><?= date('d-m-Y', strtotime($schedule['date'])) ?></td>
                                                            <td><?= $schedule['session_time'] ?></td>
                                                            <td><?= $schedule['Nama_PT'] ?></td>
                                                            <td>
                                                            <?php 
                                                                $today = date('Y-m-d'); // Tanggal hari ini
                                                                $sessionDate = date('Y-m-d', strtotime($schedule['date'])); // Tanggal sesi
                                
                                                                if (empty($schedule['rating'])):
                                                                    if ($sessionDate === $today): // Jika tanggal sesi sama dengan hari ini
                                                                ?>
                                                                <!-- Button "On Progress" -->
                                                                <button class="btnReview" onclick="openReviewModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>',
                                                                '<?= $schedule['Nama_PT']; ?>', '<?= $schedule['ID_Member'] ?>', '<?= $schedule['Nama_Member']; ?>', '<?= $schedule['date'] ?>',
                                                                '<?= $schedule['session_time']; ?>', 
                                                                '<?= $schedule['status']; ?>', '<?= $schedule['rating']; ?>', '<?= $schedule['review']; ?>')">
                                                                <i class="fa-solid fa-clock"></i></button>

                                                                <?php  elseif ($sessionDate > $today): ?>
                                                                    <!-- Jika tanggal belum sama dengan hari ini -->
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                    <i class="fa-solid fa-calendar"></i>
                                                                    </button>

                                                                <?php endif; else: ?>
                                                                <!-- Button "Done" setelah review -->
                                                                <button class="btnDone" disabled><i class="fa-solid fa-check"></i></button>
                                                            <?php endif; ?>
                                                        </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            
                                            <!-- Modal untuk memberikan rating dan review -->
                                            <div id="reviewForm" class="popup__formreview">
                                            <div class="popup__contentreview">
                                                <h2>Berikan Rating & Review untuk Sesi Personal Training</h2>
                                                <form action="/submitReview" method="POST">
                                                <!-- Sesi ID dan ID Member disembunyikan -->
                                                <!-- Fields yang disembunyikan -->
                                                <input type="hidden" id="reviewSessionId" name="ID_Sesi">
                                                <input type="hidden" id="reviewIdPT" name="ID_PT">
                                                <input type="hidden" id="reviewIdMember" name="ID_Member">
                                                <input type="hidden" id="reviewStatus" name="status">
                                                
                                                <!-- Nama PT -->
                                                <div class="formreview-row">
                                                    <label for="reviewNamaPT">Nama PT:</label>
                                                    <input type="text" id="reviewNamaPT" name="Nama_PT" disabled>
                                                </div>
                                                
                                                <!-- Nama Member -->
                                                <div class="formreview-row">
                                                    <label for="reviewNamaMember">Nama Member:</label>
                                                    <input type="text" id="reviewNamaMember" name="Nama_Member" disabled>
                                                </div>
                                                
                                                    <!-- Tanggal -->
                                                    <div class="formreview-row">
                                                        <label for="reviewDate">Tanggal:</label>
                                                        <input type="text" id="reviewDate" name="date" disabled>
                                                    </div>

                                                    <!-- Session Time -->
                                                    <div class="formreview-row">
                                                        <label for="reviewSessionTime">Session Time:</label>
                                                        <input type="text" id="reviewSessionTime" name="session_time" disabled>
                                                    </div>

                                                    <!-- Rating (1-5) -->
                                                    <div class="formreview-row">
                                                    <label for="rating">Rating:</label>
                                                        <div class="star-rating" id="starRating">
                                                            <!-- Bintang Rating -->
                                                            <span class="star" data-value="1">&#9733;</span>
                                                            <span class="star" data-value="2">&#9733;</span>
                                                            <span class="star" data-value="3">&#9733;</span>
                                                            <span class="star" data-value="4">&#9733;</span>
                                                            <span class="star" data-value="5">&#9733;</span>
                                                        </div>
                                                        <!-- Input hidden untuk menyimpan nilai rating -->
                                                        <input type="hidden" name="rating" id="rating" required>
                                                    </div>
                                                    
                                                    <!-- Review -->
                                                    <div class="formreview-row">
                                                    <label for="review">Review:</label>
                                                    <textarea name="review" id="review" placeholder="Tulis Review Anda..." required></textarea>
                                                    </div>
                                                    
                                                    <!-- Submit -->
                                                    <button type="submit" class="btnkirim">Kirim Review</button>
                                                    <button type="cancel" class="btn btn-danger" style="background-color : red" onclick="closeReviewModal()">Cancel</button>
                                                    
                                                </form>
                                                </div>
                                            </div>
                                            
                                            <!-- Modal Reschedule -->
                                            <div id="unique_reschedule_form" class="popup__formreview">
                                                <div class="popup__contentreview">
                                                    <h2>Reschedule Personal Training</h2>
                                                    <form action="/rescheduleSession" method="post">
                                                        <input type="hidden" id="reschedule_ID_Sesi" name="ID_Sesi" >
                                                        <input type="hidden" id="Unique_ID_PT" name="ID_PT" >
                                                        <input type="hidden" id="Unique_ID_Member" name="ID_Member">

                                                        <!-- Input Tanggal -->
                                                        <div class="formreview-row">
                                                            <label for="unique_tanggal_reschedule">Pilih Tanggal:</label>
                                                            <input type="text" id="unique_tanggal_reschedule" name="tanggal" required>
                                                        </div>

                                                        <!-- Dropdown Sesi -->
                                                        <div class="formreview-row">
                                                            <label for="unique_session_container">Pilih Sesi:</label>
                                                            <div id="unique_session_container" ></div>
                                                        </div>

                                                        <button type="submit" class="btn-reschedule">Reschedule</button>
                                                        <button type="button" class="btn-cancel-reschedule" onclick="closeRescheduleModal()">Cancel</button>
                                                    </form>
                                                </div>
                                            </div>


                                    <?php else: ?>
                                            <p style="color: red">Anda tidak memakai personal trainer.</p>
                                            <button class="btn add-pt" style="background-color: green" onclick="openAddPTModal( '<?= $status['ID_Record'] ?>','<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>')">Tambah PT</button>
                                    <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                <!-- Jika membership tidak aktif -->
                                <ul>
                                    <li><i class="ri-check-line"></i> Akses penuh ke gym dan semua fasilitas selama sebulan.</li>
                                    <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                    <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> bulan</li>
                                    <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                                    </ul>
                                    <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Kartu Membership Bulanan Class -->
                    <?php if ($membership['ID_Membership'] == '3'): ?>
                        <div class="membership__card">
                            <h3><?= $membership['Jenis_Membership'] ?></h3>

                            <!-- Cek status membership untuk Bulanan Class -->
                            <?php 
                                // Cek status pending atau aktif
                                $isPending = false;
                                $isActive = false;

                                if (!empty($membershipStatuses)) {
                                    foreach ($membershipStatuses as $status) {
                                        if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                            if ($status['Status'] == 'Pending') {
                                                $isPending = true;
                                            }
                                            if ($status['Status'] == 'Aktif') {
                                                $isActive = true;
                                            }
                                            break; // Keluar loop setelah ditemukan
                                        }
                                    }
                                }
                            ?>

                            <!-- Jika membership sedang dalam status pending -->
                            <?php if ($isPending): ?>
                                <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                            <!-- Jika membership aktif -->
                            <?php elseif ($isActive): ?>
                                <p class="text-success" >Your membership is active!</p>
                                <div class="membership-info-wrapper">
                                    <table class="membership-info-table">
                                        <tbody>
                                            <tr>
                                                <td><strong>Tipe Membership:</strong></td>
                                                <td><?= $membership['Jenis_Membership'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Berlaku:</strong></td>
                                                <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Berakhir:</strong></td>
                                                <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Harga:</strong></td>
                                                <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                               <!-- Form Pilih Tanggal -->
                                <div class="class-schedule-filter" style="margin: 15px;">
                                    <form action="" method="GET">
                                        <label for="selectDate" style="color: white;"><strong>Pilih Tanggal:</strong></label>
                                        <input type="date" id="selectDate" name="selected_date"
                                            value="<?= isset($selectedDate) ? $selectedDate : date('Y-m-d') ?>" 
                                            max="<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>">
                                        <button type="submit" class="btn-filter" style="background-color: green; color: white; border: none; padding: 5px 10px;">Tampilkan</button>
                                    </form>
                                </div>

                                <!-- Cek kelas hari ini -->
                                <div class="class-schedule">
                                    <?php if (!empty($classesToday)): ?>
                                    <table class="class-schedule-table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">JADWAL KELAS</th>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Kelas</th>
                                                <th>Instruktur</th>
                                                <th>Jam</th>
                                                <th>Kuota</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($classesToday as $class): ?>
                                                <tr>
                                                    <td><?= date('d-m-Y', strtotime($class['Tanggal'])) ?></td>
                                                    <td><?= $class['Nama_Class'] ?></td>
                                                    <td><?= $class['Nama_Instruktur'] ?></td>
                                                    <td><?= $class['Jam'] ?></td>
                                                    <td><?= $class['Kuota'] ?></td>
                                                    <td>
                                                        <?php 
                                                        // Cek apakah member yang sedang login sudah memesan kelas ini
                                                        $isBooked = false;
                                                        foreach ($bookedClasses as $booking) {
                                                            if ($booking['ID_Class'] == $class['ID_Class'] && $booking['ID_Member'] == session()->get('ID_Member')) {
                                                                $isBooked = true;
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($isBooked): ?>
                                                            <button class="btncoupon" onclick="openCouponModal(<?= $class['ID_Class'] ?>, '<?= $class['Nama_Class'] ?>',
                                                            '<?= $class['Nama_Instruktur'] ?>', '<?= date('d-m-Y', strtotime($class['Tanggal'])) ?>', 
                                                            '<?= $class['Jam'] ?>')">Coupon</button>
                                                        <?php elseif ($class['Kuota'] > 0): ?>
                                                            <button class="btnbookclass" onclick="bookClass(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">BOOK</button>
                                                        <?php else: ?>
                                                            <button class="btnclassfull">FULL</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <!-- Modal Pop-up untuk Coupon -->
                                                <div id="couponModal" class="popup__formreview" style="display: none;">
                                                    <div class="popup__contentreview">
                                                      <span class="close" onclick="closeCouponModal()">&times;</span>
                                                      <h2>Booking Coupon</h2>
                                                        <div class="form-row">
                                                            <label for="couponMemberName">Nama Member:</label>
                                                            <input type="text" id="couponMemberName" value="<?= session()->get('Nama_Member'); ?>" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponClassName">Nama Class:</label>
                                                            <input type="text" id="couponClassName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponInstructorName">Nama Instruktur:</label>
                                                            <input type="text" id="couponInstructorName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponDate">Tanggal Kelas:</label>
                                                            <input type="text" id="couponDate" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponTime">Jam Kelas:</label>
                                                            <input type="text" id="couponTime" disabled>
                                                        </div>
                                                            <button type="cancel" class="btn btn-danger" onclick="cancelBooking(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">Cancel Booking</button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                        <p style="color: red">Hari ini tidak ada kelas yang tersedia.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Jika membership tidak aktif -->
                                <ul>
                                    <li><i class="ri-check-line"></i> Akses penuh ke kelas-kelas yang tersedia selama sebulan.</li>
                                    <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> bulan</li>
                                    <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                                </ul>
                              <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="membership" id="membership" class="section" style="display: none;">
    <div class="section__container membership__container">
        <h2 class="section__header">MEMBERSHIP GYM</h2>

        <!-- Wrapper untuk Gym Membership -->
        <div class="membership__grid gym-membership">
            <?php if (!empty($memberships)): ?>
                <?php foreach ($memberships as $membership): ?>
                    <?php 
                        // Cek apakah ada membership Bulanan Gym atau Harian yang Pending/Aktif
                        $hasActiveBulananGym = false;
                        $hasActiveHarian = false;

                        if (!empty($membershipStatuses)) {
                            foreach ($membershipStatuses as $status) {
                                if ($status['ID_Membership'] == '1' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                                    $hasActiveHarian = true; // Harian sedang Pending/Aktif
                                }
                                if ($status['ID_Membership'] == '2' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                                    $hasActiveBulananGym = true; // Bulanan Gym sedang Pending/Aktif
                                }
                                
                            }
                        }
                        
                    ?>

                    <!-- Kartu Membership Harian -->
                    <?php if (!$hasActiveBulananGym): ?>
                        <?php if (!$hasActiveBulananGym && $membership['ID_Membership'] == '1'): ?>
                            <div class="membership__card">
                                <h3 style="font-size: 48px">Harian Gym</h3>
                                <!-- Cek status membership untuk Harian -->
                                <?php 
                                    // Cek status pending atau aktif
                                    $isPending = false;
                                    $isActive = false;

                                    if (!empty($membershipStatuses)) {
                                        foreach ($membershipStatuses as $status) {
                                            if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                                if ($status['Status'] == 'Pending') {
                                                    $isPending = true;
                                                }
                                                if ($status['Status'] == 'Aktif') {
                                                    $isActive = true;
                                                }
                                            }
                                        }
                                    }
                                ?>

                                <!-- Jika membership sedang dalam status pending -->
                                <?php if ($isPending): ?>
                                    <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                                <!-- Jika membership aktif -->
                                <?php elseif ($isActive): ?>
                                    <p class="text-success" >Your membership is active!</p>
                                    <div class="membership-info-wrapper">
                                        <table class="membership-info-table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tipe Membership:</strong></td>
                                                    <td><?= $membership['Jenis_Membership'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berlaku:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berakhir:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Harga:</strong></td>
                                                    <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <!-- Jika membership tidak aktif -->
                                    <ul>
                                        <li><i class="ri-check-line"></i> Akses penuh ke gym dan semua fasilitas untuk satu hari.</li>
                                        <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                        <li><i class="ri-check-line"></i> Konsultasi fitness singkat dengan pelatih kami.</li>
                                        <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari</li>
                                        <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?>/hari</li>
                                    </ul>
                                    <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Kartu Membership Bulanan Gym -->
                    <?php if (!$hasActiveHarian): ?>
                        <?php if (!$hasActiveHarian && $membership['ID_Membership'] == '2'): ?>
                            <div class="membership__card">
                                <h3 style="font-size: 48px">BULANAN GYM</h3>
                                <!-- Cek status membership untuk Bulanan Gym -->
                                <?php 
                                    // Cek status pending atau aktif
                                    $isPending = false;
                                    $isActive = false;
                                    $isAddOnPtPending = false;

                                    if (!empty($membershipStatuses)) {
                                        foreach ($membershipStatuses as $status) {
                                            if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                                if ($status['Status'] == 'Pending') {
                                                    $isPending = true;
                                                }
                                                if ($status['Status'] == 'Aktif') {
                                                    $isActive = true;
                                                }
                                               
                                            }
                                        }
                                    }
                                    // Cek status add-on PT (Pending)
                                    if (!empty($addOnPtStatuses)) {
                                        foreach ($addOnPtStatuses as $ptStatus) {
                                            if ($ptStatus['ID_Record'] == $status['ID_Record'] && $ptStatus['StatusPT'] == 'Pending') {
                                                $isAddOnPtPending = true;
                                                break; // Keluar loop setelah ditemukan
                                            }
                                        }
                                    }
                                ?>
                                
                                <!-- Jika membership sedang dalam status pending -->
                                <?php if ($isPending): ?>
                                    <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                                <!-- Jika membership aktif -->
                                <?php elseif ($isActive): ?>
                                    <p class="text-success">Your membership is active!</p>
                                    <div class="membership-info-wrapper">
                                        <table class="membership-info-table">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Tanggal Berlaku:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Berakhir:</strong></td>
                                                    <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Harga:</strong></td>
                                                    <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Jika Member Menggunakan PT, tampilkan Jadwal PT -->
                                    <div class="personal-training-wrapper">
                                    <?php if (!empty($personalTraining) && $status['Jenis_Membership'] === 'Bulanan_Gym' && $status['Pakai_PT'] === 'ya'): ?>            
                                        <table class="personal-training-table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" >JADWAL PERSONAL TRAINING</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jam Sesi</th>
                                                        <th>Nama PT</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($personalTraining as $schedule): ?>
                                                        <tr>
                                                            <td><?= date('d-m-Y', strtotime($schedule['date'])) ?></td>
                                                            <td><?= $schedule['session_time'] ?></td>
                                                            <td><?= $schedule['Nama_PT'] ?></td>
                                                            <td>
                                                            <?php 
                                                                $today = date('Y-m-d'); // Tanggal hari ini
                                                                $sessionDate = date('Y-m-d', strtotime($schedule['date'])); // Tanggal sesi
                                
                                                                if (empty($schedule['rating'])):
                                                                    if ($sessionDate === $today  && $schedule['Confirm'] === 'done'): // Jika tanggal sesi sama dengan hari ini
                                                                ?>
                                                                <!-- Button "On Progress" -->
                                                                <button class="btnReview" onclick="openReviewModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>',
                                                                '<?= $schedule['Nama_PT']; ?>', '<?= $schedule['ID_Member'] ?>', '<?= $schedule['Nama_Member']; ?>', '<?= $schedule['date'] ?>',
                                                                '<?= $schedule['session_time']; ?>', 
                                                                '<?= $schedule['status']; ?>', '<?= $schedule['rating']; ?>', '<?= $schedule['review']; ?>')">
                                                                <i class="fa-solid fa-clock"></i></button>

                                                                <?php  elseif ($sessionDate === $today && $schedule['Confirm'] !== 'done'): ?>
                                                                    <!-- Jika tanggal belum sama dengan hari ini -->
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                    <i class="fa-solid fa-calendar"></i>
                                                                    </button>

                                                                <?php  elseif ($sessionDate > $today): ?>
                                                                    <!-- Jika tanggal belum sama dengan hari ini -->
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                    <i class="fa-solid fa-calendar"></i>
                                                                    </button>

                                                                <?php endif; else: ?>
                                                                <!-- Button "Done" setelah review -->
                                                                <button class="btnDone" disabled><i class="fa-solid fa-check"></i></button>
                                                            <?php endif; ?>
                                                        </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            
                                            <!-- Modal untuk memberikan rating dan review -->
                                            <div id="reviewForm" class="popup__formreview">
                                            <div class="popup__contentreview">
                                                <h2>Berikan Rating & Review untuk Sesi Personal Training</h2>
                                                <form action="/submitReview" method="POST">
                                                <!-- Sesi ID dan ID Member disembunyikan -->
                                                <!-- Fields yang disembunyikan -->
                                                <input type="hidden" id="reviewSessionId" name="ID_Sesi">
                                                <input type="hidden" id="reviewIdPT" name="ID_PT">
                                                <input type="hidden" id="reviewIdMember" name="ID_Member">
                                                <input type="hidden" id="reviewStatus" name="status">
                                                
                                                <!-- Nama PT -->
                                                <div class="formreview-row">
                                                    <label for="reviewNamaPT">Nama PT:</label>
                                                    <input type="text" id="reviewNamaPT" name="Nama_PT" disabled>
                                                </div>
                                                
                                                <!-- Nama Member -->
                                                <div class="formreview-row">
                                                    <label for="reviewNamaMember">Nama Member:</label>
                                                    <input type="text" id="reviewNamaMember" name="Nama_Member" disabled>
                                                </div>
                                                
                                                    <!-- Tanggal -->
                                                    <div class="formreview-row">
                                                        <label for="reviewDate">Tanggal:</label>
                                                        <input type="text" id="reviewDate" name="date" disabled>
                                                    </div>

                                                    <!-- Session Time -->
                                                    <div class="formreview-row">
                                                        <label for="reviewSessionTime">Session Time:</label>
                                                        <input type="text" id="reviewSessionTime" name="session_time" disabled>
                                                    </div>

                                                    <!-- Rating (1-5) -->
                                                    <div class="formreview-row">
                                                    <label for="rating">Rating:</label>
                                                        <div class="star-rating" id="starRating">
                                                            <!-- Bintang Rating -->
                                                            <span class="star" data-value="1">&#9733;</span>
                                                            <span class="star" data-value="2">&#9733;</span>
                                                            <span class="star" data-value="3">&#9733;</span>
                                                            <span class="star" data-value="4">&#9733;</span>
                                                            <span class="star" data-value="5">&#9733;</span>
                                                        </div>
                                                        <!-- Input hidden untuk menyimpan nilai rating -->
                                                        <input type="hidden" name="rating" id="rating" required>
                                                    </div>
                                                    
                                                    <!-- Review -->
                                                    <div class="formreview-row">
                                                    <label for="review">Review:</label>
                                                    <textarea name="review" id="review" placeholder="Tulis Review Anda..." required></textarea>
                                                    </div>
                                                    
                                                    <!-- Submit -->
                                                    <button type="submit" class="btnkirim">Kirim Review</button>
                                                    <button type="cancel" class="btn btn-danger" style="background-color : red" onclick="closeReviewModal()">Cancel</button>
                                                    
                                                </form>
                                                </div>
                                            </div>
                                            
                                            <!-- Modal Reschedule -->
                                            <div id="unique_reschedule_form" class="popup__formreview">
                                                <div class="popup__contentreview">
                                                    <h2>Reschedule Personal Training</h2>
                                                    <form action="/rescheduleSession" method="post">
                                                        <input type="hidden" id="reschedule_ID_Sesi" name="ID_Sesi" >
                                                        <input type="hidden" id="Unique_ID_PT" name="ID_PT" >
                                                        <input type="hidden" id="Unique_ID_Member" name="ID_Member">

                                                        <!-- Input Tanggal -->
                                                        <div class="formreview-row">
                                                            <label for="unique_tanggal_reschedule">Pilih Tanggal:</label>
                                                            <input type="text" id="unique_tanggal_reschedule" name="tanggal" required>
                                                        </div>

                                                        <!-- Dropdown Sesi -->
                                                        <div class="formreview-row">
                                                            <label for="unique_session_container">Pilih Sesi:</label>
                                                            <div id="unique_session_container" ></div>
                                                        </div>

                                                        <button type="submit" class="btn-reschedule">Reschedule</button>
                                                        <button type="button" class="btn-cancel-reschedule" onclick="closeRescheduleModal()">Cancel</button>
                                                    </form>
                                                </div>
                                            </div>
                                    <?php elseif(!empty($personalTraining) && $status['Jenis_Membership'] === 'Bulanan_Gym' && $status['Pakai_PT'] === 'ya'&& $ptStatus['StatusPT'] == 'Pending' ): ?>
                                            <p class="text-warning" style="color: yellow;">Your Add on PT plan is on process</p>
                                    <?php else: ?>
                                            <p style="color: red; margin-bottom: 20px">Anda tidak memakai personal trainer.</p>
                                            <button class="btn-add-pt" onclick="openAddPTModal( '<?= $status['ID_Record'] ?>','<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>')">Tambah PT</button>
                                    <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Jika membership tidak aktif -->
                                    <ul>
                                        <li><i class="ri-check-line"></i> Akses penuh ke gym dan semua fasilitas selama sebulan.</li>
                                        <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                        <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> bulan</li>
                                        <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                                    </ul>
                                    <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>




        <h2 class="section__header" style="margin-top: 70px">MEMBERSHIP CLASS</h2>
        <!-- Wrapper untuk Class Membership -->
        <div class="membership__grid class-membership">
            <?php if (!empty($memberships)): ?>
                <?php foreach ($memberships as $membership): ?>

                    <?php if ($membership['ID_Membership'] == '3'): ?>
                        <div class="membership__card">
                            <h3 style="font-size: 48px">Bulanan Class</h3>
                            <!-- Cek status membership untuk Bulanan Class -->
                            <?php 
                                // Cek status pending atau aktif
                                $isPending = false;
                                $isActive = false;

                                if (!empty($membershipStatuses)) {
                                    foreach ($membershipStatuses as $status) {
                                        if ($status['ID_Membership'] == $membership['ID_Membership']) {
                                            if ($status['Status'] == 'Pending') {
                                                $isPending = true;
                                            }
                                            if ($status['Status'] == 'Aktif') {
                                                $isActive = true;
                                            }
                                            break; // Keluar loop setelah ditemukan
                                        }
                                    }
                                }
                            ?>

                            <!-- Jika membership sedang dalam status pending -->
                            <?php if ($isPending): ?>
                                <p class="text-warning" style="color: yellow;">Your membership plan is on process</p>
                            <!-- Jika membership aktif -->
                            <?php elseif ($isActive): ?>
                                <p class="text-success" >Your membership is active!</p>
                                <div class="membership-info-wrapper">
                                    <table class="membership-info-table">
                                        <tbody>
                                            <tr>
                                                <td><strong>Tipe Membership:</strong></td>
                                                <td><?= $membership['Jenis_Membership'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Berlaku:</strong></td>
                                                <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Berakhir:</strong></td>
                                                <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Harga:</strong></td>
                                                <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Form Pilih Tanggal -->
                                <div class="class-schedule-filter" style="margin: 15px;">
                                    <form action="" method="GET">
                                        <label for="selectDate" style="color: white;"><strong>Pilih Tanggal:</strong></label>
                                        <input type="date" id="selectDate" name="selected_date"
                                            value="<?= isset($selectedDate) ? $selectedDate : date('Y-m-d') ?>" 
                                            max="<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>">
                                        <button type="submit" class="btn-filter" style="background-color: green; color: white; border: none; padding: 5px 10px;">Tampilkan</button>
                                    </form>
                                </div>

                                <!-- Cek kelas hari ini -->
                                <div class="class-schedule">
                                    <?php if (!empty($classesToday)): ?>
                                    <table class="class-schedule-table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">JADWAL KELAS</th>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Kelas</th>
                                                <th>Instruktur</th>
                                                <th>Jam</th>
                                                <th>Kuota</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($classesToday as $class): ?>
                                                <tr>
                                                    <td><?= date('d-m-Y', strtotime($class['Tanggal'])) ?></td>
                                                    <td><?= $class['Nama_Class'] ?></td>
                                                    <td><?= $class['Nama_Instruktur'] ?></td>
                                                    <td><?= $class['Jam'] ?></td>
                                                    <td><?= $class['Kuota'] ?></td>
                                                    <td>
                                                        <?php 
                                                        // Cek apakah member yang sedang login sudah memesan kelas ini
                                                        $isBooked = false;
                                                        foreach ($bookedClasses as $booking) {
                                                            if ($booking['ID_Class'] == $class['ID_Class'] && $booking['ID_Member'] == session()->get('ID_Member')) {
                                                                $isBooked = true;
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($isBooked): ?>
                                                            <button class="btncoupon" onclick="openCouponModal(<?= $class['ID_Class'] ?>, '<?= $class['Nama_Class'] ?>',
                                                            '<?= $class['Nama_Instruktur'] ?>', '<?= date('d-m-Y', strtotime($class['Tanggal'])) ?>', 
                                                            '<?= $class['Jam'] ?>')">Coupon</button>
                                                        <?php elseif ($class['Kuota'] > 0): ?>
                                                            <button class="btnbookclass" onclick="bookClass(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">BOOK</button>
                                                        <?php else: ?>
                                                            <button class="btnclassfull">FULL</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <!-- Modal Pop-up untuk Coupon -->
                                                <div id="couponModal" class="popup__formreview" style="display: none;">
                                                    <div class="popup__contentreview">
                                                      <span class="close" onclick="closeCouponModal()">&times;</span>
                                                      <h2>Booking Coupon</h2>
                                                        <div class="form-row">
                                                            <label for="couponMemberName">Nama Member:</label>
                                                            <input type="text" id="couponMemberName" value="<?= session()->get('Nama_Member'); ?>" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponClassName">Nama Class:</label>
                                                            <input type="text" id="couponClassName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponInstructorName">Nama Instruktur:</label>
                                                            <input type="text" id="couponInstructorName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponDate">Tanggal Kelas:</label>
                                                            <input type="text" id="couponDate" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponTime">Jam Kelas:</label>
                                                            <input type="text" id="couponTime" disabled>
                                                        </div>
                                                            <button type="cancel" class="btn btn-danger" onclick="cancelBooking(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">Cancel Booking</button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                        <p style="color: red">Hari ini tidak ada kelas yang tersedia.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Jika membership tidak aktif -->
                                <ul style="padding-left: 20%">
                                    <li><i class="ri-check-line"></i> Akses penuh ke kelas-kelas yang tersedia selama sebulan.</li>
                                    <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> bulan</li>
                                    <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                                </ul>
                                <div style="margin-bottom: 15px;">
                                    <input type="text" id="filter-class" placeholder="Cari Class">
                                    <input type="text" id="filter-instructor" placeholder="Cari Instruktur">
                                    <input type="date" id="filter-date1" > 
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
                              <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php elseif ($isActive): ?>
                            <p class="text-success">Your membership is active!</p>
                            <div class="membership-info-wrapper">
                                <table class="membership-info-table">
                                    <tbody>
                                        <tr>
                                            <td><strong>Tanggal Berlaku:</strong></td>
                                            <td><?= date('d-m-Y', strtotime($status['Tgl_Berlaku'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Berakhir:</strong></td>
                                            <td><?= date('d-m-Y', strtotime($status['Tgl_Berakhir'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga:</strong></td>
                                            <td>Rp<?= number_format($status['Harga'], 0, ',', '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Form Pilih Tanggal -->
                                <div class="class-schedule-filter" style="margin: 15px;">
                                    <form action="" method="GET">
                                        <label for="selectDate" style="color: white;"><strong>Pilih Tanggal:</strong></label>
                                        <input type="date" id="selectDate" name="selected_date"
                                            value="<?= isset($selectedDate) ? $selectedDate : date('Y-m-d') ?>" 
                                            max="<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>">
                                        <button type="submit" class="btn-filter" style="background-color: green; color: white; border: none; padding: 5px 10px;">Tampilkan</button>
                                    </form>
                                </div>

                                <!-- Cek kelas hari ini -->
                                <div class="class-schedule">
                                    <?php if (!empty($classesToday)): ?>
                                    <table class="class-schedule-table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">JADWAL KELAS</th>
                                            </tr>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Nama Kelas</th>
                                                <th>Instruktur</th>
                                                <th>Jam</th>
                                                <th>Kuota</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($classesToday as $class): ?>
                                                <tr>
                                                    <td><?= date('d-m-Y', strtotime($class['Tanggal'])) ?></td>
                                                    <td><?= $class['Nama_Class'] ?></td>
                                                    <td><?= $class['Nama_Instruktur'] ?></td>
                                                    <td><?= $class['Jam'] ?></td>
                                                    <td><?= $class['Kuota'] ?></td>
                                                    <td>
                                                        <?php 
                                                        // Cek apakah member yang sedang login sudah memesan kelas ini
                                                        $isBooked = false;
                                                        foreach ($bookedClasses as $booking) {
                                                            if ($booking['ID_Class'] == $class['ID_Class'] && $booking['ID_Member'] == session()->get('ID_Member')) {
                                                                $isBooked = true;
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($isBooked): ?>
                                                            <button class="btncoupon" onclick="openCouponModal(<?= $class['ID_Class'] ?>, '<?= $class['Nama_Class'] ?>',
                                                            '<?= $class['Nama_Instruktur'] ?>', '<?= date('d-m-Y', strtotime($class['Tanggal'])) ?>', 
                                                            '<?= $class['Jam'] ?>')">Coupon</button>
                                                        <?php elseif ($class['Kuota'] > 0): ?>
                                                            <button class="btnbookclass" onclick="bookClass(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">BOOK</button>
                                                        <?php else: ?>
                                                            <button class="btnclassfull">FULL</button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <!-- Modal Pop-up untuk Coupon -->
                                                <div id="couponModal" class="popup__formreview" style="display: none;">
                                                    <div class="popup__contentreview">
                                                      <span class="close" onclick="closeCouponModal()">&times;</span>
                                                      <h2>Booking Coupon</h2>
                                                        <div class="form-row">
                                                            <label for="couponMemberName">Nama Member:</label>
                                                            <input type="text" id="couponMemberName" value="<?= session()->get('Nama_Member'); ?>" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponClassName">Nama Class:</label>
                                                            <input type="text" id="couponClassName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponInstructorName">Nama Instruktur:</label>
                                                            <input type="text" id="couponInstructorName" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponDate">Tanggal Kelas:</label>
                                                            <input type="text" id="couponDate" disabled>
                                                        </div>
                                                        <div class="form-row">
                                                            <label for="couponTime">Jam Kelas:</label>
                                                            <input type="text" id="couponTime" disabled>
                                                        </div>
                                                            <button type="cancel" class="btn btn-danger" onclick="cancelBooking(<?= $class['ID_Class'] ?>, <?= session()->get('ID_Member') ?>)">Cancel Booking</button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                        <p style="color: red">Hari ini tidak ada kelas yang tersedia.</p>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                            <ul style="padding-left: 20%">
                                <li><i class="ri-check-line"></i> Akses penuh ke kelas-kelas yang tersedia selama sebulan.</li>
                                <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> bulan</li>
                                <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                            </ul>
                                <div style="margin-bottom: 15px;">
                                    <input type="text" id="filter-class" placeholder="Cari Class">
                                    <input type="text" id="filter-instructor" placeholder="Cari Instruktur">
                                    <input type="date" id="filter-date1" > 
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
                            <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BUY NOW</button>