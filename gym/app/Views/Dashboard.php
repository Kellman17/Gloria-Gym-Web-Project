<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- External CSS -->
    <link rel="stylesheet" href="/css/Dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center py-3">
            <?php
            // Mengatur zona waktu
            date_default_timezone_set('Asia/Jakarta');

            // Membuat tanggal saat ini
            $tanggal = new DateTime();

            // Formatter untuk bahasa Indonesia
            $formatter = new IntlDateFormatter(
                'id_ID', // Lokal Indonesia
                IntlDateFormatter::FULL, // Format tanggal lengkap
                IntlDateFormatter::NONE // Tidak menampilkan waktu
            );

            // Format hari dan tanggal
            echo ucfirst($formatter->format($tanggal));
            ?>
        </h4>
        <a href="#" data-section="members-section" class="sidebar-link">
            <i class="fas fa-users"></i> Members
        </a>
        <a href="#" data-section="memberships-section" class="sidebar-link">
            <i class="fas fa-id-card"></i> Memberships
        </a>
        <a href="#" data-section="harian-section" class="sidebar-link">
            <i class="fas fa-file-alt"></i> Harian Gym Records
        </a>
        <a href="#" data-section="bulanangym-section" class="sidebar-link">
            <i class="fas fa-file-alt"></i> Bulanan Gym Records
        </a>
        <a href="#" data-section="bulananclass-section" class="sidebar-link">
            <i class="fas fa-file-alt"></i> Bulanan Class Records
        </a>
        <a href="#" data-section="tambahpt-section" class="sidebar-link">
        <i class="fas fa-file-alt"></i>  Add-on PT Records
        </a>
        <a href="#" data-section="personal-trainer-section" class="sidebar-link">
        <i class="fa-solid fa-dumbbell"></i> Personal Trainer
        </a>
        <a href="#" data-section="instruktur-section" class="sidebar-link">
        <i class="fa-solid fa-person-dress"></i> Instruktur Kelas
        </a>
        <a href="#" data-section="jadwal-class-section" class="sidebar-link">
        <i class="fa-regular fa-calendar-days"></i> Jadwal Kelas
        </a>

        <a href="#" id="logoutLink" style="color: red; font-weight:800;">
        <i class="fa-solid fa-arrow-right"></i> LOGOUT
        </a>
    </div>
    <!-- Main Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h2>GLORIA GYM Dashboard</h2>
        </div>

        <!-- Section 1: Members -->
        <section id="members-section" class="dashboard-section">
        <?php if (session()->getFlashdata('error_members')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error_members') ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success_members')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success_members') ?>
            </div>
        <?php endif; ?>
            <h3>MEMBERS</h3>
                <button class="btn btn-success" onclick="openAddMemberForm()">Add Member</button>
                <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                    <input type="search" class="form-control me-2" id="search-member" placeholder="Cari Nama member..." style="width: 200px;" />
                </div>
            
            <table id="members-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th style="width: 200px;">Nama Member</th>
                        <th>Foto Member</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= $member['ID_Member']; ?></td>
                        <td><?= $member['Nama_Member']; ?></td>
                        <td><img src="<?= base_url('uploads/member/' . $member['Foto_Member']); ?>" alt="Foto Member" width="100" height="100"></td>
                        <td><?= $member['NoHP']; ?></td>
                        <td><?= $member['Email']; ?></td>
                        <td>
                        <button class="btn btn-primary btn-sm edit-member-btn" onclick="openEditMemberForm(
                            <?= $member['ID_Member']; ?>, 
                            '<?= addslashes($member['Nama_Member']); ?>', 
                            '<?= addslashes($member['NoHP']); ?>', 
                            '<?= addslashes($member['Email']); ?>')">
                            EDIT
                        </button>

<!-- 
                            <button class="btn btn-danger btn-sm delete-member-btn" 
                                onclick="confirmMemberDelete()">
                                DELETE
                            </button> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
           
            <!-- Modal Add Member -->
            <div id="addMemberModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Add New Member</span>
                    </div>
                    <form id="addMember" action="/dashboard/createMember" method="POST">
                        <div class="modal-body">
                            <label for="newName">Nama:</label>
                            <input type="text" id="newName" name="Nama_Member">

                            <label for="newPhone">No HP:</label>
                            <input type="text" id="newPhone" name="NoHP">

                            <label for="newEmail">Email:</label>
                            <input type="email" id="newEmail" name="Email">

                            <label for="newPassword">Password:</label>
                            <input type="password" id="newPassword" name="Password"> <!-- Input untuk Password -->
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Add Member</button>
                            <button type="button" class="cancel-btn" onclick="closeAddForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Modal Edit Member -->
            <div id="editMemberModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Member</span>
                    </div>
                    <form id="editMember" action="/dashboard/updateMember" method="POST"  enctype="multipart/form-data">
                        <input type="hidden" id="editId" name="ID_Member" value="<?= $member['ID_Member']; ?>">
                        <div class="modal-body">
                            <label for="editName">Nama:</label>
                            <input type="text" id="editName" name="Nama_Member" value="<?= $member['Nama_Member']; ?>">

                            <label for="editPhone">No HP:</label>
                            <input type="text" id="editPhone" name="NoHP" value="<?= $member['NoHP']; ?>">

                            <label for="editEmail">Email:</label>
                            <input type="email" id="editEmail" name="Email" value="<?= $member['Email']; ?>">

                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>

        <!-- Section 2: Memberships -->
        <section id="memberships-section" class="dashboard-section" >
        <?php if (session()->getFlashdata('error_membership')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error_membership') ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success_membership')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success_membership') ?>
            </div>
        <?php endif; ?>
            <h3 style="margin-bottom: 30px;">MEMBERSHIPS</h3>

            <!-- Button to Open Add Membership Modal -->
            <!-- <button class="btn btn-success" onclick="openAddMembershipForm()">Add Membership</button> -->

            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Membership</th>
                        <th>Jenis Membership</th>
                        <th>Durasi</th>
                        <th>Harga</th>
                        <th>Action</th> <!-- Column for actions (Edit & Delete) -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($memberships as $membership): ?>
                    <tr>
                        <td><?= $membership['ID_Membership']; ?></td>
                        <td><?= $membership['Jenis_Membership']; ?></td>
                        <td><?= $membership['Durasi']; ?></td>
                        <td>Rp<?= number_format($membership['Harga'], 0, ',', '.'); ?></td>
                        <td>
                        <button class="btn btn-primary" onclick="openEditMembershipForm('<?= $membership['ID_Membership']; ?>', '<?= $membership['Jenis_Membership']; ?>', 
                                                                                '<?= $membership['Durasi']; ?>', '<?= $membership['Harga']; ?>')">EDIT</button>
                        <!-- <button class="btn btn-danger" onclick="confirmMembershipDelete()">DELETE</button> -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Modal Add Membership -->
            <div id="addMembershipModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Add New Membership</span>
                    </div>
                    <form id="addMembership" action="/dashboard/createMembership" method="POST">
                        <div class="modal-body">
                            <label for="newMembershipType">Jenis Membership:</label>
                            <input type="text" id="newMembershipType" name="Jenis_Membership">
    
                            <label for="newDuration">Durasi:</label>
                            <input type="text" id="newDuration" name="Durasi">
    
                            <label for="newPrice">Harga:</label>
                            <input type="text" id="newPrice" name="Harga">
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Add Membership</button>
                            <button type="button" class="cancel-btn" onclick="closeAddMembershipForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
    
            <!-- Modal Edit Membership -->
            <div id="editMembershipModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Membership</span>
                    </div>
                    <form id="editMembership" action="/dashboard/editMembership" method="POST">
                        <div class="modal-body">
                            <input type="hidden" id="editMembershipID" name="ID_Membership">
                            
                            <label for="editMembershipType">Jenis Membership:</label>
                            <input type="text" id="editMembershipType" name="Jenis_Membership">
    
                            <label for="editDuration">Durasi:</label>
                            <input type="text" id="editDuration" name="Durasi">
    
                            <label for="editPrice">Harga:</label>
                            <input type="text" id="editPrice" name="Harga">
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditMembershipForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Section 3: Membership bulanan gym Records -->
        <section id="bulanangym-section" class="dashboard-section" >
        <?php if ($successData = session()->getFlashdata('success_record')): ?>
            <?php if (is_array($successData)): ?>
                <div class="alert alert-success">
                    <?= $successData['message'] ?? '' ?>
                    <br>
                </div>
            <?php else: ?>
                    <?= $successData ?>
            <?php endif; ?>
        <?php endif; ?>


        <?php if ($errorData = session()->getFlashdata('error_record')): ?>
            <?php if (is_array($errorData)): ?>
                <div class="alert alert-danger">
                    <?= $errorData['message'] ?? '' ?>
                    <?= $errorData['reason'] ?? '' ?>
                </div>
            <?php else: ?>
                <?= $errorData ?>
            <?php endif; ?>
        <?php endif; ?>

            <h3 style="margin-bottom: 30px;">BULANAN GYM RECORDS</h3>
            <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="search" class="form-control me-2" id="search-bulanangym" placeholder="Cari Nama member..." style="width: 200px;" />
                <label for="filter-status" class="form-label me-2" style="margin-bottom: 0;">Filter Status:</label>
                <select id="filter-status" class="form-select" style="width: 150px; margin-right: 10px">
                    <option value="">Semua</option>
                </select>

                <label for="filter-pakai-pt" class="form-label me-2" style="margin-bottom: 0; margin-right: 10px">Pakai PT:</label>
                <select id="filter-pakai-pt" class="form-select me-3" style="width: 150px;">
                    <option value="">Semua</option>
                    <option value="ya">Ya</option>
                    <option value="tidak">Tidak</option>
                </select>

                <label for="filter-tglmulai" class="form-label me-2" style="margin-bottom: 0;">Bulan Mulai:</label>
                <input type="month" id="filter-tglmulai" class="form-control me-2" style="width: 180px; margin-right: 10px">

                <!-- <label for="filter-tglberakhir" class="form-label me-2" style="margin-bottom: 0;">Bulan Berakhir:</label>
                <input type="month" id="filter-tglberakhir" class="form-control" style="width: 180px;"> -->
                <span id="total-revenuegym" style="margin-left: 20px; font-weight: bold;">Total Pendapatan: </span>
            </div>
            <table id="bulanangym-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Record</th>
                        <th>Nama Member</th>
                        <th>Jenis Membership</th>
                        <th>Tgl Berlaku</th>
                        <th>Tgl Berakhir</th>
                        <th>Harga</th>
                        <th>Pakai PT</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membershipRecords as $record): ?>
                    <tr>
                        <td><?= $record['ID_Record']; ?></td>
                        <td><?= $record['Nama_Member']; ?></td>
                        <td>Bulanan Gym</td>
                        <td><?= $record['Tgl_Berlaku']; ?></td>
                        <td><?= $record['Tgl_Berakhir']; ?></td>
                        <td>Rp<?= number_format($record['Harga'], 0, ',', '.'); ?></td>
                        <td><?= $record['Pakai_PT']; ?></td>
                        <td>
                            <!-- Gambar yang akan di-zoom ketika di klik -->
                            <img src="<?= base_url('uploads/bukti/' . $record['Bukti_Pembayaran']); ?>" alt="Bukti Pembayaran" class="zoom-image"/>
                            <!-- Tombol Aksi -->
                            <?php if ($record['Status'] === 'Pending'): ?>
                                <button class="btn btn-primary btn-sm mt-2" onclick="openPaymentModal(
                                    '<?= $record['ID_Record']; ?>', 
                                    '<?= base_url('uploads/bukti/' . $record['Bukti_Pembayaran']); ?>', 
                                    '<?= $record['Status']; ?>')">
                                    Verifikasi
                                </button>
                            <?php elseif ($record['Status'] === 'Aktif' || $record['Status'] === 'Non-Aktif'): ?>
                                <!-- <button class="btn btn-danger btn-sm mt-2" onclick="setPendingStatusGym('< $record['ID_Record']; ?>')">                                
                                    Batalkan
                                </button> -->
                            <?php endif; ?>
                        </td>
                        <td data-status="<?= $record['Status']; ?>" class="status">
                            <?= $record['Status']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Modal verifikasi pembayaran-->
            <div id="paymentModal" class="payment-modal">
                <div class="payment-content">
                    <div class="payment-header" >
                        <h2>Verifikasi Pembayaran</h2>
                    </div>
                    <img id="paymentImage" class="payment-image" src="" alt="Bukti Pembayaran">
                    <form id="paymentForm">
                        <input type="hidden" id="recordID" name="ID_Record">
                        <div class="payment-body">
                            <label for="status" class="payment-label">Pilih Status:</label>
                            <div class="radio-group">
                                <div class="radio-wrapper accept">
                                        <label>
                                            <input type="radio" name="StatusGym" value="Aktif" required>
                                            Terima
                                        </label>
                                    </div>
                                    <div class="radio-wrapper reject">
                                        <label>
                                            <input type="radio" name="StatusGym" value="Non-Aktif">
                                            Tolak
                                        </label>
                                    </div>
                            </div>
                            <label for="reason" class="payment-label">Alasan:</label>
                            <textarea class="alasan" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <div class="payment-footer">
                            <button type="button" class="save-payment" onclick="savePaymentStatus()">Save</button>
                            <button type="button" class="cancel-btn" onclick="closePaymentModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Edit Status Membership -->
            <div id="editStatusModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Status Membership</span>
                    </div>
                    <form id="editStatusForm" action="/dashboard/updateStatusMembershipRecord" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="ID_Record" id="editID_Record">

                            <label for="memberName">Nama Member:</label>
                            <input type="text" id="memberName" name="Nama_Member" readonly>

                            <label for="membershipType">Jenis Membership:</label>
                            <input type="text" id="membershipType" name="Jenis_Membership" readonly>

                            <label for="startDate">Tanggal Berlaku:</label>
                            <input type="text" id="startDate" name="Tgl_Berlaku" readonly>

                            <label for="endDate">Tanggal Berakhir:</label>
                            <input type="text" id="endDate" name="Tgl_Berakhir" readonly>

                            <label for="statusDropdown">Status:</label>
                            <select id="statusDropdown" name="Status" required onchange="updateButtonStatus()">
                                <option value="Pending">Pending</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditStatusForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Section 4: Membership bulanan class Records -->
        <section id="bulananclass-section" class="dashboard-section" >
        <?php if ($successData = session()->getFlashdata('success_kelas')): ?>
            <div class="alert alert-success">
                <?php if (is_array($successData)): ?>
                    <?= $successData['message'] ?? '' ?><br>
                <?php else: ?>
                    <?= $successData ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>



        <?php if ($errorData = session()->getFlashdata('error_kelas')): ?>
            <?php if (is_array($errorData)): ?>
                <div class="alert alert-danger">
                    <?= $errorData['message'] ?? '' ?>
                    <?= $errorData['reason'] ?? '' ?>
                </div>
            <?php else: ?>
                <?= $errorData ?>
            <?php endif; ?>
        <?php endif; ?>

            <h3 style="margin-bottom: 30px;">BULANAN CLASS RECORDS</h3>
            <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="search" class="form-control me-2" id="search-bulananclass" placeholder="Cari Nama member..." style="width: 200px;" />
                <label for="filter-statusclass" class="form-label me-2" style="margin-bottom: 0;">Filter Status:</label>
                <select id="filter-statusclass" class="form-select" style="width: 150px; margin-right: 10px">
                    <option value="">Semua</option>
                </select>
                <label for="filter-tglmulaiclass" class="form-label me-2" style="margin-bottom: 0;">Bulan Mulai:</label>
                <input type="month" id="filter-tglmulaiclass" class="form-control me-2" style="width: 180px; margin-right: 10px">

                <!-- <label for="filter-tglberakhirclass" class="form-label me-2" style="margin-bottom: 0;">Bulan Berakhir:</label>
                <input type="month" id="filter-tglberakhirclass" class="form-control" style="width: 180px;"> -->
                <span id="total-revenuebulanan" style="margin-left: 20px; font-weight: bold;">Total Pendapatan: </span>
            </div>
            <table  id="bulananclass-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Record</th>
                        <th>Nama Member</th>
                        <th>Jenis Membership</th>
                        <th>Tgl Berlaku</th>
                        <th>Tgl Berakhir</th>
                        <th>Harga</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membershipRecordsClass as $recordClass): ?>
                    <tr>
                        <td><?= $recordClass['ID_Record']; ?></td>
                        <td><?= $recordClass['Nama_Member']; ?></td>
                        <td>Bulanan Class</td>
                        <td><?= $recordClass['Tgl_Berlaku']; ?></td>
                        <td><?= $recordClass['Tgl_Berakhir']; ?></td>
                        <td>Rp<?= number_format($recordClass['Harga'], 0, ',', '.'); ?></td>
                        <td>
                            <!-- Gambar yang akan di-zoom ketika di klik -->
                            <img src="<?= base_url('uploads/bukti/' . $recordClass['Bukti_Pembayaran']); ?>" alt="Bukti Pembayaran" class="zoom-image" />
                            <!-- Tombol Aksi -->
                            <?php if ($recordClass['Status'] === 'Pending'): ?>
                                <button class="btn btn-primary btn-sm mt-2" onclick="openPaymentModalClass(
                                    '<?= $recordClass['ID_Record']; ?>', 
                                    '<?= base_url('uploads/bukti/' . $recordClass['Bukti_Pembayaran']); ?>', 
                                    '<?= $recordClass['Status']; ?>')">
                                    Verifikasi
                                </button>
                            <?php elseif ($recordClass['Status'] === 'Aktif' || $recordClass['Status'] === 'Non-Aktif'): ?>
                                <!-- <button class="btn btn-danger btn-sm mt-2" onclick="setPendingStatusClass('< $recordClass['ID_Record']; ?>')">                                
                                    Batalkan
                                </button> -->
                            <?php endif; ?>
                        </td>
                        <td data-status="<?= $recordClass['Status']; ?>" class="status">
                            <?= $recordClass['Status']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal verifikasi pembayaran-->
            <div id="paymentModalClass" class="payment-modal">
                <div class="payment-content">
                    <div class="payment-header" >
                        <h2>Verifikasi Pembayaran</h2>
                    </div>
                    <img id="paymentImageClass" class="payment-image" src="" alt="Bukti Pembayaran">
                    <form id="paymentForm">
                        <input type="hidden" id="recordID" name="ID_Record">
                        <div class="payment-body">
                            <label for="status" class="payment-label">Pilih Status:</label>
                            <div class="radio-group">
                                <div class="radio-wrapper accept">
                                        <label>
                                            <input type="radio" name="Statusclass" value="Aktif" required>
                                            Terima
                                        </label>
                                    </div>
                                    <div class="radio-wrapper reject">
                                        <label>
                                            <input type="radio" name="Statusclass" value="Non-Aktif">
                                            Tolak
                                        </label>
                                    </div>
                            </div>
                            <label for="reason" class="payment-label">Alasan:</label>
                            <textarea class="alasan" id="reasonClass" name="reasonClass" rows="3" required></textarea>
                        </div>
                        <div class="payment-footer">
                            <button type="button" class="save-payment" onclick="savePaymentStatusClass()">Save</button>
                            <button type="button" class="cancel-btn" onclick="closePaymentModalClass()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Edit Status Membership -->
            <div id="editStatusModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Status Membership</span>
                    </div>
                    <form id="editStatusForm" action="/dashboard/updateStatusMembershipRecord" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="ID_Record" id="editID_Record">

                            <label for="memberName">Nama Member:</label>
                            <input type="text" id="memberName" name="Nama_Member" readonly>

                            <label for="membershipType">Jenis Membership:</label>
                            <input type="text" id="membershipType" name="Jenis_Membership" readonly>

                            <label for="startDate">Tanggal Berlaku:</label>
                            <input type="text" id="startDate" name="Tgl_Berlaku" readonly>

                            <label for="endDate">Tanggal Berakhir:</label>
                            <input type="text" id="endDate" name="Tgl_Berakhir" readonly>

                            <label for="statusDropdown">Status:</label>
                            <select id="statusDropdown" name="Status" required onchange="updateButtonStatus()">
                                <option value="Pending">Pending</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditStatusForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Section 5: Membership harian gym Records -->
        <section id="harian-section" class="dashboard-section" >
        <?php if ($successData = session()->getFlashdata('success_harian')): ?>
            <?php if (is_array($successData)): ?>
                <div class="alert alert-success">
                    <?= $successData['message'] ?? '' ?>
                    <br>
                </div>
            <?php else: ?>
                    <?= $successData ?>
            <?php endif; ?>
        <?php endif; ?>


        <?php if ($errorData = session()->getFlashdata('error_harian')): ?>
            <?php if (is_array($errorData)): ?>
                <div class="alert alert-danger">
                    <?= $errorData['message'] ?? '' ?>
                    <?= $errorData['reason'] ?? '' ?>
                </div>
            <?php else: ?>
                <?= $errorData ?>
            <?php endif; ?>
        <?php endif; ?>
            <h3 style="margin-bottom: 30px;">HARIAN GYM RECORDS</h3>
            <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="search" class="form-control me-2" id="search-harian" placeholder="Cari Nama member..." style="width: 200px;" />
                <label for="filter-statusharian" class="form-label me-2" style="margin-bottom: 0;">Filter Status:</label>
                <select id="filter-statusharian" class="form-select" style="width: 150px; margin-right: 10px">
                    <option value="">Semua</option>
                </select>
                <label for="filter-tglmulaiharian" class="form-label me-2" style="margin-bottom: 0;">Bulan Mulai:</label>
                <input type="month" id="filter-tglmulaiharian" class="form-control me-2" style="width: 180px; margin-right: 10px">

                <!-- <label for="filter-tglberakhirharian" class="form-label me-2" style="margin-bottom: 0;">Bulan Berakhir:</label>
                <input type="month" id="filter-tglberakhirharian" class="form-control" style="width: 180px;"> -->
                <span id="total-revenue" style="margin-left: 20px; font-weight: bold;">Total Pendapatan: </span>
            </div>
            <table id="harian-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Record</th>
                        <th>Nama Member</th>
                        <th>Jenis Membership</th>
                        <th>Tgl Berlaku</th>
                        <th>Tgl Berakhir</th>
                        <th>Harga</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membershipRecordsHarian as $harian): ?>
                    <tr>
                        <td><?= $harian['ID_Record']; ?></td>
                        <td><?= $harian['Nama_Member']; ?></td>
                        <td><?= $harian['Jenis_Membership']; ?></td>
                        <td><?= $harian['Tgl_Berlaku']; ?></td>
                        <td><?= $harian['Tgl_Berakhir']; ?></td>
                        <td>Rp<?= number_format($harian['Harga'], 0, ',', '.'); ?></td>
                        <td>
                            <!-- Gambar yang akan di-zoom ketika di klik -->
                            <img src="<?= base_url('uploads/bukti/' . $harian['Bukti_Pembayaran']); ?>" alt="Bukti Pembayaran" class="zoom-image" />
                            <!-- Tombol Aksi -->
                            <?php if ($harian['Status'] === 'Pending'): ?>
                                <button class="btn btn-primary btn-sm mt-2" onclick="openPaymentModalHarian(
                                    '<?= $harian['ID_Record']; ?>', 
                                    '<?= base_url('uploads/bukti/' . $harian['Bukti_Pembayaran']); ?>', 
                                    '<?= $harian['Status']; ?>')">
                                    Verifikasi
                                </button>
                            <?php elseif ($harian['Status'] === 'Aktif' || $harian['Status'] === 'Non-Aktif'): ?>
                                <!-- <button class="btn btn-danger btn-sm mt-2" onclick="setPendingStatusHarian('<$harian['ID_Record']; ?>')">                                
                                    Batalkan
                                </button> -->
                            <?php endif; ?>
                        </td>
                        <td data-status="<?= $harian['Status']; ?>" class="status">
                            <?= $harian['Status']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal verifikasi pembayaran-->
            <div id="paymentModalHarian" class="payment-modal">
                <div class="payment-content">
                    <div class="payment-header" >
                        <h2>Verifikasi Pembayaran</h2>
                    </div>
                    <img id="paymentImageHarian" class="payment-image" src="" alt="Bukti Pembayaran">
                    <form id="paymentForm">
                        <input type="hidden" id="recordIDHarian" name="ID_Record">
                        <div class="payment-body">
                            <label for="status" class="payment-label">Pilih Status:</label>
                            <div class="radio-group">
                                <div class="radio-wrapper accept">
                                        <label>
                                            <input type="radio" name="Statusharian" value="Aktif" required>
                                            Terima
                                        </label>
                                    </div>
                                    <div class="radio-wrapper reject">
                                        <label>
                                            <input type="radio" name="Statusharian" value="Non-Aktif">
                                            Tolak
                                        </label>
                                </div>
                            </div>
                            <label for="reasonHarian" class="payment-label">Alasan:</label>
                            <textarea class="alasan" id="reasonHarian" name="reasonHarian" rows="3" required></textarea>
                        </div>
                        <div class="payment-footer">
                            <button type="button" class="save-payment" onclick="savePaymentStatusHarian()">Save</button>
                            <button type="button" class="cancel-btn" onclick="closePaymentModalHarian()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Modal Edit Status Membership -->
            <div id="editStatusModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Status Membership</span>
                    </div>
                    <form id="editStatusForm" action="/dashboard/updateStatusMembershipRecord" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="ID_Record" id="editID_Record">

                            <label for="memberName">Nama Member:</label>
                            <input type="text" id="memberName" name="Nama_Member" readonly>

                            <label for="membershipType">Jenis Membership:</label>
                            <input type="text" id="membershipType" name="Jenis_Membership" readonly>

                            <label for="startDate">Tanggal Berlaku:</label>
                            <input type="text" id="startDate" name="Tgl_Berlaku" readonly>

                            <label for="endDate">Tanggal Berakhir:</label>
                            <input type="text" id="endDate" name="Tgl_Berakhir" readonly>

                            <label for="statusDropdown">Status:</label>
                            <select id="statusDropdown" name="Status" required onchange="updateButtonStatus()">
                                <option value="Pending">Pending</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditStatusForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <!-- Section 6: Personal_trainer -->
        <section id="personal-trainer-section" class="dashboard-section">
                    <?php if (session()->getFlashdata('error_trainer')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error_trainer'); ?>
                </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success_trainer')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success_trainer'); ?>
                        </div>
                    <?php endif; ?>
            <h3>PERSONAL TRAINER</h3>
                <button class="btn btn-success" onclick="openAddTrainerForm()">Add Personal Trainer</button>
                <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                    <input type="search" class="form-control me-2" id="search-pt" placeholder="Cari Nama PT..." style="width: 200px;" />
                    <label for="filter-spesialisasi" class="form-label me-2" style="margin-bottom: 0;">Filter Spesialisasi:</label>
                    <select id="filter-spesialisasi" class="form-select" style="width: 150px; margin-right: 10px">
                        <option value="">Semua</option>
                    </select>
                    <label for="filter-harga-min" class="form-label me-2" style="margin-bottom: 0;">Harga Minimal:</label>
                    <input type="number" class="form-control me-3" id="filter-harga-min" placeholder="Rp" style="width: 120px;" />
                    <label for="filter-rating-min" class="form-label me-2" style="margin-bottom: 0;">Rating Minimal:</label>
                    <input type="number" class="form-control" id="filter-rating-min" placeholder="0-5" step="0.1" style="width: 80px;" />
                </div>
      
            <table  id="pt-table"  class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th>Foto</th>
                        <th>Prestasi</th>
                        <th>Spesialisasi</th>
                        <th>Harga per 8 sesi</th>
                        <th style="width: 150px;">Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($trainers as $trainer): ?>
                    <tr>
                        <td><?= $trainer['ID_PT']; ?></td>
                        <td><?= $trainer['Email']; ?></td>
                        <td><?= $trainer['Nama_PT']; ?></td>
                        <td><img src="<?= base_url('uploads/pt_photos/' . $trainer['Foto_PT']); ?>" alt="Foto PT" width="100" height="100"></td>
                        <td><?= $trainer['Prestasi']; ?></td>
                        <td><?= $trainer['Spesialisasi']; ?></td>
                        <td>Rp<?= number_format($trainer['Harga_Sesi'], 0, ',', '.'); ?></td>
                        <td data-rating="<?= $trainer['Rating']; ?>">
                            <?php for ($i = 0; $i < floor($trainer['Rating']); $i++): ?>
                                <i class="fa fa-star text-warning"></i>
                            <?php endfor; ?>
                            <?php if ($trainer['Rating'] - floor($trainer['Rating']) > 0): ?>
                                <i class="fa fa-star-half-alt text-warning"></i>
                            <?php endif; ?>
                            <br>
                            <button class="btn btn-detail" onclick="showTrainerDetails(<?= $trainer['ID_PT']; ?>)">
                                DETAILS
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-primary" style="margin-bottom: 5px; width: 90px;" onclick="openEditTrainerForm(<?= $trainer['ID_PT']; ?>, '<?= $trainer['Email']; ?>', '<?= $trainer['Password']; ?>', '<?= $trainer['Nama_PT']; ?>',
                                         '<?=  $trainer['Foto_PT']; ?>', '<?= $trainer['Prestasi']; ?>', 
                                         '<?= $trainer['Spesialisasi']; ?>', <?= $trainer['Harga_Sesi']; ?>)">
                                         EDIT
                            </button>
                            <button class="btn btn-danger"
                                onclick="confirmTrainerDelete(<?= $trainer['ID_PT']; ?>)">
                                DELETE
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Modall detail rating dan review -->
            <div id="trainerDetailsModal" class="custom-modal">
                <div class="custom-modal-content">
                    <span class="custom-close-btn" onclick="closeTrainerDetailsModal()">&times;</span>
                    <h2 class="modal-title">Trainer Details</h2>
                    
                    <!-- Filter Section -->
                    <div class="filter-section">
                        <label for="filterMember" style="color: black">Filter by Member Name:</label>
                        <input type="search" class="form-control me-2" id="search-ptdetail" placeholder="Cari Nama Member..." style="width: 200px;" />
                    </div>

                    <table id="trainerDetailsTable" class="custom-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Member</th>
                                <th>Rating</th>
                                <th>Review</th>
                            </tr>
                        </thead>
                        <tbody id="trainerDetailsBody">
                            <!-- Data akan diisi secara dinamis melalui JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Modal Add Personal Trainer -->
            <div id="addTrainerModal" class="modal-container add-member-modal" >
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Add New Personal Trainer</span>
                    </div>
                    <form id="addTrainerForm" action="/dashboard/createTrainer" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                        <label for="Nama_PT">Nama PT:</label>
                        <input type="text" id="newPTName" name="Nama_PT" required>
                        
                        <label for="Email">Email:</label>
                        <input type="email" id="newPTEmail" name="Email" required>

                        <label for="Password">Password</label>
                        <input type="text" id="newPTPassword" name="Password" required>

                        <label for="Foto_PT">Photo:</label>
                        <input type="file" id="newPhoto" name="Foto_PT" accept="image/*" required>

                        <label for="Prestasi">Prestasi:</label>
                        <input type="text" id="newAchievement" name="Prestasi" required>

                        <label for="Spesialisasi">Spesialisasi:</label>
                        <input type="text" id="newSpecialty" name="Spesialisasi" required>

                        <label for="Harga_Sesi">Harga per 8 sesi:</label>
                        <input type="number" id="newHourlyRate" name="Harga_Sesi" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Add Trainer</button>
                            <button type="button" class="cancel-btn" onclick="closeAddTrainerForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Personal Trainer -->
            <div id="editTrainerModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Personal Trainer</span>
                    </div>
                    <form id="editTrainerForm" action="/dashboard/updateTrainer" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="ID_PT" id="editID_PT">
                            <input type="hidden" name="currentFoto" id="currentFoto">

                            <label for="editPTName">Nama PT:</label>
                            <input type="text" id="editPTName" name="Nama_PT" required>
                            
                            <label for="editPTEmail">Email:</label>
                            <input type="email" id="editPTEmail" name="Email" required>

                            
                            

                            <label for="editPhoto">Photo:</label>
                            <input type="file" id="editPhoto" name="Foto_PT" accept="image/*">

                            <label for="editAchievement">Prestasi:</label>
                            <input type="text" id="editAchievement" name="Prestasi" required>

                            <label for="editSpecialty">Spesialisasi:</label>
                            <input type="text" id="editSpecialty" name="Spesialisasi" required>

                            <label for="editHourlyRate">Harga per 8 sesi:</label>
                            <input type="number" id="editHourlyRate" name="Harga_Sesi" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditTrainerForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>

        <!-- Section 7: instruktur -->
        <section id="instruktur-section" class="dashboard-section">
            <?php if (session()->getFlashdata('error_instruktur')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error_instruktur') ?>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success_instruktur')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success_instruktur') ?>
            </div>
            <?php endif; ?>

            <h3>INSTRUKTUR</h3>
            <a onclick="openAddInstrukturForm()" class="btn btn-success">Add Instruktur</a>
            <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                    <input type="search" class="form-control me-2" id="search-instruktur" placeholder="Cari Nama Instruktur..." style="width: 200px;" />
                    <label for="filter-spesialisasiinstruktur" class="form-label me-2" style="margin-bottom: 0;">Filter Spesialisasi:</label>
                    <select id="filter-spesialisasiinstruktur" class="form-select" style="width: 150px; margin-right: 10px;">
                        <option value="">Semua</option>
                    </select>
                    <label for="filter-statusinstruktur" class="form-label me-2" style="margin-bottom: 0;">Filter Status:</label>
                    <select id="filter-statusinstruktur" class="form-select" style="width: 150px;">
                        <option value="">Semua</option>
                    </select>
                </div>
            <table id="instruktur-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 30px;">ID</th>
                        <th style="width: 300px;">Nama Instruktur</th>
                        <th>Foto</th>
                        <th>Spesialisasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instrukturs as $instruktur): ?>
                        <tr>
                            <td><?= $instruktur['ID_Instruktur'] ?></td>
                            <td><?= $instruktur['Nama_Instruktur'] ?></td>
                            <td><img src="<?= base_url('uploads/instruktur_photos/' . $instruktur['Foto']) ?>" width="100px" height="100px"></td>
                            <td><?= $instruktur['Spesialisasi'] ?></td>
                            <td><?= $instruktur['Status'] ?></td>
                            <td>
                                <button class="btn btn-primary" onclick="openEditInstrukturModal(<?= $instruktur['ID_Instruktur']; ?>, '<?= $instruktur['Nama_Instruktur']; ?>',
                                            '<?=  $instruktur['Foto']; ?>', '<?= $instruktur['Spesialisasi']; ?>', 
                                            '<?= $instruktur['Status']; ?>')">
                                            EDIT
                                </button>
                                <button class="btn btn-danger"
                                        onclick="confirmInstrukturDelete(<?= $instruktur['ID_Instruktur']; ?>)">
                                        DELETE
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal Add Instruktur -->
            <div id="addInstrukturModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Add New Instruktur</span>
                    </div>
                    <form id="addInstrukturForm" action="/dashboard/createInstruktur" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <!-- Nama Instruktur -->
                            <label for="Nama_Instruktur">Nama Instruktur:</label>
                            <input type="text" id="newInstrukturName" name="Nama_Instruktur" required>

                            <!-- Foto -->
                            <label for="Foto">Photo:</label>
                            <input type="file" id="newFoto" name="Foto" required>

                            <!-- Spesialisasi -->
                            <label for="Spesialisasi">Spesialisasi:</label>
                            <select id="newSpecialty" name="Spesialisasi" required>
                                <option value="">Pilih Spesialisasi</option>
                                <option value="Aerobik">Aerobik</option>
                                <option value="Zumba">Zumba</option>
                                <option value="Yoga">Yoga</option>
                            </select>

                            <!-- Status -->
                            <label for="Status">Status:</label>
                            <select id="newStatus" name="Status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Add Instruktur</button>
                            <button type="button" class="cancel-btn" onclick="closeAddInstrukturForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Instruktur -->
            <div id="editInstrukturModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Instruktur</span>
                    </div>
                    <form id="editInstrukturForm" action="/dashboard/updateInstruktur" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <!-- Hidden ID Instruktur -->
                            <input type="hidden" name="ID_Instruktur" id="editID_Instruktur">
                            <input type="hidden" name="currentFotoIns" id="currentFotoIns"> 

                            <!-- Nama Instruktur -->
                            <label for="editInstrukturName">Nama Instruktur:</label>
                            <input type="text" id="editInstrukturName" name="Nama_Instruktur" required>

                            <!-- Foto -->
                            <label for="editFoto">Photo:</label>
                            <input type="file" id="editFotoI" name="Foto" accept="image/*">

                            <!-- Spesialisasi -->
                            <label for="editInsSpecialty">Spesialisasi:</label>
                            <select id="editInsSpecialty" name="Spesialisasi" required>
                                <option value="">Pilih Spesialisasi</option>
                                <option value="Aerobik">Aerobik</option>
                                <option value="Zumba">Zumba</option>
                                <option value="Yoga">Yoga</option>
                            </select>

                            <!-- Status -->
                            <label for="editInsStatus">Status:</label>
                            <select id="editInsStatus" name="Status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Non-Aktif">Non-Aktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditInstrukturForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>

        <!-- SECTION 8 :JADWAL KELAS -->
        <section id="jadwal-class-section" class="dashboard-section">
            <?php if (session()->getFlashdata('error_class')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error_class') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success_class')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success_class') ?>
                </div>
            <?php endif; ?>

            <h3>JADWAL KELAS</h3>
            <button onclick="openAddClassModal()" class="btn btn-success">Add Class</button>
            <div class="d-flex align-items-center mb-3">
                <input type="text" id="search-instrukturkelas" class="form-control me-3" placeholder="Cari Nama Instruktur..." style="width: 200px;">
                
                <label for="filter-nama-class" class="form-label me-2">Filter Nama Class:</label>
                <select id="filter-nama-class" class="form-select me-3" style="width: 150px;">
                    <option value="">Semua</option>
                    <option value="Zumba">Zumba</option>
                    <option value="Yoga">Yoga</option>
                    <option value="Aerobik">Aerobik</option>
                </select>
                
                <label for="filter-tanggal" class="form-label me-2">Filter Tanggal:</label>
                <input type="date" id="filter-tanggal" class="form-control me-3" style="width: 200px;">
                
                <label for="filter-jam" class="form-label me-2">Filter Jam:</label>
                <select id="filter-jam" class="form-select" style="width: 150px;">
                    <option value="">Semua</option>
                    <option value="08:00 - 09:30">08:00 - 09:30</option>
                    <option value="11:00 - 12:30">11:00 - 12:30</option>
                    <option value="14:00 - 15:30">14:00 - 15:30</option>
                    <option value="17:00 - 18:30">17:00 - 18:30</option>
                    <option value="20:00 - 21:30">20:00 - 21:30</option>
                </select>
            </div>

            <!-- Table jadwal kelas -->
            <table id="class-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Class</th>
                        <th>Nama Class</th>
                        <th>Nama Instruktur</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Kuota</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kelas as $class): ?>
                    <tr>
                        <td><?= $class['ID_Class']; ?></td>
                        <td><?= $class['Nama_Class']; ?></td>
                        <td><?= $class['Nama_Instruktur']; ?></td>
                        <td><?= (new DateTime($class['Tanggal']))->format('d-m-Y'); ?></td>
                        <td><?= $class['Jam']; ?></td>
                        <td><?= $class['Kuota']; ?> &nbsp;
                            <button class="btn btn-detail" onclick="viewBookingMembers(<?= $class['ID_Class']; ?>)">
                                View Members
                            </button>
                        </td>
                        <td>
                        <button class="btn btn-primary" onclick="openEditClassModal(<?= $class['ID_Class']; ?>, '<?= $class['Nama_Class']; ?>', 
                                            '<?= $class['ID_Instruktur']; ?>', '<?= $class['Tanggal']; ?>',
                                            '<?= $class['Jam']; ?>', '<?= $class['Kuota']; ?>')">
                            EDIT
                        </button>
                        <button class="btn btn-danger" onclick="confirmClassDelete(<?= $class['ID_Class']; ?>)">
                            DELETE
                        </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal Add Kelas -->
            <div id="addClassModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Add New Class</span>
                    </div>
                    <form id="addClassForm" action="/dashboard/createClass" method="POST">
                        <div class="modal-body">
                            <!-- Nama Class (Select option) -->
                            <label for="Nama_Class">Nama Class:</label>
                            <select id="newClassName" name="Nama_Class" required>
                                <option value="">Pilih Nama Class</option>
                                <option value="Aerobik">Aerobik</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Zumba">Zumba</option>
                            </select>

                            <!-- Instruktur -->
                            <label for="Nama_Instruktur">Instruktur:</label>
                            <select id="newInstructor" name="ID_Instruktur" required>
                                <option value="">Pilih Instruktur</option>
                                <?php foreach ($instrukturs as $instruktur): ?>
                                    <?php if ($instruktur['Status'] === 'Aktif'): ?> <!-- Memfilter berdasarkan status Aktif -->
                                        <option value="<?= $instruktur['ID_Instruktur'] ?>" data-spesialisasi="<?= $instruktur['Spesialisasi'] ?>">
                                            <?= $instruktur['Nama_Instruktur'] ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <!-- Tanggal -->
                            <label for="Tanggal">Tanggal:</label>
                            <input type="date" id="newDate" name="Tanggal" required>    

                            <!-- Jam (Select option) -->
                            <label for="Jam">Jam:</label>
                            <select id="newTime" name="Jam" required>
                                <option value="">Pilih Jam</option>
                                <option value="08:00 - 09:30">08:00 - 09:30</option>
                                <option value="11:00 - 12:30">11:00 - 12:30</option>
                                <option value="14:00 - 15:30">14:00 - 15:30</option>
                                <option value="17:00 - 18:30">17:00 - 18:30</option>
                                <option value="20:00 - 21:30">20:00 - 21:30</option>
                            </select>

                            <!-- Kuota -->
                            <label for="Kuota">Kuota:</label>
                            <input type="number" id="newQuota" name="Kuota" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Add Class</button>
                            <button type="button" class="cancel-btn" onclick="closeAddClassForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Kelas -->
            <div id="editClassModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Class</span>
                    </div>
                    <form id="editClassForm" action="/dashboard/updateClass" method="POST">
                        <div class="modal-body">
                            <!-- Hidden ID Class -->
                            <input type="hidden" name="ID_Class" id="editClassID">

                            <!-- Nama Class -->
                            <label for="editClassName">Nama Class:</label>
                            <select id="editClassName" name="Nama_Class" required>
                                <option value="editClassName"></option>
                                <option value="Aerobik">Aerobik</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Zumba">Zumba</option>
                            </select>
                            <!-- Instruktur -->
                            <label for="editInstructor">Instruktur:</label>
                            <select id="editInstructor" name="ID_Instruktur" required>
                                <option value="editInstructor"></option>
                                <?php foreach ($instrukturs as $instruktur): ?>
                                    <?php if ($instruktur['Status'] === 'Aktif'): ?>
                                        <option value="<?= $instruktur['ID_Instruktur'] ?>" data-spesialisasi="<?= $instruktur['Spesialisasi'] ?>"><?= $instruktur['Nama_Instruktur'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>

                            <!-- Tanggal -->
                            <label for="editDate">Tanggal:</label>
                            <input type="date" id="editDate" name="Tanggal" required>

                            <!-- Jam -->
                            <label for="editTime">Jam:</label>
                            <select id="editTime" name="Jam" required>
                                <option value="">Pilih Jam</option>
                                <option value="08:00 - 09:30">08:00 - 09:30</option>
                                <option value="11:00 - 12:30">11:00 - 12:30</option>
                                <option value="14:00 - 15:30">14:00 - 15:30</option>
                                <option value="17:00 - 18:30">17:00 - 18:30</option>
                                <option value="20:00 - 21:30">20:00 - 21:30</option>
                            </select>

                            <!-- Kuota -->
                            <label for="editQuota">Kuota:</label>
                            <input type="number" id="editQuota" name="Kuota" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditClassModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal daftar member yg booking -->
            <div id="bookingMembersModal" class="modal-container">
                <div class="booking-modal-content">
                    <div class="bookingmodal-header">
                        <h3>Daftar Member</h3>
                    </div>
                    <!-- Input Pencarian -->
                    <div class="search-bar" style="margin-bottom: 10px;">
                        <input
                            type="text"
                            id="memberSearchInput"
                            placeholder="Cari Nama Member..."
                            onkeyup="filterMembers()"
                            style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ddd;"
                        />
                    </div>
                    <div class="booking-modal-body">
                        <table class="table" style="border-radius: 0px;">
                            <thead>
                                <tr >
                                    <th style="color: white;">Foto Member</th>
                                    <th style="color: white;">Nama Member</th>
                                    <th style="color: white;">Tanggal Booking</th>
                                </tr>
                            </thead>
                            <tbody id="bookingMembersTableBody" >
                                <!-- Data members akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="cancel-btn" onclick="closeBookingMembersModal()">Close</button>
                    </div>
                </div>
            </div>

        </section>

        <!-- section 9 : tambah pt -->
         <section id="tambahpt-section" class="dashboard-section">
         <?php if ($successData = session()->getFlashdata('success_addon')): ?>
            <div class="alert alert-success">
                <?php if (is_array($successData)): ?>
                    <?= $successData['message'] ?? '' ?><br>
                <?php else: ?>
                    <?= $successData ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($errorData = session()->getFlashdata('error_addon')): ?>
            <div class="alert alert-danger">
            <?php if (is_array($errorData)): ?>
                    <?= $errorData['message'] ?? '' ?>
                    <?= $errorData['reason'] ?? '' ?>
                    <?php else: ?>
                        <?= $errorData ?>
                        <?php endif; ?>
            </div>
        <?php endif; ?>

            <h3>Add-on Personal Training</h3>
            <div class="d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="search" class="form-control me-2" id="search-tambah" placeholder="Cari Nama member..." style="width: 200px;" />
                <!-- <input type="search" class="form-control me-2" id="filter-nama-pt" placeholder="Cari Nama PT..." style="width: 200px;" /> -->
                <input type="search" class="form-control me-2" id="filter-id-record" placeholder="Cari ID Record..." style="width: 200px;" />
                <label for="filter-statustambah" class="form-label me-2" style="margin-bottom: 0;">Filter Status:</label>
                <select id="filter-statustambah" class="form-select" style="width: 150px; margin-right: 10px">
                    <option value="">Semua</option>
                </select>
                <label for="filter-tglmulaitambah" class="form-label me-2" style="margin-bottom: 0;">Bulan Mulai:</label>
                <input type="month" id="filter-tglmulaitambah" class="form-control me-2" style="width: 180px; margin-right: 10px">

                <!-- <label for="filter-tglberakhirtambah" class="form-label me-2" style="margin-bottom: 0;">Bulan Berakhir:</label>
                <input type="month" id="filter-tglberakhirtambah" class="form-control" style="width: 180px;"> -->
                <span id="total-revenuetambah" style="margin-left: 20px; font-weight: bold;">Total Pendapatan: </span>
            </div>
            <table id="tambah-table" class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID Tambah PT</th>
                        <th>ID Record</th>
                        <th>Nama Member</th>
                        <th>Nama PT</th>
                        <th>Tgl Berlaku</th>
                        <th>Tgl Berakhir</th>
                        <th>Harga PT</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tambahpt as $tambah): ?>
                    <tr>
                        <td><?= $tambah['ID_Tambah_PT']; ?></td>
                        <td><?= $tambah['ID_Record']; ?></td>
                        <td><?= $tambah['Nama_Member']; ?></td>
                        <td><?= $tambah['Nama_PT']; ?></td>
                        <td><?= $tambah['Tgl_Berlaku']; ?></td>
                        <td><?= $tambah['Tgl_Berakhir']; ?></td>
                        <td>Rp<?= number_format($tambah['Harga_PT'], 0, ',', '.'); ?></td>
                        <td>
                            <!-- Gambar yang akan di-zoom ketika di klik -->
                            <img src="<?= base_url('uploads/buktitambah/' . $tambah['Bukti_TambahPT']); ?>" alt="Bukti Pembayaran" class="zoom-image" />
                            <!-- Tombol Aksi -->
                            <?php if ($tambah['StatusPT'] === 'Pending'): ?>
                                <button class="btn btn-primary btn-sm mt-2" onclick="openAddonPaymentModal(
                                    '<?= $tambah['StatusPT']; ?>', 
                                    '<?= $tambah['ID_Tambah_PT']; ?>', 
                                    '<?= $tambah['ID_Record']; ?>', 
                                    '<?= base_url('uploads/buktitambah/' . $tambah['Bukti_TambahPT']); ?>')">
                                    Verifikasi
                                </button>
                            <?php elseif ($tambah['StatusPT'] === 'Aktif' || $tambah['StatusPT'] === 'Non-Aktif'): ?>
                                <!-- <button class="btn btn-danger btn-sm mt-2" onclick="setPendingStatus(
                                    '< $tambah['ID_Tambah_PT']; ?>', '< $tambah['ID_Record']; ?>')">                                
                                    Batalkan
                                </button> -->
                            <?php endif; ?>
                        </td>
                        <td data-status="<?= $tambah['StatusPT']; ?>" class="status">
                            <?= $tambah['StatusPT']; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal Verifikasi Pembayaran Add-on PT -->
            <div id="verifyAddOnModal" class="addon-modal">
                <div class="addon-modal-content">
                    <div class="addon-modal-header">
                        <h2>Verifikasi Pembayaran Add-on PT</h2>
                    </div>
                    <input type="hidden" id="addonID" name="addonID">
                    <input type="hidden" id="addonRecordID" name="addonRecordID">
                    <img id="addonPaymentImage" class="addon-modal-image" src="" alt="Bukti Pembayaran">
                    <form id="verifyAddOnForm">
                        <div class="addon-modal-body">
                            <label for="addonStatus">Pilih Status:</label>
                            <div class="radio-group">
                                <div class="radio-wrapper accept">
                                    <label>
                                        <input type="radio" name="addonStatus" value="Aktif" required>
                                        Terima
                                    </label>
                                </div>
                                <div class="radio-wrapper reject">
                                    <label>
                                        <input type="radio" name="addonStatus" value="Non-Aktif">
                                        Tolak
                                    </label>
                                </div>
                            </div>
                            <label for="addonReason">Alasan:</label>
                            <textarea id="addonReason" name="Reason" required></textarea>
                        </div>
                        <div class="addon-modal-footer">
                            <button type="button" class="save-button" onclick="saveAddonPaymentStatus()">Save</button>
                            <button type="button" class="cancel-button" onclick="closeAddonPaymentModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Status Tambah PT -->
            <div id="editStatusTambahModal" class="modal-container add-member-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <span>Edit Status Tambah PT</span>
                    </div>
                    <form id="editStatusForm" action="/dashboard/updateStatusTambahPT" method="POST">
                        <div class="modal-body">
                            <label for="ID_Tambah_Pt">ID Tambah PT:</label>
                            <input type="text" name="ID_Tambah_PT" id="editID_Tambah_PT" readonly>

                            <label for="ID_Record">ID Record:</label>
                            <input type="text" id="ID_Record" name="ID_Record" readonly>

                            <label for="ID_PT">ID PT:</label>
                            <input type="text" id="ID_PT" name="ID_PT" readonly>

                            <label for="Harga_PT">Harga PT:</label>
                            <input type="text" id="Harga_PT" name="Harga_PT" readonly>

                            <label for="statusDropdown">Status:</label>
                            <select id="Status" name="Status" required onchange="updateButtonTambahStatus()">
                                <option value="Pending">Pending</option>
                                <option value="Aktif">Aktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit">Save Changes</button>
                            <button type="button" class="cancel-btn" onclick="closeEditStatusTambahForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
         </section>

    </div>

    <script>
    // Kirimkan flashdata dari PHP ke JavaScript
    const flashdataSection = <?= json_encode(session()->getFlashdata('activeSection') ?: '') ?>;
    </script>

    <!-- External JavaScript -->
    <script src="/Javascript/Dashboard.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
    
    <script type="text/javascript">
    // Menambahkan event listener untuk konfirmasi logout
    document.getElementById('logoutLink').addEventListener('click', function(event) {
        event.preventDefault();  // Menghentikan aksi default link, supaya tidak langsung menuju halaman

        // Menampilkan konfirmasi menggunakan SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#40ce20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            // Jika tombol 'Ya' diklik
            if (result.isConfirmed) {
                // Arahkan ke URL logout yang sesuai, misalnya '/logout'
                window.location.href = '/';  // Ubah URL logout sesuai dengan rute Anda
            }
        });
    });
</script>
</body>
</html>
