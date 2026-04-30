<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель — Заявки</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, #1a5c2e 0%, #2d7a3a 100%);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar h1 { font-size: 20px; }
        .navbar-actions { display: flex; gap: 12px; align-items: center; }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.3s;
        }
        .navbar a:hover { background: rgba(255,255,255,0.15); }
        .navbar .btn-export {
            background: white;
            color: #2d7a3a;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .navbar .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            background: white;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #1a5c2e;
        }
        .stat-card .label {
            font-size: 13px;
            color: #666;
            margin-top: 4px;
        }
        .filters {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-group label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
        }
        .filter-btn {
            padding: 8px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
        }
        .filter-btn:hover { background: #388E3C; }
        .filter-reset {
            padding: 8px 16px;
            background: #f5f5f5;
            color: #333;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }
        .table-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e8f5e9;
        }
        td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        tr:hover { background: #f8fdf8; }
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
        .badge-confirmed { background: #e8f5e9; color: #2e7d32; }
        .badge-crm-yes { background: #e8f5e9; color: #2e7d32; }
        .badge-crm-no { background: #fff8e1; color: #f57f17; }
        .view-link {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
        }
        .view-link:hover { text-decoration: underline; }
        .pagination {
            display: flex;
            justify-content: center;
            padding: 20px;
            gap: 4px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            color: #333;
            border: 1px solid #e0e0e0;
        }
        .pagination span.current {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        .pagination a:hover { background: #f5f5f5; }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .filters { flex-direction: column; }
            .navbar { flex-direction: column; gap: 12px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Админ-панель — Земля спорта 2026</h1>
        <div class="navbar-actions">
            <a href="/admin/export" class="btn-export">Экспорт в Excel</a>
            <a href="/" target="_blank">На сайт</a>
            <a href="/admin/logout">Выйти</a>
        </div>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="number">{{ $stats['total'] }}</div>
                <div class="label">Всего заявок</div>
            </div>
            <div class="stat-card">
                <div class="number">{{ $stats['individual'] }}</div>
                <div class="label">Индивидуальных</div>
            </div>
            <div class="stat-card">
                <div class="number">{{ $stats['team'] }}</div>
                <div class="label">Командных</div>
            </div>
            <div class="stat-card">
                <div class="number">{{ $stats['family'] }}</div>
                <div class="label">Семейных эстафет</div>
            </div>
        </div>

        <div class="filters">
            <form method="GET" action="/admin/dashboard" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                <div class="filter-group">
                    <label>Тип заявки</label>
                    <select name="type">
                        <option value="">Все</option>
                        <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Индивидуальная</option>
                        <option value="team" {{ request('type') === 'team' ? 'selected' : '' }}>Командная</option>
                        <option value="family" {{ request('type') === 'family' ? 'selected' : '' }}>Семейная</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Субъект РФ</label>
                    <input type="text" name="subject_rf" value="{{ request('subject_rf') }}" placeholder="Начните вводить...">
                </div>
                <div class="filter-group">
                    <label>Дата с</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label>Дата по</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
                <button type="submit" class="filter-btn">Фильтровать</button>
                <a href="/admin/dashboard" class="filter-reset">Сбросить</a>
            </form>
        </div>

        <div class="table-wrapper">
            @if($applications->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Тип</th>
                        <th>Субъект РФ</th>
                        <th>Уровень</th>
                        <th>Дисциплина</th>
                        <th>Участники</th>
                        <th>Email</th>
                        <th>CRM</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td>{{ $app->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @switch($app->type)
                                @case('individual') <span class="badge badge-individual">Инд.</span> @break
                                @case('team') <span class="badge badge-team">Команд.</span> @break
                                @case('family') <span class="badge badge-family">Семейн.</span> @break
                            @endswitch
                        </td>
                        <td>{{ Str::limit($app->subject_rf, 20) }}</td>
                        <td>{{ $app->participation_level === 'municipal' ? 'Мун.' : 'Рег.' }}</td>
                        <td>{{ $app->discipline ?? '-' }}</td>
                        <td>
                            @foreach($app->participants->take(3) as $p)
                                <div style="font-size: 12px;">{{ $p->last_name }} {{ mb_substr($p->first_name, 0, 1) }}.</div>
                            @endforeach
                            @if($app->participants->count() > 3)
                                <div style="font-size: 11px; color: #999;">+{{ $app->participants->count() - 3 }} ещё</div>
                            @endif
                        </td>
                        <td style="font-size: 12px;">{{ $app->email }}</td>
                        <td>
                            @if($app->sent_to_crm)
                                <span class="badge badge-crm-yes">Да</span>
                            @else
                                <span class="badge badge-crm-no">Нет</span>
                            @endif
                        </td>
                        <td><a href="/admin/applications/{{ $app->id }}" class="view-link">Подробнее</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $applications->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
            </div>
            @else
            <div class="empty-state">
                <p style="font-size: 18px; margin-bottom: 8px;">Заявок пока нет</p>
                <p>Они появятся здесь после отправки формы регистрации</p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
