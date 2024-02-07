<?php

use App\Http\Controllers\AccruedBenefits;
use App\Http\Controllers\AllApplications;
use App\Http\Controllers\AllApprovedUnsettledLoans;
use App\Http\Controllers\AllInterestRates;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarLoans;
use App\Http\Controllers\ChangeInMemberContribution;
use App\Http\Controllers\ChristmasLoan;
use App\Http\Controllers\Deposit;
use App\Http\Controllers\EarlySettlement;
use App\Http\Controllers\EasterLoans;
use App\Http\Controllers\EmergencyLoans;
use App\Http\Controllers\FoundersDayLoan;
use App\Http\Controllers\HappyBirthdayLoan;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanRepayment;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MonthlyContributions;
use App\Http\Controllers\PostNews;
use App\Http\Controllers\SchoolFeesLoan;
use App\Http\Controllers\TestDb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::apiResource('/loans', LoanController::class)->middleware('auth:sanctum');
    Route::apiResource('/school-fees-loan', SchoolFeesLoan::class)->middleware('auth:sanctum');
    Route::apiResource('/happy-birthday-loan', HappyBirthdayLoan::class)->middleware('auth:sanctum');
    Route::apiResource('/car-loans', CarLoans::class)->middleware('auth:sanctum');
    Route::apiResource('/happy-birthday-loan', HappyBirthdayLoan::class)->middleware('auth:sanctum');
    Route::apiResource('/founders-day-loan',FoundersDayLoan::class)->middleware('auth:sanctum');
    Route::apiResource('/emergency-loan',EmergencyLoans::class)->middleware('auth:sanctum');
    Route::apiResource('/christmas-loan',ChristmasLoan::class)->middleware('auth:sanctum');
    Route::apiResource('/easter-loan',EasterLoans::class)->middleware('auth:sanctum');
    Route::apiResource('/all-interest-rates', AllInterestRates::class)->middleware('auth:sanctum');
    Route::get('/all-school-fees-approved-loans',[AllApprovedUnsettledLoans::class,'AllSchoolFeesLoans'])->middleware('auth:sanctum');
    Route::post('/all-school-fees-filter-dates',[AllApprovedUnsettledLoans::class,'FilterSchoolFeesLoansBasedTime'])->middleware('auth:sanctum');
    Route::get('/all-pending-applications', [AllApplications::class, 'AllPendingApprovals'])->middleware('auth:sanctum');
    Route::get('/all-approved-applications', [AllApplications::class, 'AllApplicationApprovals'])->middleware('auth:sanctum');
    Route::post('/monthly-contributions', [MonthlyContributions::class, 'monthlyContributions'])->middleware('auth:sanctum');
    Route::post('/loan-repayments', [LoanRepayment::class,'LoanRepayments'])->middleware('auth:sanctum');
    Route::get('/loan-repayments', [LoanRepayment::class,'viewAllLoanRepayment'])->middleware('auth:sanctum');
    Route::get('/user-loan-repayments', [LoanRepayment::class,'getUserLoanMonthlyRepayments'])->middleware('auth:sanctum');
    Route::get('/user-application-history', [AllApplications::class, 'userApplicationHistory'])->middleware('auth:sanctum');
    Route::get('/all-contributions', [MonthlyContributions::class, 'viewAllContribution'])->middleware('auth:sanctum');
    Route::post('/get-user-accrued-benefits', [AccruedBenefits::class, 'getUserAccruedBenefits'])->middleware('auth:sanctum');
    Route::post('/get-user-contributions', [MonthlyContributions::class, 'getUserContributions'])->middleware('auth:sanctum');
    Route::post('/create-news', [PostNews::class, 'PostNews'])->middleware('auth:sanctum');
    Route::get('/fetch-data', [PostNews::class, 'fetchResults'])->middleware('auth:sanctum');
    Route::post('/change-in-member-contributions', [ChangeInMemberContribution::class, 'MakeChangeMemberContribution'])->middleware('auth:sanctum');
    Route::get('/change-in-member-contributions', [ChangeInMemberContribution::class, 'GetPendingChangeInMemberContributions'])->middleware('auth:sanctum');
    Route::get('/change-in-member-contributions/{id}', [ChangeInMemberContribution::class, 'showChangeInMemberContributionById'])->middleware('auth:sanctum');
    Route::put('/change-in-member-contributions/{id}', [ChangeInMemberContribution::class, 'ApproveChangeInMemberApplication'])->middleware('auth:sanctum');
    Route::post('/request-for-accrued-benefits', [AccruedBenefits::class, 'RequestAccruedBenefitsWithdrawal'])->middleware('auth:sanctum');
    Route::get('/all-request-for-accrued-benefits', [AccruedBenefits::class, 'GetAllPendingAccruedBenefitWithdrawals'])->middleware('auth:sanctum');
    Route::get('/all-request-for-accrued-benefits/{id}', [AccruedBenefits::class, 'showAccruedBenefitsWithdrawalById'])->middleware('auth:sanctum');
    Route::get('/calculate-user-accrued-benefit', [AccruedBenefits::class, 'CalculateUserAccruedBenefit'])->middleware('auth:sanctum');
    Route::put('/approve-request-for-accrued-benefits/{id}', [AccruedBenefits::class, 'ApproveRequestForAccruedBenefitsWithdrawal'])->middleware('auth:sanctum');
    Route::post('/deposits', [Deposit::class, 'MakeDeposit'])->middleware('auth:sanctum');
    Route::get('/deposits', [Deposit::class, 'GetAllPendingDeposits'])->middleware('auth:sanctum');
    Route::get('/deposits/{id}', [Deposit::class, 'showDepositsById'])->middleware('auth:sanctum');
    Route::put('/deposits/{id}', [Deposit::class, 'ApproveUserDeposit'])->middleware('auth:sanctum');
    Route::get('/all-early-settlement-forms',[AllApplications::class,'AllPendingEarlySettlementApprovals'])->middleware('auth:sanctum');
    Route::get('/all-early-settlement-forms/{early_settlement}',[EarlySettlement::class,'show'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-happy-birthday/{happy_birthday_loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForHappyBirthday'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-loan-application/{loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForLoanApplication'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-school-fees-application/{schoolfees}',[EarlySettlement::class,'ApproveEarlyStatementFormForSchoolFeesLoan'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-car-loans-application/{carloan}',[EarlySettlement::class,'ApproveEarlyStatementFormForCarLoan'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-foundersday-loans-application/{foundersday_loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForFoundersDayLoan'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-christmas-loans-application/{christmas_loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForChristmasLoan'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-easter-loans-application/{easter_loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForEasterLoan'])->middleware('auth:sanctum');
    Route::put('/approve-all-early-settlement-forms-emergency-loans-application/{emergency_loan}',[EarlySettlement::class,'ApproveEarlyStatementFormForEmergencyLoan'])->middleware('auth:sanctum');
    Route::get('/show-user-applied-loans',[EarlySettlement::class,'showUserUnsettledAppliedLoans'])->middleware('auth:sanctum');
    Route::post('/create-early-settlement-forms',[EarlySettlement::class,'UserSettleLoans'])->middleware('auth:sanctum');
});

Route::prefix('auth')->group(function () {
    Route::get('/get-connection',[TestDb::class,'TestDbConnection']);
    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');
    Route::post('/user', [AuthController::class, 'store']);
    Route::get('/all-users', [AuthController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/user/{id}', [AuthController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/user/{id}', [AuthController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/current-user', [AuthController::class, 'currentUser'])->middleware('auth:sanctum');
    Route::post('/logout', [LogoutController::class, 'Logout'])->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $data = $request->user();
    return response()->json([
        'status' => true,
        'data' => $data
    ], 200);

});
