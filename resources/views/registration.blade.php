<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Регистрация — Марафон «Земля спорта — 2026»</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #1a5c2e 0%, #2d7a3a 50%, #4CAF50 100%);
            border-radius: 16px 16px 0 0;
            color: white;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .form-wrapper {
            background: white;
            border-radius: 0 0 16px 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .step {
            display: none;
        }
        .step.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 8px;
        }
        .progress-step {
            width: 40px;
            height: 4px;
            border-radius: 2px;
            background: #e0e0e0;
            transition: background 0.3s;
        }
        .progress-step.active {
            background: #4CAF50;
        }
        .progress-step.completed {
            background: #2d7a3a;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            color: #333;
            font-size: 14px;
        }
        .form-group label .required {
            color: #e53935;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s, box-shadow 0.3s;
            outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        input.error, select.error {
            border-color: #e53935;
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.1);
        }
        .error-message {
            color: #e53935;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .radio-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .radio-option {
            flex: 1;
            min-width: 140px;
        }
        .radio-option input[type="radio"] {
            display: none;
        }
        .radio-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            font-weight: 500;
        }
        .radio-option input[type="radio"]:checked + label {
            border-color: #4CAF50;
            background: #e8f5e9;
            color: #2d7a3a;
        }
        .radio-option label:hover {
            border-color: #4CAF50;
            background: #f1f8e9;
        }
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .checkbox-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .checkbox-option input[type="radio"],
        .checkbox-option input[type="checkbox"] {
            width: auto;
            margin-top: 3px;
            accent-color: #4CAF50;
        }
        .checkbox-option label {
            font-size: 14px;
            cursor: pointer;
            line-height: 1.4;
        }
        .btn {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2d7a3a, #4CAF50);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
            border: 2px solid #e0e0e0;
        }
        .btn-secondary:hover {
            background: #eeeeee;
        }
        .btn-danger {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
            padding: 8px 16px;
            font-size: 13px;
        }
        .btn-danger:hover {
            background: #ffcdd2;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 12px;
        }
        .participant-card {
            border: 2px solid #e8f5e9;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            background: #fafffe;
            position: relative;
        }
        .participant-card h4 {
            color: #2d7a3a;
            margin-bottom: 16px;
            font-size: 16px;
        }
        .participant-card .remove-participant {
            position: absolute;
            top: 12px;
            right: 12px;
        }
        .add-participant-btn {
            width: 100%;
            padding: 16px;
            border: 2px dashed #4CAF50;
            border-radius: 12px;
            background: transparent;
            color: #4CAF50;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        .add-participant-btn:hover {
            background: #e8f5e9;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a5c2e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8f5e9;
        }
        .discipline-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .discipline-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .discipline-option:hover {
            border-color: #4CAF50;
            background: #f1f8e9;
        }
        .discipline-option.selected {
            border-color: #4CAF50;
            background: #e8f5e9;
        }
        .discipline-option input[type="radio"] {
            width: auto;
            accent-color: #4CAF50;
        }
        .hidden { display: none !important; }
        .success-screen {
            text-align: center;
            padding: 40px 20px;
        }
        .success-screen .icon {
            width: 80px;
            height: 80px;
            background: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        .success-screen h2 {
            color: #2d7a3a;
            margin-bottom: 12px;
        }
        .success-screen p {
            color: #666;
            line-height: 1.6;
        }
        .inline-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .inline-fields-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }
        .status-detail-input {
            margin-top: 8px;
        }
        .legal-rep-block {
            border: 2px solid #fff3cd;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
            background: #fffde7;
        }
        .legal-rep-block h5 {
            color: #f57f17;
            margin-bottom: 12px;
        }
        .consent-block {
            background: #f5f5f5;
            border-radius: 8px;
            padding: 16px;
            margin-top: 20px;
        }
        .consent-block label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            font-size: 13px;
            line-height: 1.5;
        }
        .consent-block input[type="checkbox"] {
            width: auto;
            margin-top: 2px;
            accent-color: #4CAF50;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        .loading.active { display: block; }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4CAF50;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @media (max-width: 600px) {
            .form-wrapper { padding: 20px; }
            .inline-fields, .inline-fields-3 { grid-template-columns: 1fr; }
            .discipline-grid { grid-template-columns: 1fr; }
            .radio-group { flex-direction: column; }
            .btn-group { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Всероссийский Марафон «Земля спорта — 2026»</h1>
            <p>Региональный этап — Регистрация участников</p>
        </div>
        <div class="form-wrapper">
            <!-- Landing -->
            <div id="landing" class="step active">
                <div style="text-align: center; padding: 40px 0;">
                    <h2 style="color: #1a5c2e; margin-bottom: 16px;">Добро пожаловать!</h2>
                    <p style="color: #666; margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                        Для участия в Региональном этапе Всероссийского Марафона «Земля спорта — 2026» заполните форму регистрации.
                    </p>
                    <button class="btn btn-primary" onclick="startRegistration()" style="font-size: 18px; padding: 18px 48px;">
                        Зарегистрироваться
                    </button>
                </div>
            </div>

            <!-- Step 1: Parameters -->
            <div id="step1" class="step">
                <div class="progress-bar">
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                </div>
                <h3 class="section-title">Шаг 1. Выбор параметров заявки</h3>

                <div class="form-group">
                    <label>Субъект РФ <span class="required">*</span></label>
                    <select id="subject_rf" onchange="onSubjectChange()">
                        <option value="">— Выберите субъект —</option>
                    </select>
                    <div class="error-message" id="subject_rf_error">Выберите субъект РФ</div>
                </div>

                <div class="form-group">
                    <label>Уровень участия <span class="required">*</span></label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="participation_level" id="level_municipal" value="municipal" onchange="onLevelChange()">
                            <label for="level_municipal">Муниципальный</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="participation_level" id="level_regional" value="regional" onchange="onLevelChange()">
                            <label for="level_regional">Региональный</label>
                        </div>
                    </div>
                    <div class="error-message" id="level_error">Выберите уровень участия</div>
                </div>

                <div class="form-group hidden" id="municipality_group">
                    <label>Муниципальное образование <span class="required">*</span></label>
                    <select id="municipality">
                        <option value="">— Выберите муниципальное образование —</option>
                    </select>
                    <div class="error-message" id="municipality_error">Выберите муниципальное образование</div>
                </div>

                <div class="form-group">
                    <label>Вид заявки <span class="required">*</span></label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="application_type" id="type_individual" value="individual" onchange="onTypeChange()">
                            <label for="type_individual">Индивидуальная</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="application_type" id="type_team" value="team" onchange="onTypeChange()">
                            <label for="type_team">Командная</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="application_type" id="type_family" value="family" onchange="onTypeChange()">
                            <label for="type_family">Семейная эстафета</label>
                        </div>
                    </div>
                    <div class="error-message" id="type_error">Выберите вид заявки</div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="goToLanding()">Назад</button>
                    <button class="btn btn-primary" onclick="goToStep2()">Далее</button>
                </div>
            </div>

            <!-- Step 2: Form fields -->
            <div id="step2" class="step">
                <div class="progress-bar">
                    <div class="progress-step completed"></div>
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                </div>

                <!-- Individual Form -->
                <div id="form_individual" class="hidden">
                    <h3 class="section-title">Форма №1 — Индивидуальная заявка</h3>
                    <div id="individual_participant"></div>
                    <div class="form-group">
                        <label>Дисциплина <span class="required">*</span></label>
                        <div class="discipline-grid">
                            <label class="discipline-option">
                                <input type="radio" name="discipline" value="Многоборье ГТО"> Многоборье ГТО
                            </label>
                            <label class="discipline-option">
                                <input type="radio" name="discipline" value="Силовой экстрим"> Силовой экстрим
                            </label>
                        </div>
                        <div class="error-message" id="discipline_error">Выберите дисциплину</div>
                    </div>
                </div>

                <!-- Team Form -->
                <div id="form_team" class="hidden">
                    <h3 class="section-title">Форма №2 — Командная заявка</h3>
                    <div class="form-group">
                        <label>Дисциплина <span class="required">*</span></label>
                        <div class="discipline-grid">
                            <label class="discipline-option">
                                <input type="radio" name="team_discipline" value="Мини-футбол"> Мини-футбол
                            </label>
                            <label class="discipline-option">
                                <input type="radio" name="team_discipline" value="Волейбол"> Волейбол
                            </label>
                        </div>
                        <div class="error-message" id="team_discipline_error">Выберите дисциплину</div>
                    </div>
                    <h4 style="color: #2d7a3a; margin-bottom: 16px;">Капитан команды</h4>
                    <div id="team_captain"></div>
                    <h4 style="color: #2d7a3a; margin: 20px 0 16px;">Состав команды</h4>
                    <div id="team_members"></div>
                    <button class="add-participant-btn" onclick="addTeamMember()">+ Добавить участника</button>
                </div>

                <!-- Family Form -->
                <div id="form_family" class="hidden">
                    <h3 class="section-title">Форма №3 — Семейная эстафета</h3>
                    <h4 style="color: #2d7a3a; margin-bottom: 16px;">Совершеннолетний участник</h4>
                    <div id="family_adult"></div>
                    <h4 style="color: #2d7a3a; margin: 20px 0 16px;">Несовершеннолетний участник</h4>
                    <div id="family_minor"></div>
                    <div id="legal_rep_section"></div>
                </div>

                <!-- Common email -->
                <div class="form-group" style="margin-top: 20px;">
                    <label>Электронная почта для подтверждения <span class="required">*</span></label>
                    <input type="email" id="confirmation_email" placeholder="example@mail.ru">
                    <div class="error-message" id="email_error">Введите корректный email</div>
                </div>

                <div class="consent-block">
                    <label>
                        <input type="checkbox" id="consent_checkbox">
                        Подтверждаю, что ознакомлен(а) с Положением о Марафоне и правилами проведения заявленной дисциплины. Имею медицинский допуск к участию в физкультурных мероприятиях. Согласие на обработку персональных данных прилагается.
                    </label>
                    <div class="error-message" id="consent_error" style="margin-top: 4px;">Необходимо подтвердить согласие</div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="goToStep1()">Назад</button>
                    <button class="btn btn-primary" onclick="submitForm()">Отправить заявку</button>
                </div>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Отправка заявки...</p>
                </div>
            </div>

            <!-- Success -->
            <div id="step_success" class="step">
                <div class="success-screen">
                    <div class="icon">&#10003;</div>
                    <h2>Заявка успешно отправлена!</h2>
                    <p id="success_message">Письмо с подтверждением регистрации отправлено на указанный email. Сохраните его — оно является официальным подтверждением участия.</p>
                    <button class="btn btn-primary" onclick="location.reload()" style="margin-top: 30px;">Новая регистрация</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // State
    let regions = [];
    let municipalities = {};
    let teamMemberCount = 2;

    // Init
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/regions')
            .then(r => r.json())
            .then(data => {
                regions = data;
                const select = document.getElementById('subject_rf');
                data.forEach(region => {
                    const opt = document.createElement('option');
                    opt.value = region;
                    opt.textContent = region;
                    select.appendChild(opt);
                });
            });
    });

    function startRegistration() {
        showStep('step1');
    }

    function goToLanding() {
        showStep('landing');
    }

    function showStep(stepId) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById(stepId).classList.add('active');
    }

    function onSubjectChange() {
        const subject = document.getElementById('subject_rf').value;
        if (subject && document.getElementById('level_municipal').checked) {
            loadMunicipalities(subject);
        }
    }

    function onLevelChange() {
        const isMunicipal = document.getElementById('level_municipal').checked;
        const group = document.getElementById('municipality_group');
        if (isMunicipal) {
            group.classList.remove('hidden');
            const subject = document.getElementById('subject_rf').value;
            if (subject) loadMunicipalities(subject);
        } else {
            group.classList.add('hidden');
        }
    }

    function loadMunicipalities(subject) {
        fetch('/api/municipalities?subject=' + encodeURIComponent(subject))
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('municipality');
                select.innerHTML = '<option value="">— Выберите муниципальное образование —</option>';
                data.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m;
                    select.appendChild(opt);
                });
            });
    }

    function onTypeChange() {
        // pre-render will happen on step2
    }

    function goToStep1() {
        showStep('step1');
    }

    function goToStep2() {
        // Validate step 1
        let valid = true;
        if (!document.getElementById('subject_rf').value) {
            showError('subject_rf', 'subject_rf_error'); valid = false;
        } else { hideError('subject_rf', 'subject_rf_error'); }

        const level = document.querySelector('input[name="participation_level"]:checked');
        if (!level) {
            document.getElementById('level_error').style.display = 'block'; valid = false;
        } else { document.getElementById('level_error').style.display = 'none'; }

        if (level && level.value === 'municipal' && !document.getElementById('municipality').value) {
            showError('municipality', 'municipality_error'); valid = false;
        } else { hideError('municipality', 'municipality_error'); }

        const type = document.querySelector('input[name="application_type"]:checked');
        if (!type) {
            document.getElementById('type_error').style.display = 'block'; valid = false;
        } else { document.getElementById('type_error').style.display = 'none'; }

        if (!valid) return;

        // Render form based on type
        renderForm(type.value);
        showStep('step2');
    }

    function renderForm(type) {
        document.getElementById('form_individual').classList.add('hidden');
        document.getElementById('form_team').classList.add('hidden');
        document.getElementById('form_family').classList.add('hidden');

        if (type === 'individual') {
            document.getElementById('form_individual').classList.remove('hidden');
            document.getElementById('individual_participant').innerHTML = renderIndividualParticipant();
        } else if (type === 'team') {
            document.getElementById('form_team').classList.remove('hidden');
            document.getElementById('team_captain').innerHTML = renderTeamCaptain();
            document.getElementById('team_members').innerHTML = '';
            teamMemberCount = 0;
            addTeamMember();
            addTeamMember();
        } else if (type === 'family') {
            document.getElementById('form_family').classList.remove('hidden');
            document.getElementById('family_adult').innerHTML = renderFamilyAdult();
            document.getElementById('family_minor').innerHTML = renderFamilyMinor();
            document.getElementById('legal_rep_section').innerHTML = renderLegalRep();
        }
    }

    function renderIndividualParticipant() {
        return `
        <div class="participant-card">
            <h4>Сведения об участнике</h4>
            <div class="inline-fields-3">
                <div class="form-group">
                    <label>Фамилия <span class="required">*</span></label>
                    <input type="text" id="ind_last_name" placeholder="Иванов" required>
                </div>
                <div class="form-group">
                    <label>Имя <span class="required">*</span></label>
                    <input type="text" id="ind_first_name" placeholder="Иван" required>
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" id="ind_patronymic" placeholder="Иванович">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Дата рождения <span class="required">*</span></label>
                    <input type="date" id="ind_birth_date">
                </div>
                <div class="form-group">
                    <label>Пол <span class="required">*</span></label>
                    <select id="ind_gender">
                        <option value="">— Выберите —</option>
                        <option value="male">Мужской</option>
                        <option value="female">Женский</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Статус участника <span class="required">*</span></label>
                <div class="checkbox-group">
                    <div class="checkbox-option">
                        <input type="radio" name="ind_status" id="ind_status_rural" value="rural" onchange="toggleStatusDetail('ind')">
                        <label for="ind_status_rural">Проживающий на сельских территориях и агломерациях</label>
                    </div>
                    <div class="checkbox-option">
                        <input type="radio" name="ind_status" id="ind_status_worker" value="apk_worker" onchange="toggleStatusDetail('ind')">
                        <label for="ind_status_worker">Работающий в сфере АПК</label>
                    </div>
                    <div class="checkbox-option">
                        <input type="radio" name="ind_status" id="ind_status_student" value="apk_student" onchange="toggleStatusDetail('ind')">
                        <label for="ind_status_student">Обучающийся по направлениям АПК</label>
                    </div>
                </div>
                <input type="text" id="ind_status_detail" class="status-detail-input hidden" placeholder="Укажите адрес / место работы / образовательную организацию">
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Контактный телефон <span class="required">*</span></label>
                    <input type="tel" id="ind_phone" placeholder="+7 (999) 123-45-67">
                </div>
                <div class="form-group">
                    <label>Электронная почта <span class="required">*</span></label>
                    <input type="email" id="ind_email" placeholder="email@example.ru">
                </div>
            </div>
            <div class="form-group">
                <label>Размер одежды</label>
                <select id="ind_clothing_size">
                    <option value="">— Выберите —</option>
                    <option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option><option>XXXL</option>
                </select>
            </div>
        </div>`;
    }

    function renderTeamCaptain() {
        return `
        <div class="participant-card">
            <div class="inline-fields-3">
                <div class="form-group">
                    <label>Фамилия <span class="required">*</span></label>
                    <input type="text" id="captain_last_name" required>
                </div>
                <div class="form-group">
                    <label>Имя <span class="required">*</span></label>
                    <input type="text" id="captain_first_name" required>
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" id="captain_patronymic">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Контактный телефон <span class="required">*</span></label>
                    <input type="tel" id="captain_phone" placeholder="+7 (999) 123-45-67">
                </div>
                <div class="form-group">
                    <label>Электронная почта <span class="required">*</span></label>
                    <input type="email" id="captain_email" placeholder="email@example.ru">
                </div>
            </div>
        </div>`;
    }

    function addTeamMember() {
        teamMemberCount++;
        const container = document.getElementById('team_members');
        const div = document.createElement('div');
        div.className = 'participant-card';
        div.id = 'team_member_' + teamMemberCount;
        div.innerHTML = `
            <h4>Участник ${container.children.length + 1}</h4>
            <button class="btn btn-danger remove-participant" onclick="removeTeamMember(${teamMemberCount})">Удалить</button>
            <div class="inline-fields-3">
                <div class="form-group">
                    <label>Фамилия <span class="required">*</span></label>
                    <input type="text" class="tm_last_name" required>
                </div>
                <div class="form-group">
                    <label>Имя <span class="required">*</span></label>
                    <input type="text" class="tm_first_name" required>
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" class="tm_patronymic">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Дата рождения <span class="required">*</span></label>
                    <input type="date" class="tm_birth_date">
                </div>
                <div class="form-group">
                    <label>Пол <span class="required">*</span></label>
                    <select class="tm_gender">
                        <option value="">— Выберите —</option>
                        <option value="male">Мужской</option>
                        <option value="female">Женский</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Статус участника</label>
                <select class="tm_status" onchange="toggleTeamStatusDetail(this)">
                    <option value="">— Выберите —</option>
                    <option value="rural">Проживающий на сельских территориях</option>
                    <option value="apk_worker">Работающий в сфере АПК</option>
                    <option value="apk_student">Обучающийся по направлениям АПК</option>
                </select>
                <input type="text" class="tm_status_detail hidden" placeholder="Укажите подробности">
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Контактный телефон</label>
                    <input type="tel" class="tm_phone" placeholder="+7 (999) 123-45-67">
                </div>
                <div class="form-group">
                    <label>Размер одежды</label>
                    <select class="tm_clothing_size">
                        <option value="">— Выберите —</option>
                        <option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option><option>XXXL</option>
                    </select>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    function removeTeamMember(id) {
        const el = document.getElementById('team_member_' + id);
        if (el) el.remove();
        // Re-number
        const members = document.getElementById('team_members').children;
        for (let i = 0; i < members.length; i++) {
            members[i].querySelector('h4').textContent = 'Участник ' + (i + 1);
        }
    }

    function renderFamilyAdult() {
        return `
        <div class="participant-card">
            <h4>Совершеннолетний участник</h4>
            <div class="inline-fields-3">
                <div class="form-group">
                    <label>Фамилия <span class="required">*</span></label>
                    <input type="text" id="adult_last_name" required>
                </div>
                <div class="form-group">
                    <label>Имя <span class="required">*</span></label>
                    <input type="text" id="adult_first_name" required>
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" id="adult_patronymic">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Дата рождения <span class="required">*</span></label>
                    <input type="date" id="adult_birth_date">
                </div>
                <div class="form-group">
                    <label>Пол <span class="required">*</span></label>
                    <select id="adult_gender">
                        <option value="">— Выберите —</option>
                        <option value="male">Мужской</option>
                        <option value="female">Женский</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Статус участника <span class="required">*</span></label>
                <div class="checkbox-group">
                    <div class="checkbox-option">
                        <input type="radio" name="adult_status" id="adult_status_rural" value="rural" onchange="toggleStatusDetail('adult')">
                        <label for="adult_status_rural">Проживающий на сельских территориях и агломерациях</label>
                    </div>
                    <div class="checkbox-option">
                        <input type="radio" name="adult_status" id="adult_status_worker" value="apk_worker" onchange="toggleStatusDetail('adult')">
                        <label for="adult_status_worker">Работающий в сфере АПК</label>
                    </div>
                    <div class="checkbox-option">
                        <input type="radio" name="adult_status" id="adult_status_student" value="apk_student" onchange="toggleStatusDetail('adult')">
                        <label for="adult_status_student">Обучающийся по направлениям АПК</label>
                    </div>
                </div>
                <input type="text" id="adult_status_detail" class="status-detail-input hidden" placeholder="Укажите адрес / место работы / образовательную организацию">
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Контактный телефон <span class="required">*</span></label>
                    <input type="tel" id="adult_phone" placeholder="+7 (999) 123-45-67">
                </div>
                <div class="form-group">
                    <label>Электронная почта <span class="required">*</span></label>
                    <input type="email" id="adult_email" placeholder="email@example.ru">
                </div>
            </div>
            <div class="form-group">
                <label>Размер одежды</label>
                <select id="adult_clothing_size">
                    <option value="">— Выберите —</option>
                    <option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option><option>XXXL</option>
                </select>
            </div>
        </div>`;
    }

    function renderFamilyMinor() {
        return `
        <div class="participant-card">
            <h4>Несовершеннолетний участник</h4>
            <div class="inline-fields-3">
                <div class="form-group">
                    <label>Фамилия <span class="required">*</span></label>
                    <input type="text" id="minor_last_name" required>
                </div>
                <div class="form-group">
                    <label>Имя <span class="required">*</span></label>
                    <input type="text" id="minor_first_name" required>
                </div>
                <div class="form-group">
                    <label>Отчество</label>
                    <input type="text" id="minor_patronymic">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Дата рождения <span class="required">*</span></label>
                    <input type="date" id="minor_birth_date">
                </div>
                <div class="form-group">
                    <label>Возраст (полных лет) <span class="required">*</span></label>
                    <input type="number" id="minor_age" min="1" max="17" placeholder="Возраст">
                </div>
            </div>
            <div class="inline-fields">
                <div class="form-group">
                    <label>Пол <span class="required">*</span></label>
                    <select id="minor_gender">
                        <option value="">— Выберите —</option>
                        <option value="male">Мужской</option>
                        <option value="female">Женский</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Размер одежды</label>
                    <select id="minor_clothing_size">
                        <option value="">— Выберите —</option>
                        <option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option>
                    </select>
                </div>
            </div>
        </div>`;
    }

    function renderLegalRep() {
        return `
        <div class="legal-rep-block">
            <h5>Сведения о законном представителе несовершеннолетнего</h5>
            <p style="font-size: 12px; color: #666; margin-bottom: 16px;">* Если взрослый участник является законным представителем несовершеннолетнего, отметьте галочку ниже.</p>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="adult_is_rep" onchange="toggleLegalRepFields()" style="width: auto; accent-color: #4CAF50;">
                    Взрослый участник является законным представителем
                </label>
            </div>
            <div id="legal_rep_fields">
                <div class="inline-fields-3">
                    <div class="form-group">
                        <label>Фамилия <span class="required">*</span></label>
                        <input type="text" id="rep_last_name">
                    </div>
                    <div class="form-group">
                        <label>Имя <span class="required">*</span></label>
                        <input type="text" id="rep_first_name">
                    </div>
                    <div class="form-group">
                        <label>Отчество</label>
                        <input type="text" id="rep_patronymic">
                    </div>
                </div>
                <div class="form-group">
                    <label>Статус <span class="required">*</span></label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="rep_status" id="rep_parent" value="parent">
                            <label for="rep_parent">Родитель</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="rep_status" id="rep_adopter" value="adopter">
                            <label for="rep_adopter">Усыновитель</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="rep_status" id="rep_guardian" value="guardian">
                            <label for="rep_guardian">Опекун</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="rep_status" id="rep_trustee" value="trustee">
                            <label for="rep_trustee">Попечитель</label>
                        </div>
                    </div>
                </div>
                <div class="inline-fields">
                    <div class="form-group">
                        <label>Документ (серия, номер)</label>
                        <input type="text" id="rep_document" placeholder="Серия и номер документа">
                    </div>
                    <div class="form-group">
                        <label>Контактный телефон</label>
                        <input type="tel" id="rep_phone" placeholder="+7 (999) 123-45-67">
                    </div>
                </div>
            </div>
        </div>`;
    }

    function toggleLegalRepFields() {
        const isRep = document.getElementById('adult_is_rep').checked;
        const fields = document.getElementById('legal_rep_fields');
        if (isRep) {
            fields.classList.add('hidden');
        } else {
            fields.classList.remove('hidden');
        }
    }

    function toggleStatusDetail(prefix) {
        const detail = document.getElementById(prefix + '_status_detail');
        const checked = document.querySelector('input[name="' + prefix + '_status"]:checked');
        if (checked) {
            detail.classList.remove('hidden');
            if (checked.value === 'rural') {
                detail.placeholder = 'Адрес регистрации по паспорту';
            } else if (checked.value === 'apk_worker') {
                detail.placeholder = 'Место работы (в сфере АПК)';
            } else if (checked.value === 'apk_student') {
                detail.placeholder = 'Образовательная организация';
            }
        } else {
            detail.classList.add('hidden');
        }
    }

    function toggleTeamStatusDetail(selectEl) {
        const detail = selectEl.parentElement.querySelector('.tm_status_detail');
        if (selectEl.value) {
            detail.classList.remove('hidden');
            if (selectEl.value === 'rural') detail.placeholder = 'Адрес регистрации по паспорту';
            else if (selectEl.value === 'apk_worker') detail.placeholder = 'Место работы (в сфере АПК)';
            else detail.placeholder = 'Образовательная организация';
        } else {
            detail.classList.add('hidden');
        }
    }

    function showError(inputId, errorId) {
        document.getElementById(inputId).classList.add('error');
        document.getElementById(errorId).style.display = 'block';
    }

    function hideError(inputId, errorId) {
        document.getElementById(inputId).classList.remove('error');
        document.getElementById(errorId).style.display = 'none';
    }

    function submitForm() {
        const type = document.querySelector('input[name="application_type"]:checked').value;
        const email = document.getElementById('confirmation_email').value;
        const consent = document.getElementById('consent_checkbox').checked;

        // Validate email
        if (!email || !email.includes('@')) {
            showError('confirmation_email', 'email_error');
            return;
        } else { hideError('confirmation_email', 'email_error'); }

        // Validate consent
        if (!consent) {
            document.getElementById('consent_error').style.display = 'block';
            return;
        } else { document.getElementById('consent_error').style.display = 'none'; }

        let participants = [];
        let discipline = null;
        let valid = true;

        if (type === 'individual') {
            discipline = document.querySelector('input[name="discipline"]:checked');
            if (!discipline) {
                document.getElementById('discipline_error').style.display = 'block';
                return;
            } else { document.getElementById('discipline_error').style.display = 'none'; }
            discipline = discipline.value;

            const p = collectIndividualParticipant();
            if (!p) return;
            p.email = p.email || email;
            participants.push(p);
        } else if (type === 'team') {
            discipline = document.querySelector('input[name="team_discipline"]:checked');
            if (!discipline) {
                document.getElementById('team_discipline_error').style.display = 'block';
                return;
            } else { document.getElementById('team_discipline_error').style.display = 'none'; }
            discipline = discipline.value;

            const captain = collectTeamCaptain();
            if (!captain) return;
            captain.is_captain = true;
            participants.push(captain);

            const members = collectTeamMembers();
            if (!members) return;
            participants = participants.concat(members);
        } else if (type === 'family') {
            const adult = collectFamilyAdult();
            if (!adult) return;
            participants.push(adult);

            const minor = collectFamilyMinor();
            if (!minor) return;
            participants.push(minor);
        }

        const data = {
            type: type,
            subject_rf: document.getElementById('subject_rf').value,
            participation_level: document.querySelector('input[name="participation_level"]:checked').value,
            municipality: document.getElementById('municipality').value || null,
            discipline: discipline,
            email: email,
            participants: participants,
        };

        // Show loading
        document.getElementById('loading').classList.add('active');

        fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(r => r.json())
        .then(result => {
            document.getElementById('loading').classList.remove('active');
            if (result.success) {
                document.getElementById('success_message').textContent = result.message;
                showStep('step_success');
            } else {
                alert(result.message || 'Произошла ошибка при отправке. Проверьте заполнение полей.');
            }
        })
        .catch(err => {
            document.getElementById('loading').classList.remove('active');
            alert('Ошибка сети. Попробуйте позже.');
            console.error(err);
        });
    }

    function collectIndividualParticipant() {
        const lastName = document.getElementById('ind_last_name').value.trim();
        const firstName = document.getElementById('ind_first_name').value.trim();
        if (!lastName || !firstName) {
            alert('Заполните ФИО участника');
            return null;
        }
        const status = document.querySelector('input[name="ind_status"]:checked');
        const phone = document.getElementById('ind_phone').value.trim();
        const indEmail = document.getElementById('ind_email').value.trim();
        if (!phone) { alert('Укажите контактный телефон'); return null; }
        if (!indEmail) { alert('Укажите электронную почту участника'); return null; }
        if (!document.getElementById('ind_birth_date').value) { alert('Укажите дату рождения'); return null; }
        if (!document.getElementById('ind_gender').value) { alert('Укажите пол'); return null; }
        if (!status) { alert('Выберите статус участника'); return null; }

        return {
            last_name: lastName,
            first_name: firstName,
            patronymic: document.getElementById('ind_patronymic').value.trim() || null,
            birth_date: document.getElementById('ind_birth_date').value,
            gender: document.getElementById('ind_gender').value,
            participant_status: status ? status.value : null,
            status_detail: document.getElementById('ind_status_detail').value.trim() || null,
            phone: phone,
            email: indEmail,
            clothing_size: document.getElementById('ind_clothing_size').value || null,
            is_minor: false,
        };
    }

    function collectTeamCaptain() {
        const lastName = document.getElementById('captain_last_name').value.trim();
        const firstName = document.getElementById('captain_first_name').value.trim();
        const phone = document.getElementById('captain_phone').value.trim();
        const capEmail = document.getElementById('captain_email').value.trim();
        if (!lastName || !firstName) { alert('Заполните ФИО капитана'); return null; }
        if (!phone) { alert('Укажите телефон капитана'); return null; }
        if (!capEmail) { alert('Укажите email капитана'); return null; }
        return {
            last_name: lastName,
            first_name: firstName,
            patronymic: document.getElementById('captain_patronymic').value.trim() || null,
            phone: phone,
            email: capEmail,
            is_captain: true,
            is_minor: false,
        };
    }

    function collectTeamMembers() {
        const cards = document.getElementById('team_members').querySelectorAll('.participant-card');
        const members = [];
        for (const card of cards) {
            const ln = card.querySelector('.tm_last_name').value.trim();
            const fn = card.querySelector('.tm_first_name').value.trim();
            if (!ln || !fn) { alert('Заполните ФИО всех участников команды'); return null; }
            const bd = card.querySelector('.tm_birth_date').value;
            const gn = card.querySelector('.tm_gender').value;
            if (!bd) { alert('Укажите дату рождения для участника ' + fn + ' ' + ln); return null; }
            if (!gn) { alert('Укажите пол для участника ' + fn + ' ' + ln); return null; }
            members.push({
                last_name: ln,
                first_name: fn,
                patronymic: card.querySelector('.tm_patronymic').value.trim() || null,
                birth_date: bd,
                gender: gn,
                participant_status: card.querySelector('.tm_status').value || null,
                status_detail: card.querySelector('.tm_status_detail').value.trim() || null,
                phone: card.querySelector('.tm_phone').value.trim() || null,
                clothing_size: card.querySelector('.tm_clothing_size').value || null,
                is_minor: false,
                is_captain: false,
            });
        }
        return members;
    }

    function collectFamilyAdult() {
        const lastName = document.getElementById('adult_last_name').value.trim();
        const firstName = document.getElementById('adult_first_name').value.trim();
        if (!lastName || !firstName) { alert('Заполните ФИО совершеннолетнего участника'); return null; }
        if (!document.getElementById('adult_birth_date').value) { alert('Укажите дату рождения взрослого'); return null; }
        if (!document.getElementById('adult_gender').value) { alert('Укажите пол взрослого участника'); return null; }
        const status = document.querySelector('input[name="adult_status"]:checked');
        if (!status) { alert('Выберите статус взрослого участника'); return null; }
        const phone = document.getElementById('adult_phone').value.trim();
        const adEmail = document.getElementById('adult_email').value.trim();
        if (!phone) { alert('Укажите телефон взрослого'); return null; }
        if (!adEmail) { alert('Укажите email взрослого'); return null; }

        return {
            last_name: lastName,
            first_name: firstName,
            patronymic: document.getElementById('adult_patronymic').value.trim() || null,
            birth_date: document.getElementById('adult_birth_date').value,
            gender: document.getElementById('adult_gender').value,
            participant_status: status.value,
            status_detail: document.getElementById('adult_status_detail').value.trim() || null,
            phone: phone,
            email: adEmail,
            clothing_size: document.getElementById('adult_clothing_size').value || null,
            is_minor: false,
        };
    }

    function collectFamilyMinor() {
        const lastName = document.getElementById('minor_last_name').value.trim();
        const firstName = document.getElementById('minor_first_name').value.trim();
        if (!lastName || !firstName) { alert('Заполните ФИО несовершеннолетнего участника'); return null; }
        if (!document.getElementById('minor_birth_date').value) { alert('Укажите дату рождения несовершеннолетнего'); return null; }
        if (!document.getElementById('minor_age').value) { alert('Укажите возраст несовершеннолетнего'); return null; }
        if (!document.getElementById('minor_gender').value) { alert('Укажите пол несовершеннолетнего'); return null; }

        const result = {
            last_name: lastName,
            first_name: firstName,
            patronymic: document.getElementById('minor_patronymic').value.trim() || null,
            birth_date: document.getElementById('minor_birth_date').value,
            age: parseInt(document.getElementById('minor_age').value),
            gender: document.getElementById('minor_gender').value,
            clothing_size: document.getElementById('minor_clothing_size').value || null,
            is_minor: true,
        };

        // Legal representative
        const adultIsRep = document.getElementById('adult_is_rep').checked;
        if (!adultIsRep) {
            const repLn = document.getElementById('rep_last_name').value.trim();
            const repFn = document.getElementById('rep_first_name').value.trim();
            if (!repLn || !repFn) { alert('Заполните ФИО законного представителя'); return null; }
            const repStatus = document.querySelector('input[name="rep_status"]:checked');
            if (!repStatus) { alert('Укажите статус законного представителя'); return null; }
            result.legal_representative = {
                last_name: repLn,
                first_name: repFn,
                patronymic: document.getElementById('rep_patronymic').value.trim() || null,
                status: repStatus.value,
                document: document.getElementById('rep_document').value.trim() || null,
                phone: document.getElementById('rep_phone').value.trim() || null,
            };
        }

        return result;
    }
    </script>
</body>
</html>
