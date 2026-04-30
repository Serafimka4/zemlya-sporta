<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявка №{{ $application->id }} — Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f5f7fa; min-height: 100vh; }
        .navbar {
            background: linear-gradient(135deg, #1a5c2e 0%, #2d7a3a 100%);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar h1 { font-size: 20px; }
        .navbar a { color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; }
        .navbar a:hover { background: rgba(255,255,255,0.15); }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; }
        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .card h3 {
            color: #1a5c2e;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8f5e9;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .info-item {
            padding: 8px 0;
        }
        .info-item .label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .info-item .value {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }
        .participant-card {
            border: 1px solid #e8f5e9;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            background: #fafffe;
        }
        .participant-card h4 {
            color: #2d7a3a;
            font-size: 15px;
            margin-bottom: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-individual { background: #e3f2fd; color: #1565c0; }
        .badge-team { background: #fff3e0; color: #e65100; }
        .badge-family { background: #f3e5f5; color: #7b1fa2; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 16px;
        }
        .back-link:hover { text-decoration: underline; }
        .legal-rep {
            border: 1px solid #fff3cd;
            background: #fffde7;
            border-radius: 8px;
            padding: 12px;
            margin-top: 8px;
        }
        .legal-rep h5 { color: #f57f17; margin-bottom: 8px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Заявка №{{ $application->id }}</h1>
        <a href="/admin/dashboard">&larr; Назад к списку</a>
    </div>

    <div class="container">
        <div class="card">
            <h3>Общая информация</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Тип заявки</div>
                    <div class="value">
                        @switch($application->type)
                            @case('individual') <span class="badge badge-individual">Индивидуальная</span> @break
                            @case('team') <span class="badge badge-team">Командная</span> @break
                            @case('family') <span class="badge badge-family">Семейная эстафета</span> @break
                        @endswitch
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">Дата подачи</div>
                    <div class="value">{{ $application->created_at->format('d.m.Y H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Субъект РФ</div>
                    <div class="value">{{ $application->subject_rf }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Уровень участия</div>
                    <div class="value">{{ $application->participation_level === 'municipal' ? 'Муниципальный' : 'Региональный' }}</div>
                </div>
                @if($application->municipality)
                <div class="info-item">
                    <div class="label">Муниципальное образование</div>
                    <div class="value">{{ $application->municipality }}</div>
                </div>
                @endif
                @if($application->discipline)
                <div class="info-item">
                    <div class="label">Дисциплина</div>
                    <div class="value">{{ $application->discipline }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="label">Email</div>
                    <div class="value">{{ $application->email }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Отправлено в CRM</div>
                    <div class="value">{{ $application->sent_to_crm ? 'Да' : 'Нет' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3>Участники ({{ $application->participants->count() }})</h3>
            @foreach($application->participants as $i => $p)
            <div class="participant-card">
                <h4>
                    {{ $p->last_name }} {{ $p->first_name }} {{ $p->patronymic ?? '' }}
                    @if($p->is_captain) <span style="color: #e65100; font-size: 12px;">(капитан)</span> @endif
                    @if($p->is_minor) <span style="color: #7b1fa2; font-size: 12px;">(несоверш.)</span> @endif
                </h4>
                <div class="info-grid">
                    @if($p->birth_date)
                    <div class="info-item">
                        <div class="label">Дата рождения</div>
                        <div class="value">{{ $p->birth_date->format('d.m.Y') }}</div>
                    </div>
                    @endif
                    @if($p->gender)
                    <div class="info-item">
                        <div class="label">Пол</div>
                        <div class="value">{{ $p->gender === 'male' ? 'Мужской' : 'Женский' }}</div>
                    </div>
                    @endif
                    @if($p->participant_status)
                    <div class="info-item">
                        <div class="label">Статус</div>
                        <div class="value">
                            @switch($p->participant_status)
                                @case('rural') Сельский житель @break
                                @case('apk_worker') Работник АПК @break
                                @case('apk_student') Обучающийся АПК @break
                            @endswitch
                        </div>
                    </div>
                    @endif
                    @if($p->status_detail)
                    <div class="info-item">
                        <div class="label">Детали статуса</div>
                        <div class="value">{{ $p->status_detail }}</div>
                    </div>
                    @endif
                    @if($p->phone)
                    <div class="info-item">
                        <div class="label">Телефон</div>
                        <div class="value">{{ $p->phone }}</div>
                    </div>
                    @endif
                    @if($p->email)
                    <div class="info-item">
                        <div class="label">Email</div>
                        <div class="value">{{ $p->email }}</div>
                    </div>
                    @endif
                    @if($p->clothing_size)
                    <div class="info-item">
                        <div class="label">Размер одежды</div>
                        <div class="value">{{ $p->clothing_size }}</div>
                    </div>
                    @endif
                    @if($p->age)
                    <div class="info-item">
                        <div class="label">Возраст</div>
                        <div class="value">{{ $p->age }} лет</div>
                    </div>
                    @endif
                </div>

                @if($p->legalRepresentative)
                <div class="legal-rep">
                    <h5>Законный представитель</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">ФИО</div>
                            <div class="value">{{ $p->legalRepresentative->last_name }} {{ $p->legalRepresentative->first_name }} {{ $p->legalRepresentative->patronymic ?? '' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">Статус</div>
                            <div class="value">
                                @switch($p->legalRepresentative->status)
                                    @case('parent') Родитель @break
                                    @case('adopter') Усыновитель @break
                                    @case('guardian') Опекун @break
                                    @case('trustee') Попечитель @break
                                @endswitch
                            </div>
                        </div>
                        @if($p->legalRepresentative->document)
                        <div class="info-item">
                            <div class="label">Документ</div>
                            <div class="value">{{ $p->legalRepresentative->document }}</div>
                        </div>
                        @endif
                        @if($p->legalRepresentative->phone)
                        <div class="info-item">
                            <div class="label">Телефон</div>
                            <div class="value">{{ $p->legalRepresentative->phone }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
