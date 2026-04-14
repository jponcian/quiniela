<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: DejaVu Sans, sans-serif;
        background-color: #020617;
        color: #e2e8f0;
        width: 100%;
        min-height: 100%;
    }

    /* ── HEADER ─────────────────────────────────────────────── */
    .header {
        background: linear-gradient(135deg, #0f172a 0%, #0d2040 50%, #0a3300 100%);
        padding: 28px 36px 20px;
        border-bottom: 3px solid #10b981;
        position: relative;
        overflow: hidden;
    }
    .header-badge {
        display: inline-block;
        background-color: #10b981;
        color: #020617;
        font-size: 7px;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 8px;
    }
    .header h1 {
        font-size: 30px;
        font-weight: bold;
        color: #ffffff;
        letter-spacing: -1px;
        line-height: 1;
        text-transform: uppercase;
    }
    .header h1 span { color: #10b981; }
    .header-sub {
        font-size: 9px;
        color: #64748b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-top: 4px;
    }
    .header-ball {
        position: absolute;
        right: 24px;
        top: 16px;
        font-size: 64px;
        opacity: 0.12;
    }
    .header-meta {
        display: table;
        width: 100%;
        margin-top: 16px;
    }
    .header-meta-cell {
        display: table-cell;
        vertical-align: middle;
    }
    .header-meta-cell.right { text-align: right; }
    .user-name {
        font-size: 13px;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
    }
    .user-label {
        font-size: 8px;
        color: #64748b;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .generated-label {
        font-size: 8px;
        color: #475569;
        letter-spacing: 0.5px;
    }

    /* ── STATS BAR ──────────────────────────────────────────── */
    .stats-bar {
        background: #0f172a;
        border-bottom: 1px solid #1e293b;
        padding: 12px 36px;
    }
    .stats-table { width: 100%; }
    .stat-cell {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        border-right: 1px solid #1e293b;
        padding: 0 24px;
    }
    .stat-cell:last-child { border-right: none; }
    .stat-number {
        font-size: 22px;
        font-weight: bold;
        color: #10b981;
        line-height: 1;
    }
    .stat-label {
        font-size: 7.5px;
        color: #64748b;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    /* ── SECTION TITLE ──────────────────────────────────────── */
    .section-title {
        font-size: 10px;
        font-weight: bold;
        color: #10b981;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 14px 36px 8px;
        border-left: 3px solid #10b981;
        margin: 0 0 0 36px;
    }

    /* ── PREDICTIONS TABLE ──────────────────────────────────── */
    .table-wrap { padding: 0 36px 20px; }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead tr {
        background: #0f172a;
        border-bottom: 2px solid #10b981;
    }
    thead th {
        font-size: 7.5px;
        font-weight: bold;
        color: #475569;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 8px 10px;
        text-align: left;
    }
    thead th.center { text-align: center; }
    tbody tr {
        border-bottom: 1px solid #1e293b;
    }
    tbody tr:nth-child(even) { background: #0b1120; }
    tbody tr:last-child { border-bottom: none; }
    td {
        padding: 9px 10px;
        vertical-align: middle;
        font-size: 9px;
        color: #cbd5e1;
    }
    td.center { text-align: center; }
    .team-name { font-size: 10px; color: #fff; font-weight: bold; }
    .team-vs { font-size: 7px; color: #475569; font-style: italic; }
    .date-text { font-size: 8px; color: #64748b; }
    .score-box {
        display: inline-block;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 13px;
        font-weight: bold;
        color: #fff;
        min-width: 26px;
        text-align: center;
    }
    .score-dash { font-size: 10px; color: #475569; padding: 0 3px; }
    .points-badge {
        display: inline-block;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 8.5px;
        font-weight: bold;
    }
    .points-3 { background: #064e3b; color: #10b981; }
    .points-1 { background: #1a2e05; color: #84cc16; }
    .points-0 { background: #1e293b; color: #64748b; }
    .points-pending { background: #172039; color: #60a5fa; }
    .status-pill {
        display: inline-block;
        border-radius: 10px;
        padding: 2px 7px;
        font-size: 7.5px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }
    .pill-finished { background: #1e293b; color: #64748b; }
    .pill-live { background: #3b0000; color: #f87171; }
    .pill-upcoming { background: #0c2a19; color: #34d399; }
    .pill-locked { background: #1a1508; color: #f59e0b; }

    /* ── FOOTER ─────────────────────────────────────────────── */
    .footer {
        background: #0f172a;
        border-top: 1px solid #1e293b;
        padding: 12px 36px;
        margin-top: 10px;
    }
    .footer-table { width: 100%; }
    .footer-left { display: table-cell; vertical-align: middle; }
    .footer-right { display: table-cell; vertical-align: middle; text-align: right; }
    .footer-brand {
        font-size: 11px;
        font-weight: bold;
        color: #10b981;
        text-transform: uppercase;
    }
    .footer-copy { font-size: 7.5px; color: #334155; margin-top: 2px; }
    .footer-disclaimer {
        font-size: 7px;
        color: #334155;
        font-style: italic;
        max-width: 240px;
        text-align: right;
    }
    .no-predictions {
        text-align: center;
        padding: 40px 0;
        color: #475569;
        font-size: 11px;
        font-style: italic;
    }
</style>
</head>
<body>

<!-- ── HEADER ─────────────────────────────────────────────── -->
<div class="header">
    <div class="header-ball">⚽</div>
    <div class="header-badge">⚽ Quiniela Mundialista 2026</div>
    <h1>Hoja de<br><span>Pronósticos</span></h1>
    <div class="header-sub">La Liga · Temporada 2025/26</div>

    <div class="header-meta" style="margin-top:16px;">
        <div class="header-meta-cell">
            <div class="user-label">Participante</div>
            <div class="user-name">{{ $user->name }}</div>
            @if($user->cedula)
                <div class="user-label" style="margin-top:2px;">C.I. {{ $user->cedula }}</div>
            @endif
        </div>
        <div class="header-meta-cell right">
            <div class="generated-label">Generado el {{ now()->timezone('America/Caracas')->translatedFormat('d \d\e F, Y — H:i') }}</div>
        </div>
    </div>
</div>

<!-- ── STATS BAR ──────────────────────────────────────────── -->
<div class="stats-bar">
    <table class="stats-table">
        <tr>
            <td class="stat-cell" style="display:table-cell;">
                <div class="stat-number">{{ $totalPredictions }}</div>
                <div class="stat-label">Pronósticos</div>
            </td>
            <td class="stat-cell" style="display:table-cell;">
                <div class="stat-number" style="color:#facc15;">{{ $totalPoints }}</div>
                <div class="stat-label">Puntos Totales</div>
            </td>
            <td class="stat-cell" style="display:table-cell;">
                <div class="stat-number" style="color:#a78bfa;">{{ $exactPredictions }}</div>
                <div class="stat-label">Exactos (3 pts)</div>
            </td>
            <td class="stat-cell" style="display:table-cell;">
                <div class="stat-number" style="color:#84cc16;">{{ $partialPredictions }}</div>
                <div class="stat-label">Parciales (1 pt)</div>
            </td>
        </tr>
    </table>
</div>

<!-- ── TABLE ──────────────────────────────────────────────── -->
@if($predictions->count() > 0)
<div style="padding: 16px 36px 4px;">
    <div class="section-title" style="margin:0; padding: 0 0 0 10px;">Detalle de Pronósticos</div>
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Partido</th>
                <th>Fecha</th>
                <th class="center">Tu pronóstico</th>
                <th class="center">Resultado</th>
                <th class="center">Puntos</th>
                <th class="center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($predictions as $index => $prediction)
            @php
                $game = $prediction->game;
                $hasResult = in_array($game->status, ['finished', 'in_play']);
                $pts = $prediction->points_earned ?? null;
            @endphp
            <tr>
                <td style="color:#475569; font-size:8px;">{{ $index + 1 }}</td>
                <td>
                    <span class="team-name">{{ $game->team_a }}</span>
                    <span class="team-vs"> vs </span>
                    <span class="team-name">{{ $game->team_b }}</span>
                </td>
                <td>
                    <span class="date-text">{{ $game->match_date->timezone('America/Caracas')->translatedFormat('d M, H:i') }}</span>
                </td>
                <td class="center">
                    <span class="score-box">{{ $prediction->home_score }}</span>
                    <span class="score-dash">–</span>
                    <span class="score-box">{{ $prediction->away_score }}</span>
                </td>
                <td class="center">
                    @if($hasResult)
                        <span class="score-box" style="border-color:{{ $game->status == 'finished' ? '#10b981' : '#f87171' }};">{{ $game->score_a }}</span>
                        <span class="score-dash">–</span>
                        <span class="score-box" style="border-color:{{ $game->status == 'finished' ? '#10b981' : '#f87171' }};">{{ $game->score_b }}</span>
                    @else
                        <span style="color:#334155; font-size:8px;">— / —</span>
                    @endif
                </td>
                <td class="center">
                    @if($prediction->is_calculated && $pts !== null)
                        @if($pts == 3)
                            <span class="points-badge points-3">🏆 3 pts</span>
                        @elseif($pts == 1)
                            <span class="points-badge points-1">✓ 1 pt</span>
                        @else
                            <span class="points-badge points-0">0 pts</span>
                        @endif
                    @else
                        <span class="points-badge points-pending">Pendiente</span>
                    @endif
                </td>
                <td class="center">
                    @if($game->status == 'finished')
                        <span class="status-pill pill-finished">Finalizado</span>
                    @elseif($game->status == 'in_play')
                        <span class="status-pill pill-live">En Vivo</span>
                    @elseif($game->isLocked())
                        <span class="status-pill pill-locked">Cerrado</span>
                    @else
                        <span class="status-pill pill-upcoming">Abierto</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="table-wrap" style="padding-top:20px;">
    <div class="no-predictions">No tienes pronósticos registrados aún.</div>
</div>
@endif

<!-- ── FOOTER ─────────────────────────────────────────────── -->
<div class="footer">
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                <div class="footer-brand">⚽ Quiniela2026</div>
                <div class="footer-copy">© 2026 · Calabozo, Venezuela · Uso personal</div>
            </td>
            <td class="footer-right">
                <div class="footer-disclaimer">
                    Sistema de puntuación: 3 pts por marcador exacto · 1 pt por acierto de resultado
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
