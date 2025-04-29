<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'SiteController::about');
$routes->get('/class', 'SiteController::class');
$routes->get('/trainer', 'SiteController::trainer');
$routes->get('/instructor', 'SiteController::instructor');
$routes->get('/membership', 'SiteController::membership');
$routes->get('/contact', 'SiteController::contact');

$routes->get('/Portal', 'Portal::index'); // Mengarahkan ke Portal.php
$routes->post('/signup', 'Portal::signup'); // Untuk proses signup
$routes->post('/login', 'Portal::login'); // Untuk proses login
$routes->get('/member', 'Portal::test'); // untuk ke page member.php
$routes->post('/resetPassword', 'Portal::resetPasswordRequest'); // Untuk mengirim request reset password
$routes->get('/resetPasswordForm/(:any)', 'Portal::resetPasswordForm/$1');
$routes->post('/updatePassword', 'Portal::updatePassword'); // Untuk memperbarui password
// $routes->get('/berhasillogin', 'Portal::afterlogin'); // untuk arahin klo berhasil login

$routes->post('/BuyMembership', 'BuyMembership::submit');//untuk proses pembelian membership
$routes->post('/updatesessiontopaid', 'BuyMembership::updateSessionToPaid');
$routes->post('/addPTToMembership', 'BuyMembership::addPTToMembership');


$routes->get('/loginpt', 'LoginPTController::index');
$routes->post('/loginpt/login', 'LoginPTController::login');
$routes->post('/pt/forgotPassword', 'LoginPTController::forgotPassword');
$routes->get('/pt/resetPasswordForm/(:any)', 'LoginPTController::resetPasswordForm/$1');
$routes->post('/pt/resetPasswordSubmit', 'LoginPTController::resetPasswordSubmit');
$routes->get('/logout', 'LoginPTController::logout');


$routes->get('/PT', 'PersonalTrainerController::index'); // Halaman daftar PT
$routes->get('/PT/create', 'PersonalTrainerController::create'); // Halaman form tambah PT
$routes->post('/PT/store', 'PersonalTrainerController::store'); // Proses simpan data PT
$routes->get('/PT/delete/(:num)', 'PersonalTrainerController::delete/$1'); // Hapus PT
$routes->get('/PT/jadwal/(:num)', 'PersonalTrainerController::jadwal/$1');
$routes->get('/PT/edit/(:num)', 'PersonalTrainerController::edit/$1');
$routes->post('/PT/update', 'PersonalTrainerController::update');
$routes->get('/getTrainerSchedule/(:num)', 'PersonalTrainerController::getTrainerSchedule/$1');
$routes->post('/saveJadwal', 'PersonalTrainerController::saveJadwal');
$routes->post('/clearJadwal', 'PersonalTrainerController::clearJadwal');
$routes->get('/getJadwal/(:num)/(:any)', 'PersonalTrainerController::getJadwal/$1/$2');
$routes->post('/updateLatihan', 'PersonalTrainerController::updateLatihan');
$routes->post('/PT/confirmSession/(:num)', 'PersonalTrainerController::confirmSession/$1');
$routes->post('/PT/requestReschedule', 'PersonalTrainerController::requestReschedule');
$routes->post('/PT/requestReschedule1', 'PersonalTrainerController::requestReschedule1');
$routes->post('/logoutpt', 'PersonalTrainerController::logoutpt');


$routes->get('/berhasillogin', 'HomegymafterloginController::login'); // untuk arahin klo berhasil login
$routes->post('/updateProfile', 'HomegymafterloginController::updateProfile');
$routes->get('/PersonalTraining', 'HomegymafterloginController::PersonalTraining');
$routes->post('/submitReview',  'HomegymafterloginController::submitReview');
$routes->post('/bookClass', 'HomegymafterloginController::bookClass');
$routes->get('/getBookingDetails', 'HomegymafterloginController::getBookingDetails');
$routes->post('/cancelBooking/(:num)', 'HomegymafterloginController::cancelBooking/$1');

$routes->get('/getAvailableDates', 'RescheduleController::getAvailableDates'); // Kalender
$routes->get('/getAvailableSessions', 'RescheduleController::getAvailableSessions');
$routes->post('/rescheduleSession', 'RescheduleController::rescheduleSession'); // Proses reschedule


$routes->get('/getTrainerScheduleMonthly/(:num)/(:num)/(:num)', 'PersonalTrainingController::getTrainerScheduleMonthly/$1/$2/$3');
$routes->get('/getTrainerScheduleDaily/(:num)/(:any)', 'PersonalTrainingController::getTrainerScheduleDaily/$1/$2');
$routes->get('/getSavedSessions/(:num)/(:segment)', 'PersonalTrainingController::getSavedSessions/$1/$2'); // Untuk mengambil sesi yang disimpan
$routes->get('/getTotalSessions/(:num)', 'PersonalTrainingController::getTotalSessions/$1');
$routes->post('/saveTempSession', 'PersonalTrainingController::saveTempSession');
$routes->post('/saveSessions', 'PersonalTrainingController::saveSessions');
$routes->post('/resetSessions', 'PersonalTrainingController::resetSessions');
$routes->post('bookSessions', 'PersonalTrainingController::bookSessions'); // Untuk menyimpan sesi ke database
$routes->get('/getTotalSessions/(:num)', 'PersonalTrainingController::getTotalSessions/$1');
$routes->delete('cancelSession/(:segment)/(:segment)/(:segment)/(:segment)', 'PersonalTrainingController::cancelSession/$1/$2/$3/$4');

$routes->get('/dashboard', 'DashboardController::index');
$routes->post('/dashboard/createMember', 'DashboardController::createMember');
$routes->post('/dashboard/updateMember', 'DashboardController::updateMember');
$routes->post('/dashboard/deleteMember/(:num)', 'DashboardController::deleteMember/$1');
$routes->post('/dashboard/createMembership', 'DashboardController::createMembership');
$routes->post('/dashboard/editMembership', 'DashboardController::editMembership');
$routes->post('/dashboard/deleteMembership/(:num)', 'DashboardController::deleteMembership/$1');
$routes->post('/dashboard/createTrainer', 'DashboardController::createTrainer');
$routes->post('/dashboard/updateTrainer', 'DashboardController::updateTrainer');
$routes->post('/dashboard/deleteTrainer/(:num)', 'DashboardController::deleteTrainer/$1');
$routes->get('/getTrainerDetails/(:num)', 'DashboardController::getTrainerDetails/$1');
$routes->post('/dashboard/updateStatusMembershipRecord', 'DashboardController::updateStatusMembershipRecord');
$routes->post('/updateMembershipStatus', 'DashboardController::updateMembershipStatus');
$routes->post('/updateMembershipStatusHarian', 'DashboardController::updateMembershipStatusHarian');
$routes->post('/updateMembershipStatusClass', 'DashboardController::updateMembershipStatusClass');

$routes->post('/dashboard/createInstruktur', 'DashboardController::createInstruktur');
$routes->post('/dashboard/updateInstruktur', 'DashboardController::updateInstruktur');
$routes->post('/dashboard/deleteInstruktur/(:num)', 'DashboardController::deleteInstruktur/$1');
$routes->post('/dashboard/createClass', 'DashboardController::createClass');
$routes->get('/getBookingMembers/(:num)', 'DashboardController::getBookingMembers/$1');
$routes->get('/dashboard/getUnavailableTimes', 'DashboardController::getUnavailableTimes');
$routes->post('/dashboard/updateClass', 'DashboardController::updateClass');
$routes->get('/dashboard/getUnavailableTimesEdit', 'DashboardController::getUnavailableTimesEdit');
$routes->get('/dashboard/deleteClass/(:num)', 'DashboardController::deleteClass/$1');
$routes->post('/dashboard/updateStatusTambahPT', 'DashboardController::updateStatusTambahPT');
$routes->post('/updateAddonStatus', 'DashboardController::updateAddonStatus');



?>