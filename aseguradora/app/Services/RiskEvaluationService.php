<?php

namespace App\Services;

use App\Models\Trade;
use App\Models\Account;
use App\Models\RiskRule;
use App\Models\Incident;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class RiskEvaluationService
{
    public function evaluateTrade(Trade $trade)
    {
        $account = $trade->account;
        
        // Solo evaluar con reglas del propietario de la cuenta
        $activeRules = RiskRule::where('is_active', true)
            ->where('created_by_user_id', $account->owner_id)
            ->with(['ruleType', 'parameter', 'actions'])
            ->get();
        
        $violations = [];

        foreach ($activeRules as $rule) {
            $violated = $this->checkRule($rule, $trade, $account);
            
            if ($violated) {
                $incident = $this->createIncident($rule, $trade, $account, $violated['value']);
                $this->executeActions($rule, $incident, $account);
                $violations[] = [
                    'rule' => $rule->name,
                    'severity' => $rule->severity,
                    'incident_id' => $incident->id,
                ];
            }
        }

        return $violations;
    }

    protected function checkRule(RiskRule $rule, Trade $trade, Account $account)
    {
        $slug = $rule->ruleType->slug;

        switch ($slug) {
            case 'duration-check':
                return $this->checkDuration($rule, $trade);
            case 'volume-consistency':
                return $this->checkVolumeConsistency($rule, $trade, $account);
            case 'time-range-operation':
                return $this->checkTimeRangeOperation($rule, $trade, $account);
            default:
                return false;
        }
    }

    protected function checkDuration(RiskRule $rule, Trade $trade)
    {
        if ($trade->status !== 'closed' || !$trade->close_time) {
            return false;
        }

        $durationParam = $rule->parameter->durationParameter;
        if (!$durationParam) return false;

        $duration = $trade->open_time->diffInSeconds($trade->close_time);
        
        if ($duration < $durationParam->duration) {
            return ['value' => "Duration: {$duration}s < {$durationParam->duration}s"];
        }

        return false;
    }

    protected function checkVolumeConsistency(RiskRule $rule, Trade $trade, Account $account)
    {
        $volumeParam = $rule->parameter->volumeTradeParameter;
        if (!$volumeParam) return false;

        $recentTrades = Trade::where('account_id', $account->id)
            ->where('id', '!=', $trade->id)
            ->orderBy('open_time', 'desc')
            ->limit($volumeParam->lookback_trades)
            ->get();

        if ($recentTrades->count() < $volumeParam->lookback_trades) {
            return false;
        }

        $avgVolume = $recentTrades->avg('volume');
        $minAllowed = $avgVolume * $volumeParam->min_factor;
        $maxAllowed = $avgVolume * $volumeParam->max_factor;

        if ($trade->volume < $minAllowed || $trade->volume > $maxAllowed) {
            return ['value' => "Volume: {$trade->volume} outside range [{$minAllowed}, {$maxAllowed}]"];
        }

        return false;
    }

    protected function checkTimeRangeOperation(RiskRule $rule, Trade $trade, Account $account)
    {
        $timeParam = $rule->parameter->timeRangeOperationParameter;
        if (!$timeParam) return false;

        $windowStart = now()->subMinutes($timeParam->time_window_minutes);
        
        $openTradesCount = Trade::where('account_id', $account->id)
            ->where('status', 'open')
            ->where('open_time', '>=', $windowStart)
            ->count();

        if ($openTradesCount < $timeParam->min_open_trades || $openTradesCount > $timeParam->max_open_trades) {
            return ['value' => "Open trades: {$openTradesCount} outside range [{$timeParam->min_open_trades}, {$timeParam->max_open_trades}]"];
        }

        return false;
    }

    protected function createIncident(RiskRule $rule, Trade $trade, Account $account, $triggeredValue)
    {
        $existingIncident = Incident::where('account_id', $account->id)
            ->where('risk_rule_id', $rule->id)
            ->where('trade_id', $trade->id)
            ->first();

        if ($existingIncident) {
            $existingIncident->increment('count');
            return $existingIncident;
        }

        return Incident::create([
            'account_id' => $account->id,
            'risk_rule_id' => $rule->id,
            'trade_id' => $trade->id,
            'count' => 1,
            'triggered_value' => $triggeredValue,
            'is_executed' => false,
        ]);
    }

    protected function executeActions(RiskRule $rule, Incident $incident, Account $account)
    {
        if ($rule->severity === 'Soft' && $incident->count < 3) {
            return;
        }

        foreach ($rule->actions as $action) {
            switch ($action->slug) {
                case 'notify-email':
                    $this->notifyUser($account->owner, $rule, $incident);
                    break;
                case 'disable-account':
                    $account->update(['status' => 'disable']);
                    $this->notifyAccountDisabled($account->owner, $rule, $incident, $account);
                    break;
                case 'disable-trading':
                    $account->update(['trading_status' => 'disable']);
                    $this->notifyTradingDisabled($account->owner, $rule, $incident, $account);
                    break;
                case 'notify-admin':
                    $this->notifyAdmins($rule, $incident, $account);
                    break;
                case 'close-open-trades':
                    $this->closeOpenTrades($account);
                    $this->notifyTradesClosed($account->owner, $rule, $incident, $account);
                    break;
            }
        }

        $incident->update(['is_executed' => true]);
    }

    protected function notifyUser($user, RiskRule $rule, Incident $incident)
    {
        Notification::create([
            'user_id' => $user->id,
            'mensaje' => "Violación de regla: {$rule->name}",
            'metadata' => json_encode([
                'rule_id' => $rule->id,
                'incident_id' => $incident->id,
                'severity' => $rule->severity,
            ]),
        ]);
    }

    protected function notifyAdmins(RiskRule $rule, Incident $incident, Account $account)
    {
        $admins = \App\Models\User::where('is_admin', true)->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'mensaje' => "Alerta Admin: Violación en cuenta {$account->login}",
                'metadata' => json_encode([
                    'rule_id' => $rule->id,
                    'incident_id' => $incident->id,
                    'account_id' => $account->id,
                ]),
            ]);
        }
    }

    protected function notifyAccountDisabled($user, RiskRule $rule, Incident $incident, Account $account)
    {
        Notification::create([
            'user_id' => $user->id,
            'mensaje' => "🚫 CUENTA DESHABILITADA: Tu cuenta {$account->login} ha sido deshabilitada por violación de la regla '{$rule->name}'",
            'metadata' => json_encode([
                'rule_id' => $rule->id,
                'incident_id' => $incident->id,
                'account_id' => $account->id,
                'severity' => $rule->severity,
                'action' => 'account_disabled',
            ]),
        ]);
    }

    protected function notifyTradingDisabled($user, RiskRule $rule, Incident $incident, Account $account)
    {
        Notification::create([
            'user_id' => $user->id,
            'mensaje' => "⚠️ TRADING DESHABILITADO: El trading en tu cuenta {$account->login} ha sido deshabilitado por violación de la regla '{$rule->name}'",
            'metadata' => json_encode([
                'rule_id' => $rule->id,
                'incident_id' => $incident->id,
                'account_id' => $account->id,
                'severity' => $rule->severity,
                'action' => 'trading_disabled',
            ]),
        ]);
    }

    protected function notifyTradesClosed($user, RiskRule $rule, Incident $incident, Account $account)
    {
        Notification::create([
            'user_id' => $user->id,
            'mensaje' => "🔒 TRADES CERRADOS: Todos los trades abiertos en tu cuenta {$account->login} han sido cerrados por violación de la regla '{$rule->name}'",
            'metadata' => json_encode([
                'rule_id' => $rule->id,
                'incident_id' => $incident->id,
                'account_id' => $account->id,
                'severity' => $rule->severity,
                'action' => 'trades_closed',
            ]),
        ]);
    }

    protected function closeOpenTrades(Account $account)
    {
        Trade::where('account_id', $account->id)
            ->where('status', 'open')
            ->update([
                'status' => 'closed',
                'close_time' => now(),
                'close_price' => DB::raw('open_price'), // Cerrar al mismo precio de apertura
            ]);
    }
}
