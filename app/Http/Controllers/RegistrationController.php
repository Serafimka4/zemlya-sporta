<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Participant;
use App\Models\LegalRepresentative;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registration');
    }

    public function getRegions()
    {
        $regions = config('regions.subjects');
        sort($regions, SORT_LOCALE_STRING);
        return response()->json($regions);
    }

    public function getMunicipalities(Request $request)
    {
        $subject = $request->input('subject');
        $municipalities = config('regions.municipalities.' . $subject, []);
        return response()->json($municipalities);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:individual,team,family',
            'subject_rf' => 'required|string',
            'participation_level' => 'required|in:municipal,regional',
            'municipality' => 'required_if:participation_level,municipal|nullable|string',
            'email' => 'required|email',
            'participants' => 'required|array|min:1',
            'participants.*.last_name' => 'required|string|max:255',
            'participants.*.first_name' => 'required|string|max:255',
            'participants.*.patronymic' => 'nullable|string|max:255',
            'participants.*.birth_date' => 'nullable|date',
            'participants.*.gender' => 'nullable|in:male,female',
            'participants.*.participant_status' => 'nullable|in:rural,apk_worker,apk_student',
            'participants.*.status_detail' => 'nullable|string|max:500',
            'participants.*.phone' => 'nullable|string|max:20',
            'participants.*.email' => 'nullable|email|max:255',
            'participants.*.clothing_size' => 'nullable|string|max:10',
            'participants.*.is_minor' => 'nullable|boolean',
            'participants.*.is_captain' => 'nullable|boolean',
            'participants.*.age' => 'nullable|integer|min:1|max:120',
            'participants.*.legal_representative' => 'nullable|array',
            'participants.*.legal_representative.last_name' => 'nullable|string|max:255',
            'participants.*.legal_representative.first_name' => 'nullable|string|max:255',
            'participants.*.legal_representative.patronymic' => 'nullable|string|max:255',
            'participants.*.legal_representative.status' => 'nullable|in:parent,adopter,guardian,trustee',
            'participants.*.legal_representative.document' => 'nullable|string|max:255',
            'participants.*.legal_representative.phone' => 'nullable|string|max:20',
        ]);

        // Additional validation for specific types
        if ($validated['type'] === 'individual') {
            $request->validate([
                'discipline' => 'required|string',
            ]);
        }
        if ($validated['type'] === 'team') {
            $request->validate([
                'discipline' => 'required|string',
                'participants' => 'required|array|min:2',
            ]);
        }

        $token = Str::random(64);

        $application = Application::create([
            'type' => $validated['type'],
            'subject_rf' => $validated['subject_rf'],
            'participation_level' => $validated['participation_level'],
            'municipality' => $validated['municipality'] ?? null,
            'discipline' => $request->input('discipline'),
            'email' => $validated['email'],
            'data' => $request->all(),
            'confirmation_token' => $token,
            'status' => 'confirmed',
        ]);

        foreach ($validated['participants'] as $pData) {
            $participant = Participant::create([
                'application_id' => $application->id,
                'last_name' => $pData['last_name'],
                'first_name' => $pData['first_name'],
                'patronymic' => $pData['patronymic'] ?? null,
                'birth_date' => $pData['birth_date'] ?? null,
                'gender' => $pData['gender'] ?? null,
                'participant_status' => $pData['participant_status'] ?? null,
                'status_detail' => $pData['status_detail'] ?? null,
                'phone' => $pData['phone'] ?? null,
                'email' => $pData['email'] ?? null,
                'clothing_size' => $pData['clothing_size'] ?? null,
                'is_minor' => $pData['is_minor'] ?? false,
                'is_captain' => $pData['is_captain'] ?? false,
                'age' => $pData['age'] ?? null,
            ]);

            if (!empty($pData['legal_representative']) && !empty($pData['legal_representative']['last_name'])) {
                LegalRepresentative::create([
                    'participant_id' => $participant->id,
                    'last_name' => $pData['legal_representative']['last_name'],
                    'first_name' => $pData['legal_representative']['first_name'] ?? '',
                    'patronymic' => $pData['legal_representative']['patronymic'] ?? null,
                    'status' => $pData['legal_representative']['status'] ?? 'parent',
                    'document' => $pData['legal_representative']['document'] ?? null,
                    'phone' => $pData['legal_representative']['phone'] ?? null,
                ]);
            }
        }

        // Send to Tilda CRM
        $this->sendToTildaCrm($application);

        // Send confirmation email
        $this->sendConfirmationEmail($application);

        return response()->json([
            'success' => true,
            'message' => 'Заявка успешно отправлена! Письмо с подтверждением отправлено на ' . $application->email,
            'application_id' => $application->id,
        ]);
    }

    private function sendToTildaCrm(Application $application)
    {
        try {
            $participants = $application->participants;
            $firstParticipant = $participants->first();

            $crmData = [
                'name' => $firstParticipant ? $firstParticipant->last_name . ' ' . $firstParticipant->first_name : '',
                'email' => $application->email,
                'phone' => $firstParticipant ? $firstParticipant->phone : '',
                'Тип заявки' => $this->getTypeLabel($application->type),
                'Субъект РФ' => $application->subject_rf,
                'Уровень участия' => $application->participation_level === 'municipal' ? 'Муниципальный' : 'Региональный',
                'Муниципальное образование' => $application->municipality ?? '-',
                'Дисциплина' => $application->discipline ?? '-',
            ];

            // Add all participants info
            foreach ($participants as $i => $p) {
                $num = $i + 1;
                $crmData["Участник_{$num}_ФИО"] = trim("{$p->last_name} {$p->first_name} {$p->patronymic}");
                $crmData["Участник_{$num}_Телефон"] = $p->phone ?? '';
                $crmData["Участник_{$num}_Email"] = $p->email ?? '';
                $crmData["Участник_{$num}_Статус"] = $p->participant_status ?? '';
            }

            // Send to Tilda CRM webhook
            $webhookUrl = config('services.tilda.crm_webhook_url');
            if ($webhookUrl) {
                $response = Http::timeout(10)->post($webhookUrl, $crmData);
                if ($response->successful()) {
                    $application->update(['sent_to_crm' => true]);
                }
            }

            Log::info('Tilda CRM data prepared', ['application_id' => $application->id, 'data' => $crmData]);

        } catch (\Exception $e) {
            Log::error('Failed to send to Tilda CRM', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function sendConfirmationEmail(Application $application)
    {
        try {
            $participants = $application->participants;
            $firstParticipant = $participants->first();

            Mail::send('emails.confirmation', [
                'application' => $application,
                'participant' => $firstParticipant,
                'participants' => $participants,
            ], function ($message) use ($application) {
                $message->to($application->email)
                    ->subject('Подтверждение регистрации — Марафон «Земля спорта — 2026»');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function getTypeLabel(string $type): string
    {
        return match ($type) {
            'individual' => 'Индивидуальная',
            'team' => 'Командная',
            'family' => 'Семейная эстафета',
            default => $type,
        };
    }
}
