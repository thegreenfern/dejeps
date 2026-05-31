<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TraineeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/role', [HomeController::class, 'selectRole'])->name('role.select');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{notification}/lire', [NotificationController::class, 'markReadAndRedirect'])->name('notifications.read');
Route::get('/documents/uc12/{document}', [DocumentController::class, 'downloadUc12'])->name('uc12.document.download');
Route::get('/calendrier', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/calendrier/sync', [CalendarController::class, 'sync'])->name('calendar.sync');

// Onboarding wizard (no role guard — trainee identity lives in session)
Route::prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/etape-1',        [OnboardingController::class, 'step1'])->name('step1');
    Route::post('/etape-1',       [OnboardingController::class, 'saveStep1'])->name('step1.save');
    Route::get('/etape-2',        [OnboardingController::class, 'step2'])->name('step2');
    Route::post('/etape-2',       [OnboardingController::class, 'saveStep2'])->name('step2.save');
    Route::get('/etape-3',        [OnboardingController::class, 'step3'])->name('step3');
    Route::post('/etape-3',       [OnboardingController::class, 'saveStep3'])->name('step3.save');
    Route::get('/confirmation',   [OnboardingController::class, 'confirmation'])->name('confirmation');
});

// Instructor routes
Route::prefix('formateur')->name('instructor.')->group(function () {
    Route::get('/tableau-de-bord',              [InstructorController::class, 'dashboard'])->name('dashboard');
    Route::get('/stagiaire/{trainee}',                    [InstructorController::class, 'show'])->name('trainee.show');
    Route::get('/stagiaire/{trainee}/seance/ajouter',    [InstructorController::class, 'addSession'])->name('session.add');
    Route::get('/stagiaire/{trainee}/seance/{slug}/modifier',    [InstructorController::class, 'editSession'])->name('session.edit');
    Route::delete('/stagiaire/{trainee}/seance/{slug}',          [InstructorController::class, 'deleteSession'])->name('session.delete');
    Route::get('/uc12',                         [InstructorController::class, 'uc12'])->name('uc12');
    Route::post('/uc12/settings',               [InstructorController::class, 'saveUc12Settings'])->name('uc12.settings.save');
    Route::post('/uc12/documents',              [InstructorController::class, 'uploadDocument'])->name('uc12.document.upload');
    Route::delete('/uc12/documents/{document}', [InstructorController::class, 'deleteDocument'])->name('uc12.document.delete');
    Route::post('/uc12/{trainee}/{uc}',          [InstructorController::class, 'saveTraineeUc'])->name('uc12.trainee.save');
    Route::post('/stagiaire/{trainee}/jalons',   [InstructorController::class, 'saveProjectMilestones'])->name('milestones.save');
    Route::post('/uc3/{trainee}',                 [InstructorController::class, 'saveUc3'])->name('uc3.save');
    Route::post('/uc3/{trainee}/seance',          [InstructorController::class, 'saveUc3Seance'])->name('uc3.seance.save');
    Route::post('/epmsp/{trainee}/{type}',        [InstructorController::class, 'saveEpmsp'])->name('epmsp.save');
    Route::get('/stagiaire/{trainee}/positionnement',         [InstructorController::class, 'positioning'])->name('positioning');
    Route::post('/stagiaire/{trainee}/positionnement',        [InstructorController::class, 'savePositioning'])->name('positioning.save');
    Route::get('/stagiaire/{trainee}/positionnement/rapport', [InstructorController::class, 'positioningReport'])->name('positioning-report');

    Route::post('/stagiaire/{trainee}/peda/statut', [InstructorController::class, 'savePedaStatus'])->name('peda.status.save');
    Route::patch('/retour/{notification}',           [InstructorController::class, 'updateFeedback'])->name('feedback.update');
});

// Trainee routes
Route::prefix('stagiaire')->name('trainee.')->group(function () {
    Route::get('/choisir',                          [TraineeController::class, 'select'])->name('select');
    Route::post('/choisir',                         [TraineeController::class, 'identify'])->name('identify');
    Route::get('/tableau-de-bord',                  [TraineeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil',                           [TraineeController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profil',                          [TraineeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/uc12/dossier',                    [TraineeController::class, 'saveDossierUrl'])->name('uc12.dossier.save');
    Route::post('/demander-retour',                 [TraineeController::class, 'requestReview'])->name('review.request');
    Route::get('/seances/ajouter',                  [TraineeController::class, 'addSeance'])->name('seances.add');
    Route::get('/seances/{slug}/modifier',          [TraineeController::class, 'editSeance'])->name('seances.edit');
    Route::post('/seances',                         [TraineeController::class, 'saveSeance'])->name('seances.save');
    Route::delete('/seances/{slug}',                [TraineeController::class, 'deleteSeance'])->name('seances.delete');
});
