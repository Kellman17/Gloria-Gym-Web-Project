<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="/css/Homegym.css">
    <link rel="stylesheet" href="/css/Homegymafterlogin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <title>GLORIA GYM</title>
  </head>
  <body>

  <?php if (session()->getFlashdata('success')) : ?>
    <div class="alert-backdrop"></div>
    <div class="custom-alert">
        <h3>Pembelian Berhasil!</h3>
        <p>Terima kasih sudah melakukan pembelian membership.</p>
        <button onclick="this.parentElement.style.display='none'; document.querySelector('.alert-backdrop').style.display='none';">OK</button>
    </div>
  <?php endif; ?>

    <nav>
      <div class="nav__bar">
        <div class="nav__header">
          <div class="nav__logo">
            <a href="#"><img src="<?= base_url('assets/Logo1.png') ?>" alt="logo" /></a>
          </div>
          <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-line"></i>
          </div>
        </div>
        <h2 style="color : gold">WELCOME <?= session()->get('Nama_Member') ?>!</h2>
        <nav>
        <div class="nav__bar">
            <ul class="nav__links">
            <li><a href="#" data-section="profile-section" class="active">PROFILE</a></li>
            <li><a href="#" data-section="history-section">HISTORY</a></li>
            <li><a href="#" data-section="membership" >MEMBERSHIP</a></li>
            <li><a href="#" data-section="class-section">CLASS</a></li>
            <li><a href="#" data-section="trainer-section">TRAINER</a></li>
            <li><a href="#" data-section="instructor-section">INSTRUCTOR</a></li>
            <li><a href="#" onclick="handleLogout()">LOGOUT</a></li>
            </ul>
        </div>
        </nav>

      </div>
    </nav>

    <!-- section profile -->
    <section id="profile-section" class="section" style="display: none; background-image:url('/assets/header.jpg');
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat; padding-bottom: 100px">
    <div class="section__container profile__container">
        <h2 class="section__header" style="margin-bottom: 20px; font-size: 60px; color: white">PROFILE</h2>
        <div class="profile__card">
            <!-- Profile Image -->
            <div class="profile__image">
                <img src="<?= base_url('uploads/member/' . session()->get('Foto_Member')) ?>" alt="Profile Picture">
            </div>

            <!-- Profile Information -->
            <div class="profile__info">
            <!-- Gunakan tabel untuk menyusun data -->
        <table class="profile__table">
            <tr>
                <td><strong>Nama:</strong></td>
                <td><?= session()->get('Nama_Member') ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?= session()->get('Email') ?></td>
            </tr>
            <tr>
                <td><strong>No HP:</strong></td>
                <td><?= session()->get('NoHP') ?></td>
            </tr>
        </table>
            </div>


            <!-- Edit Button -->
            <button class="btn-editprofile" onclick="openEditProfileModal()">Edit Profile</button>
        </div>
    </div>

    <!-- Modal for Edit Profile -->
<!-- Modal for Edit Profile -->
<div id="editProfileModal" class="profile-popup__form" style="display: none;">
    <div class="profile-popup__content">
        <h2>Edit Profile</h2>
        <form id="editProfileForm" action="/updateProfile" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ID_Member1" value="<?= session()->get('ID_Member') ?>">

            <!-- Profile Picture -->
            <div class="profile-form-row">
                <label for="Foto_Member">Upload Foto:</label>
                <input type="file" name="Foto_Member1" id="EditFoto_Member" accept="image/*">
            </div>

            <!-- Nama -->
            <div class="profile-form-row">
                <label for="Nama_Member">Nama:</label>
                <input type="text" name="Nama_Member1" id="EditNama_Member" value="<?= session()->get('Nama_Member') ?>" required>
            </div>

            <!-- Email -->
            <div class="profile-form-row">
                <label for="Email">Email:</label>
                <input type="email" name="Email1" id="EditEmail" value="<?= session()->get('Email') ?>" required>
            </div>

            <!-- No HP -->
            <div class="profile-form-row">
                <label for="NoHP">No HP:</label>
                <input type="text" name="NoHP1" id="EditNoHP" value="<?= session()->get('NoHP') ?>" required>
            </div>

            <!-- Buttons -->
            <div class="profile-popup__buttons">
                <button type="submit" class="btn-save">Save</button>
                <button type="button" class="btn-cancel" onclick="closeEditProfileModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


</section>


<!-- membership section -->
<section class="membership" id="membership" class="section" style="display: none;">
    <div class="section__container membership__container">

        <!-- Section untuk Harian Gym -->
        <div class="membership__grid gym-membership">
            <?php 
                $hasActiveBulananGym = false;
                $hasActiveHarianGym = false;

                // Periksa status membership
                if (!empty($membershipStatuses)) {
                    foreach ($membershipStatuses as $status) {
                        if ($status['ID_Membership'] == '1' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                            $hasActiveHarianGym = true;
                        }
                        if ($status['ID_Membership'] == '2' && in_array($status['Status'], ['Pending', 'Aktif'])) {
                            $hasActiveBulananGym = true;
                        }
                    }
                }

                // Filter data membership khusus untuk Harian Gym
                $harianGymMemberships = array_filter($memberships, function($membership) {
                    return $membership['ID_Membership'] == '1';
                });
            ?>

            <?php if (!$hasActiveBulananGym): ?>
                <?php foreach ($harianGymMemberships as $membership): ?>
                    <div class="membership__card">
                        <h3 style="font-size: 48px">Harian Gym</h3>
                        <?php 
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
                                        break;
                                    }
                                }
                            }
                        ?>
                        <?php if ($isPending): ?>
                            <p class="text-warning" style="color: yellow;">Membership Anda Dalam Proses</p>
                        <?php elseif ($isActive): ?>
                            <p class="text-success">Membership Anda aktif!</p>
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
                        <?php else: ?>
                            <ul style="padding-left: 20%">
                                <li><i class="ri-check-line"></i> Akses penuh ke gym untuk satu hari.</li>
                                <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> hari.</li>
                                <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                            </ul>
                            <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BELI SEKARANG</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </div>

        <!-- Section untuk Bulanan Gym -->
        <div class="membership__grid gym-membership">
            <?php 
                // Filter data membership khusus untuk Bulanan Gym
                $bulananGymMemberships = array_filter($memberships, function($membership) {
                    return $membership['ID_Membership'] == '2';
                });
            ?>

            <?php if (!$hasActiveHarianGym): ?>
                <?php foreach ($bulananGymMemberships as $membership): ?>
                    <div class="membership__card">
                        <h3 style="font-size: 48px">Bulanan Gym</h3>
                        <?php 
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
                        
                        <?php if ($isPending): ?>
                            <p class="text-warning" style="color: yellow;">Membership Anda Dalam Proses</p>
                        <?php elseif ($isActive): ?>
                            <p class="text-success">Membership Anda aktif!</p>
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
                                            <td>
                                            <?php 
                                                    // Inisialisasi harga total
                                                    $totalHarga = $status['Harga'];

                                                    // Tambahkan harga Add-On PT jika ada
                                                    if (!empty($addOnPtStatuses)) {
                                                        foreach ($addOnPtStatuses as $ptStatus) {
                                                            if ($ptStatus['ID_Record'] == $status['ID_Record'] && $ptStatus['StatusPT'] == 'Aktif') {
                                                                $totalHarga += $ptStatus['Harga_PT'];
                                                            }
                                                        }
                                                    }
                                                ?>
                                                Rp <?= number_format($totalHarga, 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Jika Member Menggunakan PT, tampilkan Jadwal PT -->
                            <div class="personal-training-wrapper">
                            <?php
                                // Cek apakah membership bundling atau add-on berdasarkan harga
                                $isAddOnPtPending = false;
                                $verifiedPT = [];

                                $isBundling = false;

                                // Cek apakah ini membership bundling berdasarkan harga
                                if (!empty($membershipStatuses)) {
                                    foreach ($membershipStatuses as $status) {
                                        if ($status['ID_Membership'] === '2' && $status['Harga'] > 125000 && $status['Pakai_PT'] === 'ya') { // Harga > 125000 = bundling
                                            $isBundling = true;
                                        }
                                    }
                                }


                                // Cek apakah ada PT yang masih pending atau sudah diverifikasi
                                if (!empty($personalTraining)) {
                                    foreach ($personalTraining as $schedule) {
                                        if ($schedule['status'] === 'paid') {
                                            $verifiedPT[] = $schedule; // PT yang sudah diverifikasi
                                        }
                                    }
                                }

                                // Cek status pending pada Add-On PT
                                if (!empty($addOnPtStatuses)) {
                                    foreach ($addOnPtStatuses as $ptStatus) {
                                        if ($ptStatus['StatusPT'] === 'Pending' ) {
                                            $isAddOnPtPending = true;
                                            break;
                                        }
                                    }
                                }
                                ?>
                                    <?php if ($isBundling ): ?>
                                        <!-- Jika bundling -->
                                        <table class="personal-training-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="6">JADWAL PERSONAL TRAINING BUNDLING</th>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jam Sesi</th>
                                                    <th>Latihan</th>
                                                    <th>Nama PT</th>
                                                    <th>Action</th>
                                                    <th>Pesan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($verifiedPT as $schedule): ?>
                                                    <tr>
                                                        <td><?= date('d-m-Y', strtotime($schedule['date'])) ?></td>
                                                        <td><?= $schedule['session_time'] ?></td>
                                                        <td><?= $schedule['Latihan'] ?></td>
                                                        <td><?= $schedule['Nama_PT'] ?></td>
                                                        <td>
                                                            <?php
                                                            $today = date('Y-m-d');
                                                            $sessionDate = date('Y-m-d', strtotime($schedule['date']));
                                                            if (empty($schedule['rating']) ):
                                                                if ($sessionDate <= $today && $schedule['Confirm'] === 'done'): ?>
                                                                    <button class="btnReview" onclick="openReviewModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>',
                                                                    '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>', '<?= $schedule['Nama_Member'] ?>', '<?= $schedule['date'] ?>',
                                                                    '<?= $schedule['session_time'] ?>', 
                                                                    '<?= $schedule['status'] ?>', '<?= $schedule['rating'] ?>', '<?= $schedule['review'] ?>')">
                                                                        <i class="fa-solid fa-clock"></i> Rating
                                                                    </button>
                                                                <?php elseif ($sessionDate === $today && $schedule['Confirm'] !== 'done'): ?>
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                        <i class="fa-solid fa-calendar"></i> Reschedule
                                                                    </button>
                                                                <?php elseif ($sessionDate > $today ): ?>
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                        <i class="fa-solid fa-calendar"></i> Reschedule
                                                                    </button>
                                                                <?php endif; else: ?>
                                                                    <button class="btnDone" disabled><i class="fa-solid fa-check"></i> Done</button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($schedule['Confirm'] === 'request_reschedule'): ?>
                                                                <p style="color: red;"><?= htmlspecialchars($schedule['Pesan']); ?></p>
                                                            <?php else: ?>
                                                                <p>-</p> <!-- Tampilkan tanda strip jika tidak ada pesan -->
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif (!$isBundling && $isAddOnPtPending): ?>
                                        <!-- Jika Add-On PT Pending -->
                                        <p class="text-warning" style="color: yellow;">Add-On PT Anda masih dalam proses verifikasi. Silakan tunggu.</p>
                                    
                                    <?php endif; ?>

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
                                                        <label for="reviewSessionTime">Jam Sesi:</label>
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
                                                    <button type="submit" class="btnkirim">Submit</button>
                                                    <button type="cancel" class="btncancelrating" onclick="closeReviewModal()">Cancel</button>
                                                    
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
                                                            <div id="unique_session_container" class="session-options-container"></div>
                                                        </div>

                                                        <button type="submit" class="btnkirim">Confirm</button>
                                                        <button type="button" class="btncancelrating" onclick="closeRescheduleModal()">Cancel</button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                    <?php if (!$isBundling && !empty($verifiedPT) && $ptStatus['StatusPT'] === 'Aktif' && $status['Pakai_PT'] === 'ya'): ?>
                                        <table class="personal-training-table">
                                            <thead>
                                                <tr>
                                                    <th colspan="6">JADWAL PERSONAL TRAINING ADD ON</th>
                                                </tr>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jam Sesi</th>
                                                    <th>Latihan</th>
                                                    <th>Nama PT</th>
                                                    <th>Action</th>
                                                    <th>Pesan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($verifiedPT as $schedule): ?>
                                                    <tr>
                                                        <td><?= date('d-m-Y', strtotime($schedule['date'])) ?></td>
                                                        <td><?= $schedule['session_time'] ?></td>
                                                        <td><?= $schedule['Latihan'] ?></td>
                                                        <td><?= $schedule['Nama_PT'] ?></td>
                                                        <td>
                                                            <?php
                                                            $today = date('Y-m-d');
                                                            $sessionDate = date('Y-m-d', strtotime($schedule['date']));

                                                            if (empty($schedule['rating'])):
                                                                if ($sessionDate <= $today && $schedule['Confirm'] === 'done'): ?>
                                                                    <button class="btnReview" onclick="openReviewModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>',
                                                                    '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>', '<?= $schedule['Nama_Member'] ?>', '<?= $schedule['date'] ?>',
                                                                    '<?= $schedule['session_time'] ?>', 
                                                                    '<?= $schedule['status'] ?>', '<?= $schedule['rating'] ?>', '<?= $schedule['review'] ?>')">
                                                                        <i class="fa-solid fa-clock"></i> Rating
                                                                    </button>
                                                                <?php elseif ($sessionDate >= $today && $schedule['Confirm'] !== 'done'): ?>
                                                                    <button class="btnReschedule" onclick="openRescheduleModal('<?= $schedule['ID_Sesi'] ?>', '<?= $schedule['ID_PT'] ?>', '<?= $schedule['Nama_PT'] ?>', '<?= $schedule['ID_Member'] ?>')">
                                                                        <i class="fa-solid fa-calendar"></i> Reschedule
                                                                    </button>
                                                                <?php endif; else: ?>
                                                                    <button class="btnDone" disabled><i class="fa-solid fa-check"></i> Done</button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($schedule['Confirm'] === 'request_reschedule'): ?>
                                                                <p style="color: red;"><?= htmlspecialchars($schedule['Pesan']); ?></p>
                                                            <?php else: ?>
                                                                <p>-</p> <!-- Tampilkan tanda strip jika tidak ada pesan -->
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php elseif (!$isBundling && !$isAddOnPtPending): ?>
                                        <p style="color: red; margin-bottom: 20px; font-size: 20px;">Anda tidak memiliki jadwal PT yang aktif.</p>
                                        <button class="btn-add-pt" onclick="openAddPTModal('<?= $status['ID_Record'] ?>','<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>')">Tambah PT</button>
                                    <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <ul style="padding-left: 20%">
                                <li><i class="ri-check-line"></i> Akses penuh ke gym selama sebulan.</li>
                                <li><i class="ri-check-line"></i> Akses ke ruang loker dan shower.</li>
                                <li><i class="ri-check-line"></i> Gratis konsultasi singkat dengan trainer kami.</li>
                                <li><i class="ri-check-line"></i> Paket ini berdurasi <?= $membership['Durasi'] ?> Hari.</li>
                                <li><i class="ri-check-line"></i> Harga: Rp<?= number_format($membership['Harga'], 0, ',', '.') ?></li>
                            </ul>
                            <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BELI SEKARANG</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
            <?php endif; ?>
        </div>

        <!-- Section untuk Bulanan Class -->
        <div class="membership__grid class-membership">
            <?php foreach ($memberships as $membership): ?>
                <?php if ($membership['ID_Membership'] == '3'): ?>
                    <div class="membership__card">
                        <h3 style="font-size: 48px">Bulanan Class</h3>
                        <?php 
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
                                        break;
                                    }
                                }
                            }
                        ?>
                        <?php if ($isPending): ?>
                            <p class="text-warning" style="color: yellow;">Membership Anda Dalam Proses</p>
                        <?php elseif ($isActive): ?>
                            <p class="text-success">Membership Anda Aktif!</p>
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
                            <div style="margin-bottom: 15px;">
                                <input type="text" id="filter-class-active" placeholder="Cari Kelas">
                                <input type="text" id="filter-instructor-active" placeholder="Cari Instruktur">
                                <input type="date" id="filter-date-active" 
                                        value="<?= date('Y-m-d') ?>" 
                                        min="<?= date('Y-m-d') ?>" 
                                        max="<?= date('Y-m-d', strtotime($status['Tgl_Berakhir'])) ?>">
                                <select id="filter-time-active" class="form-select" style="width: 150px;">
                                    <option value="">Pilih Waktu</option>
                                    <option value="08:00 - 09:30">08:00 - 09:30</option>
                                    <option value="11:00 - 12:30">11:00 - 12:30</option>
                                    <option value="14:00 - 15:30">14:00 - 15:30</option>
                                    <option value="17:00 - 18:30">17:00 - 18:30</option>
                                    <option value="20:00 - 21:30">20:00 - 21:30</option>
                                </select>
                            </div>


                                <!-- Cek kelas hari ini -->
                                <div class="class-schedule">
                                    <?php if (!empty($classes)): ?>
                                    <table id="class-schedule-table-active" class="class-schedule-table">
                                        <thead>
                                            <tr>
                                                <th colspan="6" style="text-align: center">JADWAL KELAS</th>
                                            </tr>
                                            <tr>
                                                <th>Nama Kelas</th>
                                                <th>Instruktur</th>
                                                <th>Tanggal</th>
                                                <th>Jam</th>
                                                <th>Kuota</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($classes as $class): ?>
                                                <tr>
                                                    <td><?= $class['Nama_Class'] ?></td>
                                                    <td><?= $class['Nama_Instruktur'] ?></td>
                                                    <td><?= date('d-m-Y', strtotime($class['Tanggal'])) ?></td>
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
                                                            '<?= $class['Jam'] ?>')">COUPON</button>
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
                                                        <input type="hidden" id="couponClassId" value="">
                                                            <button type="cancel" style="width: 200px; margin-top: 10px" class="btncancelrating" onclick="cancelBooking( <?= session()->get('ID_Member') ?>)">Cancel Booking</button>
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
                            <button class="btn btn-buy" data-id="<?= $membership['ID_Membership'] ?>" data-membership-type="<?= $membership['Jenis_Membership'] ?>" data-duration="<?= $membership['Durasi'] ?>" data-price="<?= $membership['Harga'] ?>">BELI SEKARANG</button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Pop-up Modal untuk Menambahkan PT -->
<div id="addPTModal" class="popup__form" style="padding-top:100px">
    <div class="popup__content">
        <span class="close" onclick="closeAddPTModal()">&times;</span>
        <h2 style="text-align: center">Tambah Personal Trainer</h2>
        <form action="/addPTToMembership" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="ID_Member1" value="<?= session()->get('ID_Member') ?>">
            <input type="hidden" name="Nama_Member1" value="<?= session()->get('Nama_Member') ?>">   
            <input type="hidden" name="ID_Record" id="ID_Record" >

             <!-- Tanggal Berlaku dan Berakhir -->
          <div class="form-row">
            <label>Tanggal Berlaku:</label>
            <input type="date" name="Tgl_Berlaku" id="tgl_berlaku" value="<?= date('Y-m-d') ?>" readonly />
          </div>
          <div class="form-row">
            <label>Tanggal Berakhir:</label>
            <input type="date" name="Tgl_Berakhir" id="tgl_berakhir1" readonly />
          </div>

            <!-- Pilih Personal Trainer -->
            <div class="form-row">
                <label for="ID_PT">Pilih Personal Trainer:</label>
                <button type="button" class="btn pilihpt" onclick="openSelectPTModal()">Pilih PT</button>
            </div>

                      <!-- Modal untuk memilih PT -->
                <div id="selectPT" class="popup__form" style="display: none; padding-top: 100px" role="dialog" aria-labelledby="selectPTTitle" aria-hidden="false">
                    <div class="popup__content" style="max-height: 600px;overflow-y: auto; ">
                        <span class="close" onclick="closeSelectPTModal()" role="button" aria-label="Close">&times;</span>
                        <h3 id="selectPTTitle" style="text-align: center">Pilih Personal Trainer</h3>
                        <p style="text-align: center">Silakan pilih Personal Trainer Anda:</p>

                        <div class="trainer-cards">
                            <?php foreach ($trainers as $trainer): ?>
                                <div class="trainer-card" onclick="selectPT(<?= $trainer['ID_PT']; ?>, '<?= $trainer['Nama_PT']; ?>', <?= $trainer['Harga_Sesi']; ?>)">
                                    <img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']); ?>" alt="<?= $trainer['Nama_PT']; ?>" class="trainer-photo">
                                    <div class="trainer-info">
                                        <input type="hidden" name="ID_PT1" id="ID_PT1" value="<?= $trainer['ID_PT']; ?>">
                                        <h4><?= $trainer['Nama_PT']; ?></h4>
                                        <p><?= $trainer['Prestasi']; ?></p>
                                        <p><strong>Spesialisasi:</strong> <?= $trainer['Spesialisasi']; ?></p>
                                        <p><strong>Harga:</strong> Rp<?= number_format($trainer['Harga_Sesi'], 0, ',', '.'); ?>/8 sesi</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" id="trainerSelect" name="trainerSelect">
                    </div>
                </div>

                <!-- Modal untuk Kalender Jadwal PT -->
          <div id="modalKalender1" class="popup__form" style="display: none; padding-top: 100px;" role="dialog" aria-labelledby="calendarTitle" aria-hidden="false">
              <div class="popup__content">
                  <span class="close" onclick="closeKalenderModal1()" role="button" aria-label="Close">&times;</span>
                  <h3 id="calendarTitle">Jadwal PT - <span id="trainerName1"></span> - <span id="calendarMonthYear1"></span></h3>
                  <div class="calendar-navigation">
                      <button id="prevMonth" class="calendar-button" onclick="navigateMonth1(-1)">Bulan Sebelumnya</button>
                      <button id="nextMonth" class="calendar-button" onclick="navigateMonth1(1)">Bulan Berikutnya</button>
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
                      <tbody id="calendarBody1" class="calendar-container">
                          <!-- Tanggal akan diisi oleh JavaScript -->
                      </tbody>
                  </table>
                  <!-- Keterangan Warna -->
                  <div class="color-legend">
                      <div><span class="legend available-day"></span> Terdapat Jadwal PT</div>
                      <div><span class="legend unavailable-day"></span> Tidak Terdapat Jadwal PT</div>
                   
                  </div>
                
                  <!-- Tombol Reset Sesi dan Booking Sesi -->
                  <div class="button-wrapper" style="margin-top: 20px;">
                      
                      <div id="remainingSessions1" class="session-remaining-info"></div>
                      <button id="savedSessionsButton1" class="btn-show" onclick="showSavedSessions1()">Lihat Sesi yang Dibooking</button>
                  </div>

               
                  <!-- Modal untuk menampilkan daftar sesi yang sudah dipesan -->
                  <div id="modalSavedSessions1" class="popup__form" style="display: none;" role="dialog" aria-labelledby="savedSessionsTitle" aria-hidden="false">
                      <div class="popup__content">
                      <span class="close" onclick="closeSavedSessionsModal1()" role="button" aria-label="Close">&times;</span>
                          <h3 id="savedSessionsTitle" class="savedtitle">Daftar Booking Sesi</h3>
                          <!-- Tabel untuk menampilkan sesi yang sudah dibooking -->
                            <table id="savedSessionsTable1" class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Sesi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="savedSessionsList1">
                                    <!-- Daftar sesi akan diisi menggunakan JavaScript -->
                                </tbody>
                            </table>
                          <div class="button-wrapper">
                            <button id="resetSessionButton1" class="btn-reset" onclick="resetSessions1()">Reset Sesi</button>
                            <button id="continueButton1" class="btn-lanjutkan" onclick="continueBooking1()">Lanjutkan</button>
                          </div>
                      </div>
                  </div>

                  <!-- Elemen untuk Slot Waktu yang Tersedia -->
                  <div id="slotContainerKalender1" class="slot-container" >
                      <!-- Slot waktu akan diisi oleh JavaScript -->
                      <div id="slotTimes1">
                          <!-- Jadwal sesi yang tersedia akan dimasukkan di sini -->
                      </div>
                  </div>
              </div>
          </div>

          <!-- Modal Jadwal untuk Memilih Sesi yang Tersedia -->
          <div id="modalJadwal1" class="popup__form" style="display: none; padding-top:100px" role="dialog" aria-labelledby="modalTitle" aria-hidden="false">
            <div class="popup__content">
                <div class="slot-container">
                  <span class="close" role="button" aria-label="Close" onclick="closeJadwalModal1()">&times;</span>
                  <h3 id="modalTitle">Pilih Slot Waktu untuk Tanggal: <span id="selectedDate1"></span></h3>
                  <div id="slotContainer1">
                    <!-- Slot waktu yang tersedia akan ditampilkan di sini -->
                  </div>
                    <div class="button-wrapper"> 
                    <button class="btn-save" id="saveSessionButton1" data-trainer-id="" onclick="saveSessions1()">Simpan Sesi</button>
                    </div>
                </div>
            </div>
          </div>


            <!-- Harga PT (ditambahkan ke harga membership) -->
            <div class="form-row">
                <label for="Harga_PT">Harga PT:</label>
                <input type="text" id="Harga_PT" name="Harga_PT" readonly>
            </div>
            <!-- Informasi Pembayaran -->
          <div class="payment-info">
            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
            <p style="font-size: 15px;"><strong>Bank BCA</strong> - No Rek: <strong>123456789</strong> a.n. Gloria Gym</p>
            <p style="font-size: 15px; font-weight: bold;">Jumlah yang harus dibayar: <span id="paymentAmount1" style="color: red"></span></p>
          </div>

            <!-- Upload Bukti PT -->
            <div class="form-row">
                <label for="Bukti_TambahPT">Upload Bukti PT:</label>
                <input type="file" name="Bukti_TambahPT" accept="image/*" required>
            </div>

            <!-- Tombol submit -->
            <button type="button" class="btn btn__primary" id="submitBtnAddPT">Tambah PT</button>
        </form>
    </div>
</div>

    <!-- Pop-up Modal untuk beli ----------------------------------->
    <div id="popupForm" class="popup__form">
      <div class="popup__content">
        <span class="close" onclick="closeForm()">&times;</span>
        <h2 style="text-align:center; margin-bottom: 10px">Konfirmasi Pembelian Membership</h2>
        <form action="/BuyMembership" method="POST" enctype="multipart/form-data">
          <!-- Informasi Member -->
          <input type="hidden" name="ID_Member" id="ID_Member" value="<?= session()->get('ID_Member') ?>" />
          <div class="form-row">
            <label>Nama:</label>
            <input type="text" name="Nama_Member" id="Nama_Member" value="<?= session()->get('Nama_Member') ?>" readonly />
          </div>
          <div class="form-row">
            <label>Email:</label>
            <input type="email" name="Email" value="<?= session()->get('Email') ?>" readonly />
          </div>
          <div class="form-row">
            <label>No HP:</label>
            <input type="text" name="NoHP" value="<?= session()->get('NoHP') ?>" readonly />
          </div>
          

          <!-- Informasi Membership -->
          <input type="hidden" name="ID_Membership" value="<?= session()->get('ID_Membership') ?>">
          <div class="form-row">
            <label>Jenis Membership:</label>
            <input type="text" id="membershipType" name="Jenis_Membership" readonly />
          </div>
          <div class="form-row">
            <label>Durasi(hari):</label>
            <input type="text" id="membershipDuration" name="Durasi" readonly />
          </div>
          <div class="form-row">
            <label>Harga:</label>
            <input type="text" id="membershipPrice" name="Total_Harga" readonly />
            <!-- Hidden inputs untuk harga dan status PT -->
            <input type="hidden" name="Harga_PT" id="Harga_PT" value="0">
            <input type="hidden" name="Pakai_PT" id="Pakai_PT" value="tidak">
          </div>

          <!-- Input Hidden untuk Member ID -->
          <input type="hidden" id="memberId" name="memberId" value="<?= isset($member_id) ? $member_id : ''; ?>">

          <!-- Checkbox untuk memilih apakah pengguna ingin menggunakan PT -->
          <div class="form-row" id="use_pt_row" style="display: none;">
              <label for="use_pt">Pakai Personal Trainer:</label>
              <input type="checkbox" id="use_pt" name="use_pt">
          </div>

          <!-- Modal untuk memilih PT -->
        <div id="selectPTModal" class="popup__form" style="display: none;" role="dialog" aria-labelledby="selectPTTitle" aria-hidden="false">
                    <div class="popup__content" style="width:600px">
                        <span class="close" onclick="closePTModal()" role="button" aria-label="Close">&times;</span>
                        <h3 id="selectPTTitle">Pilih Personal Trainer</h3>
                        <p>Silakan pilih Personal Trainer Anda:</p>

                        <div class="trainer-cards" >
                            <?php foreach ($trainers as $trainer): ?>
                                <div class="trainer-card"  onclick="selectTrainer(<?= $trainer['ID_PT']; ?>, '<?= $trainer['Nama_PT']; ?>', <?= $trainer['Harga_Sesi']; ?>)">
                                
                                    <img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']); ?>" alt="<?= $trainer['Nama_PT']; ?>" class="trainer-photo">
                                    <div class="trainer-info">
                                        <input type="hidden" name="ID_PT" id="ID_PT" value="<?= $trainer['ID_PT']; ?>">
                                        <h4><?= $trainer['Nama_PT']; ?></h4>
                                        <p><?= $trainer['Prestasi']; ?></p>
                                        <p><strong>Spesialisasi:</strong> <?= $trainer['Spesialisasi']; ?></p>
                                        <p><strong>Harga:</strong> Rp<?= number_format($trainer['Harga_Sesi'], 0, ',', '.'); ?>/8 sesi</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" id="trainerSelect" name="trainerSelect">
                    </div>
        </div>

          <!-- Modal untuk Kalender Jadwal PT -->
          <div id="modalKalender" class="popup__form" style="display: none;" role="dialog" aria-labelledby="calendarTitle" aria-hidden="false">
              <div class="popup__content" style="margin-top: 50px">
                  <span class="close" onclick="closeKalenderModal()" role="button" aria-label="Close">&times;</span>
                  <h3 id="calendarTitle">Jadwal PT - <span id="trainerName"></span> - <span id="calendarMonthYear"></span></h3>
                  <div class="calendar-navigation">
                      <button id="prevMonth" class="calendar-button" onclick="navigateMonth(-1)">Bulan Sebelumnya</button>
                      <button id="nextMonth" class="calendar-button" onclick="navigateMonth(1)">Bulan Berikutnya</button>
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
                      <div><span class="legend available-day"></span> Terdapat Jadwal PT</div>
                      <div><span class="legend unavailable-day"></span> Tidak Terdapat Jadwal PT</div>
                   
                  </div>
                
                  <!-- Tombol Reset Sesi dan Booking Sesi -->
                  <div class="button-wrapper" style="margin-top: 20px;">
                      
                      <div id="remainingSessions" class="session-remaining-info"></div>
                      <button id="savedSessionsButton" class="btn-show" onclick="showSavedSessions()">Lihat Sesi yang Dibooking</button>
                  </div>

               
                  <!-- Modal untuk menampilkan daftar sesi yang sudah dipesan -->
                  <div id="modalSavedSessions" class="popup__form" style="display: none;" role="dialog" aria-labelledby="savedSessionsTitle" aria-hidden="false">
                      <div class="popup__content">
                      <span class="close" onclick="closeSavedSessionsModal()" role="button" aria-label="Close">&times;</span>
                          <h3 id="savedSessionsTitle" class="savedtitle">Daftar Booking Sesi</h3>
                          <!-- Tabel untuk menampilkan sesi yang sudah dibooking -->
                            <table id="savedSessionsTable" class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Sesi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="savedSessionsList">
                                    <!-- Daftar sesi akan diisi menggunakan JavaScript -->
                                </tbody>
                            </table>
                          <div class="button-wrapper">
                            <button id="resetSessionButton" class="btn-reset" onclick="resetSessions()">Reset Sesi</button>
                            <button id="continueButton" class="btn-lanjutkan" onclick="continueBooking()">Lanjutkan</button>
                          </div>
                      </div>
                  </div>

                  <!-- Elemen untuk Slot Waktu yang Tersedia -->
                  <div id="slotContainerKalender" class="slot-container" style="margin-top: 20px;">
                      <!-- Slot waktu akan diisi oleh JavaScript -->
                      <div id="slotTimes">
                          <!-- Jadwal sesi yang tersedia akan dimasukkan di sini -->
                      </div>
                  </div>
              </div>
          </div>

          <!-- Modal Jadwal untuk Memilih Sesi yang Tersedia -->
          <div id="modalJadwal" class="popup__form" style="display: none;" role="dialog" aria-labelledby="modalTitle" aria-hidden="false">
            <div class="popup__content" style="margin-top: 50px">
                <div class="slot-container">
                  <span class="close" role="button" aria-label="Close" onclick="closeJadwalModal()">&times;</span>
                  <h3 id="modalTitle">Pilih Slot Waktu untuk Tanggal: <span id="selectedDate"></span></h3>
                  <div id="slotContainer">
                    <!-- Slot waktu yang tersedia akan ditampilkan di sini -->
                  </div>
                    <div class="button-wrapper"> 
                    <button class="btn-save" id="saveSessionButton" data-trainer-id="" onclick="saveSessions()">Simpan Sesi</button>
                    </div>
                </div>
            </div>
          </div>


          <!-- Tanggal Berlaku dan Berakhir -->
          <div class="form-row">
            <label>Tanggal Berlaku:</label>
            <input type="date" name="Tgl_Berlaku" id="tgl_berlaku" value="<?= date('Y-m-d') ?>" readonly />
          </div>
          <div class="form-row">
            <label>Tanggal Berakhir:</label>
            <input type="date" name="Tgl_Berakhir" id="tgl_berakhir" readonly />
          </div>

          <!-- Informasi Pembayaran -->
          <div class="payment-info">
            <p>Silakan lakukan pembayaran ke rekening berikut:</p>
            <p style="font-size: 15px;"><strong>Bank BCA</strong> - No Rek: <strong>123456789</strong> a.n. Gloria Gym</p>
            <p style="font-size: 15px; font-weight: bold;">Jumlah yang harus dibayar: <span id="paymentAmount" style="color: red"></span></p>
          </div>

          <!-- Upload Bukti Pembayaran -->
          <div class="form-row">
            <label>Upload Bukti Pembayaran:</label>
            <input type="file" name="Bukti_Pembayaran" accept="image/*" required />
          </div>

          <!-- <button type="button" class="btn btn__primary" id="submitBtn">Submit</button> -->

          <button type="submit" class="btn btn__primary" id="submitBtn">Submit</button>
        </form>
      </div>
    </div>

<!-- Section History Membership -->
<section id="history-section" class="section" style="display: none;">
    <div class="section__container history__container">
        <h2 class="section__header">HISTORY</h2>
        <!-- Tabs untuk memilih kategori -->
        <div class="history-tabs">
            <button id="tab-transaksi" class="history-tab active">History Transaksi</button>
            <button id="tab-pt" class="history-tab">History Personal Training</button>
            <button id="tab-class" class="history-tab">History Booking Class</button>
        </div>
        <!-- History Transaksi -->
        <div id="transaksi-history" class="history-category">
            <div class="history__grid">
                <?php if (!empty($membershipHistory) || !empty($addOnPtStatuses)): ?>
                    <?php 
                    // Gabungkan data membership dan add-on PT
                    $combinedHistories = [];
                    if (!empty($membershipHistory)) {
                        foreach ($membershipHistory as $history) {
                            $history['type'] = 'membership'; // Tandai data sebagai membership
                            $combinedHistories[] = $history;
                        }
                    }
                    if (!empty($addOnPtStatuses)) {
                        foreach ($addOnPtStatuses as $ptHistory) {
                            $ptHistory['type'] = 'addon_pt'; // Tandai data sebagai add-on PT
                    
                            // Pastikan Reason ada, walau nilainya null
                            // Pastikan Reason ada, walaupun nilainya null
                            $ptHistory['Reason'] = isset($ptHistory['Reason']) ? $ptHistory['Reason'] : 'Tidak ada alasan';
    
                    
                            $combinedHistories[] = $ptHistory;
                        }
                    }
                    
                    
                    // Urutkan berdasarkan tanggal berlaku (descending)
                    usort($combinedHistories, function ($a, $b) {
                        return strtotime($b['Tgl_Berlaku']) - strtotime($a['Tgl_Berlaku']);
                    });
                    ?>
    
                    <?php foreach ($combinedHistories as $history): ?>
                        <div class="history__card">
                            <?php if ($history['type'] === 'membership'): ?>
                                <h3><?= str_replace('_', ' ', $history['Jenis_Membership']); ?></h3>
                                <p><strong>Status:</strong> 
                                    <?php if ($history['Status'] == 'Aktif'): ?>
                                        <span class="status-active">Aktif</span>
                                    <?php elseif ($history['Status'] == 'Non-Aktif'): ?>
                                        <span class="status-nonaktif">Ditolak</span>
                                    <?php elseif ($history['Status'] == 'Pending'): ?>
                                        <span class="status-pending">Pending</span>
                                    <?php else: ?>
                                        <span class="status-selesai">Selesai</span>
                                    <?php endif; ?>
                                </p>
                                <p><strong>Tanggal Berlaku:</strong> <?= date('d-m-Y', strtotime($history['Tgl_Berlaku'])); ?></p>
                                <p><strong>Tanggal Berakhir:</strong> <?= date('d-m-Y', strtotime($history['Tgl_Berakhir'])); ?></p>
                                <p><strong>Harga:</strong> Rp<?= number_format($history['Harga'], 0, ',', '.'); ?></p>
                                <?php if ($history['Status'] == 'Non-Aktif'): ?>
                                    <p><strong>Alasan:</strong> <?= $history['Alasan']; ?></p>
                                <?php endif; ?>
                            <?php elseif ($history['type'] === 'addon_pt'): ?>
                                <h3> Add-on PT <?= $history['Nama_PT']; ?></h3>
                                <p><strong>Status:</strong> 
                                    <?php if ($history['StatusPT'] == 'Aktif'): ?>
                                        <span class="status-active">Aktif</span>
                                    <?php elseif ($history['StatusPT'] == 'Non-Aktif'): ?>
                                        <span class="status-nonaktif">Ditolak</span>
                                    <?php elseif ($history['StatusPT'] == 'Pending'): ?>
                                        <span class="status-pending">Pending</span>
                                    <?php else: ?>
                                        <span class="status-selesai">Selesai</span>
                                    <?php endif; ?>
                                </p>
                                <p><strong>Tanggal Berlaku:</strong> <?= date('d-m-Y', strtotime($history['Tgl_Berlaku'])); ?></p>
                                <p><strong>Tanggal Berakhir:</strong> <?= date('d-m-Y', strtotime($history['Tgl_Berakhir'])); ?></p>
                                <p><strong>Harga:</strong> Rp<?= number_format($history['Harga_PT'], 0, ',', '.'); ?></p>
                                <?php if ($history['StatusPT'] == 'Non-Aktif'): ?>
                                    <p><strong>Alasan:</strong> <?= isset($history['Reason']) ? $history['Reason'] : 'Tidak ada alasan'; ?></p>
                                <?php endif; ?>
    
    
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: red; text-align: center;">Belum ada history transaksi.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- History Personal Training -->
        <div id="pt-history" class="history-category" style="display:none;">
                        <table id="pt-history-table" class="history-table">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID Sesi</th>
                                    <th>Nama PT</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Latihan</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if (is_array($trainHistory) && !empty($trainHistory)): ?>
                                <?php foreach ($trainHistory as $trainhistory): ?>
                                    <tr>
                                    <td><?= $trainhistory['ID_Sesi'] ?? 'N/A'; ?></td>
                                    <td><?= $trainhistory['Nama_PT'] ?? 'N/A'; ?></td>
                                    <td><?= date('d-m-Y', strtotime($trainhistory['date'])); ?></td>
                                    <td><?= $trainhistory['session_time'] ?? 'N/A'; ?></td>
                                    <td><?= $trainhistory['Latihan'] ?? 'Belum ada'; ?></td>
                                    <td><?= $trainhistory['rating'] ?? 'Belum ada'; ?></td>
                                    <td><?= $trainhistory['review'] ?? 'Belum ada'; ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            </tbody>
                        </table>
        </div>

        <!-- History Booking Class -->
        <div id="class-history" class="history-category" style="display:none;">
            <!-- Tampilkan data booking class di sini -->
            <table id="class-history-table" class="history-table">
                <thead class="table-primary">
                    <tr>
                        <th>ID Booking</th>
                        <th>Nama Kelas</th>  <!-- Changed from 'Nama PT' to 'Nama Kelas' -->
                        <th>Nama Instruktur</th>
                        <th>Tanggal Kelas</th>
                        <th>Jam Kelas</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historyClasses as $historyclass): ?>
                    <tr>
                        <td><?= $historyclass['ID_Booking']; ?></td>
                        <td><?= $historyclass['Nama_Class']; ?></td>  <!-- Displaying class name from join -->
                        <td><?= $historyclass['Nama_Instruktur']; ?></td>
                        <td><?= date('d-m-Y', strtotime($historyclass['Tanggal'])); ?></td>
                        <td><?= $historyclass['Jam']; ?></td>
                        
                    </tr>
                    <?php endforeach; ?>
                </tbody>
  
            </table>
        </div>
    </div>
</section>


<!-- sectiion class -->
<section id="class-section" class="section__container session" style="display: none;">
        <div class="session__card">
            <h4>GYM FLOOR</h4>
            <p>
                Sculpt your physique and build muscle mass with our specialized
                bodybuilding programs at Gloria GYM.
            </p>
        </div>
        <div class="session__card">
            <h4>ZUMBA CLASS</h4>
            <p>
                Join our Zumba class at Gloria GYM for a fun, high-energy workout that combines dance and cardio.
            </p>
        </div>
        <div class="session__card">
            <h4>YOGA CLASS</h4>
            <p>
                Find balance, flexibility, and inner peace with our calming yet strengthening yoga sessions.
            </p>
        </div>
        <div class="session__card">
            <h4>AEROBIC CLASS</h4>
            <p>
                Get your body moving with our fun and energetic aerobics classes! Improve cardiovascular health.
            </p>
        </div>
    </section>

    <!-- section trainer -->
    <section class="section__container trainer__container" id="trainer-section" style="display: none; background-color: white;">
    <h2 class="section__header">MEET OUR TRAINERS</h2>
    <div class="trainer__grid">
        <?php foreach ($trainers as $trainer): ?>
            <div class="trainer__card">
                <img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']) ?>" alt="<?= $trainer['Nama_PT'] ?>" />
                <h4><?= strtoupper($trainer['Nama_PT']) ?></h4>
                <p><?= $trainer['Spesialisasi'] ?> Coach</p>
                <p><?= number_format($trainer['Rating'], 1, ',', '.') ?> 
                  <?php 
                      $fullStars = floor($trainer['Rating']); // Bintang penuh
                      $halfStar = ($trainer['Rating'] - $fullStars >= 0.5) ? true : false; // Bintang setengah

                      // Menampilkan bintang penuh
                      for ($i = 0; $i < $fullStars; $i++) {
                          echo '<i class="fas fa-star" style="color: yellow;"></i>';
                      }

                      // Menampilkan bintang setengah
                      if ($halfStar) {
                          echo '<i class="fas fa-star-half-alt" style="color: yellow;"></i>';
                      }
                  ?>
                </p>
                <div class="trainer__socials">
                    <!-- Placeholder Icon Media Sosial -->
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                    <a href="#"><i class="ri-twitter-fill"></i></a>
                    <a href="#"><i class="ri-youtube-fill"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>

<!-- section instructor -->
<section class="section__container trainer__container" id="instructor-section" style="display: none; background-color: white;">
    <h2 class="section__header">MEET OUR INSTRUCTORS</h2>
    <div class="trainer__grid">
        <?php foreach ($instrukturs as $instruktur): ?>
            <div class="trainer__card">
                <img src="<?= base_url('uploads/instruktur_photos/' . $instruktur['Foto']) ?>" />
                <h4><?= strtoupper($instruktur['Nama_Instruktur']) ?></h4>
                <p><?= $instruktur['Spesialisasi'] ?> Coach</p>
    
                <div class="trainer__socials">
                    <!-- Placeholder Icon Media Sosial -->
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                    <a href="#"><i class="ri-twitter-fill"></i></a>
                    <a href="#"><i class="ri-youtube-fill"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>


    <!-- <script src="https://unpkg.com/ScrollReveal"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="/Javascript/Homegymafterlogin.js"></script>
    <script src="/Javascript/Script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
            const successMessage = "<?= session()->getFlashdata('sukses_update') ?>";

    </script>
  </body>
</html>
