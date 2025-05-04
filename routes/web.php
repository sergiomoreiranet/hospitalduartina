<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectorAdministratorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientFlowController;
use App\Http\Controllers\ReceptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas protegidas por autenticação e admin
    Route::middleware(['admin'])->group(function () {
        // Gerenciamento de usuários
        Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
        Route::get('/users/export-pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
        Route::resource('users', UserController::class);
        
        // Gerenciamento de setores
        Route::resource('sectors', SectorController::class);
        Route::put('sectors/{sector}/restore', [SectorController::class, 'restore'])->name('sectors.restore');
        
        // Rota alternativa para desativação (GET)
        Route::get('sectors/{sector}/deactivate', [SectorController::class, 'deactivateGet'])->name('sectors.deactivate');
        
        // Rota para processar a desativação
        Route::post('/sectors/{sector}/confirm-deactivate', [SectorController::class, 'confirmDeactivate'])->name('sectors.confirm-deactivate');
        Route::post('/sectors/{sector}/direct-deactivate', [SectorController::class, 'directDeactivate'])->name('sectors.direct-deactivate');
        
        // Rota para diagnóstico de banco de dados
        Route::get('/sectors/{sector}/diagnostic-db', [SectorController::class, 'diagnosticDb'])->name('sectors.diagnostic-db');
        
        // Rotas específicas de setores
        Route::prefix('sectors/{sector}')->group(function () {
            // Visualização de usuários e administradores
            Route::get('users', [SectorController::class, 'users'])->name('sectors.users');
            Route::get('administrators', [SectorController::class, 'administrators'])->name('sectors.administrators');
            
            // Gerenciamento de administradores
            Route::post('administrators', [SectorController::class, 'addAdministrator'])->name('sectors.add-administrator');
            Route::delete('administrators/{user}', [SectorController::class, 'removeAdministrator'])->name('sectors.remove-administrator');
        });
    });

    // Rotas para administradores de setores
    Route::get('/sectors/{sector}/administrators', [SectorAdministratorController::class, 'index'])
        ->name('sectors.administrators');
    Route::get('/sectors/{sector}/administrators/create', [SectorAdministratorController::class, 'create'])
        ->name('sectors.administrators.create');
    Route::post('/sectors/{sector}/administrators', [SectorAdministratorController::class, 'store'])
        ->name('sectors.administrators.store');
    Route::get('/sectors/{sector}/administrators/{administrator}/edit', [SectorAdministratorController::class, 'edit'])
        ->name('sectors.administrators.edit');
    Route::put('/sectors/{sector}/administrators/{administrator}', [SectorAdministratorController::class, 'update'])
        ->name('sectors.administrators.update');
    Route::delete('/sectors/{sector}/administrators/{administrator}', [SectorAdministratorController::class, 'destroy'])
        ->name('sectors.administrators.destroy');

    // Rotas de Pacientes
    Route::resource('pacientes', PatientController::class);

    // Rotas de Fluxo de Pacientes
    Route::resource('patient-flows', PatientFlowController::class);
    Route::post('patient-flows/{patientFlow}/start-attendance', [PatientFlowController::class, 'startAttendance'])->name('patient-flows.start-attendance');
    Route::post('patient-flows/{patientFlow}/finish-attendance', [PatientFlowController::class, 'finishAttendance'])->name('patient-flows.finish-attendance');
    Route::get('/patient-flows', [PatientFlowController::class, 'index'])->name('patient-flows.index');
    Route::get('/patient-flows/{patientFlow}', [PatientFlowController::class, 'show'])->name('patient-flows.show');
    Route::patch('/patient-flows/{patientFlow}/status', [PatientFlowController::class, 'updateStatus'])->name('patient-flows.update-status');

    // Rotas da Recepção
    Route::get('/reception', [ReceptionController::class, 'index'])->name('reception.index');
    Route::get('/reception/{patientFlow}/triage', [ReceptionController::class, 'triage'])->name('reception.triage');
    Route::put('/reception/{patientFlow}/triage', [ReceptionController::class, 'updateTriage'])->name('reception.update-triage');
});

require __DIR__.'/auth.php';
