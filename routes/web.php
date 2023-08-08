<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('users/home');
	return redirect('/home');
});


Auth::routes(['verify' => true , 'reset' => true, 'register' => false]);


Route::get('/route-cache', function() {
	$exitCode = Artisan::call('route:cache');
	return 'Routes cache has been cleared';
});

Route::get('/config-cache', function() {
	$exitCode = Artisan::call('config:cache');
	return 'Config cache has been cleared';
}); 

Route::get('/view-cache', function() {
	$exitCode = Artisan::call('view:cache');
	return 'Views cache has been cleared';
}); 

//Auth::routes();

//Sanitize User Input
Route::group(['middleware' => ['XssSanitizer']], function () {


	Route::group(['middleware' => ['BackNotification']], function () {

		//Office Public IP
		Route::resource('address','AddressController');
		//attendance settings
		Route::resource('attendance-setting','AttendanceSettingController');
		//hybrid employees
		Route::resource('hybrid-employee','HybridEmployeeController');

		//special attendance
		Route::resource('special-attendance', 'SpecialAttendanceController');

		//work setting switch
		Route::get('/switch-work-setting/{id}', 'WorkSettingController@switchWorkSetting');
		Route::get('/assign-work-setting/{id}/{work_setting}', 'WorkSettingController@assignWorkSetting');



		//communication utility allowance
		Route::resource('communication-utility', 'CommunicationUtilityController');
		Route::post('/exportutility', 'CommunicationUtilityController@export');

		//Accounting
		Route::resource('accounting-dashboard', 'AccountingController');
		Route::get('/deductionscontri', 'AccountingController@barChartDeductions');
		Route::get('/gsiscontri', 'AccountingController@barChartGsis');
		Route::get('/pagibigcontri', 'AccountingController@barChartPagibig');
		Route::get('/philhealthcontri', 'AccountingController@barChartPhilhealth');
		Route::get('/ilseacontri', 'AccountingController@barChartIlsea');
		Route::get('/taxcontri', 'AccountingController@lineChartTax');



		//ACCOUNTING/CASH
		//send mass mail in background
		Route::get('/send-bulk-mail-reminder/{period}/{status}','SendBulkMailController@massMailReminder');
		Route::get('/send-bulk-mail/{period}/{status}','SendBulkMailController@massMailPayslip');

		//payslip
		Route::get('/deletePayslip','PayslipController@destroy');
		Route::get('/deleteManyPayslip','PayslipController@destroyMany');
		Route::get('/update-payslip-status/{period}/{status}', 'PayslipController@changeStatus');
		//Route::get('/mass-mail-payslip/{period}/{status}', 'PayslipController@send');
		Route::resource('payslip-import', 'PayslipController');
		Route::post('import', 'PayslipController@import')->name('import');
		Route::get('/payslip-general', 'PayslipController@general');
		Route::get('/payslip-mail', 'PayslipController@mail');

		Route::resource('payslip-report', 'PayslipLogController');
		Route::post('export-payslip-mailing', 'PayslipLogController@payslipMailing')->name('export-payslip-mailing');

		Route::get('/payslip-report-download', 'PayslipLogController@indexDownload');
		Route::post('/export-payslip-download', 'PayslipLogController@payslipDownloading')->name('export-payslip-download');




		//Route::group(['middleware' => ['auth' ,'role:Admin|Chief LEO/Supervisor']], function() {

		//HR dashboard

		Route::resource('dashboard', 'DashboardController');

		//division dashboard
		Route::resource('division-dashboard', 'DivisionDashboardController');

		Route::resource('supervise-general-leave', 'SuperviseLeaveController');
		Route::get('/review-leave-supervisor/{id}/{supervisor_id}', 'SuperviseLeaveController@reviewLeave');
		Route::get('/approve-leave-supervisor/{id}', 'SuperviseLeaveController@approveLeave');
		Route::post('/approve-leave-supervisor/mandatory-forced-leave/{id}', 'SuperviseLeaveController@approveLeaveOthers');

		Route::post('disapprove-leave-supervisor','SuperviseLeaveController@leaveDecline');

		Route::resource('supervise-general-cto', 'SuperviseCtoController');
		Route::get('/review-cto-supervisor/{id}/{supervisor_id}', 'SuperviseCtoController@reviewCto');
		Route::get('/approve-cto-supervisor/{id}', 'SuperviseCtoController@approveCto');
		Route::post('disapprove-cto-supervisor','SuperviseCtoController@ctoDecline');

		//	});
		//Route::get('/dashboard', 'DashboardController@index');
		Route::get('/piechartPercentageToday', 'DashboardController@piechartPercentageToday');
		Route::get('/piechartPercentageMonth', 'DashboardController@piechartPercentageMonth');
		Route::get('/piechartPercentageYear', 'DashboardController@piechartPercentageYear');

		Route::get('/home', 'HomeController@index')->name('home');





		Route::get('/users/details/{id}', 'UserController@view');

		Route::get('/disableuser', 'UserController@disable');
		Route::get('/enableuser', 'UserController@enable');


		Route::get('/changeuserstat', 'UserController@changeuserstatus');

		Route::post('/resetpassword', 'UserController@resetpassword');

		Route::resource('users', 'UserController');



		Route::resource('roles', 'RoleController');

		Route::resource('permissions', 'PermissionController');

		Route::resource('posts', 'PostController');

		Route::post('myattendance/show', 'MyAttendanceController@show');

		//Route::get('/myattendance/export', 'MyAttendanceController@export');
					// Admin


		Route::get('/errorlog','ErrorLogController@index');
		Route::get('/clearlog','ErrorLogController@destroy');
		Route::get('/exportlog','ErrorLogController@export');
		Route::get('/testingmail','ErrorLogController@testingmail');


		Route::get('/admin','AdminController@index');

		Route::resource('errorlog','ErrorLogController');

		Route::post('/editOBAO', 'OBAOController@update');
		Route::get('/viewOBAO/{id}','OBAOController@viewOBAO');
		Route::get('/deleteOBAO','OBAOController@destroy');
		Route::resource('manageobao','OBAOController');

		Route::get('/deleteattendanceobao', 'ObaoAttendancesController@destroyattendance');
		Route::resource('manageattendanceobao','ObaoAttendancesController');

		Route::resource('manageattendanceothers','OtherAttendancesController');
		Route::get('/deleteattendanceother', 'OtherAttendancesController@destroyattendance');
		Route::post('manageattendanceothers/filter','OtherAttendancesController@show');


		Route::resource('manageattendance','ManageAttendanceController');
		//Route::post('/addattendance','ManageAttendanceController@store');
		Route::post('/accomplishmentadmin', 'ManageAttendanceController@accomplishment');
		Route::get('/deleteattendance', 'ManageAttendanceController@destroyattendance');
		Route::get('/autoabsent', 'ManageAttendanceController@autoabsent');
		Route::post('/markpresent', 'ManageAttendanceController@markpresent');


		Route::resource('manageleaves','ManageLeavesController');
		Route::get('/declineleave', 'ManageLeavesController@decline');
		Route::get('/declineleavecto', 'ManageLeavesController@declinecto');

		Route::get('/approveleave', 'ManageLeavesController@approve');
		Route::get('/approveleavecto', 'ManageLeavesController@approvecto');




		Route::get('/manageleaves/details/{id}', 'ManageLeavesController@show');
		Route::get('/manageleaves/detailscto/{id}', 'ManageLeavesController@showcto');

		Route::get('/manageleaves/exportword/{id}', 'ManageLeavesController@exportWord');

		Route::get('/manageleaves/exportwordcto/{id}', 'ManageLeavesController@exportWordCto');

		Route::get('/archivedleave', 'ManageLeavesController@archivedLeave');

		Route::post('/archive', 'ManageLeavesController@archive');
		Route::post('/restore', 'ManageLeavesController@restore');

		//new leave
		Route::resource('managefiledleaves','ManageFiledLeavesController');

		Route::get('review-leave/{id}/{hr_id}','ManageFiledLeavesController@reviewLeave');
		Route::post('approve-leave','ManageFiledLeavesController@leaveApprove');
		Route::post('disapprove-leave','ManageFiledLeavesController@leaveDecline');

		Route::get('certify-leave/{id}','ManageFiledLeavesController@show');
		Route::get('/archive-leave/{id}','ManageFiledLeavesController@archiveLeave');
		Route::get('route-leave/{id}','ManageFiledLeavesController@routeLeave');
		Route::get('route-leave/{id}/approve','ManageFiledLeavesController@approveOverride');
		Route::get('remind-leave/{status}/{id}','ManageFiledLeavesController@remindLeave');

		Route::resource('managefiledcto','ManageFiledCtoController');
		Route::get('/archive-cto/{id}','ManageFiledLeavesController@archiveCto');
		Route::get('certify-cto/{id}','ManageFiledCtoController@show');
		Route::get('route-cto/{id}','ManageFiledCtoController@routeCto');
		Route::get('review-cto/{id}/{hr_id}','ManageFiledCtoController@reviewCto');
		Route::post('approve-cto','ManageFiledCtoController@ctoApprove');
		Route::post('disapprove-cto','ManageFiledCtoController@ctoDecline');
		Route::get('route-cto/{id}/approve','ManageFiledCtoController@approveOverride');

		Route::resource('employeereport','EmployeeReportController');

		Route::post('/report','EmployeeReportController@report')->name('report');

		Route::get('/exportemployees', 'EmployeeReportController@export');


		//Route::resource('studentreport','StudentReportController');
		//Route::post('/singlereport','StudentReportController@report')->name('singlereport');


		//Route::get('/export', 'StudentReportController@export');


		Route::resource('attendancereport','AttendanceReportController');
		//Route::post('/singlereport','StudentReportController@report')->name('singlereport');


		Route::get('/export', 'AttendanceReportController@export');
		Route::get('/exportOther', 'AttendanceReportController@exportOther');
		Route::get('/exportObao', 'AttendanceReportController@exportObao');


		Route::resource('leavereport','LeaveReportController');
		//Route::post('/singlereport','StudentReportController@report')->name('singlereport');


		Route::get('/exportleave', 'LeaveReportController@export');
		Route::get('/exportcto', 'LeaveReportController@exportCto');
		Route::get('/export-archive', 'LeaveReportController@exportArchive');






		Route::get('/delete-data/{attendance_id}','ManageAttendanceController@destroy')->name('delete-data');
		Route::post('manageattendance/filter','ManageAttendanceController@show');


		Route::resource('manageholidays','HolidaysController');

		Route::get('/deleteHoliday','HolidaysController@destroy');

		Route::get('/viewHoliday/{id}','HolidaysController@viewHoliday');

		Route::post('/editHoliday','HolidaysController@edit');

		//leave credits certifiy
		Route::resource('leave-credits','LeaveCreditsController');
		Route::resource('cto-credits','CtoCreditsController');
		//Route::post('/storeleavecredits','LeaveCreditsController@store');


		//leave card
		Route::resource('leave-card','LeaveCardController');

		//forms
		Route::resource('forms','FormsController');
		Route::post('/start-elections','FormsController@startForm');
		Route::post('/end-elections','FormsController@endForm');
		Route::post('/archive-elections','FormsController@archiveForm');
		Route::get('/vote-results/{id}', 'FormsController@export');

		//categories
		Route::resource('categories','CategoriesController');
		Route::post('/archive-category','FormsController@archiveCategory');

		//candidates
		Route::resource('candidates','CandidatesController');
		Route::post('/remove-candidate','CandidatesController@deleteCandidate');

		//voters
		Route::resource('voters','VotersController');
		Route::post('/remove-voter','VotersController@deleteVoter');

		//election committee dashboard
		Route::get('/elections-dashboard','ElectionDashboardController@home');
		Route::get('/voters-percentage', 'ElectionDashboardController@votersPercentage');
		Route::get('/voters-position', 'ElectionDashboardController@votersPosition');
		// Admin



	});

	


// User

Route::group(['middleware' => ['FrontNotification']], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('index','AttendanceController');
	Route::resource('myattendance','MyAttendanceController');
	Route::get('/accomplishment', 'AttendanceController@accomplishment');

	Route::resource('archived-leaves','MyLeavesController');
	Route::resource('myleaves','RequestedLeavesController');
	Route::get('/download-leave/{id}', 'RequestedLeavesController@export');
	Route::get('/download-cto/{id}', 'RequestedLeavesController@exportCto');

	Route::get('/leave/details/{id}/{type}', 'RequestedLeavesController@view');

	Route::get('/cancel-filed-leave', 'RequestedLeavesController@cancelLeave');
	Route::get('/cancel-filed-cto', 'RequestedLeavesController@cancelCto');
	Route::get('/disable-leave/{id}', 'RequestedLeavesController@archiveLeave');

	Route::get('/unlock/{type}/{id}', 'RequestedLeavesController@unlockDownload');

	Route::get('/archiveleave', 'MyLeavesController@archiveleave');

	Route::get('/exportmyleave/{id}', 'MyLeavesController@export');

	Route::get('/exportmyleavecto/{id}', 'MyLeavesController@exportcto');

	Route::get('/exportmypayslip/{id}', 'EmployeePayslipController@export');


	Route::get('/leaves/details/{id}', 'MyLeavesController@show');
	Route::get('/leaves/detailsCTO/{id}', 'MyLeavesController@showCTO');


	Route::get('/holidayDates','MyLeavesController@allHolidays');


	Route::get('/checkWorkingDay/{date}/{id}','MyLeavesController@checker');

	//Route::resource('requestleave','LeaveController');
	//Route::resource('requestleave','LeaveController');

	Route::redirect('/requestleave', '/file-leave');


	Route::resource('file-leave','FiledLeavesController');
	Route::resource('settings','SettingsController');
	Route::resource('profile','ProfileController');
	Route::resource('my-payslip','EmployeePayslipController');

	//elections
	Route::post('/submit-vote','IlseaController@submitVote');
	Route::get('/ilsea', 'IlseaController@resolution');
	Route::get('/ilsea/elections/{id}', 'IlseaController@vote');
	Route::get('/vote-confirmation', 'IlseaController@confirmation');
	Route::get('/live-results/{id}', 'IlseaController@results');
	



});





//password expiration
Route::get('/passwordExpiration','Auth\PwdExpirationController@showPasswordExpirationForm');
Route::post('/passwordExpiration','Auth\PwdExpirationController@postPasswordExpiration')->name('passwordExpiration');


//password reset
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//public for e-sign signatory DED/ED signatory (LEAVE)
Route::get('signatory-review/leave/primary/{leave_id}/{id_number}','SignatoryLeaveController@showPrimary');
Route::get('signatory-review/leave/secondary/{leave_id}/{id_number}','SignatoryLeaveController@showSecondary');

//signatory approve leave (LEAVE)
Route::get('signatory-approve/leave/secondary/{user_id}/{leave_id}','SignatoryLeaveController@approveSecondary');
Route::get('signatory-approve/leave/primary/{user_id}/{leave_id}','SignatoryLeaveController@approvePrimary');

//signatory decline leave (LEAVE)
Route::post('/signatory-decline/leave/primary', 'SignatoryLeaveController@disapproveLeave');
Route::post('/signatory-decline/leave/secondary', 'SignatoryLeaveController@disapproveLeave');

//public route for leave download (LEAVE)
Route::get('/download/leave/{id}', 'PublicDownloadController@export');


//public for e-sign signatory DED/ED signatory (CTO)
Route::get('signatory-review/cto/primary/{leave_id}/{id_number}','SignatoryCtoController@showPrimary');
Route::get('signatory-review/cto/secondary/{leave_id}/{id_number}','SignatoryCtoController@showSecondary');

//signatory approve leave (LEAVE)
Route::get('signatory-approve/cto/secondary/{user_id}/{leave_id}','SignatoryCtoController@approveSecondary');
Route::get('signatory-approve/cto/primary/{user_id}/{leave_id}','SignatoryCtoController@approvePrimary');

//signatory decline leave (CTO)
Route::post('/signatory-decline/cto/primary', 'SignatoryCtoController@disapproveCto');
Route::post('/signatory-decline/cto/secondary', 'SignatoryCtoController@disapproveCto');

//public route for leave download (CTO)
Route::get('/download/cto/{id}', 'PublicDownloadController@exportCto');

});