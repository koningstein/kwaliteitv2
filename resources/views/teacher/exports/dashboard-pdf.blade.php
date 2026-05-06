<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kwaliteit in Beeld — {{ $activeTeam->name }}</title>
    <style>

        /* ============================================================
           PAGINA-INSTELLINGEN
        ============================================================ */
        @page { margin: 2.2cm 2.4cm 2.2cm 2.4cm; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.55;
            margin: 0; padding: 0;
        }

        p { margin: 0; }

        /* ============================================================
           PAGINA-EINDE
        ============================================================ */
        .pb { page-break-before: always; }

        /* ============================================================
           OMSLAGBLAD
        ============================================================ */
        .cover-top {
            border-bottom: 3px solid #0f172a;
            padding-bottom: 18px;
            margin-bottom: 24px;
        }
        .cover-instrument {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .cover-title {
            font-size: 28pt;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .cover-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .cover-meta-table td {
            padding: 5px 0;
            font-size: 10pt;
            vertical-align: top;
            border-bottom: 1px solid #f1f5f9;
        }
        .cml { font-weight: bold; color: #475569; width: 36%; }
        .cmv { color: #0f172a; }

        .cover-sec-head {
            font-size: 7.5pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-top: 18px;
            margin-bottom: 8px;
        }
        .cover-person { font-size: 9pt; color: #1e293b; padding: 2px 0; }
        .cover-email  { font-size: 8pt;  color: #94a3b8; }
        .cover-empty  { font-size: 8.5pt; color: #94a3b8; font-style: italic; }

        /* ============================================================
           INTRO PAGINA'S
        ============================================================ */
        .page-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 5px;
            margin-bottom: 14px;
        }
        .section-head {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            margin-top: 16px;
            margin-bottom: 6px;
        }
        .intro-text {
            font-size: 9pt;
            color: #334155;
            margin-bottom: 6px;
            line-height: 1.6;
        }
        .bullet { padding: 2px 0 2px 14px; font-size: 9pt; color: #334155; }

        /* Thema-overzicht tabel */
        .theme-overview-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8.5pt;
        }
        .theme-overview-table th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 5px 8px;
            text-align: left;
            font-weight: bold;
        }
        .theme-overview-table td {
            padding: 4px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .theme-overview-table tr:nth-child(even) td { background-color: #f8fafc; }
        .theme-code-cell {
            font-weight: bold;
            width: 10%;
            white-space: nowrap;
        }

        /* Werkwijze kleurbalk */
        .werkwijze-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .werkwijze-table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
            vertical-align: top;
        }
        .wz-green  { background-color: #dcfce7; border-left: 4px solid #16a34a; }
        .wz-amber  { background-color: #fef9c3; border-left: 4px solid #d97706; }
        .wz-red    { background-color: #fee2e2; border-left: 4px solid #dc2626; }
        .wz-label  { font-weight: bold; }

        /* Cyclus fases */
        .cyclus-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .cyclus-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
            vertical-align: top;
        }
        .cyclus-year {
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            width: 22%;
            white-space: nowrap;
        }

        /* ============================================================
           INHOUDSBLADEN — THEMA'S
        ============================================================ */

        /* --- Thema-header --- */
        .theme-header {
            padding: 9px 14px;
            margin-bottom: 0;
        }
        .theme-header-title {
            font-size: 13pt;
            font-weight: bold;
            color: #ffffff;
            margin: 0;
        }

        /* --- Standaard --- */
        .standard-wrap { margin-bottom: 18px; }

        .standard-header {
            background-color: #f1f5f9;
            padding: 7px 12px;
            margin-top: 14px;
            margin-bottom: 8px;
        }
        .standard-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
        }
        .standard-desc { font-size: 8.5pt; color: #475569; margin: 0; }

        .basiskwaliteit-head {
            font-size: 8pt;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 4px 0;
        }
        .basiskwaliteit-text { font-size: 8.5pt; color: #475569; margin: 0 0 10px 0; }

        /* --- Criterium --- */
        .criterion-wrap { margin-bottom: 10px; }

        .crit-score-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .crit-score-table th {
            background-color: #1e293b;
            color: #ffffff;
            padding: 4px 8px;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            border: 1px solid #1e293b;
        }
        .crit-score-table th.crit-col { text-align: left; }
        .crit-score-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .crit-text-cell {
            font-size: 9pt;
            color: #0f172a;
            line-height: 1.5;
        }
        .crit-num {
            font-size: 8pt;
            font-weight: bold;
            color: #94a3b8;
            margin-bottom: 1px;
        }
        .score-cell {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            width: 7%;
            white-space: nowrap;
        }

        /* Score kleuren */
        .score-v { background-color: #16a34a; color: #ffffff; }
        .score-a { background-color: #d97706; color: #ffffff; }
        .score-o { background-color: #dc2626; color: #ffffff; }
        .score-n { background-color: #f1f5f9; color: #cbd5e1; }

        /* Indicatoren */
        .indicator-list { margin: 5px 0 0 0; padding-left: 0; }
        .indicator-item {
            font-size: 8pt;
            color: #475569;
            padding: 1px 0 1px 12px;
        }

        /* Toelichting */
        .toelichting-row td {
            background-color: #fffbeb;
            font-size: 8pt;
            color: #78350f;
            font-style: italic;
            padding: 4px 8px;
            border: 1px solid #fde68a;
        }

        /* ============================================================
           ACTIEPUNTEN
        ============================================================ */
        .ap-section-wrap {
            margin: 14px 0 0 0;
            border-top: 2px solid #e2e8f0;
            padding-top: 10px;
        }
        .ap-section-title {
            font-size: 9pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 10px 0;
        }
        .ap-crit-ref {
            font-size: 8.5pt;
            font-weight: bold;
            color: #475569;
            margin: 8px 0 4px 0;
            padding: 3px 8px;
            background-color: #f8fafc;
            border-left: 3px solid #94a3b8;
        }

        .ap-block {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            margin-bottom: 6px;
        }
        .ap-nr-desc {
            font-size: 9.5pt;
            font-weight: bold;
            color: #1e293b;
            margin: 0 0 5px 0;
        }
        .ap-meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .ap-meta-table td { padding: 1px 6px 1px 0; vertical-align: top; }
        .ap-lbl { font-weight: bold; color: #94a3b8; width: 20%; }
        .ap-val { color: #334155; width: 30%; }

        /* Status kleuren actiepunten */
        .aps-niet-gestart { color: #64748b; }
        .aps-op-schema    { color: #1d4ed8; font-weight: bold; }
        .aps-loopt-achter { color: #b45309; font-weight: bold; }
        .aps-uitgesteld   { color: #c2410c; font-weight: bold; }
        .aps-afgerond     { color: #15803d; font-weight: bold; }

        /* ============================================================
           EVALUATIES
        ============================================================ */
        .eval-wrap {
            margin-top: 7px;
            padding-top: 6px;
            border-top: 1px dotted #e2e8f0;
        }
        .eval-head {
            font-size: 7.5pt;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0 0 4px 0;
        }
        .eval-item {
            padding: 4px 0 4px 10px;
            border-left: 2px solid #cbd5e1;
            margin-bottom: 5px;
        }
        .eval-meta  { font-size: 7.5pt; color: #94a3b8; margin: 0 0 1px 0; }
        .eval-body  { font-size: 8.5pt; color: #374151; margin: 0; }

        /* ============================================================
           AANDACHTSPUNTEN VOOR HET TEAMPLAN
        ============================================================ */
        .aap-wrap {
            margin-top: 14px;
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            padding: 10px 12px;
        }
        .aap-title {
            font-size: 9pt;
            font-weight: bold;
            color: #92400e;
            margin: 0 0 8px 0;
        }
        .aap-item {
            font-size: 8.5pt;
            color: #78350f;
            padding: 2px 0 2px 12px;
        }
        .aap-score-v { color: #15803d; font-weight: bold; }
        .aap-score-a { color: #d97706; font-weight: bold; }
        .aap-score-o { color: #dc2626; font-weight: bold; }

    </style>
</head>
<body>

{{-- ================================================================
     PAGINA 1: OMSLAGBLAD
================================================================ --}}
<div style="page-break-after: always; padding-top: 60px;">

    <div class="cover-top">
        <p class="cover-instrument">Instrument voor het meten van de basiskwaliteit van het onderwijs</p>
        <p class="cover-title">Kwaliteit in Beeld</p>
    </div>

    <table class="cover-meta-table">
        <tr>
            <td class="cml">Team</td>
            <td class="cmv">{{ $activeTeam->name }}</td>
        </tr>
        @if($locations->isNotEmpty())
        <tr>
            <td class="cml">Locatie</td>
            <td class="cmv">{{ $locations->pluck('name')->join(', ') }}</td>
        </tr>
        @endif
        @if($teamLeaders->isNotEmpty())
        <tr>
            <td class="cml">Onderwijsteamleider</td>
            <td class="cmv">{{ $teamLeaders->pluck('name')->join(', ') }}</td>
        </tr>
        @endif
        <tr>
            <td class="cml">Rapportageperiode(s)</td>
            <td class="cmv">{!! $periods->pluck('label')->join(' &bull; ') !!}</td>
        </tr>
        <tr>
            <td class="cml">Datum</td>
            <td class="cmv">{{ $exportDate }}</td>
        </tr>
    </table>

    {{-- Teamleden --}}
    <p class="cover-sec-head">Teamleden</p>
    @if($teamMembers->isNotEmpty())
        @foreach($teamMembers as $member)
            <table style="width:100%; border-collapse:collapse; margin-bottom:1px;">
                <tr>
                    <td class="cover-person" style="width:50%;">{{ $member->name }}</td>
                    <td class="cover-email">{{ $member->email }}</td>
                </tr>
            </table>
        @endforeach
    @else
        <p class="cover-empty">Geen teamleden geregistreerd.</p>
    @endif

    {{-- Teamleider(s) --}}
    <p class="cover-sec-head">Teamleider(s)</p>
    @if($teamLeaders->isNotEmpty())
        @foreach($teamLeaders as $leader)
            <table style="width:100%; border-collapse:collapse; margin-bottom:1px;">
                <tr>
                    <td class="cover-person" style="width:50%;">{{ $leader->name }}</td>
                    <td class="cover-email">{{ $leader->email }}</td>
                </tr>
            </table>
        @endforeach
    @else
        <p class="cover-empty">Geen teamleider geregistreerd.</p>
    @endif

    {{-- Kwaliteitszorgmedewerker(s) --}}
    <p class="cover-sec-head">Kwaliteitszorgmedewerker(s)</p>
    @if($kwaliteitsMedewerkers->isNotEmpty())
        @foreach($kwaliteitsMedewerkers as $kw)
            <table style="width:100%; border-collapse:collapse; margin-bottom:1px;">
                <tr>
                    <td class="cover-person" style="width:50%;">{{ $kw->name }}</td>
                    <td class="cover-email">{{ $kw->email }}</td>
                </tr>
            </table>
        @endforeach
    @else
        <p class="cover-empty">Geen kwaliteitszorgmedewerker geregistreerd.</p>
    @endif

</div>
{{-- EINDE OMSLAGBLAD --}}


{{-- ================================================================
     PAGINA 2: INLEIDING + THEMA-OVERZICHT
================================================================ --}}
<div style="page-break-after: always;">

    <p class="page-title">Inleiding</p>

    <p class="intro-text">
        Kwaliteit in Beeld is het instrument waarmee onderwijsteams in kaart brengen hoe het staat
        met de basiskwaliteit van het onderwijs. Met dit instrument brengen opleidingen in beeld wat
        goed gaat, waar ruimte is voor verbetering en waar actie nodig is.
        Zo ontstaat een gedeeld beeld van zowel de sterke punten als de aandachtspunten binnen de opleiding.
    </p>
    <p class="intro-text" style="margin-top:6px;">
        De kwaliteitscriteria zijn gebaseerd op het toezichtkader van de onderwijsinspectie.
        Daardoor sluiten we aan op externe standaarden en hanteren we binnen de opleiding
        &eacute;&eacute;n gezamenlijke norm voor onderwijskwaliteit.
    </p>

    <p class="section-head" style="margin-top:12px;">De uitkomsten vormen de basis voor:</p>
    <p class="bullet">&bull;&nbsp;het opstellen van het teamplan;</p>
    <p class="bullet">&bull;&nbsp;het maken van gerichte keuzes en verbeteracties;</p>
    <p class="bullet">&bull;&nbsp;de inhoud van managementgesprekken.</p>

    {{-- Thema-overzicht uit database --}}
    <p class="section-head" style="margin-top:16px;">Thema&#39;s en standaarden</p>

    <table class="theme-overview-table">
        <thead>
            <tr>
                <th style="width:10%;">Code</th>
                <th style="width:30%;">Thema</th>
                <th>Standaard</th>
            </tr>
        </thead>
        <tbody>
            @foreach($themes as $theme)
                @php $standardCount = $theme->standards->count(); @endphp
                @foreach($theme->standards as $sIdx => $standard)
                    <tr>
                        @if($sIdx === 0)
                            <td class="theme-code-cell" rowspan="{{ $standardCount }}"
                                style="background-color:{{ $theme->color }}; color:#ffffff; text-align:center; vertical-align:middle;">
                                {{ $theme->code }}
                            </td>
                            <td rowspan="{{ $standardCount }}" style="font-weight:bold; vertical-align:top;">
                                {{ $theme->name }}
                            </td>
                        @endif
                        <td>
                            <strong>{{ $standard->code }}</strong>&nbsp;&nbsp;{{ $standard->name }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</div>
{{-- EINDE PAGINA 2 --}}


{{-- ================================================================
     PAGINA 3: CYCLUSBESCHRIJVING + WERKWIJZE
================================================================ --}}
<div>

    <p class="page-title">Cyclusbeschrijving</p>

    <p class="intro-text">
        De kwaliteitscyclus bestaat uit meerdere meetmomenten. Per rapportageperiode worden de
        criteria beoordeeld en vastgelegd. De scores kunnen per periode veranderen op basis van
        geboekte resultaten. Prioriteiten worden bijgesteld, afgerond of aangevuld op basis van de voortgang.
    </p>

    <table class="cyclus-table" style="margin-top:10px;">
        @foreach($periods as $pIdx => $period)
            <tr>
                <td class="cyclus-year">{{ $period->label }}</td>
                <td>
                    @if($pIdx === 0)
                        <strong>Startfase</strong> &mdash;
                        Het onderwijsteam vult de criteria van Kwaliteit in Beeld in.
                        De uitkomsten vormen de basis voor het bepalen van prioriteiten in het teamplan.
                    @else
                        <strong>Evaluatie en bijstelling</strong> &mdash;
                        De voortgang op de gekozen prioriteiten wordt ge&euml;valueerd.
                        Scores kunnen veranderen op basis van geboekte resultaten.
                        Prioriteiten worden bijgesteld, afgerond of aangevuld.
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    {{-- Werkwijze --}}
    <p class="page-title" style="margin-top: 24px;">Werkwijze</p>

    <p class="intro-text">
        Bespreek samen met (een deel van) je team de kwaliteitscriteria en bijbehorende indicatoren.
        Beoordeel elk criterium aan de hand van de volgende kleurcodering.
    </p>

    <table class="werkwijze-table" style="margin-top:10px;">
        <tr>
            <td class="wz-green">
                <p class="wz-label" style="color:#15803d;">Voldoende</p>
                <p style="margin-top:2px; font-size:8.5pt;">
                    Het criterium is op orde als &aacute;lle indicatoren aantoonbaar gerealiseerd zijn.
                </p>
            </td>
        </tr>
        <tr>
            <td class="wz-amber">
                <p class="wz-label" style="color:#92400e;">Aandacht</p>
                <p style="margin-top:2px; font-size:8.5pt;">
                    Het criterium is deels op orde als er &eacute;&eacute;n indicator niet aantoonbaar gerealiseerd is.
                </p>
            </td>
        </tr>
        <tr>
            <td class="wz-red">
                <p class="wz-label" style="color:#991b1b;">Onvoldoende</p>
                <p style="margin-top:2px; font-size:8.5pt;">
                    Het criterium is onvoldoende als twee of meer indicatoren niet aantoonbaar gerealiseerd zijn.
                </p>
            </td>
        </tr>
    </table>

    <p class="section-head" style="margin-top:14px;">Verantwoordelijkheden</p>
    <p class="intro-text">De teamleider is verantwoordelijk voor het:</p>
    <p class="bullet">&bull;&nbsp;organiseren van het overleg;</p>
    <p class="bullet">&bull;&nbsp;betrekken van het team;</p>
    <p class="bullet">&bull;&nbsp;volledig en tijdig invullen van de criteria.</p>
    <p class="intro-text" style="margin-top:8px;">
        De kwaliteitszorgmedewerker ondersteunt het proces, denkt kritisch mee en helpt bij het
        maken van keuzes. Zij kunnen als sparringpartner of gespreksbegeleider aansluiten.
    </p>

</div>
{{-- EINDE PAGINA 3 --}}


{{-- ================================================================
     PAGINA'S 4+: INHOUD PER THEMA
================================================================ --}}

@foreach($themes as $themeIdx => $theme)

    <div style="page-break-before: always;">

        {{-- ─── THEMA-HEADER ─────────────────────────────────────────── --}}
        <div class="theme-header" style="background-color:{{ $theme->color }};">
            <p class="theme-header-title">
                KWALITEITSGEBIED {{ strtoupper($theme->name) }} ({{ strtoupper($theme->code) }})
            </p>
        </div>

        @if($theme->standards->isEmpty())
            <p style="color:#94a3b8; font-style:italic; font-size:8.5pt; margin-top:8px;">
                Geen standaarden aanwezig voor dit thema.
            </p>
        @endif

        @foreach($theme->standards as $standard)

            {{-- ─── STANDAARD ─────────────────────────────────────────── --}}
            <div class="standard-wrap">

                <div class="standard-header" style="border-left: 5px solid {{ $theme->color }};">
                    <p class="standard-title">{{ $standard->code }}. {{ $standard->name }}</p>
                    @if($standard->description)
                        <p class="standard-desc">{{ $standard->description }}</p>
                    @endif
                </div>

                {{-- BASISKWALITEIT beschrijving --}}
                @if($standard->description)
                    <p class="basiskwaliteit-head">Basiskwaliteit</p>
                    <p class="basiskwaliteit-text">{{ $standard->description }}</p>
                @endif

                @if($standard->criteria->isEmpty())
                    <p style="color:#94a3b8; font-style:italic; font-size:8.5pt; margin: 4px 0 10px 12px;">
                        Geen criteria aanwezig voor deze standaard.
                    </p>
                @endif

                {{-- ─── CRITERIA ───────────────────────────────────────── --}}
                @foreach($standard->criteria as $criterion)
                    @php
                        $scoresByPeriod = $criterion->scores->keyBy('reporting_period_id');
                        $hasAnyScore    = $criterion->scores->whereNotNull('status')->isNotEmpty();
                    @endphp

                    <div class="criterion-wrap">

                        {{-- SCORE-TABEL met jaar-kolommen --}}
                        <table class="crit-score-table">
                            <thead>
                                <tr>
                                    <th class="crit-col">Criterium</th>
                                    @foreach($periods as $period)
                                        <th style="width:7%; white-space:nowrap; font-size:7.5pt;">
                                            {{ $period->label }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="crit-text-cell">
                                        <p class="crit-num">Criterium {{ $criterion->number }}</p>
                                        {{ $criterion->text }}
                                    </td>
                                    @foreach($periods as $period)
                                        @php $score = $scoresByPeriod->get($period->id); @endphp
                                        @if($score && $score->status === 'sufficient')
                                            <td class="score-cell score-v">X</td>
                                        @elseif($score && $score->status === 'attention')
                                            <td class="score-cell score-a">X</td>
                                        @elseif($score && $score->status === 'insufficient')
                                            <td class="score-cell score-o">X</td>
                                        @else
                                            <td class="score-cell score-n"></td>
                                        @endif
                                    @endforeach
                                </tr>

                                {{-- INDICATOREN als extra rij in de tabel --}}
                                @if($criterion->indicators->isNotEmpty())
                                    <tr>
                                        <td colspan="{{ $periods->count() + 1 }}" style="padding: 2px 8px 6px 16px; border:1px solid #e2e8f0;">
                                            @foreach($criterion->indicators as $indicator)
                                                <p class="indicator-item">&bull;&nbsp;{{ $indicator->text }}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif

                                {{-- TOELICHTING (criterion explanation) --}}
                                @if($criterion->explanation)
                                    <tr class="toelichting-row">
                                        <td colspan="{{ $periods->count() + 1 }}">
                                            <strong>Toelichting:</strong> {{ $criterion->explanation }}
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>

                    </div>
                    {{-- EINDE criterium-wrap --}}

                @endforeach
                {{-- EINDE criteria loop --}}


                {{-- ─── ACTIEPUNTEN (ingevuld door het team) ─────────────── --}}
                @php
                    $criteriaWithAPs = $standard->criteria->filter(
                        fn($c) => $c->actionPoints->isNotEmpty()
                    );
                @endphp

                @if($criteriaWithAPs->isNotEmpty())
                    <div class="ap-section-wrap">
                        <p class="ap-section-title">
                            Actiepunten &mdash; Ingevuld door het team
                        </p>

                        @foreach($criteriaWithAPs as $criterion)

                            <p class="ap-crit-ref">
                                Criterium {{ $criterion->number }}:
                                {{ \Illuminate\Support\Str::limit($criterion->text, 100) }}
                            </p>

                            @foreach($criterion->actionPoints as $apIdx => $ap)
                                @php
                                    $sn  = $ap->status?->name ?? '';
                                    $cls = match(true) {
                                        str_contains($sn, 'schema')     => 'aps-op-schema',
                                        str_contains($sn, 'achter')     => 'aps-loopt-achter',
                                        str_contains($sn, 'Uitgesteld') => 'aps-uitgesteld',
                                        str_contains($sn, 'Afgerond')   => 'aps-afgerond',
                                        default                          => 'aps-niet-gestart',
                                    };
                                @endphp

                                <div class="ap-block" style="border-left: 3px solid {{ $theme->color }};">

                                    <p class="ap-nr-desc">{{ $apIdx + 1 }}. {{ $ap->description }}</p>

                                    <table class="ap-meta-table">
                                        <tr>
                                            <td class="ap-lbl">Toegewezen aan</td>
                                            <td class="ap-val">{{ $ap->user?->name ?? '—' }}</td>
                                            <td class="ap-lbl">Status</td>
                                            <td class="ap-val">
                                                <span class="{{ $cls }}">{{ $sn ?: '—' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ap-lbl">Startdatum</td>
                                            <td class="ap-val">
                                                {{ $ap->start_date ? \Carbon\Carbon::parse($ap->start_date)->format('d-m-Y') : '—' }}
                                            </td>
                                            <td class="ap-lbl">Einddatum</td>
                                            <td class="ap-val">
                                                {{ $ap->end_date ? \Carbon\Carbon::parse($ap->end_date)->format('d-m-Y') : '—' }}
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- Evaluaties --}}
                                    @if($ap->evaluations->isNotEmpty())
                                        <div class="eval-wrap">
                                            <p class="eval-head">
                                                Evaluaties ({{ $ap->evaluations->count() }})
                                            </p>
                                            @foreach($ap->evaluations as $eval)
                                                <div class="eval-item">
                                                    <p class="eval-meta">
                                                        {{ $eval->created_at->format('d-m-Y') }}
                                                        @if($eval->creator)
                                                            &mdash; {{ $eval->creator->name }}
                                                        @endif
                                                    </p>
                                                    <p class="eval-body">{{ $eval->description }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>
                                {{-- EINDE ap-block --}}

                            @endforeach

                        @endforeach

                    </div>
                @endif
                {{-- EINDE actiepunten --}}


                {{-- ─── AANDACHTSPUNTEN VOOR HET TEAMPLAN ─────────────────── --}}
                @php
                    $aandachtsCriteria = $standard->criteria->filter(function ($c) {
                        return $c->scores->whereIn('status', ['attention', 'insufficient'])->isNotEmpty()
                            || $c->actionPoints->isNotEmpty();
                    });
                @endphp

                @if($aandachtsCriteria->isNotEmpty())
                    <div class="aap-wrap">
                        <p class="aap-title">
                            {{ $standard->code }}. {{ $standard->name }}
                            &mdash; Aandachtspunten voor het teamplan
                        </p>

                        @foreach($aandachtsCriteria as $c)
                            @php
                                $worstScore  = null;
                                $scoreLabels = [];
                                foreach ($c->scores->whereNotNull('status') as $sc) {
                                    $scoreLabels[] = match($sc->status) {
                                        'sufficient'   => '<span class="aap-score-v">Voldoende</span>',
                                        'attention'    => '<span class="aap-score-a">Aandacht</span>',
                                        'insufficient' => '<span class="aap-score-o">Onvoldoende</span>',
                                        default        => '',
                                    };
                                }
                                $apCount = $c->actionPoints->count();
                            @endphp

                            <p class="aap-item">
                                &bull;&nbsp;<strong>Criterium {{ $c->number }}:</strong>
                                {{ \Illuminate\Support\Str::limit($c->text, 90) }}
                                @if(!empty($scoreLabels))
                                    &mdash; {!! implode(', ', $scoreLabels) !!}
                                @endif
                                @if($apCount > 0)
                                    &mdash; {{ $apCount }} actiepunt{{ $apCount > 1 ? 'en' : '' }}
                                @endif
                            </p>

                        @endforeach

                    </div>
                @endif
                {{-- EINDE aandachtspunten --}}

            </div>
            {{-- EINDE standard-wrap --}}

        @endforeach
        {{-- EINDE standards loop --}}

    </div>
    {{-- EINDE THEMA --}}

@endforeach

</body>
</html>
