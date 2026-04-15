<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: Helvetica, Arial, sans-serif;
        background-color: #ffffff;
        color: #1e293b;
        width: 100%;
        min-height: 100%;
    }

    /* ── HEADER ─────────────────────────────────────────────── */
    .header {
        background-color: #f8fafc;
        padding: 30px 40px;
        border-bottom: 2px solid #10b981;
    }
    .header-badge {
        display: inline-block;
        background-color: #10b981;
        color: #fff;
        font-size: 8px;
        font-weight: bold;
        padding: 4px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .header h1 {
        font-size: 28px;
        font-weight: bold;
        color: #0f172a;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .header h1 span { color: #10b981; }
    .header-sub {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: bold;
    }

    .header-meta {
        margin-top: 20px;
        width: 100%;
    }
    .user-name {
        font-size: 14px;
        font-weight: bold;
        color: #0f172a;
    }
    .user-label {
        font-size: 9px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: bold;
    }

    /* ── STATS BAR ──────────────────────────────────────────── */
    .stats-bar {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 15px 40px;
    }
    .stats-table { width: 100%; border-collapse: collapse; }
    .stat-cell {
        text-align: center;
        border-right: 1px solid #e2e8f0;
        padding: 10px;
    }
    .stat-cell:last-child { border-right: none; }
    .stat-number {
        font-size: 20px;
        font-weight: bold;
        color: #0f172a;
    }
    .stat-label {
        font-size: 8px;
        color: #94a3b8;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* ── TABLE ──────────────────────────────────────────────── */
    .table-wrap { padding: 20px 40px; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f1f5f9;
        font-size: 8px;
        font-weight: bold;
        color: #475569;
        text-transform: uppercase;
        padding: 10px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    td {
        padding: 12px 10px;
        font-size: 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .team-name { font-weight: bold; color: #0f172a; }
    .score-box {
        display: inline-block;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 4px 8px;
        font-weight: bold;
        border-radius: 4px;
        min-width: 25px;
        text-align: center;
    }
    .points-badge {
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: bold;
    }
    .points-5 { background: #dcfce7; color: #15803d; } /* Exacto */
    .points-3 { background: #fef9c3; color: #a16207; } /* Parcial */
    .points-0 { background: #f1f5f9; color: #64748b; }
    .points-pending { border: 1px solid #e2e8f0; color: #94a3b8; }

    /* ── FOOTER ─────────────────────────────────────────────── */
    .footer {
        padding: 20px 40px;
        border-top: 1px solid #e2e8f0;
        margin-top: 20px;
    }
    .footer-brand { font-size: 11px; font-weight: bold; color: #10b981; }
    .footer-disclaimer { font-size: 8px; color: #94a3b8; font-style: italic; text-align: right; }
</style>
</head>
<body>

<div class="header">
    <div class="header-badge">Quiniela Mundialista 2026</div>
    <h1>Hoja de <span>Pronósticos</span></h1>
    <div class="header-sub">Temporada 2025/26 · Listado de Usuario</div>

    <table class="header-meta">
        <tr>
            <td>
                <div class="user-label">Participante</div>
                <div class="user-name">{{ $user->name }} {{ $user->lastname }}</div>
                @if($user->cedula) <div style="font-size:10px; color:#64748b;">C.I. {{ $user->cedula }}</div> @endif
            </td>
            <td style="text-align:right; vertical-align: bottom;">
                <div class="user-label">Exportado el</div>
                <div style="font-size:10px; color:#64748b;">{{ now()->timezone('America/Caracas')->format('d/m/Y - H:i') }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="stats-bar">
    <table class="stats-table">
        <tr>
            <td class="stat-cell">
                <div class="stat-number">{{ $totalPredictions }}</div>
                <div class="stat-label">Pronósticos</div>
            </td>
            <td class="stat-cell">
                <div class="stat-number" style="color: #10b981;">{{ $totalPoints }}</div>
                <div class="stat-label">Puntos Totales</div>
            </td>
            <td class="stat-cell">
                <div class="stat-number">{{ $exactPredictions }}</div>
                <div class="stat-label">Exactos (5 pts)</div>
            </td>
            <td class="stat-cell">
                <div class="stat-number">{{ $partialPredictions }}</div>
                <div class="stat-label">Parciales (3 pts)</div>
            </td>
        </tr>
    </table>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Partido</th>
                <th style="text-align:center">Pronóstico</th>
                <th style="text-align:center">Resultado</th>
                <th style="text-align:center">Puntos</th>
                <th style="text-align:right">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($predictions as $index => $prediction)
            @php
                $game = $prediction->game;
                $hasResult = in_array($game->status, ['finished', 'in_play']);
                $pts = $prediction->points_earned;
            @endphp
            <tr>
                <td style="color:#94a3b8">{{ $index + 1 }}</td>
                <td>
                    <div class="team-name">{{ $game->team_a }} vs {{ $game->team_b }}</div>
                    <div style="font-size:8px; color:#94a3b8">{{ $game->match_date->timezone('America/Caracas')->format('d M, H:i') }}</div>
                </td>
                <td style="text-align:center">
                    <span class="score-box">{{ $prediction->home_score }} - {{ $prediction->away_score }}</span>
                </td>
                <td style="text-align:center">
                    @if($hasResult)
                        <span class="score-box" style="border-color:#10b981">{{ $game->score_a }} - {{ $game->score_b }}</span>
                    @else
                        <span style="color:#cbd5e1">-</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if($prediction->is_calculated)
                        @if($pts == 5)
                            <span class="points-badge points-5">5 pts</span>
                        @elseif($pts == 3)
                            <span class="points-badge points-3">3 pts</span>
                        @else
                            <span class="points-badge points-0">0 pts</span>
                        @endif
                    @else
                        <span class="points-badge points-pending">Pend.</span>
                    @endif
                </td>
                <td style="text-align:right; font-size:8px; font-weight:bold; color:#64748b">
                    {{ strtoupper($game->status == 'finished' ? 'Final' : ($game->status == 'in_play' ? 'En Vivo' : 'Pendiente')) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    <table style="width:100%">
        <tr>
            <td>
                <div class="footer-brand">⚽ Quiniela2026 Virtual</div>
                <div style="font-size:8px; color:#94a3b8 mt:2px;">© 2026 · Comprobante oficial de participación</div>
            </td>
            <td class="footer-disclaimer">
                Reglas: Exacto (5 pts) · Ganador/Empate (3 pts).<br>
                Los puntos se confirman tras finalizar el partido oficial.
            </td>
        </tr>
    </table>
</div>

</body>
</html>
