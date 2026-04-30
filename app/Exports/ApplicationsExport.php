<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicationsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Application::with('participants.legalRepresentative')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '№ заявки',
            'Дата подачи',
            'Тип заявки',
            'Субъект РФ',
            'Уровень участия',
            'Муниципальное образование',
            'Дисциплина',
            'Email заявки',
            'Статус',
            'Отправлено в CRM',
            'Участники (ФИО)',
            'Телефоны участников',
            'Email участников',
            'Статусы участников',
            'Детали статусов',
            'Даты рождения',
            'Пол',
            'Размеры одежды',
            'Законные представители',
        ];
    }

    public function map($application): array
    {
        $participants = $application->participants;

        $names = $participants->map(function ($p) {
            $fio = trim("{$p->last_name} {$p->first_name} " . ($p->patronymic ?? ''));
            if ($p->is_captain) $fio .= ' (капитан)';
            if ($p->is_minor) $fio .= ' (несоверш.)';
            return $fio;
        })->implode("\n");

        $phones = $participants->pluck('phone')->filter()->implode("\n");
        $emails = $participants->pluck('email')->filter()->implode("\n");

        $statuses = $participants->map(function ($p) {
            return match ($p->participant_status) {
                'rural' => 'Сельский житель',
                'apk_worker' => 'Работник АПК',
                'apk_student' => 'Обучающийся АПК',
                default => '-',
            };
        })->implode("\n");

        $statusDetails = $participants->pluck('status_detail')->filter()->implode("\n");
        $birthDates = $participants->pluck('birth_date')->map(fn($d) => $d ? $d->format('d.m.Y') : '-')->implode("\n");
        $genders = $participants->map(fn($p) => $p->gender === 'male' ? 'М' : ($p->gender === 'female' ? 'Ж' : '-'))->implode("\n");
        $sizes = $participants->pluck('clothing_size')->filter()->implode("\n");

        $legalReps = $participants->map(function ($p) {
            if ($p->legalRepresentative) {
                $rep = $p->legalRepresentative;
                $status = match ($rep->status) {
                    'parent' => 'Родитель',
                    'adopter' => 'Усыновитель',
                    'guardian' => 'Опекун',
                    'trustee' => 'Попечитель',
                    default => $rep->status,
                };
                return trim("{$rep->last_name} {$rep->first_name} " . ($rep->patronymic ?? '')) . " ({$status})";
            }
            return null;
        })->filter()->implode("\n");

        $type = match ($application->type) {
            'individual' => 'Индивидуальная',
            'team' => 'Командная',
            'family' => 'Семейная эстафета',
            default => $application->type,
        };

        $level = $application->participation_level === 'municipal' ? 'Муниципальный' : 'Региональный';

        return [
            $application->id,
            $application->created_at->format('d.m.Y H:i'),
            $type,
            $application->subject_rf,
            $level,
            $application->municipality ?? '-',
            $application->discipline ?? '-',
            $application->email,
            $application->status === 'confirmed' ? 'Подтверждена' : $application->status,
            $application->sent_to_crm ? 'Да' : 'Нет',
            $names,
            $phones,
            $emails,
            $statuses,
            $statusDetails ?: '-',
            $birthDates,
            $genders,
            $sizes ?: '-',
            $legalReps ?: '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50'],
                ],
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            ],
        ];
    }
}
