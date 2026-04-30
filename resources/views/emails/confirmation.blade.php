<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение регистрации</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #2d7a3a 0%, #4CAF50 100%); padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Земля спорта — 2026</h1>
        <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0;">Подтверждение регистрации</p>
    </div>
    <div style="background: #f9f9f9; padding: 30px; border: 1px solid #e0e0e0; border-top: none; border-radius: 0 0 10px 10px;">
        <p>Уважаем{{ $participant && $participant->gender === 'female' ? 'ая' : 'ый' }}
            <strong>{{ $participant ? $participant->last_name . ' ' . $participant->first_name . ' ' . ($participant->patronymic ?? '') : '' }}</strong>!</p>

        <p>Ваша заявка на участие в Региональном этапе Всероссийского Марафона «Земля спорта — 2026» успешно зарегистрирована.</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #4CAF50;">
            <h3 style="margin-top: 0; color: #2d7a3a;">Данные заявки:</h3>
            <p><strong>Номер заявки:</strong> {{ $application->id }}</p>
            <p><strong>Тип:</strong>
                @switch($application->type)
                    @case('individual') Индивидуальная @break
                    @case('team') Командная @break
                    @case('family') Семейная эстафета @break
                @endswitch
            </p>
            <p><strong>Субъект РФ:</strong> {{ $application->subject_rf }}</p>
            @if($application->municipality)
                <p><strong>Муниципальное образование:</strong> {{ $application->municipality }}</p>
            @endif
            @if($application->discipline)
                <p><strong>Дисциплина:</strong> {{ $application->discipline }}</p>
            @endif
        </div>

        @if($participants->count() > 1)
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #2d7a3a;">Участники:</h3>
            <ol>
                @foreach($participants as $p)
                    <li>{{ $p->last_name }} {{ $p->first_name }} {{ $p->patronymic ?? '' }}</li>
                @endforeach
            </ol>
        </div>
        @endif

        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
            <p style="margin: 0;"><strong>Важно:</strong> Данное письмо является официальным подтверждением участия. Сохраните его и предъявите по месту проведения или организаторам при необходимости.</p>
        </div>

        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            С уважением,<br>
            Организационный комитет Всероссийского Марафона «Земля спорта — 2026»
        </p>
    </div>
</body>
</html>
